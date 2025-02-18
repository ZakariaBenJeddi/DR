self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open('my-cache').then(function(cache) {
      return cache.addAll([
        '/',
        '/index.php',
        '../assets/css/style.css', // Assurez-vous que ce chemin est correct
        '../assets/js/main.js'   // Assurez-vous que ce chemin est correct
      ]);
    }).catch(function(error) {
      console.error('Failed to cache:', error);
    })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request).then(function(response) {
      return response || fetch(event.request);
    })
  );
});
