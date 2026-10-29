import './bootstrap';

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
});
