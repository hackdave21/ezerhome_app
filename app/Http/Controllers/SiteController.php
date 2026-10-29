<?php

namespace App\Http\Controllers;

use App\Models\OrgMember;
use App\Models\Page;
use App\Models\Partner;
use Illuminate\Contracts\View\View;

class SiteController extends Controller
{
    /** Documents légaux, dont le texte est fourni par le client et éditable par l'Admin. */
    public const LEGAL = [
        'mentions-legales' => 'Mentions légales',
        'confidentialite' => 'Confidentialité',
        'cgu' => 'CGU',
    ];

    public function legal(string $slug): View
    {
        abort_unless(isset(self::LEGAL[$slug]), 404);

        $page = Page::published($slug);
        abort_if($page === null, 404);

        return view('site.legal', [
            'page' => $page,
            'slug' => $slug,
            'docs' => self::LEGAL,
            'sommaire' => $this->sommaire((string) $page->body),
        ]);
    }

    public function usageDons(): View
    {
        return view('site.usage-dons', ['page' => Page::published('a-quoi-servent-les-dons')]);
    }

    /** Page « Notre équipe » : membres visibles, groupés par comité (grille V1). */
    public function equipe(): View
    {
        return view('site.equipe', [
            'groupes' => OrgMember::visible()->with('photo')->get()->groupBy('committee'),
        ]);
    }

    /** Page « Nos partenaires » : logos publiés (accord écrit + actif). */
    public function partenaires(): View
    {
        return view('site.partenaires', [
            'partenaires' => Partner::published()->with('logo')->get(),
        ]);
    }

    /**
     * Sommaire déduit des <section id="…"> du texte : un titre h2 par section.
     *
     * @return list<array{id: string, titre: string}>
     */
    private function sommaire(string $html): array
    {
        preg_match_all('/<section[^>]*\sid="([^"]+)"[^>]*>.*?<h2[^>]*>(.*?)<\/h2>/s', $html, $m, PREG_SET_ORDER);

        return array_map(fn (array $x) => [
            'id' => $x[1],
            'titre' => trim(html_entity_decode(strip_tags($x[2]))),
        ], $m);
    }
}
