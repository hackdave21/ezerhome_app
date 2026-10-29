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
 * Notifications d'assistance au demandeur : toujours en file d'attente, modèles (« message-type »)
 * modifiables par l'Admin via les réglages, et sans aucun détail sensible (seuls le numéro de suivi
 * et l'état de la demande peuvent apparaître).
 */
class AssistanceNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public const TEMPLATES = [
        'received' => [
            'subject' => "Votre demande d'assistance (:ref)",
            'body' => "Votre demande d'assistance a bien été reçue. Votre numéro de suivi est :ref : conservez-le pour suivre votre demande.",
        ],
        'status_en_examen' => [
            'subject' => "Votre demande :ref est en cours d'examen",
            'body' => "Votre demande d'assistance :ref est en cours d'examen par notre comité.",
        ],
        'status_acceptee' => [
            'subject' => 'Votre demande :ref a été acceptée',
            'body' => "Bonne nouvelle : votre demande d'assistance :ref a été acceptée. Nous allons vous recontacter.",
        ],
        'status_close' => [
            'subject' => 'Votre demande :ref est clôturée',
            'body' => "Votre demande d'assistance :ref est clôturée. Merci de votre confiance.",
        ],
        'status_refusee' => [
            'subject' => 'Votre demande :ref',
            'body' => "Votre demande d'assistance :ref n'a pas pu aboutir. Vous pouvez nous contacter pour en savoir plus.",
        ],
    ];

    /** @param array<string, string> $vars Valeurs non sensibles (ref). */
    public function __construct(public string $type, public array $vars = [])
    {
        abort_unless(isset(self::TEMPLATES[$this->type]), 500);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->resolve('subject'));
    }

    public function content(): Content
    {
        return new Content(text: 'emails.assistance', with: ['corps' => $this->resolve('body')]);
    }

    private function resolve(string $part): string
    {
        $template = Setting::get("notif.assistance.{$this->type}.{$part}", self::TEMPLATES[$this->type][$part]);

        $remplacements = [];
        foreach ($this->vars as $cle => $valeur) {
            $remplacements[':'.$cle] = $valeur;
        }

        return strtr((string) $template, $remplacements);
    }
}
