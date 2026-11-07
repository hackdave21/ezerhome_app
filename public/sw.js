/* Service worker Ezer Home — installation « application » et mode hors-ligne. */
const CACHE_VERSION = 'ezerhome-v1';
const OFFLINE_URL = '/offline.html';
const PRECACHE_URLS = [
    OFFLINE_URL,
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/apple-touch-icon.png',
];

/* Pages et espaces qui ne doivent jamais être mis en cache. */
const NEVER_CACHE = [
    '/admin',
    '/espace',
    '/comite',
    '/intervenant',
    '/livewire',
    '/verification',
    '/login',
    '/logout',
    '/medias',
    '/connexion',
    '/mot-de-passe-oublie',
    '/nouveau-mot-de-passe',
    '/double-authentification',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_VERSION).then((cache) =>
            Promise.allSettled(PRECACHE_URLS.map((url) => cache.add(url)))
        )
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((key) => key !== CACHE_VERSION).map((key) => caches.delete(key)))
        )
    );
    self.clients.claim();
});

function isExcluded(url) {
    return NEVER_CACHE.some((path) => url.pathname === path || url.pathname.startsWith(path + '/'));
}

function isStaticAsset(url) {
    return (
        url.pathname.startsWith('/build/') ||
        url.pathname.startsWith('/assets/') ||
        url.pathname.startsWith('/icons/') ||
        /\.(?:css|js|png|jpe?g|svg|webp|gif|ico|woff2?|ttf)$/i.test(url.pathname)
    );
}

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin || isExcluded(url)) return;

    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const copy = response.clone();
                    caches.open(CACHE_VERSION).then((cache) => cache.put(request, copy)).catch(() => {});
                    return response;
                })
                .catch(async () => {
                    const cached = await caches.match(request);
                    return cached || caches.match(OFFLINE_URL);
                })
        );
        return;
    }

    if (isStaticAsset(url)) {
        event.respondWith(
            caches.match(request).then((cached) => {
                const network = fetch(request)
                    .then((response) => {
                        const copy = response.clone();
                        caches.open(CACHE_VERSION).then((cache) => cache.put(request, copy)).catch(() => {});
                        return response;
                    })
                    .catch(() => cached);
                return cached || network;
            })
        );
        return;
    }

    event.respondWith(
        fetch(request)
            .then((response) => {
                if (response.ok) {
                    const copy = response.clone();
                    caches.open(CACHE_VERSION).then((cache) => cache.put(request, copy)).catch(() => {});
                }
                return response;
            })
            .catch(() => caches.match(request))
    );
});
