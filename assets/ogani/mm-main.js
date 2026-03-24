/* Minimale JS voor Ogani-layout (zonder prijs-slider / owl op elke pagina) */
(function ($) {
  'use strict';

  $(window).on('load', function () {
    $('.loader').fadeOut();
    $('#preloder').delay(200).fadeOut('slow');
  });

  $('.set-bg').each(function () {
    var bg = $(this).data('setbg');
    if (bg) {
      $(this).css('background-image', 'url(' + bg + ')');
    }
  });

  $('.humberger__open').on('click', function () {
    $('.humberger__menu__wrapper').addClass('show__humberger__menu__wrapper');
    $('.humberger__menu__overlay').addClass('active');
    $('body').addClass('over_hid');
  });

  $('.humberger__menu__overlay').on('click', function () {
    $('.humberger__menu__wrapper').removeClass('show__humberger__menu__wrapper');
    $('.humberger__menu__overlay').removeClass('active');
    $('body').removeClass('over_hid');
  });

  if ($('.mobile-menu').length && typeof $.fn.slicknav === 'function') {
    $('.mobile-menu').slicknav({
      prependTo: '#mobile-menu-wrap',
      allowParentLinks: true,
    });
  }

  $('.hero__categories__all').on('click', function () {
    $('.hero__categories ul').slideToggle(400);
  });

  if ($('.product__discount__slider').length && typeof $.fn.owlCarousel === 'function') {
    $('.product__discount__slider').owlCarousel({
      loop: true,
      margin: 16,
      items: 3,
      dots: true,
      nav: true,
      navText: [
        '<span class="mm-owl-nav-inner" aria-hidden="true">‹</span>',
        '<span class="mm-owl-nav-inner" aria-hidden="true">›</span>',
      ],
      smartSpeed: 900,
      autoHeight: false,
      autoplay: true,
      autoplayTimeout: 5200,
      autoplayHoverPause: true,
      responsive: {
        0: { items: 1, nav: false },
        520: { items: 2, nav: true },
        992: { items: 3, nav: true },
      },
    });
  }
})(jQuery);
