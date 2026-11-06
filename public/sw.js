const CACHE_NAME = 'digital-academy-v1';
const STATIC_ASSETS = [
    '/css/modern-academy.css',
    '/js/currency-lang-switcher.js',
    '/js/gamification-engine.js',
    '/offline.html',
    '/images/pwa/icon-192.png',
    '/images/pwa/icon-512.png',
    '/manifest.json'
];

// Install: Cache critical static shell
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

// Activate: Clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch: Network-first for pages, cache-first for static assets with offline fallback
self.addEventListener('fetch', (event) => {
    const req = event.request;
    const url = new URL(req.url);

    // Ignore non-GET or cross-origin requests
    if (req.method !== 'GET' || url.origin !== location.origin) {
        return;
    }

    // Static assets: Cache-first
    if (url.pathname.startsWith('/css/') || url.pathname.startsWith('/images/') || url.pathname.startsWith('/js/')) {
        event.respondWith(
            caches.match(req).then((cached) => {
                return cached || fetch(req).then((response) => {
                    if (response.status === 200) {
                        const copy = response.clone();
                        caches.open(CACHE_NAME).then(c => c.put(req, copy));
                    }
                    return response;
                });
            })
        );
        return;
    }

    // HTML Navigation requests: Network-first with offline fallback
    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req).catch(() => {
                return caches.match(req).then((cached) => {
                    return cached || caches.match('/offline.html');
                });
            })
        );
    }
});
