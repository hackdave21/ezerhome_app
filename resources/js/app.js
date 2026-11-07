import './bootstrap';

/*
 * Application installable (PWA) : enregistrement du service worker en production.
 * Il met en cache l'interface et sert une page hors-ligne.
 */
if ('serviceWorker' in navigator && import.meta.env.PROD) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

/*
 * Glisser-déposer accessible pour réordonner une liste. Chaque <li> porte data-id et
 * draggable="true". À chaque dépôt, on envoie au serveur la liste des identifiants dans leur
 * nouvel ordre (callback), que le composant Livewire enregistre.
 */
document.addEventListener('alpine:init', () => {
    window.Alpine.data('ezerSortable', (onReorder) => ({
        dragId: null,

        init() {
            const list = this.$el;

            list.addEventListener('dragstart', (e) => {
                const item = e.target.closest('[data-id]');
                if (!item) return;
                this.dragId = item.dataset.id;
                e.dataTransfer.effectAllowed = 'move';
            });

            list.addEventListener('dragover', (e) => {
                e.preventDefault();
                const over = e.target.closest('[data-id]');
                const dragged = list.querySelector(`[data-id="${this.dragId}"]`);
                if (!over || !dragged || over === dragged) return;

                const rect = over.getBoundingClientRect();
                const after = (e.clientY - rect.top) / rect.height > 0.5;
                list.insertBefore(dragged, after ? over.nextSibling : over);
            });

            list.addEventListener('drop', (e) => e.preventDefault());

            list.addEventListener('dragend', () => {
                if (this.dragId === null) return;
                this.dragId = null;
                const ids = [...list.querySelectorAll('[data-id]')].map((el) => el.dataset.id);
                onReorder(ids);
            });
        },
    }));

    /*
     * Invitation à installer l'application (écran d'accueil).
     * Android/Chrome : bouton natif via beforeinstallprompt.
     * iOS/Safari : mode d'emploi « Partager → Sur l'écran d'accueil ».
     */
    window.Alpine.data('ezerInstallApp', () => ({
        visible: false,
        iosHelp: false,
        deferredPrompt: null,
        consentGiven: window.ezerConsent() !== null,
        isIos: /iphone|ipad|ipod/i.test(window.navigator.userAgent),
        isStandalone:
            window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true,

        dismissed() {
            try {
                return localStorage.getItem('ezer_install_dismissed') === '1';
            } catch (e) {
                return false;
            }
        },

        init() {
            if (this.isStandalone || this.dismissed()) return;

            window.addEventListener('ezer:consent', () => {
                this.consentGiven = true;
                this.maybeShow();
            });

            window.addEventListener('beforeinstallprompt', (event) => {
                event.preventDefault();
                this.deferredPrompt = event;
                this.maybeShow();
            });

            window.addEventListener('appinstalled', () => {
                this.visible = false;
                this.deferredPrompt = null;
            });

            if (this.isIos) {
                window.setTimeout(() => this.maybeShow(), 2500);
            }
        },

        maybeShow() {
            if (this.isStandalone || !this.consentGiven) return;
            if (this.dismissed()) return;
            if (!this.deferredPrompt && !this.isIos) return;
            this.visible = true;
        },

        async install() {
            if (this.isIos) {
                this.iosHelp = true;
                return;
            }
            if (!this.deferredPrompt) return;

            this.deferredPrompt.prompt();
            await this.deferredPrompt.userChoice.catch(() => {});
            this.deferredPrompt = null;
            this.visible = false;
        },

        dismiss() {
            this.visible = false;
            this.iosHelp = false;
            try {
                localStorage.setItem('ezer_install_dismissed', '1');
            } catch (e) {}
        },
    }));

    /*
     * Bandeau de consentement aux cookies. Aucun cookie de mesure ou de publicité
     * n'est posé aujourd'hui : le choix est enregistré pour être respecté le jour
     * où des outils de statistiques seront ajoutés.
     */
    window.ezerConsent = () => {
        try {
            return localStorage.getItem('ezer_cookie_consent');
        } catch (e) {
            return null;
        }
    };

    window.Alpine.data('ezerCookieConsent', () => ({
        visible: false,

        init() {
            this.visible = window.ezerConsent() === null;
        },

        decide(choice) {
            this.visible = false;
            try {
                localStorage.setItem('ezer_cookie_consent', choice);
                const maxAge = 60 * 60 * 24 * 180;
                document.cookie = `ezer_cookie_consent=${choice}; Max-Age=${maxAge}; Path=/; SameSite=Lax`;
            } catch (e) {}
            window.dispatchEvent(new CustomEvent('ezer:consent', { detail: { choice } }));
        },

        accept() {
            this.decide('accepted');
        },

        refuse() {
            this.decide('refused');
        },
    }));
});
