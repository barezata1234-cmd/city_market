const CACHE_NAME = 'citymarket-offline-v1';
const APP_SHELL = [
    '/offline/index.html',
    '/offline/manifest.json',
    'https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.4.3/bcrypt.min.js',
];

// دامەزراندن: کاشکردنی هەموو فایلەکانی سەرەکی (app shell) تاکو بەبێ ئینتەرنێت بارببن
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(APP_SHELL))
    );
    self.skipWaiting();
});

// چالاککردن: سڕینەوەی کاشی کۆن
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

// وەرگرتنی داواکارییەکان: بۆ فایلەکانی app shell کاش-یەکەم بەکاربهێنە،
// بۆ داواکاری API (fetch) هەمیشە هەوڵی network بدە (بۆ داتای نوێ)
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // API calls: تەنها لە ڕێگەی network (سکریپتی سەرەکی خۆی هەڵسوکەوتی offline دەکات)
    if (url.pathname.startsWith('/api/')) {
        return; // بگەڕێوە بۆ ڕەفتاری ئاسایی وێبگەڕ
    }

    // app shell: cache-first
    event.respondWith(
        caches.match(event.request).then((cached) => cached || fetch(event.request))
    );
});
