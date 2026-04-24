(function () {
  'use strict';

  var cache = Object.create(null);

  function fetchImage(query) {
    if (!query) {
      return Promise.resolve(null);
    }

    if (cache[query]) {
      return Promise.resolve(cache[query]);
    }

    return fetch('/api/pexels_image.php?q=' + encodeURIComponent(query))
      .then(function (response) {
        if (!response.ok) {
          throw new Error('Request failed');
        }

        return response.json();
      })
      .then(function (data) {
        cache[query] = data.url || null;
        return cache[query];
      })
      .catch(function () {
        cache[query] = null;
        return null;
      });
  }

  function loadPreviewImages() {
    var images = Array.prototype.slice.call(document.querySelectorAll('img.pexels-img[data-query]'));

    if (!images.length) {
      return;
    }

    function loadChunk(startIndex) {
      var chunk = images.slice(startIndex, startIndex + 4);

      if (!chunk.length) {
        return Promise.resolve();
      }

      return Promise.all(chunk.map(function (image) {
        return fetchImage(image.dataset.query).then(function (url) {
          var wrap = image.closest('.img-wrap');

          if (!url) {
            if (wrap) {
              wrap.classList.add('img-loaded');
            }
            return;
          }

          image.addEventListener('load', function () {
            image.classList.add('is-loaded');
            if (wrap) {
              wrap.classList.add('img-loaded');
            }
          }, { once: true });

          image.src = url;
        });
      })).then(function () {
        return loadChunk(startIndex + 4);
      });
    }

    loadChunk(0);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadPreviewImages);
  } else {
    loadPreviewImages();
  }
})();
