<?php

namespace App\Mail;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentTransactionStatusMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public PaymentTransaction $transaction)
    {
        $this->transaction->loadMissing('order.customer', 'order.items');
    }

    public function envelope(): Envelope
    {
        $orderNumber = $this->transaction->order?->number ?? $this->transaction->order_ref;

        return new Envelope(
            subject: $this->transaction->status === 'approved'
                ? 'Pago aprobado de tu pedido '.$orderNumber
                : 'Actualizacion de pago de tu pedido '.$orderNumber,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transactions.status',
            with: [
                'transaction' => $this->transaction,
                'order' => $this->transaction->order,
                'customer' => $this->transaction->order?->customer,
                'items' => $this->transaction->order?->items ?? collect(),
            ],
        );
    }
}
