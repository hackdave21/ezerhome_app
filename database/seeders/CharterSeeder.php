<?php

namespace Database\Seeders;

use App\Models\Charter;
use Illuminate\Database\Seeder;

/**
 * Chartes provisoires, reprises de la maquette (pages/espace/charte.html).
 * Les textes définitifs sont fournis par le client : les publier comme NOUVELLE version
 * (php artisan charters:publish), ce qui exigera une nouvelle signature.
 */
class CharterSeeder extends Seeder
{
    public function run(): void
    {
        if (! Charter::where('type', Charter::LISTENING)->exists()) {
            Charter::publish(Charter::LISTENING, '1.0', $this->listening());
        }

        if (! Charter::where('type', Charter::PROVIDER)->exists()) {
            Charter::publish(Charter::PROVIDER, '1.0', $this->section(1, 'Objet', "[Texte provisoire : la charte de l'intervenant sera fournie par le client.]"));
        }
    }

    private function section(int $n, string $titre, string $texte): string
    {
        return '<section class="flex flex-col gap-1.5"><h2 class="font-semibold"><span class="text-ezer-green">'.$n.'.</span> '.e($titre).'</h2><p>'.e($texte).'</p></section>'."\n";
    }

    private function listening(): string
    {
        $sections = [
            ['Objet', "Cette charte encadre l'écoute proposée gratuitement par Ezer Home. Elle protège la personne écoutée comme la personne qui écoute."],
            ['Confidentialité', "Ce que vous partagez reste confidentiel. Il ne peut être révélé qu'en cas de danger grave et immédiat pour vous ou pour une autre personne ; vous en serez alors informé(e) autant que possible."],
            ['Respect et non-jugement', 'La personne qui vous écoute vous accueille sans jugement. Elle ne décide pas à votre place. Les échanges se font dans le respect mutuel.'],
            ['Liberté', "Vous pouvez suspendre ou arrêter l'accompagnement à tout moment, sans avoir à vous justifier."],
            ['Cadre des échanges', "Les rencontres ont lieu aux horaires convenus ensemble. La messagerie sert à organiser les rendez-vous et à échanger entre deux rencontres ; elle n'est pas surveillée en permanence."],
            ['Limites', "L'écoute ne remplace ni un suivi médical ou psychologique, ni un service d'urgence. Si nécessaire, nous vous orienterons vers un professionnel."],
            ['Gratuité', "L'écoute est entièrement gratuite. Aucun paiement ne vous sera jamais demandé dans ce cadre."],
            ['Vos données', 'Seules les personnes chargées de votre accompagnement ont accès à vos informations. Vous pouvez demander leur export ou leur suppression depuis votre profil.'],
            // Exigence du plan (1.4.5) : formulation à faire valider par le juriste avant la mise en ligne.
            ['Notes de séance', "[Texte provisoire, à valider par le juriste] Les notes prises par la personne qui vous écoute sont partagées avec le bureau d'Ezer Home, à des fins de suivi et de supervision."],
        ];

        return collect($sections)->map(fn ($s, $i) => $this->section($i + 1, $s[0], $s[1]))->implode('');
    }
}
