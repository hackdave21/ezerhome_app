/**
 * Ezer Home - Prévisualisation des e-mails transactionnels
 *
 * Usage dans une page :
 *   <div data-ezer-mail="reunion-programmee" data-ezer-mail-format="ordinateur"></div>
 *   <div data-ezer-mail-nav data-ezer-mail-nav-actif="reunion-programmee"></div>
 *   <script src="../../assets/js/emails.js"></script>
 *
 * Format : "ordinateur" (gabarit 680 px) ou "mobile" (téléphone, 375 px)
 * Gabarit conforme à MessageModele.dc.html (maquette).
 */
(function () {
  var B = document.currentScript.src.replace(/assets\/js\/emails\.js.*$/, '');

  var LOGO = '<a href="https://ezerhome.tg" style="display: inline-block; text-decoration: none"><img src="' + B + 'assets/images/Logo_Ezer-01.png" alt="Ezer Home" width="118" height="62" style="display:block;border:0"></a>';

  var S = {
    ordinateur: { largeur: '680px', pad: '24px 40px', cellPad: '28px 40px', piedPad: '24px 40px', h1: '28px', h1lh: '34px', btnMax: '320px' },
    mobile: { largeur: '375px', pad: '12px', cellPad: '20px 18px', piedPad: '20px 18px', h1: '24px', h1lh: '30px', btnMax: '100%' }
  };

  var ESPACE = 'Vous recevez ce message car vous avez un compte Ezer Home.';
  var RDV = 'https://ezerhome.tg/espace/rdv/K7P2';

  var BASE = {
    code: '', statut: '', aCode: false, aStatut: false, aDetails: false,
    aBouton: true, aNote: true, salut: 'Bonjour Afi,', raison: ESPACE, details: null
  };

  var M = {
    'verification': {
      objet: 'Votre code de vérification Ezer Home', apercu: 'Saisissez ce code pour activer votre compte.',
      titre: 'Confirmez votre adresse', intro: 'Vous venez de créer votre compte. Saisissez ce code sur la page de vérification, ou utilisez le bouton ci-dessous.',
      aCode: true, code: '482 913', bouton: 'Confirmer mon adresse', lien: 'https://ezerhome.tg/verifier/●●●●', lienTexte: 'https://ezerhome.tg/verifier/●●●●',
      note: "Vous n'êtes pas à l'origine de cette inscription ? Ignorez ce message : aucun compte ne sera activé.", salut: 'Bonjour,'
    },
    'reunion-programmee': {
      objet: 'Rendez-vous confirmé le mardi 20 octobre', apercu: 'Retrouvez le lien de connexion dans votre espace.',
      titre: 'Votre rendez-vous est confirmé', intro: 'Un rendez-vous a été programmé pour vous. Vous pouvez le retrouver à tout moment dans votre espace.',
      aDetails: true,
      details: [
        { l: 'Date', v: 'Mardi 20 octobre 2026' },
        { l: 'Heure', v: '10 h 00 – 11 h 00 (heure de Lomé)' },
        { l: 'Format', v: 'En ligne' }
      ],
      bouton: 'Voir mon rendez-vous', lien: RDV, lienTexte: RDV,
      note: "Le bouton de connexion à la réunion s'active 15 minutes avant le début. Un empêchement ? Vous pouvez demander à déplacer le rendez-vous depuis votre espace."
    },
    'reunion-modifiee': {
      objet: 'Votre rendez-vous a été déplacé', apercu: 'Nouvelle date : jeudi 22 octobre à 15 h 00.',
      titre: 'Votre rendez-vous a changé', intro: 'La date de votre rendez-vous a été modifiée. Voici les nouvelles informations.',
      aDetails: true,
      details: [
        { l: 'Nouvelle date', v: 'Jeudi 22 octobre 2026', avant: 'Mardi 20 octobre 2026' },
        { l: 'Nouvelle heure', v: '15 h 00 – 16 h 00', avant: '10 h 00 – 11 h 00' },
        { l: 'Format', v: 'En ligne' }
      ],
      bouton: 'Voir mon rendez-vous', lien: RDV, lienTexte: RDV,
      note: "Cette nouvelle date ne vous convient pas ? Proposez-en une autre depuis votre espace, sans avoir à vous justifier."
    },
    'reunion-annulee': {
      objet: 'Votre rendez-vous du 20 octobre est annulé', apercu: 'Vous pouvez choisir un nouveau créneau.',
      titre: 'Rendez-vous annulé', intro: "Votre rendez-vous du mardi 20 octobre à 10 h 00 n'aura pas lieu. Nous en sommes désolés. Votre accompagnement continue : un nouveau créneau peut être fixé rapidement.",
      bouton: 'Choisir un nouveau créneau', lien: RDV, lienTexte: RDV,
      note: "Si vous avez besoin de parler avant, écrivez-nous depuis la messagerie de votre espace."
    },
    'rappel': {
      objet: 'Rappel : rendez-vous demain à 10 h 00', apercu: 'Le lien de connexion sera actif 15 minutes avant.',
      titre: 'À demain', intro: 'Petit rappel de votre rendez-vous.',
      aDetails: true,
      details: [
        { l: 'Date', v: 'Demain, mardi 20 octobre' },
        { l: 'Heure', v: '10 h 00 (heure de Lomé)' },
        { l: 'Format', v: 'En ligne' }
      ],
      bouton: 'Ouvrir mon rendez-vous', lien: RDV, lienTexte: RDV,
      note: "Conseil : installez-vous dans un endroit calme, avec une connexion stable. Si le réseau coupe, revenez simplement sur le lien."
    },
    'assistance': {
      objet: 'Votre demande ASS-2026-00042 a été mise à jour', apercu: 'Consultez le suivi de votre demande.',
      titre: 'Votre demande avance', intro: "Le comité d'assistance a examiné votre demande. Le détail de la décision est disponible sur la page de suivi.",
      aStatut: true, statut: 'En examen',
      bouton: 'Suivre ma demande', lien: 'https://ezerhome.tg/assistance/suivi', lienTexte: 'https://ezerhome.tg/assistance/suivi',
      note: 'Munissez-vous de votre numéro de demande ASS-2026-00042 et du numéro de téléphone indiqué lors du dépôt.',
      raison: "Vous recevez ce message car vous avez déposé une demande d'assistance.", salut: 'Bonjour,'
    },
    'recu-don': {
      objet: 'Merci pour votre don – reçu REC-2026-0117', apercu: 'Votre reçu est joint à ce message.',
      titre: 'Merci pour votre don', intro: 'Votre générosité aide directement les familles accompagnées par Ezer Home. Votre reçu est joint à ce message au format PDF.',
      aDetails: true,
      details: [
        { l: 'Montant', v: '10 000 F CFA' },
        { l: 'Date', v: '13 octobre 2026' },
        { l: 'Reçu', v: 'REC-2026-0117 (PDF joint)' }
      ],
      bouton: 'Télécharger le reçu', lien: 'https://ezerhome.tg/recu/●●●●', lienTexte: 'https://ezerhome.tg/recu/●●●●',
      note: "Pour savoir à quoi servent les dons, consultez la page dédiée sur notre site. Une question sur ce reçu ? Répondez via la page Contact en indiquant son numéro.",
      raison: 'Vous recevez ce message car vous avez fait un don à Ezer Home.', salut: 'Bonjour,'
    },
    'desactivation': {
      objet: 'Votre compte Ezer Home sera désactivé le 12 novembre', apercu: 'Connectez-vous pour le garder actif.',
      titre: 'Votre compte va être désactivé', intro: "Nous n'avons pas eu de nouvelles depuis 12 mois. Pour protéger vos données, votre compte sera désactivé le 12 novembre 2026, puis vos données seront supprimées selon notre politique de conservation.",
      bouton: 'Garder mon compte', lien: 'https://ezerhome.tg/connexion', lienTexte: 'https://ezerhome.tg/connexion',
      note: "Il suffit de vous connecter une fois avant cette date. Vous ne souhaitez plus utiliser Ezer Home ? Vous n'avez rien à faire."
    }
  };

  var NAVMAIL = [
    ['verification-compte', 'Vérification de compte', 'pages/emails/verification-compte.html'],
    ['verification-telephone', 'Vérification (téléphone)', 'pages/emails/verification-telephone.html'],
    ['reunion-programmee', 'Réunion programmée', 'pages/emails/reunion-programmee.html'],
    ['reunion-modifiee', 'Réunion modifiée', 'pages/emails/reunion-modifiee.html'],
    ['reunion-annulee', 'Réunion annulée', 'pages/emails/reunion-annulee.html'],
    ['rappel-reunion', 'Rappel de réunion', 'pages/emails/rappel-reunion.html'],
    ['rappel-telephone', 'Rappel (téléphone)', 'pages/emails/rappel-telephone.html'],
    ['statut-assistance', "Statut d'assistance", 'pages/emails/statut-assistance.html'],
    ['recu-don', 'Reçu de don', 'pages/emails/recu-don.html'],
    ['preavis-desactivation', 'Préavis de désactivation', 'pages/emails/preavis-desactivation.html'],
    ['formats-courts', 'SMS et WhatsApp', 'pages/messages/formats-courts.html'],
    ['composants', 'Composants de référence', 'pages/composants/composants.html']
  ];

  function bandLine(cle, valeur, muted) {
    return '<div style="display: flex; flex-wrap: wrap; gap: 4px 12px"><span style="color: #5b6656">' + cle + '</span><span style="' + (muted ? 'color: #5b6656' : 'font-weight: 600') + '">' + valeur + '</span></div>';
  }

  function tableDetails(m) {
    var trs = [];
    for (var i = 0; i < m.details.length; i++) {
      var d = m.details[i];
      var avant = d.avant
        ? '<br><span style="font-weight: 400; color: #5b6656; text-decoration: line-through">' + d.avant + '</span>'
        : '';
      var top = i === 0 ? '' : 'border-top: 1px solid #d3e6db; ';
      trs.push(
        '<tr><td style="padding: 12px 16px; ' + (i === 0 ? 'width: 38%; ' : '') + 'color: #5b6656; vertical-align: top; ' + top + '">' + d.l +
        '</td><td style="padding: 12px 16px; font-weight: 600; ' + top + '">' + d.v + avant + '</td></tr>'
      );
    }
    return '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; border: 1px solid #c8dccf; border-radius: 12px; font-size: 15px; line-height: 22px">' + trs.join('') + '</table>';
  }

  function corps(m, s) {
    var html =
      '<h1 style="margin: 0; font-family: \'Cooper Black\', Georgia, serif; font-weight: 400; font-size: ' + s.h1 + '; line-height: ' + s.h1lh + '; color: #0e1c03">' + m.titre + '</h1>' +
      '<p style="margin: 0">' + m.salut + '</p>' +
      '<p style="margin: 0">' + m.intro + '</p>';

    if (m.aCode) {
      html += '<div style="display: flex; flex-direction: column; gap: 6px; padding: 16px; border-radius: 12px; background: #e9f7f0; text-align: center">' +
        '<span style="font-size: 14px; line-height: 20px; color: #5b6656">Votre code de vérification</span>' +
        '<span style="font-size: 32px; line-height: 40px; font-weight: 600; letter-spacing: 0.2em; font-variant-numeric: tabular-nums">' + m.code + '</span>' +
        '<span style="font-size: 14px; line-height: 20px; color: #5b6656">Valable 10 minutes</span></div>';
    }

    if (m.aStatut) {
      html += '<div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px 12px; padding: 16px; border-radius: 12px; background: #e9f7f0">' +
        '<span style="font-size: 14px; color: #5b6656">Nouveau statut</span>' +
        '<span style="display: inline-flex; align-items: center; min-height: 28px; padding: 0 12px; border-radius: 12px; border: 2px solid #a67c52; background: #ffffff; font-size: 14px; font-weight: 600; color: #0e1c03">' + m.statut + '</span></div>';
    }

    if (m.aDetails) {
      html += tableDetails(m);
    }

    if (m.aBouton) {
      html += '<div style="display: flex; flex-direction: column; gap: 8px">' +
        '<a href="' + m.lien + '" style="display: block; box-sizing: border-box; max-width: ' + s.btnMax + '; min-height: 52px; padding: 14px 24px; border-radius: 12px; background: #187d50; color: #ffffff; font-weight: 600; font-size: 16px; line-height: 24px; text-align: center; text-decoration: none">' + m.bouton + '</a>' +
        '<span style="font-size: 13px; line-height: 18px; color: #5b6656">Le bouton ne fonctionne pas ? Copiez ce lien : <span style="color: #0e1c03; word-break: break-all">' + m.lienTexte + '</span></span></div>';
    }

    if (m.aNote) {
      html += '<p style="margin: 0; font-size: 15px; line-height: 22px; color: #5b6656">' + m.note + '</p>';
    }

    html += '<p style="margin: 0">Avec toute notre attention,<br>L\'équipe Ezer Home</p>';

    html += '<div style="display: flex; gap: 10px; align-items: flex-start; padding: 12px 14px; border: 1px dashed #6f8577; border-radius: 12px; font-size: 13px; line-height: 18px; color: #5b6656">' +
      '<svg aria-hidden="true" style="flex: 0 0 auto; margin-top: 1px" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0e1c03" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>' +
      '<span>Ezer Home ne vous demandera jamais votre mot de passe ou un code par téléphone, SMS ou WhatsApp. Ce message ne contient aucune information sur votre suivi : les détails restent dans votre espace sécurisé.</span></div>';

    return html;
  }

  function renderMail(modele, format) {
    var s = S[format] || S.ordinateur;
    var m = Object.assign({}, BASE, M[modele] || M['verification']);

    var bandeau =
      '<div style="box-sizing: border-box; padding: 12px 16px; border: 1px solid #c8dccf; border-radius: 12px; background: #ffffff; display: flex; flex-direction: column; gap: 2px; font-size: 13px; line-height: 18px">' +
        bandLine('De', 'Ezer Home &lt;notifications@ezerhome.tg&gt;') +
        bandLine('Objet', m.objet) +
        bandLine('Aperçu', m.apercu, true) +
      '</div>';

    var table =
      '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px; margin: 0 auto; border-collapse: separate; border-spacing: 0; background: #ffffff; border: 1px solid #c8dccf; border-radius: 12px">' +
        '<tr><td style="padding: ' + s.cellPad + '; border-bottom: 2px solid #a67c52">' + LOGO + '</td></tr>' +
        '<tr><td style="padding: ' + s.cellPad + '"><div style="display: flex; flex-direction: column; gap: 20px">' + corps(m, s) + '</div></td></tr>' +
        '<tr><td style="padding: ' + s.piedPad + '; background: #0e1c03; border-radius: 0 0 11px 11px; color: #ffffff; font-size: 13px; line-height: 20px">' +
          '<div style="display: flex; flex-direction: column; gap: 8px">' +
            '<span style="font-family: \'Cooper Black\', Georgia, serif; font-size: 18px; line-height: 24px; color: #22b573">Ezer Home</span>' +
            '<span>138, Boulevard du 13 Janvier, Hanoukopé, Lomé</span>' +
            '<span>+228 98 13 57 57 · <a href="https://wa.me/22898135757" style="color: #ffffff">WhatsApp</a> · <a href="mailto:mail@ezerhome.org" style="color: #ffffff">mail@ezerhome.org</a></span>' +
            '<span style="color: #c8dccf">' + m.raison + '</span>' +
            '<span><a href="https://ezerhome.tg/confidentialite" style="color: #ffffff">Confidentialité</a> · <a href="https://ezerhome.tg/espace/profil#notifications" style="color: #ffffff">Préférences de notification</a> · <a href="https://ezerhome.tg/contact" style="color: #ffffff">Nous écrire</a></span>' +
          '</div>' +
        '</td></tr>' +
      '</table>';

    return '<div style="width: ' + s.largeur + '; max-width: 100%; margin: 0 auto; box-sizing: border-box; padding: ' + s.pad + '; background: #f6faf8; font-family: \'Poppins\', system-ui, sans-serif; color: #0e1c03; font-size: 16px; line-height: 24px; display: flex; flex-direction: column; gap: 16px">' +
      bandeau + table + '</div>';
  }

  function renderNav(actif, excluComposants) {
    var items = NAVMAIL.filter(function (n) { return !(excluComposants && n[2].indexOf('emails') === -1); }).map(function (n) {
      var on = n[0] === actif;
      return '<a href="' + B + n[2] + '"' + (on ? ' aria-current="true"' : '') +
        ' class="px-3 py-1.5 rounded-ezer text-xs font-semibold ' +
        (on ? 'bg-ezer-dark text-white' : 'bg-white text-ezer-dark border border-ezer-borderMuted hover:bg-ezer-green-light') + '">' + n[1] + '</a>';
    }).join('');
    return '<div class="flex flex-wrap items-center gap-2" role="group" aria-label="Modèles de messages">' + items + '</div>';
  }

  document.querySelectorAll('[data-ezer-mail]').forEach(function (slot) {
    slot.outerHTML = renderMail(slot.getAttribute('data-ezer-mail'), slot.getAttribute('data-ezer-mail-format') || 'ordinateur');
  });

  document.querySelectorAll('[data-ezer-mail-nav]').forEach(function (slot) {
    var ctx = document.body.querySelector('[data-ezer-mail]');
    var actif = slot.getAttribute('data-ezer-mail-nav-actif') || (ctx && ctx.getAttribute('data-ezer-mail'));
    slot.outerHTML = renderNav(actif, !ctx);
  });
})();


