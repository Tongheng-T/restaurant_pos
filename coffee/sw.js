self.addEventListener("install", event => {
  console.log("Service Worker installing...");
  event.waitUntil(
    caches.open("thpos-cache").then(cache => {
      return cache.addAll([
        "/",
        "/index",
       
        "/manifest.json",
        "/ui/logo/96.ico",
        "/ui/logo/256.ico"
      ]);
    })
  );
});

// Install SW and cache files
self.addEventListener("install", event => {
  console.log("Service Worker installing...");
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(urlsToCache);
    })
  );
});

// Activate SW
self.addEventListener("activate", event => {
  console.log("Service Worker activated");
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

// Fetch strategy: cache first, fallback network
self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});