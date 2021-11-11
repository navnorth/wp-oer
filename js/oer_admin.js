jQuery(document).ready(function($) {
	/**
	 * Dismiss our activation notice
	 */
	$('#oer-dismissible-notice .notice-dismiss').on("click", function(e) {
                
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

function admin_togglenavigation(ref){
	// add active-cat class to currently selected subject area
	jQuery(".wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div,.wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div-large, .wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div-medium, .wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div-small").removeClass('active-cat');
	jQuery(".wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div,.wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div-large, .wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div-medium, .wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div-small").children( ".active-arrow" ).remove();
	jQuery(ref).toggleClass('active-cat');
	jQuery(ref).append( "<div class='active-arrow'></div>" );
	
	var htmldata = jQuery(ref).children(".oer-child-category").html();
	var datcls = jQuery(ref).attr("data-class");
	var datid = jQuery(ref).attr("data-id");

	// adjust height of child category
	jQuery(".wp-block-wp-oer-plugin-wp-oer-subjects-index .oer_child_content_wpr").each(function(index, element) {
		if(jQuery(this).attr("data-id") == datcls) {
			var dspl = jQuery(this).css("display");
			if(dspl == "block") {
				if(jQuery(this).attr("data-class") == datid) {
					jQuery(this).slideUp({
						duration:"slow",
						complete: function(){
							jQuery(this).parent(".oer_snglctwpr").height("auto");
						}
					});
				} else {
					var hght_upr = jQuery(ref).height();
					var hght_lwr = jQuery(ref).children(".oer-child-category").attr("data-height");
					var ttl_hght = parseInt(hght_upr) + parseInt(hght_lwr) + parseInt(80);
					jQuery(ref).parent(".oer_snglctwpr").height(ttl_hght);

					jQuery(this).html("");
					jQuery(this).slideUp("slow");
					jQuery(this).html(htmldata);
					jQuery(this).attr("data-class", datid);
					jQuery(this).slideDown("slow");
				}
			} else {
				var hght_upr = jQuery(ref).height();
				var hght_lwr = jQuery(ref).children(".oer-child-category").attr("data-height");
				var ttl_hght = parseInt(hght_upr) + parseInt(hght_lwr) + parseInt(80);
				jQuery(ref).parent(".oer_snglctwpr").height(ttl_hght);
				jQuery(this).html(htmldata);
				jQuery(this).attr("data-class", datid);
				jQuery(this).slideDown("slow");
			}
		} else {
			jQuery(this).slideUp({
				duration:"slow",
				complete: function(){
					jQuery(this).parent(".oer_snglctwpr").height("auto");
				}
			});
		}
	});

}