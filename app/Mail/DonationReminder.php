<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Rappel mensuel pour les dons réguliers (V1 : simple notification, aucun prélèvement).
 * Modèle modifiable par l'Admin via les réglages, sans donnée sensible.
 */
class DonationReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public const DEFAULTS = [
        'subject' => 'Votre don régulier à Ezer Home',
        'body' => "Bonjour, vous avez choisi de soutenir Ezer Home chaque mois. Si vous le souhaitez, vous pouvez renouveler votre don depuis notre site. Aucun prélèvement n'est effectué automatiquement. Merci de votre fidélité.",
    ];

    public function envelope(): Envelope
    {
        return new Envelope(subject: Setting::get('notif.donation.reminder.subject', self::DEFAULTS['subject']));
    }

    public function content(): Content
    {
        return new Content(text: 'emails.donation-reminder', with: [
            'corps' => Setting::get('notif.donation.reminder.body', self::DEFAULTS['body']),
        ]);
    }
}
