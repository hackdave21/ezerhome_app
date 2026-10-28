/**
 * Ezer Home - Espaces internes (comité, intervenants, administration)
 *
 * <div data-layout="interne-header" data-espace="comite"></div>
 * <div data-layout="interne-nav"   data-espace="comite" data-actif="demandes"></div>
 * <div data-layout="interne-footer"></div>
 *
 * data-espace : comite | intervenant | admin
 */
(function () {
  var B = document.currentScript.src.replace(/assets\/js\/interne\.js.*$/, '');

  var ESPACES = {
    comite: {
      titre: 'Comité d\'assistance',
      accueil: 'pages/comite/demandes.html',
      nav: [
        { id: 'demandes', label: 'Demandes', href: 'pages/comite/demandes.html', badge: '3', badgeSr: 'nouvelles' },
        { id: 'photos', label: 'Photos d\'actions', href: 'pages/comite/photos.html' }
      ]
    },
    intervenant: {
      titre: 'Espace thérapeute / coach',
      accueil: 'pages/intervenant/dashboard.html',
      nav: [
        { titre: 'Accompagnement' },
        { id: 'dashboard', label: 'Tableau de bord', href: 'pages/intervenant/dashboard.html' },
        { id: 'agenda', label: 'Agenda', href: 'pages/intervenant/agenda.html' },
        { id: 'messagerie', label: 'Messagerie', href: 'pages/intervenant/messagerie.html', badge: '3', badgeSr: 'messages non lus' },
        { titre: 'Formation et coaching' },
        { id: 'offres', label: 'Mes offres', href: 'pages/intervenant/offres.html' },
        { id: 'inscrits', label: 'Inscrits', href: 'pages/intervenant/inscrits.html' }
      ]
    },
    admin: {
      titre: 'Administration',
      accueil: 'pages/admin/dashboard.html',
      nav: [
        { titre: 'Pilotage' },
        { id: 'dashboard', label: 'Tableau de bord', href: 'pages/admin/dashboard.html' },
        { titre: 'Accompagnement' },
        { id: 'ecoute', label: 'Demandes d\'écoute', href: 'pages/admin/affectation-ecoute.html', badge: '4', badgeSr: 'demandes à affecter', badgeDark: true },
        { id: 'candidatures', label: 'Candidatures', href: 'pages/admin/candidatures.html', badge: '3', badgeSr: 'candidatures à examiner', badgeDark: true },
        { id: 'reunions', label: 'Suivi des réunions', href: 'pages/admin/reunions.html' },
        { titre: 'Comptes' },
        { id: 'utilisateurs', label: 'Utilisateurs', href: 'pages/admin/utilisateurs.html' },
        { titre: 'Finances et actions' },
        { id: 'paiements', label: 'Paiements', href: 'pages/admin/paiements.html', badge: '5', badgeSr: 'paiements à confirmer', badgeDark: true },
        { id: 'dons', label: 'Dons', href: 'pages/admin/dons.html' },
        { id: 'assistance', label: 'Assistance', href: 'pages/comite/demandes.html' },
        { titre: 'Site' },
        { id: 'cms', label: 'Pages et contenus', href: 'pages/admin/cms-pages.html' },
        { id: 'mediatheque', label: 'Médiathèque', href: 'pages/admin/mediatheque.html' },
        { id: 'equipe', label: 'Organigramme et partenaires', href: 'pages/admin/organigramme.html' },
        { id: 'notifications', label: 'Notifications', href: 'pages/admin/notifications.html' },
        { titre: 'Système' },
        { id: 'parametres', label: 'Paramètres', href: 'pages/admin/parametres.html' },
        { id: 'audit', label: 'Journal d\'audit', href: 'pages/admin/audit.html' }
      ]
    }
  };

  var ICON_LOGOUT = '<svg aria-hidden="true" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><path d="m16 17 5-5-5-5"></path><path d="M21 12H9"></path></svg>';

  function espaceDe(slot) {
    var cle = slot.getAttribute('data-espace') || 'comite';
    var e = ESPACES[cle];
    if (!e) throw new Error('Espace interne inconnu : ' + cle);
    return e;
  }

  function headerInterne(slot) {
    var e = espaceDe(slot);
    var user = slot.getAttribute('data-user') || 'Ablavi';
    var initiales = slot.getAttribute('data-initiales') || user.slice(0, 2).toUpperCase();
    var role = slot.getAttribute('data-role') || '';

    return '' +
      '<header class="bg-white border-b border-ezer-green-borderLight">' +
        '<div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-3 flex items-center justify-between gap-4">' +
          '<a href="' + B + e.accueil + '" class="flex flex-col leading-none">' +
            '<span class="font-cooper text-xl text-ezer-green">Ezer Home</span>' +
            '<span class="text-xs uppercase tracking-wider text-ezer-muted mt-1">' + e.titre + '</span>' +
          '</a>' +
          '<div class="flex items-center gap-4">' +
            '<span class="flex items-center gap-2.5">' +
              '<span aria-hidden="true" class="w-9 h-9 rounded-full bg-ezer-green text-white text-[13px] font-semibold inline-flex items-center justify-center">' + initiales + '</span>' +
              '<span class="hidden lg:inline font-semibold text-sm">' + user + (role ? '<span class="text-ezer-muted font-normal"> · ' + role + '</span>' : '') + '</span>' +
              '<span class="sr-only">Mon compte : ' + user + '</span>' +
            '</span>' +
            '<a href="' + B + 'pages/auth/connexion.html" aria-label="Déconnexion" class="inline-flex items-center gap-2 font-semibold text-sm text-ezer-dark hover:text-ezer-green">' +
              ICON_LOGOUT + '<span class="hidden lg:inline">Déconnexion</span>' +
            '</a>' +
          '</div>' +
        '</div>' +
      '</header>';
  }

  function navInterne(slot) {
    var e = espaceDe(slot);
    var actif = slot.getAttribute('data-actif') || '';

    var items = e.nav.map(function (n) {
      if (n.titre) {
        return '<span aria-hidden="true" class="hidden lg:block px-4 pt-4 pb-1 text-xs font-semibold uppercase tracking-wider text-ezer-muted">' + n.titre + '</span>';
      }
      var on = n.id === actif;
      return '<a href="' + B + n.href + '"' + (on ? ' aria-current="page"' : '') +
        ' class="flex-1 lg:flex-none flex items-center justify-center lg:justify-between gap-2 px-4 min-h-[48px] font-semibold text-[15px] lg:rounded-ezer ' +
        (on ? 'text-ezer-green bg-ezer-green-light shadow-ezer-nav lg:shadow-[inset_3px_0_0_#187d50]' : 'text-ezer-dark hover:bg-ezer-green-pale') + '">' +
        '<span class="inline-flex items-center">' + n.label +
        (n.badge ? '<span class="ml-1.5 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full ' + (n.badgeDark ? 'bg-ezer-dark' : 'bg-ezer-red') + ' text-white text-[11px] font-semibold">' + n.badge + '<span class="sr-only"> ' + n.badgeSr + '</span></span>' : '') +
        '</span></a>';
    }).join('');

    return '' +
      '<nav aria-label="' + e.titre + '" aria-orientation="horizontal" ' +
        'class="w-full lg:w-[220px] lg:shrink-0 -mx-4 px-4 lg:px-0 lg:py-6 flex lg:flex-row lg:flex-col lg:gap-0.5 overflow-x-auto lg:overflow-visible border-b lg:border-0 border-ezer-green-border bg-white sticky top-0 lg:top-[73px] lg:bg-transparent z-40">' +
        items +
      '</nav>';
  }

  function footerInterne() {
    return '<footer class="border-t border-ezer-green-borderLight bg-white px-4 py-5 text-center text-[13px] text-ezer-muted">' +
      'Espace confidentiel · Ezer Home · Les données des demandeurs ne doivent jamais sortir de la plateforme.' +
    '</footer>';
  }

  document.querySelectorAll('[data-layout="interne-header"]').forEach(function (slot) {
    slot.outerHTML = headerInterne(slot);
  });
  document.querySelectorAll('[data-layout="interne-nav"]').forEach(function (slot) {
    slot.outerHTML = navInterne(slot);
  });
  document.querySelectorAll('[data-layout="interne-footer"]').forEach(function (slot) {
    slot.outerHTML = footerInterne();
  });
})();