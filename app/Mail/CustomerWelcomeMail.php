<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerWelcomeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu registro en La Tienda de Mi Abue fue confirmado',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customers.welcome',
            with: [
                'user' => $this->user,
            ],
        );
    }
}
