<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Photo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\TripayService;
use Illuminate\Support\Str;

class CheckoutComponent extends Component
{
    public $photo;
    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $payment_method;
    public $payment_channels = [];

    protected $rules = [
        'customer_name'  => 'required|string|max:255',
        'customer_email' => 'required|email|max:255',
        'customer_phone' => 'required|numeric|digits_between:10,15',
        'payment_method' => 'required|string',
    ];

    public function mount($photoId, TripayService $tripayService)
    {
        $this->photo = Photo::with('album')->findOrFail($photoId);

        // Ambil daftar metode pembayaran dari Tripay
        $response = $tripayService->getPaymentChannels();
        if (isset($response['success']) && $response['success']) {
            $this->payment_channels = $response['data'];
        }
    }

    public function processPayment(TripayService $tripayService)
    {
        $this->validate();

        // 1. Buat Data Order Baru
        $order = Order::create([
            'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
            'customer_name'  => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'subtotal'       => $this->photo->price,
            'total_amount'   => $this->photo->price,
            'status'         => 'unpaid',
        ]);

        // 2. Buat Item Order
        OrderItem::create([
            'order_id' => $order->id,
            'photo_id' => $this->photo->id,
            'price'    => $this->photo->price,
        ]);

        // 3. Request Transaksi ke Tripay API
        $tripayResponse = $tripayService->createTransaction($order, $this->payment_method);

        if (isset($tripayResponse['success']) && $tripayResponse['success']) {
            // Redirect langsung ke URL Instruksi Pembayaran Tripay
            return redirect()->away($tripayResponse['data']['checkout_url']);
        }

        session()->flash('error', $tripayResponse['message'] ?? 'Gagal terhubung ke payment gateway.');
    }

    public function render()
    {
        return view('livewire.public.checkout-component')
            ->layout('layouts.app');
    }
}
