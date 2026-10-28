<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Navigation des espaces internes (comité, intervenant, administration), selon la maquette (interne.js).
 * Les entrées dont la page n'existe pas encore sont affichées mais pointent vers « # » : elles se
 * branchent toutes seules dès que la route correspondante est déclarée.
 */
class InternalNav
{
    public const SPACES = [
        'comite' => [
            'titre' => "Comité d'assistance",
            'accueil' => 'comite.demandes',
            'nav' => [
                ['id' => 'demandes', 'label' => 'Demandes', 'route' => 'comite.demandes'],
                ['id' => 'photos', 'label' => "Photos d'actions", 'route' => 'comite.photos'],
            ],
        ],
        'intervenant' => [
            'titre' => 'Espace thérapeute / coach',
            'accueil' => 'intervenant.dashboard',
            'nav' => [
                ['titre' => 'Accompagnement'],
                ['id' => 'dashboard', 'label' => 'Tableau de bord', 'route' => 'intervenant.dashboard'],
                ['id' => 'agenda', 'label' => 'Agenda', 'route' => 'intervenant.agenda'],
                ['id' => 'messagerie', 'label' => 'Messagerie', 'route' => 'intervenant.messagerie'],
                ['titre' => 'Formation et coaching'],
                ['id' => 'offres', 'label' => 'Mes offres', 'route' => 'intervenant.offres'],
                ['id' => 'inscrits', 'label' => 'Inscrits', 'route' => 'intervenant.inscrits'],
            ],
        ],
        'admin' => [
            'titre' => 'Administration',
            'accueil' => 'admin.dashboard',
            'nav' => [
                ['titre' => 'Pilotage'],
                ['id' => 'dashboard', 'label' => 'Tableau de bord', 'route' => 'admin.dashboard'],
                ['titre' => 'Accompagnement'],
                ['id' => 'ecoute', 'label' => "Demandes d'écoute", 'route' => 'admin.ecoute'],
                ['id' => 'candidatures', 'label' => 'Candidatures', 'route' => 'admin.candidatures'],
                ['id' => 'reunions', 'label' => 'Suivi des réunions', 'route' => 'admin.reunions'],
                ['titre' => 'Comptes'],
                ['id' => 'utilisateurs', 'label' => 'Utilisateurs', 'route' => 'admin.utilisateurs'],
                ['titre' => 'Finances et actions'],
                ['id' => 'paiements', 'label' => 'Paiements', 'route' => 'admin.paiements'],
                ['id' => 'dons', 'label' => 'Dons', 'route' => 'admin.dons'],
                ['id' => 'assistance', 'label' => 'Assistance', 'route' => 'comite.demandes'],
                ['titre' => 'Site'],
                ['id' => 'cms', 'label' => 'Pages et contenus', 'route' => 'admin.cms'],
                ['id' => 'mediatheque', 'label' => 'Médiathèque', 'route' => 'admin.mediatheque'],
                ['id' => 'equipe', 'label' => 'Organigramme et partenaires', 'route' => 'admin.organigramme'],
                ['id' => 'notifications', 'label' => 'Notifications', 'route' => 'admin.notifications'],
                ['titre' => 'Système'],
                ['id' => 'parametres', 'label' => 'Paramètres', 'route' => 'admin.parametres'],
                ['id' => 'audit', 'label' => "Journal d'audit", 'route' => 'admin.audit'],
            ],
        ],
    ];

    /** @return array{titre: string, accueil: string, nav: list<array<string, mixed>>} */
    public static function space(string $space): array
    {
        $config = self::SPACES[$space] ?? self::SPACES['comite'];

        $config['accueil'] = self::url($config['accueil']);
        $config['nav'] = array_map(fn ($item) => isset($item['route']) ? $item + ['url' => self::url($item['route'])] : $item, $config['nav']);

        return $config;
    }

    private static function url(string $route): string
    {
        return Route::has($route) ? route($route) : '#';
    }
}
