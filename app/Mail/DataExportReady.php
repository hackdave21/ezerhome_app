<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DataExportReady extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public string $profilUrl) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Vos données sont prêtes');
    }

    public function content(): Content
    {
        return new Content(text: 'emails.data-export-ready');
    }
}
