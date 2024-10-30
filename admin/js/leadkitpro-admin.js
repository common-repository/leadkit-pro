(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	
	 $(function() {
		 
		// Delete Campaign Cache
		$('.lcd_refresh_campaigns').live('click', function () {

			var data = {
				'action': 'refresh_campaigns',
			};

			$('.lcd_refresh_icon').hide();

			jQuery.post(ajaxurl, data, function(response) {
				$(".lcd_campaigns_dd" ).replaceWith(response);
			});
		});

		// Delete All Cache Data
		$('.clearAllCahceData').live('click', function () {

			var data = {
				'action': 'lkpr_clear_all_cache_data',
			};

			$('.clearData').hide();
			$('.clearingCacheData').show();

			jQuery.post(ajaxurl, data, function(response) {
				console.log(response);
				$('.clearData').show();
				$('.clearingCacheData').hide();
			});
		}); 
	 	
	 })

})( jQuery );
