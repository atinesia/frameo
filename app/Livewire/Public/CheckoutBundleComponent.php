<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Bundle;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\TripayService;
use Illuminate\Support\Str;

class CheckoutBundleComponent extends Component
{
    public $bundle;
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

    public function mount($bundleId, TripayService $tripayService)
    {
        $this->bundle = Bundle::with('album.photos')->findOrFail($bundleId);

        $response = $tripayService->getPaymentChannels();
        if (isset($response['success']) && $response['success']) {
            $this->payment_channels = $response['data'];
        }
    }

    public function processPayment(TripayService $tripayService)
    {
        $this->validate();

        // 1. Buat Order
        $order = Order::create([
            'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
            'customer_name'  => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'subtotal'       => $this->bundle->price,
            'total_amount'   => $this->bundle->price,
            'status'         => 'unpaid',
        ]);

        // 2. Masukkan SEMUA foto di dalam album ke order_items
        foreach ($this->bundle->album->photos as $photo) {
            OrderItem::create([
                'order_id' => $order->id,
                'photo_id' => $photo->id,
                'price'    => 0, // Harga per foto dihitung paket
            ]);
        }

        // 3. Request ke Tripay
        $tripayResponse = $tripayService->createTransaction($order, $this->payment_method);

        if (isset($tripayResponse['success']) && $tripayResponse['success']) {
            return redirect()->away($tripayResponse['data']['checkout_url']);
        }

        session()->flash('error', $tripayResponse['message'] ?? 'Gagal terhubung ke payment gateway.');
    }

    public function render()
    {
        return view('livewire.public.checkout-bundle-component')
            ->layout('layouts.app');
    }
}
