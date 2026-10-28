<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Pages légales : textes et structure repris de la maquette (pages/site/legal.html).
 * Les passages entre crochets sont à compléter par le client via le back-office.
 */
class PageSeeder extends Seeder
{
    private const CHECK = '<svg aria-hidden="true" class="shrink-0 mt-1 w-[18px] h-[18px] text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>';

    public function run(): void
    {
        foreach ($this->documents() as $slug => $doc) {
            // firstOrCreate : ne jamais écraser un texte déjà édité par l'Admin.
            Page::firstOrCreate(['slug' => $slug], [
                'title' => $doc['titre'],
                'body' => $this->html($doc),
                'is_published' => true,
            ]);
        }
    }

    /** @param array{intro: string, sections: list<array<string, mixed>>} $doc */
    private function html(array $doc): string
    {
        $html = '<p class="text-[17px] leading-relaxed">'.e($doc['intro'])."</p>\n";

        foreach ($doc['sections'] as $i => $s) {
            $html .= '<section id="'.$s['id'].'" class="flex flex-col gap-3.5 scroll-mt-36" aria-labelledby="'.$s['id'].'-t">'
                .'<h2 id="'.$s['id'].'-t" class="font-cooper font-normal text-2xl lg:text-[26px]"><span class="text-ezer-green">'.($i + 1).'.</span> '.e($s['titre']).'</h2>';

            foreach ($s['paras'] as $p) {
                $html .= '<p>'.e($p).'</p>';
            }

            if (! empty($s['liste'])) {
                $html .= '<ul class="flex flex-col gap-2 list-none">';
                foreach ($s['liste'] as $li) {
                    $html .= '<li class="flex items-start gap-2.5">'.self::CHECK.'<span>'.e($li).'</span></li>';
                }
                $html .= '</ul>';
            }

            $html .= "</section>\n";
        }

        return $html;
    }

