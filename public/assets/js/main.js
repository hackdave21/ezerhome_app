/**
 * Ezer Home - Interactions JavaScript légères
 */
var EZER_BASE = (document.currentScript && document.currentScript.src || '').replace(/assets\/js\/main\.js.*$/, '');

document.addEventListener('DOMContentLoaded', () => {
  // Le menu mobile est géré par assets/js/layout.js

  // Simple Tab switcher
  const tabButtons = document.querySelectorAll('[data-tab-target]');
  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.getAttribute('data-tab-target');
      const group = btn.getAttribute('data-tab-group') || 'default';

      // Update buttons
      document.querySelectorAll(`[data-tab-group="${group}"]`).forEach(b => {
        b.classList.remove('text-ezer-green', 'border-b-2', 'border-ezer-green', 'font-semibold', 'bg-ezer-green-light');
        b.classList.add('text-ezer-muted');
      });
      btn.classList.add('text-ezer-green', 'border-b-2', 'border-ezer-green', 'font-semibold');
      btn.classList.remove('text-ezer-muted');

      // Update panes
      document.querySelectorAll(`[data-tab-pane="${group}"]`).forEach(pane => {
        pane.classList.add('hidden');
      });
      const activePane = document.getElementById(target);
      if (activePane) {
        activePane.classList.remove('hidden');
      }
    });
  });

  // State switcher demo helper (for testing different screen states: errors, loading, success)
  const stateToggles = document.querySelectorAll('[data-state-target]');
  stateToggles.forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.getAttribute('data-state-target');
      const group = btn.getAttribute('data-state-group') || 'states';

      const holder = document.querySelector('[data-state-switcher="' + group + '"]');
      const on = (holder && holder.getAttribute('data-active-class')) || 'bg-ezer-dark text-white';
      const off = (holder && holder.getAttribute('data-idle-class')) || 'bg-white text-ezer-dark';

      document.querySelectorAll('[data-state-group="' + group + '"]').forEach(b => {
        b.classList.remove(...on.split(' '));
        b.classList.add(...off.split(' '));
        b.setAttribute('aria-pressed', 'false');
      });
      btn.classList.remove(...off.split(' '));
      btn.classList.add(...on.split(' '));
      btn.setAttribute('aria-pressed', 'true');

      document.querySelectorAll('[data-state-pane="' + group + '"]').forEach(pane => {
        pane.classList.add('hidden');
      });
      const activePane = document.getElementById(target);
      if (activePane) {
        activePane.classList.remove('hidden');
      }
    });
  });

  // États génériques (chargement / vide / erreur) utilisés par l'espace personnel
  // et tous les espaces internes (comité, intervenants, administration).
  initEtats();
});

