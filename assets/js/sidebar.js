(function () {
  'use strict';

  var openBtn = document.getElementById('sidebar-open');
  var closeBtn = document.getElementById('sidebar-close');
  var sidebar = document.getElementById('sidebar-menu');
  var backdrop = document.getElementById('sidebar-backdrop');

  if (!sidebar) return;

  var savedScrollY = 0;
  var bodyScrollLocked = false;

  function isDrawerMode() {
    return window.matchMedia('(max-width: 960px)').matches;
  }

  /** Breedte van de scrollbar — voorkomt dat de hele site horizontaal “springt” als die verdwijnt */
  function getScrollbarWidth() {
    return Math.max(0, window.innerWidth - document.documentElement.clientWidth);
  }

  function lockBodyScroll() {
    if (!isDrawerMode()) return;
    bodyScrollLocked = true;
    savedScrollY = window.scrollY || document.documentElement.scrollTop || 0;
    var sbw = getScrollbarWidth();
    if (sbw > 0) {
      document.documentElement.style.setProperty('--mm-sbw', sbw + 'px');
      document.body.style.paddingRight = 'var(--mm-sbw)';
    }
    document.body.style.position = 'fixed';
    document.body.style.top = '-' + savedScrollY + 'px';
    document.body.style.left = '0';
    document.body.style.right = '0';
    document.body.style.width = '100%';
  }

  function unlockBodyScroll() {
    if (!bodyScrollLocked) return;
    bodyScrollLocked = false;
    document.body.style.paddingRight = '';
    document.documentElement.style.removeProperty('--mm-sbw');
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.left = '';
    document.body.style.right = '';
    document.body.style.width = '';
    window.scrollTo(0, savedScrollY);
  }

  function openDrawer() {
    lockBodyScroll();
    document.body.classList.add('sidebar-open');
    sidebar.classList.add('is-drawer-open');
    if (backdrop) {
      backdrop.hidden = false;
    }
    if (openBtn) {
      openBtn.setAttribute('aria-expanded', 'true');
    }
  }

  function closeDrawer() {
    document.body.classList.remove('sidebar-open');
    sidebar.classList.remove('is-drawer-open');
    if (backdrop) {
      backdrop.hidden = true;
    }
    if (openBtn) {
      openBtn.setAttribute('aria-expanded', 'false');
    }
    unlockBodyScroll();
    if (openBtn && typeof openBtn.focus === 'function') {
      try {
        openBtn.focus({ preventScroll: true });
      } catch (e) {
        openBtn.focus();
      }
    }
  }

  if (openBtn) {
    openBtn.addEventListener('click', function (e) {
      e.preventDefault();
      if (document.body.classList.contains('sidebar-open')) {
        closeDrawer();
      } else {
        openDrawer();
      }
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('mousedown', function (e) {
      e.preventDefault();
    });
    closeBtn.addEventListener('click', function (e) {
      e.preventDefault();
      closeDrawer();
    });
  }

  if (backdrop) {
    backdrop.addEventListener('mousedown', function (e) {
      e.preventDefault();
    });
    backdrop.addEventListener('click', function (e) {
      e.preventDefault();
      closeDrawer();
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && document.body.classList.contains('sidebar-open')) {
      closeDrawer();
    }
  });

  window.addEventListener('resize', function () {
    if (window.matchMedia('(min-width: 961px)').matches) {
      closeDrawer();
    }
  });
})();
