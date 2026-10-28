<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        $objet = $this->data['profil'] === 'organisation'
            ? 'Donateur institutionnel : '.$this->data['organisation']
            : $this->data['objet'];

        return new Envelope(
            replyTo: empty($this->data['email']) ? [] : [new Address($this->data['email'], $this->data['nom'])],
            subject: '[Contact] '.$objet,
        );
    }

    public function content(): Content
    {
        return new Content(text: 'emails.contact');
    }
}