function initEtats() {
  const B = EZER_BASE;

  function chargement(box) {
    const lignes = [[62, 34], [74, 26], [48, 40], [66, 30]].map(w =>
      '<div class="flex items-center gap-3 py-2">' +
      '<span class="shrink-0 w-10 h-10 rounded-full bg-ezer-green-borderLight"></span>' +
      '<span class="flex-1 flex flex-col gap-1.5">' +
      '<span class="h-2.5 rounded-full bg-ezer-green-borderLight" style="width:' + w[0] + '%"></span>' +
      '<span class="h-2.5 rounded-full bg-ezer-green-borderLight" style="width:' + w[1] + '%"></span>' +
      '</span></div>'
    ).join('');

    return '<div role="status" aria-live="polite" aria-busy="true" class="flex flex-col gap-5">' +
      '<p class="flex items-center gap-3 font-semibold">' +
      '<svg aria-hidden="true" class="shrink-0 w-5 h-5 animate-spin text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 3a9 9 0 1 0 9 9"></path></svg>' +
      (box.getAttribute('data-chargement-titre') || 'Chargement…') + '</p>' +
      '<div class="flex flex-col">' + lignes + '</div>' +
      '<p class="text-sm text-ezer-muted">Connexion lente ? Les informations s\'affichent dès qu\'elles arrivent. Rien n\'est perdu.</p>' +
    '</div>';
  }

  function vide(box) {
    return '<div class="border border-dashed border-ezer-borderMuted rounded-ezer bg-ezer-green-pale p-6 lg:p-8 flex flex-col items-center text-center gap-3">' +
      '<span aria-hidden="true" class="w-12 h-12 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">' +
        '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10H3z"></path><path d="M3 9l2.5-5h13L21 9"></path><path d="M12 14h.01"></path></svg>' +
      '</span>' +
      '<h2 class="font-cooper font-normal text-2xl">' + (box.getAttribute('data-vide-titre') || 'Rien à afficher') + '</h2>' +
      '<p class="text-ezer-muted max-w-[520px]">' + (box.getAttribute('data-vide-texte') || '') + '</p>' +
      (box.getAttribute('data-vide-cta')
        ? '<a href="' + B + box.getAttribute('data-vide-href') + '" class="mt-2 inline-flex items-center justify-center min-h-[48px] px-6 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">' + box.getAttribute('data-vide-cta') + '</a>'
        : '') +
    '</div>';
  }

  function erreur(box) {
    return '<div role="alert" class="border-2 border-ezer-dark rounded-ezer p-5 lg:px-6 flex flex-col items-center text-center gap-3">' +
      '<span aria-hidden="true" class="w-12 h-12 rounded-full border-2 border-ezer-dark inline-flex items-center justify-center">' +
        '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v6M12 16.5v.5"></path></svg>' +
      '</span>' +
      '<h2 class="font-cooper font-normal text-2xl">' + (box.getAttribute('data-erreur-titre') || 'Impossible d\'afficher pour le moment.') + '</h2>' +
      '<p class="text-ezer-muted max-w-[520px]">La connexion semble interrompue ou le service ne répond pas. Vos données sont en sécurité.</p>' +
      '<button type="button" data-etat-retry class="mt-1 inline-flex items-center justify-center min-h-[48px] px-6 rounded-ezer border-2 border-ezer-green bg-white text-ezer-green font-semibold hover:bg-ezer-green-light transition-colors">Réessayer</button>' +
      '<p class="text-sm text-ezer-muted">Si le problème continue, indiquez ce code au support : <strong class="font-semibold">' + (box.getAttribute('data-erreur-code') || 'EZ-503') + '</strong></p>' +
    '</div>';
  }

  document.querySelectorAll('[data-etat-panes]').forEach(box => {
    const id = box.getAttribute('data-etat');
    const normal = box.querySelector('[data-etat-pane="normal"]');
    const switcher = document.querySelector('[data-etat-switcher="' + id + '"]');

    const panes = document.createElement('div');
    panes.innerHTML =
      '<div data-etat-pane="chargement" class="hidden">' + chargement(box) + '</div>' +
      '<div data-etat-pane="vide" class="hidden">' + vide(box) + '</div>' +
      '<div data-etat-pane="erreur" class="hidden">' + erreur(box) + '</div>';
    box.insertBefore(panes, normal);

    function appliquer(etat) {
      box.querySelectorAll('[data-etat-pane]').forEach(p => {
        p.classList.toggle('hidden', p.getAttribute('data-etat-pane') !== etat);
      });
      if (!switcher) return;
      switcher.querySelectorAll('[data-etat-btn]').forEach(b => {
        const on = b.getAttribute('data-etat-btn') === etat;
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
        b.classList.toggle('bg-ezer-dark', on);
        b.classList.toggle('text-white', on);
        b.classList.toggle('bg-white', !on);
        b.classList.toggle('text-ezer-dark', !on);
        b.classList.toggle('border', !on);
        b.classList.toggle('border-ezer-borderMuted', !on);
      });
    }

    box.addEventListener('click', ev => {
      if (ev.target.closest('[data-etat-retry]')) appliquer('chargement');
    });

    if (switcher) {
      switcher.querySelectorAll('[data-etat-btn]').forEach(b => {
        b.addEventListener('click', () => appliquer(b.getAttribute('data-etat-btn')));
      });
    }
    appliquer('normal');
  });
}
