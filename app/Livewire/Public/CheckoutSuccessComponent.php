<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Order;

class CheckoutSuccessComponent extends Component
{
    public $order;

    public function mount($order_number)
    {
        $this->order = Order::with('items.photo.album')
            ->where('order_number', $order_number)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.checkout-success-component')
            ->layout('layouts.app');
    }
}
