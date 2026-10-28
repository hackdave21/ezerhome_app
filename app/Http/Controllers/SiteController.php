<?php

namespace App\Http\Controllers;

use App\Models\Page;
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
