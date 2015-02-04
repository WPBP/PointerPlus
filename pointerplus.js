/* PointerPlus Based on QueryLoop Pointer */

jQuery(function($) {
  'use strict';
    $.each(pointerplus, function (key, pointer) {
      pointer.class += ' pp-' + key;
      if (!pointer.show) {
        pointer.show = 'open';
      }
      $(pointer.selector).pointer({
        content: '<h3>' + pointer.title + '</h3><p>' + pointer.text + '</p>',
        position: {
          edge: pointer.edge,
          align: pointer.align
        },
        pointerWidth: parseInt(pointer.width),
        pointerClass: 'wp-pointer pointerplus' + pointer.class,
        buttons: function (event, t) {
          if (pointer.jsnext) {
            var jsnext = new Function('t', '$', pointer.jsnext);
            return jsnext(event, t, jQuery);
          } else {
            var close = (wpPointerL10n) ? wpPointerL10n.dismiss : 'Dismiss',
                    button = jQuery('<a class="close" href="#">' + close + '</a>');
            return button.bind('click.pointer', function (e) {
              e.preventDefault();
              t.element.pointer('close');
            });
          }
        },
        close: function () {
          $.post(ajaxurl, {
            pointer: key,
            action: 'dismiss-wp-pointer'
          });
        }
      }).pointer(pointer.show);
      // Hack for custom dashicons
      if (pointer.icon_class !== '') {
        $('.pp-' + key + ' .wp-pointer-content').addClass('pp-pointer-content').removeClass('wp-pointer-content');
        $('.pp-' + key + ' .pp-pointer-content h3').addClass('dashicons-before').addClass(pointer.icon_class);
      }
    });
});