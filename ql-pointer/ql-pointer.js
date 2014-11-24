/* QueryLoop Admin Pointer - http://queryloop.com */

(function($) {
	'use strict';
	$(document).ready(function () {
		$.each(ql_pointer, function(key, pointer) {
			$(pointer.selector).pointer({
				content: pointer.title + pointer.text,
				position: {
					edge: pointer.edge,
					align: pointer.align
				},
				pointerWidth: parseInt(pointer.width),
				pointerClass: ( 'left' == pointer.edge || 'right' == pointer.edge ) ? 'wp-pointer ' + pointer.class : 'wp-pointer',
				close: function () {
					$.post(ajaxurl, {
						pointer: key,
						action: 'dismiss-wp-pointer'
					});
				}
			}).pointer('open');
		});
	});
})(jQuery);