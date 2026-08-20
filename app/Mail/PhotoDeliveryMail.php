<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class PhotoDeliveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $downloadUrl;

    public function __construct(Order $order, string $downloadUrl)
    {
        $this->order = $order;
        $this->downloadUrl = $downloadUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Foto Asli Pesanan Anda #' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.photo-delivery',
        );
    }
}
