/**
 * Zet body.header-scrolled bij scroll → compacte header (CSS), lichte schaduw.
 * Hysteresis tegen flikkeren; pauzeert als zijmenu open is.
 */
(function () {
  'use strict';

  var ON = 72;
  var OFF = 24;
  var ticking = false;
  var isScrolled = false;

  function apply() {
    ticking = false;
    if (document.body.classList.contains('sidebar-open')) {
      return;
    }
    var y = window.scrollY || document.documentElement.scrollTop || 0;
    if (!isScrolled && y > ON) {
      isScrolled = true;
      document.body.classList.add('header-scrolled');
    } else if (isScrolled && y < OFF) {
      isScrolled = false;
      document.body.classList.remove('header-scrolled');
    }
  }

  function onScroll() {
    if (!ticking) {
      window.requestAnimationFrame(apply);
      ticking = true;
    }
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', function () {
    onScroll();
  }, { passive: true });

  apply();
})();
