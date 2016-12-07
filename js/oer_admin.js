jQuery(document).ready(function($) {
	/**
	 * Dismiss our activation notice
	 */
	$('#oer-dismissible-notice .notice-dismiss').click(function() {
                
		//* Data to make available via the $_POST variable
		data = {
			action: 'oer_activation_notice',
			wp_ajax_oer_admin_nonce: wp_ajax_oer_admin.wp_ajax_oer_admin_nonce
		};

		//* Process the AJAX POST request
		$.post(
                       ajaxurl,
                       data,
                       function(response) { console.log(response.message); }
                       );

		return false;
	});
});