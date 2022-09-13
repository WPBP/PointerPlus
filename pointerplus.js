/* PointerPlus Based on QueryLoop Pointer */

//Read the var with data
var pp_scripts = document.getElementsByTagName("script");
pp_scripts = pp_scripts[pp_scripts.length - 1];

function getParams(script_choosen) {
	// Get an array of key=value strings of params
	var pa = script_choosen.src.split("?").pop().split("&");
	// Split each key=value into array, the construct js object
	var p = {};
	for (var j = 0; j < pa.length; j++) {
		var kv = pa[j].split("=");
		p[kv[0]] = kv[1];
	}
	return p;
}

jQuery(function ($) {
	'use strict';
	setTimeout(function () {
		var pointerplus = window[getParams(pp_scripts).var];
		$.each(pointerplus, function (key, pointer) {
			if ( pointer.selector === '' || typeof pointer.selector === "undefined" ) {
				return;
			}
			pointer.class += ' pp-' + key;
			if (!pointer.show) {
				pointer.show = 'open';
			}
			var test = $(pointer.selector).pointer({
				content: '<h3>' + pointer.title + '</h3><p>' + pointer.text + '</p>',
				position: {
					edge: pointer.edge,
					align: pointer.align
				},
				pointerWidth: parseInt(pointer.width),
				pointerClass: 'wp-pointer pointerplus' + pointer.class,
				buttons: function (event, t) {
					var button;
					if (pointer.jsnext) {
						var jsnext = new Function('t', '$', pointer.jsnext);
						return jsnext(t, jQuery);
					} else if (pointer.next) {
						button = jQuery('<a id="pointer-close" class="button action">' + pointerplus.l10n.next + '</a>');
						button.bind('click.pointer', function () {
							t.element.pointer('close');
							jQuery(pointerplus[pointer.next].selector).pointer('open');
							if (pointerplus[pointer.next].icon_class !== '') {
								$('.pp-' + pointer.next + ' .pp-pointer-content h3').addClass('dashicons-before').addClass(pointerplus[pointer.next].icon_class);
							}
						});
						return button;
					} else {
						var close = (wpPointerL10n) ? wpPointerL10n.dismiss : 'Dismiss', button = jQuery('<a class="close" href="#">' + close + pointerplus.l10n.dismiss + '</a>');
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
		$.each(pointerplus, function (key, pointer) {
			if (pointerplus[pointer.next]) {
				jQuery(pointerplus[pointer.next].selector).pointer('close');
			}
		});
	}, 300);
});
