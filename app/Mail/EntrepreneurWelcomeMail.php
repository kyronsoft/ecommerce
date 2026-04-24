<?php

namespace App\Mail;

use App\Models\PaymentTransaction;
use App\Models\Store;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EntrepreneurWelcomeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public PaymentTransaction $transaction,
        public User $user,
        public Store $store,
        public string $plainPassword,
    ) {
        $this->transaction->loadMissing('order.customer');
    }

    public function envelope(): Envelope
    {
        $planName = (string) ($this->transaction->request_payload['plan']['name'] ?? 'tu plan emprendedor');

        return new Envelope(
            subject: 'Acceso al backoffice y condiciones de '.$planName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.entrepreneurs.welcome',
            with: [
                'transaction' => $this->transaction,
                'user' => $this->user,
                'store' => $this->store,
                'plainPassword' => $this->plainPassword,
                'plan' => $this->transaction->request_payload['plan'] ?? [],
                'entrepreneur' => $this->transaction->request_payload['entrepreneur'] ?? [],
                'backofficeUrl' => route('admin.login'),
            ],
        );
    }

    public function attachments(): array
    {
        $path = storage_path('app/attachments-disclaimer-vendedores.pdf');

        if (! is_file($path)) {
            return [];
        }

        return [
            Attachment::fromPath($path)
                ->as('Disclaimer y condiciones esenciales para vendedores.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
