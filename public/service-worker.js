const CACHE_NAME = "onenwifi-v3";

const urlsToCache = [
  "/offline.html",
  "/manifest.json",
  "/icons/icon-192.png",
  "/icons/icon-512.png"
];

self.addEventListener("install", event => {

  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );

  self.skipWaiting();
});


self.addEventListener("activate", event => {

  event.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.filter(key => key !== CACHE_NAME)
        .map(key => caches.delete(key))
      );
    })
  );

  self.clients.claim();
});


self.addEventListener("fetch", event => {

  if (event.request.method !== "GET") return;

  const url = new URL(event.request.url);

  // hanya cache asset lokal
  if (url.origin === location.origin &&
      (url.pathname.endsWith(".css") ||
       url.pathname.endsWith(".js") ||
       url.pathname.endsWith(".png") ||
       url.pathname.endsWith(".jpg") ||
       url.pathname.endsWith(".svg"))) {

    event.respondWith(
      caches.match(event.request).then(res => {
        return res || fetch(event.request).then(fetchRes => {
          return caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, fetchRes.clone());
            return fetchRes;
          });
        });
      })
    );

  } else {

    // selain asset → langsung ke network
    event.respondWith(fetch(event.request));

  }

});

// self.addEventListener("fetch", event => {

//   if (event.request.method !== "GET") return;

//   event.respondWith(

//     caches.match(event.request)
//       .then(cacheRes => {

//         // jika ada di cache → pakai cache
//         if (cacheRes) {
//           return cacheRes;
//         }

//         // kalau tidak ada → ambil dari internet
//         return fetch(event.request)
//           .then(fetchRes => {

//             const responseClone = fetchRes.clone();

//             caches.open(CACHE_NAME).then(cache => {
//               cache.put(event.request, responseClone);
//             });

//             return fetchRes;

//           })
//           .catch(() => {
//             return caches.match("/offline.html");
//           });

//       })

//   );

// });

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