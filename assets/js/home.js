(function () {
  'use strict';

  var STORAGE_KEY = 'mm_favorites';

  function getFavs() {
    try {
      var raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return [];
      var arr = JSON.parse(raw);
      return Array.isArray(arr) ? arr.map(Number) : [];
    } catch (e) {
      return [];
    }
  }

  function setFavs(ids) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
  }

  function syncFavButtons() {
    var favs = getFavs();
    document.querySelectorAll('.fav-btn[data-fav]').forEach(function (btn) {
      var id = Number(btn.getAttribute('data-fav'));
      var on = favs.indexOf(id) !== -1;
      btn.classList.toggle('is-fav', on);
      btn.setAttribute('aria-pressed', on ? 'true' : 'false');
      var icon = btn.querySelector('.fav-icon');
      if (icon) icon.textContent = on ? '♥' : '♡';
    });
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.fav-btn[data-fav]');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    var id = Number(btn.getAttribute('data-fav'));
    var favs = getFavs();
    var i = favs.indexOf(id);
    if (i === -1) favs.push(id);
    else favs.splice(i, 1);
    setFavs(favs);
    syncFavButtons();
  });

  syncFavButtons();

  var tabVj = document.getElementById('tab-voorjou');
  var tabBuurt = document.getElementById('tab-buurt');
  var panelVj = document.getElementById('panel-voorjou');
  var panelBuurt = document.getElementById('panel-buurt');

  if (tabVj && tabBuurt && panelVj && panelBuurt) {
    function activate(which) {
      var isVj = which === 'voorjou';
      tabVj.classList.toggle('is-active', isVj);
      tabBuurt.classList.toggle('is-active', !isVj);
      tabVj.setAttribute('aria-selected', isVj ? 'true' : 'false');
      tabBuurt.setAttribute('aria-selected', !isVj ? 'true' : 'false');
      panelVj.classList.toggle('is-active', isVj);
      panelBuurt.classList.toggle('is-active', !isVj);
      panelVj.hidden = !isVj;
      panelBuurt.hidden = isVj;
    }

    tabVj.addEventListener('click', function (e) {
      e.preventDefault();
      activate('voorjou');
    });
    tabBuurt.addEventListener('click', function (e) {
      e.preventDefault();
      activate('buurt');
    });
  }
})();
