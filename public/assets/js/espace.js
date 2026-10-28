/**
 * Ezer Home - Espace personnel : coque commune
 *
 * <div data-layout="espace-tabs" data-onglet="ecoute"></div>
 * Les états (chargement / vide / erreur) sont gérés par assets/js/main.js.
 */
(function () {
  var B = document.currentScript.src.replace(/assets\/js\/espace\.js.*$/, '');

  var ONGLETS = [
    { id: 'ecoute', label: 'Écoute', href: 'suivi-ecoute.html', badge: true },
    { id: 'formations', label: 'Formations', href: 'formations.html' },
    { id: 'coaching', label: 'Coaching', href: 'coaching.html' },
    { id: 'profil', label: 'Profil et confidentialité', href: 'profil.html' }
  ];

  var BADGE = '<span class="ml-1.5 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-ezer-red text-white text-[11px] font-semibold">2<span class="sr-only"> messages non lus</span></span>';

  function shellEspace(onglet) {
    var tabs = ONGLETS.map(function (t) {
      var on = t.id === onglet;
      return '<a href="' + B + 'pages/espace/' + t.href + '"' + (on ? ' aria-current="page"' : '') +
        ' class="flex-1 lg:flex-none flex items-center justify-center lg:justify-between gap-2 px-4 py-3.5 min-h-[52px] font-semibold text-[15px] lg:rounded-ezer ' +
        (on ? 'text-ezer-green bg-ezer-green-light shadow-ezer-nav lg:shadow-[inset_3px_0_0_#187d50]' : 'text-ezer-dark hover:bg-ezer-green-pale') + '">' +
        '<span class="inline-flex items-center">' + t.label + (t.badge ? BADGE : '') + '</span>' +
        '</a>';
    }).join('');

    return '' +
      '<section class="bg-ezer-green-light">' +
        '<div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-5 lg:py-6 flex flex-wrap items-end justify-between gap-3">' +
          '<div class="flex flex-col gap-1">' +
            '<p class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Mon espace</p>' +
            '<h1 class="font-cooper font-normal text-2xl lg:text-[34px]">Bonjour, Afi</h1>' +
          '</div>' +
          '<a href="' + B + 'pages/auth/connexion.html" class="inline-flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">Se déconnecter</a>' +
        '</div>' +
      '</section>' +

      '<div class="max-w-[1400px] mx-auto px-6 lg:px-12">' +
        '<nav role="tablist" aria-label="Rubriques de mon espace" aria-orientation="horizontal" ' +
          'class="lg:flex lg:gap-12 -mx-6 px-6 lg:-mx-12 lg:px-0 lg:pt-8 lg:pb-0 lg:flex-col overflow-x-auto border-b lg:border-0 border-ezer-green-border bg-white sticky top-0 lg:top-24 lg:bg-transparent z-40">' +
          tabs +
        '</nav>' +
      '</div>';
  }

  document.querySelectorAll('[data-layout="espace-tabs"]').forEach(function (slot) {
    slot.outerHTML = shellEspace(slot.getAttribute('data-onglet') || '');
  });
})();