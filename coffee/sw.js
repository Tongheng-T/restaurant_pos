self.addEventListener("install", event => {
  console.log("Service Worker installing...");
  event.waitUntil(
    caches.open("thpos-cache").then(cache => {
      return cache.addAll([
        "/",
        "/index",
        "/ui/",
        "/manifest.json",
        "/ui/logo/96.ico",
        "/ui/logo/256.ico"
      ]);
    })
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});
