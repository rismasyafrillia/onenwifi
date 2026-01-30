const CACHE_NAME = "onenwifi-v2";

const urlsToCache = [
  "/",
  "/offline.html",
  "/manifest.json",
  "/icons/icon-192.png",
  "/icons/icon-512.png"
];

self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
      .catch(err => console.log("Cache error:", err))
  );
  self.skipWaiting();
});

self.addEventListener("activate", event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.filter(k => k !== CACHE_NAME)
            .map(k => caches.delete(k))
      )
    )
  );
  self.clients.claim();
});

self.addEventListener("fetch", event => {
  event.respondWith(
    fetch(event.request)
      .catch(() =>
        caches.match(event.request)
          .then(res => res || caches.match("/offline.html"))
      )
  );
});

self.addEventListener("push", function(event) {
  let data = {};

  if (event.data) {
    data = event.data.json();
  }

  const title = data.title || "OneN Wifi";
  const options = {
    body: data.body || "Notifikasi baru",
    icon: "/icons/icon-192.png",
    badge: "/icons/icon-192.png"
  };

  event.waitUntil(
    self.registration.showNotification(title, options)
  );
});
