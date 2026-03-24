/**
 * 1) Voorkomt jump naar # (lege hash-links).
 * 2) Bewaart scrollpositie bij filteren op de homepage (zelfde tab).
 */
(function () {
  'use strict';

  var STORAGE_KEY = 'mm_listing_scroll_v1';
  var MAX_AGE_MS = 90000;

  document.addEventListener(
    'click',
    function (e) {
      var a = e.target.closest('a[href]');
      if (!a) return;
      var href = a.getAttribute('href');
      if (!href) return;

      if (href === '#' || href === '#!' || href === '#0') {
        e.preventDefault();
        return;
      }

      if (!document.body.classList.contains('is-home')) return;
      if (a.target === '_blank' || a.getAttribute('download')) return;

      try {
        var u = new URL(href, location.href);
        if (u.origin !== location.origin) return;
        if (u.pathname.indexOf('ad.php') !== -1 || u.pathname.indexOf('place.php') !== -1) {
          return;
        }

        var htrim = href.trim();
        var hasFilters =
          (u.search && u.search.length > 1) || htrim.indexOf('?') === 0;
        if (!hasFilters) return;

        var y = window.scrollY || document.documentElement.scrollTop || 0;
        sessionStorage.setItem(
          STORAGE_KEY,
          JSON.stringify({ y: y, t: Date.now() })
        );
      } catch (err) {
        /* ignore */
      }
    },
    true
  );

  function restoreScroll() {
    if (!document.body.classList.contains('is-home')) return;
    var raw = sessionStorage.getItem(STORAGE_KEY);
    if (!raw) return;
    sessionStorage.removeItem(STORAGE_KEY);
    var data;
    try {
      data = JSON.parse(raw);
    } catch (e) {
      return;
    }
    if (!data || typeof data.y !== 'number' || typeof data.t !== 'number') return;
    if (Date.now() - data.t > MAX_AGE_MS) return;

    function go() {
      window.scrollTo(0, Math.max(0, data.y));
    }
    go();
    requestAnimationFrame(go);
    setTimeout(go, 0);
    setTimeout(go, 100);
    window.addEventListener('load', go);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', restoreScroll);
  } else {
    restoreScroll();
  }
})();