    /** @return array<string, array{titre: string, intro: string, sections: list<array<string, mixed>>}> */
    private function documents(): array
    {
        return [
            'mentions-legales' => [
                'titre' => 'Mentions légales',
                'intro' => "Cette page présente les informations légales relatives au site d'Ezer Home.",
                'sections' => [
                    ['id' => 'editeur', 'titre' => 'Éditeur du site', 'paras' => [
                        "Le site [adresse du site] est édité par l'association Ezer Home, association à but non lucratif déclarée sous le récépissé n° [NUMÉRO] du [date].",
                        'Siège : [Quartier, rue], Lomé, Togo. Téléphone : +228 [00 00 00 00]. E-mail : [contact@ezerhome.tg].',
                    ]],
                    ['id' => 'publication', 'titre' => 'Directeur de la publication', 'paras' => ['[NOM Prénoms], en qualité de [fonction].']],
                    ['id' => 'hebergement', 'titre' => 'Hébergement', 'paras' => ["Le site est hébergé par [nom de l'hébergeur], [adresse], [téléphone]."]],
                    ['id' => 'propriete', 'titre' => 'Propriété intellectuelle', 'paras' => [
                        "Le nom Ezer Home, son logo et l'ensemble des contenus du site (textes, images, documents) appartiennent à l'association, sauf mention contraire.",
                        "Toute reproduction, même partielle, sans autorisation écrite de l'association est interdite.",
                    ]],
                    ['id' => 'credits', 'titre' => 'Crédits', 'paras' => ['Conception et réalisation : [nom]. Photographies : [crédits].']],
                    ['id' => 'ed-contact', 'titre' => 'Contact', 'paras' => ['Pour toute question relative au site, écrivez-nous à [contact@ezerhome.tg].']],
                ],
            ],
            'confidentialite' => [
                'titre' => 'Politique de confidentialité',
                'intro' => 'La confiance est au cœur de notre action. Cette page explique, simplement, quelles données nous collectons, pourquoi, et comment vous gardez la main sur elles.',
                'sections' => [
                    ['id' => 'responsable', 'titre' => 'Qui est responsable de vos données ?', 'paras' => ["L'association Ezer Home, dont le siège est situé [Quartier, rue], Lomé, est responsable des données collectées sur ce site."]],
                    ['id' => 'donnees', 'titre' => 'Quelles données collectons-nous ?', 'paras' => ['Nous ne demandons que ce qui est nécessaire au service que vous choisissez :'], 'liste' => [
                        'Écoute : un prénom ou un pseudonyme, un numéro de téléphone, et ce que vous choisissez de partager.',
                        "Assistance : nom et prénoms, téléphone, e-mail facultatif, type d'aide et description de votre situation.",
                        'Formations et coaching : identité, coordonnées, inscriptions et paiements.',
                        'Dons : montant et moyen de paiement, plus votre nom ou contact si vous les indiquez. Le don anonyme ne demande aucune donnée personnelle.',
                        'Contact : les informations saisies dans le formulaire.',
                    ]],
                    ['id' => 'finalites', 'titre' => 'Pourquoi ?', 'paras' => ['Uniquement pour traiter votre demande, assurer le service choisi et vous recontacter. Jamais à des fins commerciales.']],
                    ['id' => 'acces', 'titre' => 'Qui y a accès ?', 'paras' => [
                        "Seules les personnes de l'association chargées de votre demande : l'équipe d'écoute, le comité d'assistance ou le bureau, selon le cas.",
                        'Vos données ne sont ni vendues, ni louées, ni cédées.',
                    ]],
                    ['id' => 'conservation', 'titre' => 'Combien de temps les gardons-nous ?', 'paras' => ['[Durées de conservation par type de données, à définir.]']],
                    ['id' => 'securite', 'titre' => 'Comment les protégeons-nous ?', 'paras' => ['Accès protégé par mot de passe, échanges chiffrés (HTTPS), et accès réservé aux seules personnes habilitées.']],
                    ['id' => 'droits', 'titre' => 'Vos droits', 'paras' => [
                        'Vous pouvez demander à consulter, corriger ou supprimer vos données, ou retirer votre consentement, en écrivant à [contact@ezerhome.tg].',
                        'Ces droits s\'exercent conformément à [référence de la loi togolaise sur la protection des données personnelles]. Vous pouvez aussi saisir [autorité compétente].',
                    ]],
                    ['id' => 'cookies', 'titre' => 'Cookies', 'paras' => ['Le site utilise uniquement les cookies nécessaires à son fonctionnement (session, sécurité). [À confirmer.]']],
                ],
            ],
            'cgu' => [
                'titre' => "Conditions générales d'utilisation",
                'intro' => "Ces conditions encadrent l'utilisation du site Ezer Home et de ses services. En utilisant le site, vous les acceptez.",
                'sections' => [
                    ['id' => 'acces-site', 'titre' => 'Accès au site', 'paras' => ["Le site est accessible gratuitement. L'association s'efforce d'en assurer la disponibilité, sans pouvoir la garantir en permanence."]],
                    ['id' => 'compte', 'titre' => 'Compte personnel', 'paras' => ["Un compte est nécessaire pour l'écoute, les formations et le coaching. Vous êtes responsable de la confidentialité de votre mot de passe."]],
                    ['id' => 'services', 'titre' => 'Services gratuits et tarifés', 'paras' => ['Chaque offre porte un badge « Gratuit » ou « Tarifé » :'], 'liste' => [
                        'Écoute et accompagnement : gratuit.',
                        'Assistance matérielle : gratuite, sans compte.',
                        "Formations et coaching : gratuits ou tarifés, selon l'offre affichée.",
                    ]],
                    ['id' => 'paiements', 'titre' => 'Inscriptions et paiements', 'paras' => [
                        'Les prix sont indiqués en francs CFA. Le paiement se fait par Mixx By Yas, Moov Money ou en espèces au siège.',
                        "L'inscription est confirmée après vérification du paiement par le bureau. Conditions d'annulation : [à définir].",
                    ]],
                    ['id' => 'dons', 'titre' => 'Dons', 'paras' => ['Les dons sont libres. Ils sont confirmés manuellement par le bureau après vérification du paiement.']],
                    ['id' => 'charte', 'titre' => "Charte d'écoute", 'paras' => ["L'accès à l'écoute suppose l'acceptation et la signature de la charte d'écoute."]],
                    ['id' => 'responsabilite', 'titre' => 'Responsabilité', 'paras' => ["L'écoute et le coaching ne remplacent ni un avis médical, ni un service d'urgence."]],
                    ['id' => 'droit', 'titre' => 'Droit applicable', 'paras' => ['Les présentes conditions sont soumises au droit togolais. Elles peuvent évoluer ; la date de mise à jour figure en haut de page.']],
                ],
            ],
        ];
    }
}
