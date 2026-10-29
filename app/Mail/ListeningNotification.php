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
 * Notifications d'écoute : toujours en file d'attente, modèles modifiables par l'Admin (settings),
 * et JAMAIS de contenu sensible (pas de description, pas d'identité de bénéficiaire). Seule une
 * référence courte non identifiante et le canal de contact choisi peuvent apparaître.
 */
class ListeningNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** Modèles par défaut, surchargeables via les réglages (clé « notif.listening.<type>.subject|body »). */
    public const TEMPLATES = [
        'received' => [
            'subject' => "Nouvelle demande d'écoute",
            'body' => "Une nouvelle demande d'écoute (:ref) a été reçue. Connectez-vous à l'espace d'administration pour l'affecter à un intervenant.",
        ],
        'assigned_therapist' => [
            'subject' => "Une demande d'écoute vous est affectée",
            'body' => "Une demande d'écoute (:ref) vous a été affectée. Connectez-vous à votre espace pour l'accepter ou la décliner.",
        ],
        'assigned_consultant' => [
            'subject' => 'Votre demande est prise en charge',
            'body' => "Bonjour, votre demande d'écoute (:ref) a été prise en charge. Un intervenant vous contactera via :canal.",
        ],
        'declined_admin' => [
            'subject' => "Demande d'écoute à réaffecter",
            'body' => "La demande d'écoute (:ref) est à réaffecter : l'intervenant pressenti n'est pas disponible. Connectez-vous pour l'attribuer à un autre intervenant.",
        ],
        'closed_consultant' => [
            'subject' => 'Votre accompagnement est clôturé',
            'body' => 'Votre accompagnement (:ref) a été clôturé. Merci de votre confiance ; vous pouvez à tout moment formuler une nouvelle demande.',
        ],
    ];

    /** @param array<string, string> $vars Valeurs non sensibles (ref, canal). */
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
        return new Content(text: 'emails.listening', with: [
            'corps' => $this->resolve('body'),
        ]);
    }

    /** Valeur du modèle (réglage prioritaire, sinon défaut), avec substitution des :placeholders. */
    private function resolve(string $part): string
    {
        $template = Setting::get("notif.listening.{$this->type}.{$part}", self::TEMPLATES[$this->type][$part]);

        $remplacements = [];
        foreach ($this->vars as $cle => $valeur) {
            $remplacements[':'.$cle] = $valeur;
        }

        return strtr((string) $template, $remplacements);
    }
}
