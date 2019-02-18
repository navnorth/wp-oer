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
	
	$('.search-standard-text').on("keypress", function(e){
		if (e.which == 13) {
			if ($(this).val().length>0) {
				$('.search_std_btn').click();
			} else {
				displaydefaultStandards();
			}
		}
	});
	
	/**
	 *
	 * Search Standard Button Click
	 *
	 **/
	$('.search_std_btn').on("click", function(){
		data = {
			action: 'load_searched_standards',
			post_id: $(this).attr('data-postid'),
			keyword: $('.search-standard-text').val()
		}
		
		//* Process the AJAX POST request
		$.post(
                       ajaxurl,
                       data
		       ).done( function(response) {
			list = $('#standardModal #oer_search_results_list .search_results_list');
			list.html("");
			list.html(response);
			$('#standardModal #oer_standards_list').hide();
			$('#standardModal #oer_search_results_list').show();
			});

		return false;
	});
});