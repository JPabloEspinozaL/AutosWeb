const CACHE_NAME = "concesionaria-soa-v1";
const urlsToCache = [
    "/",
    "/dashboard",
    "https://cdn.tailwindcss.com",
    "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap"
];

// 1. Instalación: Guardar archivos estáticos
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(urlsToCache);
        })
    );
});

// 2. Fetch: Servir contenido (Network First, luego Cache)
self.addEventListener("fetch", (event) => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});

// 3. Activación: Limpiar cachés viejas
self.addEventListener("activate", (event) => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});