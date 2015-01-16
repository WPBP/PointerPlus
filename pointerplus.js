/* PointerPlus Based on QueryLoop Pointer */

(function ($) {
  'use strict';
  $(document).ready(function () {
    $.each(pointerplus, function (key, pointer) {
      pointer.class += ' pp-' + key;
      $(pointer.selector).pointer({
        content: '<h3>' + pointer.title + '</h3><p>' + pointer.text + '</p>',
        position: {
          edge: pointer.edge,
          align: pointer.align
        },
        pointerWidth: parseInt(pointer.width),
        pointerClass: 'wp-pointer pointerplus' + pointer.class,
        close: function () {
          $.post(ajaxurl, {
            pointer: key,
            action: 'dismiss-wp-pointer'
          });
        }
      }).pointer('open');
      // Hack for custom dashicons
      if (pointer.icon_class !== '') {
        $('.pp-' + key + ' .wp-pointer-content').addClass('pp-pointer-content').removeClass('wp-pointer-content');
        $('.pp-' + key + ' .pp-pointer-content h3').addClass('dashicons-before').addClass(pointer.icon_class);
      }
    });
  });
})(jQuery);