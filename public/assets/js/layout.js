/**
 * Ezer Home - En-tête et pied de page partagés
 *
 * Usage dans une page :
 *   <div data-layout="header" data-active="ecoute"></div>
 *   ...
 *   <div data-layout="footer" data-sobre="1"></div>
 *   <script src="../../assets/js/layout.js"></script>
 *
 * Options en-tête : data-active, data-menu="ouvert", data-don="1"
 * Options pied    : data-sobre="1" (masque les liens commerciaux)
 */
(function () {
  var B = document.currentScript.src.replace(/assets\/js\/layout\.js.*$/, '');

  var NAV = [
    { id: 'accueil', label: 'Accueil', href: 'pages/site/accueil.html' },
    { id: 'a-propos', label: 'À propos', href: 'pages/site/a-propos.html' },
    { id: 'approche', label: 'Notre approche', href: 'pages/site/accueil.html#notre-approche' },
    { id: 'parcours', label: 'Le Parcours', href: 'pages/site/accueil.html#le-parcours' },
    { id: 'ecoute', label: 'Écoute', href: 'pages/site/ecoute.html', badge: true },
    { id: 'formation', label: 'Formation', href: 'pages/site/formations.html' },
    { id: 'coaching', label: 'Coaching', href: 'pages/site/coaching.html' },
    { id: 'assistance', label: 'Assistance', href: 'pages/assistance/demande.html', badge: true },
    { id: 'contact', label: 'Contact', href: 'pages/site/contact-particulier.html' }
  ];

  var MOBILE_EXTRA = [
    { label: 'Notre équipe', href: 'pages/site/equipe.html' },
    { label: 'Club EZER (Telegram et WhatsApp)', href: 'pages/site/accueil.html#club-ezer' },
    { label: 'Une parole pour aujourd\'hui', href: 'pages/site/accueil.html#parole-du-jour' },
    { label: 'Charte d\'accompagnement', href: 'pages/site/accueil.html#charte-accompagnement' }
  ];

  var ICON = {
    pin: '<svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"></path><circle cx="12" cy="9.5" r="2.5"></circle></svg>',
    tel: '<svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"></path></svg>',
    mail: '<svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>',
    user: '<svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>',
    chat: '<svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3.5 20.5l1.3-4A8.5 8.5 0 1 1 8 19.6z"></path><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2z"></path></svg>',
    burger: '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"></path></svg>',
    close: '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"></path></svg>',
    chevron: '<svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"></path></svg>'
  };

  var BADGE = '<span class="text-xs font-semibold px-2 py-0.5 rounded-full border border-ezer-green bg-ezer-green-light text-ezer-green">Gratuit</span>';

  function header(opts) {
    var actif = opts.active || '';
    var ouvert = opts.menu === 'ouvert';
    var showDon = opts.don !== false;

    var navDesktop = NAV.map(function (n) {
      var on = n.id === actif;
      return '<a href="' + B + n.href + '"' + (on ? ' aria-current="page"' : '') +
        ' class="px-3 py-2 font-semibold text-[15px] ' +
        (on ? 'text-ezer-green shadow-ezer-nav' : 'text-ezer-dark hover:text-ezer-green') + '">' +
        n.label + '</a>';
    }).join('');

    var navMobile = NAV.concat(MOBILE_EXTRA).map(function (n) {
      var on = n.id === actif || n.id === 'equipe' && actif === 'equipe';
      return '<li class="border-b border-ezer-green-borderLight last:border-0">' +
        '<a href="' + B + n.href + '"' + (on ? ' aria-current="page"' : '') +
        ' class="flex items-center justify-between gap-3 min-h-[52px] font-semibold text-[17px] ' +
        (on ? 'text-ezer-green' : 'text-ezer-dark') + '">' +
        '<span class="inline-flex items-center gap-2.5">' + n.label + (n.badge ? BADGE : '') + '</span>' +
        ICON.chevron + '</a></li>';
    }).join('');

    return '' +
      '<div class="bg-ezer-dark text-white text-xs lg:text-sm">' +
        '<div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-2 flex flex-wrap items-center justify-between gap-2 lg:gap-6">' +
          '<div class="flex flex-wrap items-center gap-4 lg:gap-7">' +
            '<span class="inline-flex items-center gap-1.5">' + ICON.pin + '138, Boulevard du 13 Janvier, Hanoukopé, Lomé</span>' +
            '<a href="tel:+22898135757" class="hover:underline inline-flex items-center gap-1.5">' + ICON.tel + '+228 98 13 57 57</a>' +
            '<a href="mailto:mail@ezerhome.org" class="hover:underline inline-flex items-center gap-1.5">' + ICON.mail + 'mail@ezerhome.org</a>' +
          '</div>' +
          '<a href="' + B + 'pages/auth/connexion.html" class="inline-flex items-center gap-2 font-semibold hover:text-ezer-green-light">' +
            ICON.user + 'Mon espace</a>' +
        '</div>' +
      '</div>' +

      '<header class="sticky top-0 z-50 bg-white border-b border-ezer-green-borderLight">' +
        '<div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-3 flex items-center justify-between gap-4">' +
          '<a href="' + B + 'pages/site/accueil.html" aria-label="Ezer Home, retour à l\'accueil" class="block shrink-0">' +
            '<img src="' + B + 'assets/images/Logo_Ezer-01.png" alt="Logo Ezer Home" class="h-16 lg:h-20 w-auto object-contain">' +
          '</a>' +

          '<nav aria-label="Navigation principale" class="hidden lg:flex items-center gap-1">' + navDesktop + '</nav>' +

          '<div class="hidden lg:flex items-center gap-3">' +
            (showDon ? '<a href="' + B + 'pages/dons/don.html" class="inline-flex items-center justify-center min-h-[44px] px-4 rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold text-[15px] hover:bg-ezer-green hover:text-white transition-colors">Faire un don <span class="text-xs ml-1 font-normal opacity-85">(dès 100 F)</span></a>' : '') +
            '<a href="https://wa.me/22898135757" target="_blank" rel="noopener" class="inline-flex items-center gap-2 min-h-[48px] px-4 rounded-ezer bg-ezer-green text-white font-semibold text-[15px] hover:bg-ezer-green-dark transition-colors">' + ICON.chat + 'WhatsApp</a>' +
          '</div>' +

          '<div class="flex lg:hidden items-center gap-2">' +
            '<a href="https://wa.me/22898135757" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 min-h-[40px] px-3 rounded-ezer bg-ezer-green text-white font-semibold text-sm">' + ICON.chat + 'WhatsApp</a>' +
            '<button type="button" data-toggle-menu="menu-mobile" data-menu-toggle aria-expanded="' + ouvert + '" aria-controls="menu-mobile" ' +
              'class="w-11 h-11 inline-flex items-center justify-center border border-ezer-borderMuted rounded-ezer text-ezer-dark hover:bg-gray-100" ' +
              'aria-label="' + (ouvert ? 'Fermer le menu' : 'Ouvrir le menu') + '">' + (ouvert ? ICON.close : ICON.burger) + '</button>' +
          '</div>' +
        '</div>' +

        '<nav id="menu-mobile" aria-label="Menu principal" class="' + (ouvert ? '' : 'hidden ') + 'lg:hidden border-t border-ezer-green-borderLight bg-white px-4 py-4 flex flex-col gap-4">' +
          '<ul class="flex flex-col">' + navMobile + '</ul>' +
          '<div class="grid grid-cols-2 gap-3">' +
            '<a href="' + B + 'pages/auth/connexion.html" class="inline-flex items-center justify-center min-h-[48px] rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold text-[15px]">Se connecter</a>' +
            '<a href="' + B + 'pages/auth/inscription.html" class="inline-flex items-center justify-center min-h-[48px] rounded-ezer bg-ezer-green text-white font-semibold text-[15px]">Créer un compte</a>' +
          '</div>' +
          (showDon ? '<a href="' + B + 'pages/dons/don.html" class="w-full inline-flex items-center justify-center min-h-[48px] rounded-ezer border border-ezer-borderMuted text-ezer-dark font-semibold text-[15px]">Faire un don (dès 100 FCFA)</a>' : '') +
        '</nav>' +
      '</header>';
  }

  function footer(opts) {
    var sobre = opts.sobre === true || opts.sobre === 'true';
    var groupes = [
      {
        titre: 'Services',
        liens: [
          { label: 'Écoute', href: 'pages/site/ecoute.html' },
          { label: 'Formation', href: 'pages/site/formations.html' },
          { label: 'Coaching', href: 'pages/site/coaching.html' },
          { label: 'Assistance', href: 'pages/assistance/demande.html' }
        ]
      },
      {
        titre: 'L\'association',
        liens: [
          { label: 'À propos', href: 'pages/site/a-propos.html' },
          { label: 'Notre équipe', href: 'pages/site/equipe.html' },
          { label: 'Partenaires', href: 'pages/site/partenaires.html' },
          { label: 'Faire un don', href: 'pages/dons/don.html', commercial: true },
          { label: 'À quoi servent les dons', href: 'pages/site/usage-dons.html', commercial: true },
          { label: 'Devenir thérapeute ou coach', href: 'pages/intervenant/candidature.html', commercial: true }
        ]
      },
      {
        titre: 'Informations',
        liens: [
          { label: 'Mentions légales', href: 'pages/site/legal.html' },
          { label: 'Confidentialité', href: 'pages/site/legal.html#confidentialite' },
          { label: 'CGU', href: 'pages/site/legal.html#cgu' },
          { label: 'Contact', href: 'pages/site/contact-particulier.html' },
          { label: 'Se connecter', href: 'pages/auth/connexion.html' }
        ]
      }
    ];

    var colonnes = groupes.map(function (g) {
      var liens = g.liens.filter(function (l) { return !sobre || !l.commercial; }).map(function (l) {
        return '<a href="' + B + l.href + '" class="text-[15px] text-ezer-dark hover:text-ezer-green py-1.5">' + l.label + '</a>';
      }).join('');
      return '<nav aria-label="' + g.titre + '" class="flex-1 min-w-[140px] flex flex-col">' +
        '<p class="text-xs font-semibold uppercase tracking-wider text-ezer-muted mb-2">' + g.titre + '</p>' +
        liens + '</nav>';
    }).join('');

    return '' +
      '<footer class="bg-white border-t-4 border-t-ezer-brown mt-12">' +
        '<div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-10 lg:py-14 flex flex-col gap-8">' +
          '<div class="flex flex-wrap gap-8">' +
            '<div class="flex-1 min-w-[260px] max-w-[320px] flex flex-col gap-4">' +
              '<img src="' + B + 'assets/images/Logo_Ezer-01.png" alt="Ezer Home" class="h-14 w-auto object-contain self-start">' +
              '<address class="not-italic flex flex-col gap-1 text-[15px] text-ezer-dark">' +
                '<span>138, Boulevard du 13 Janvier, Hanoukopé, Lomé</span>' +
                '<a href="tel:+22898135757" class="text-ezer-green hover:underline">+228 98 13 57 57</a>' +
                '<a href="mailto:mail@ezerhome.org" class="text-ezer-green hover:underline">mail@ezerhome.org</a>' +
              '</address>' +
            '</div>' +
            colonnes +
          '</div>' +
          '<div class="border-t border-ezer-green-borderLight pt-6 flex flex-wrap items-center justify-between gap-4 text-[13px] text-ezer-muted">' +
            '<p>© 2026 Ezer Home · Accompagnement · Assistance · Formation</p>' +
            '<a href="' + B + 'index.html" class="text-ezer-green font-semibold hover:underline">Plan du prototype</a>' +
          '</div>' +
        '</div>' +
      '</footer>';
  }

  function miniFooter() {
    return '' +
      '<footer class="border-t border-ezer-green-borderLight bg-white px-4 py-5 text-sm text-ezer-muted">' +
        '<div class="max-w-[1400px] mx-auto flex flex-wrap justify-between gap-2 lg:gap-6">' +
          '<span>© 2026 Ezer Home</span>' +
          '<nav aria-label="Liens utiles" class="flex flex-wrap gap-1 lg:gap-4">' +
            '<a href="' + B + 'pages/site/legal.html#confidentialite" class="py-1.5 text-ezer-green hover:underline">Confidentialité</a>' +
            '<a href="' + B + 'pages/site/legal.html#cgu" class="py-1.5 text-ezer-green hover:underline">CGU</a>' +
            '<a href="' + B + 'pages/site/contact-particulier.html" class="py-1.5 text-ezer-green hover:underline">Contact</a>' +
          '</nav>' +
        '</div>' +
      '</footer>';
  }

  function mount() {
    var slots = document.querySelectorAll('[data-layout]');
    slots.forEach(function (slot) {
      var kind = slot.getAttribute('data-layout');
      var sobre = slot.getAttribute('data-sobre');
      var html;
      if (kind === 'footer') html = footer({ sobre: sobre });
      else if (kind === 'footer-mini') html = miniFooter();
      else html = header({
        active: slot.getAttribute('data-active') || '',
        menu: slot.getAttribute('data-menu') || '',
        don: slot.getAttribute('data-don') !== '0'
      });
      slot.outerHTML = html;
    });

    document.addEventListener('click', function (ev) {
      var btn = ev.target.closest && ev.target.closest('[data-menu-toggle]');
      if (!btn) return;
      var panel = document.getElementById('menu-mobile');
      if (!panel) return;
      var isHidden = panel.classList.contains('hidden');
      panel.classList.toggle('hidden', !isHidden);
      btn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
      btn.setAttribute('aria-label', isHidden ? 'Fermer le menu' : 'Ouvrir le menu');
      btn.innerHTML = isHidden ? ICON.close : ICON.burger;
    });

    document.addEventListener('click', function (ev) {
      var link = ev.target.closest && ev.target.closest('#menu-mobile a');
      if (!link) return;
      var panel = document.getElementById('menu-mobile');
      var btn = document.querySelector('[data-menu-toggle]');
      if (panel && !panel.classList.contains('hidden')) {
        panel.classList.add('hidden');
        if (btn) {
          btn.setAttribute('aria-expanded', 'false');
          btn.setAttribute('aria-label', 'Ouvrir le menu');
          btn.innerHTML = ICON.burger;
        }
      }
    });
  }

  mount();
})();