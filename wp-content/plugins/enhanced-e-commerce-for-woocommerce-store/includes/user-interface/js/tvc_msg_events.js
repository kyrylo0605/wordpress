"use strict";
//shorthand for ready event.
jQuery( function() {
	jQuery( 'div[data-dismissible] button.notice-dismiss' ).on("click",
		function( event ) {
			event.preventDefault();
			var $this = jQuery( this );

			var attr_value, option_name, dismissible_length, data;

			attr_value = $this.parent().attr( 'data-dismissible' ).split( '-' );

			// remove the dismissible length from the attribute value and rejoin the array.
			dismissible_length = attr_value.pop();

			option_name = attr_value.join( '-' );

			data = {
				'action': 'dismiss_admin_notice',
				'option_name': option_name,
				'dismissible_length': dismissible_length,
			};

			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post( ajaxurl, data );
		}
	); });
