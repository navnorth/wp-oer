jQuery(document).ready(function($) {
	/**--jQuery(".wp-block-wp-oer-plugin-wp-oer-subjects-index .oer_snglctwpr").each(function(index, element) {
		var hght = jQuery(this).children(".oer-cat-div,.oer-cat-div-large,.oer-cat-div-medium,.oer-cat-div-small").children(".oer-child-category").height();
		jQuery(this).children(".oer-cat-div,.oer-cat-div-large,.oer-cat-div-medium,.oer-cat-div-small").children(".oer-child-category").attr("data-height", hght);
		jQuery(this).children(".oer-cat-div,.oer-cat-div-large,.oer-cat-div-medium,.oer-cat-div-small").children(".oer-child-category").hide();
    });--**/
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

	/** Hide Subjects Index Block Child Category Div on load **/
	setTimeout(function() {
		if ($(".wp-block-wp-oer-plugin-wp-oer-subjects-index .oer_snglctwpr").length>0){
		    $(".wp-block-wp-oer-plugin-wp-oer-subjects-index .oer_snglctwpr").each(function(index, element) {
				let childCat = $(this).children(".oer-cat-div,.oer-cat-div-large,.oer-cat-div-medium,.oer-cat-div-small").find(".oer-child-category");
				let hght = childCat.height();
				childCat.attr("data-height", hght);
				childCat.hide();
		    });
	    }
	}, 10000);

});

function admin_togglenavigation(ref)
{
	jQuery(".wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div,.wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div-large, wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div-medium, .wp-block-wp-oer-plugin-wp-oer-subjects-index .oer-cat-div-small").each(function(index, value)
	{
		if(value == ref)
		{
			if(jQuery(value).hasClass("active-cat"))
			{
				jQuery(value).removeClass("active-cat");
			}
			else
			{
				jQuery(value).addClass("active-cat");
			}


			if ( jQuery(value).children(".active-arrow").length )
			{
				jQuery(value).children( ".active-arrow" ).remove();
			}
			else
			{
				jQuery(value).append( "<div class='active-arrow'></div>" );
			}
		}
		else
		{
			jQuery(value).removeClass("active-cat");
			jQuery(value).children( ".active-arrow" ).remove();
		}
	});
	
	var htmldata = jQuery(ref).children(".oer-child-category").html();
	var datcls = jQuery(ref).attr("data-class");
	var datid = jQuery(ref).attr("data-id");
	
	jQuery(".wp-block-wp-oer-plugin-wp-oer-subjects-index .oer_child_content_wpr").each(function(index, element) {
		if(jQuery(this).attr("data-id") == datcls)
		{
			var dspl = jQuery(this).css("display");
			if(dspl == "block")
			{
				if(jQuery(this).attr("data-class") == datid)
				{
					jQuery(this).slideUp("slow");
					jQuery(this).parent(".oer_snglctwpr").height("auto");
				}
				else
				{
					jQuery(this).html("");
					jQuery(this).slideUp("slow");
					jQuery(this).html(htmldata);
					jQuery(this).attr("data-class", datid);
					jQuery(this).slideDown("slow");

					var hght_upr = jQuery(ref).height();
					var hght_lwr = jQuery(ref).children(".oer-child-category").attr("data-height");
					var ttl_hght = parseInt(hght_upr) + parseInt(hght_lwr) + parseInt(80);
					jQuery(ref).parent(".oer_snglctwpr").height(ttl_hght);
				}
			}
			else
			{
				jQuery(this).html(htmldata);
				jQuery(this).attr("data-class", datid);
				jQuery(this).slideDown("slow");

				var hght_upr = jQuery(ref).height();
				console.log(hght_upr);
				var hght_lwr = jQuery(ref).children(".oer-child-category").attr("data-height");
				console.log(hght_lwr);
				var ttl_hght = parseInt(hght_upr) + parseInt(hght_lwr) + parseInt(80);
				console.log(ttl_hght);
				jQuery(ref).parent(".oer_snglctwpr").height(ttl_hght);
			}

		}
		else
		{
			jQuery(this).slideUp("slow");
			jQuery(this).parent(".oer_snglctwpr").height("auto");
		}
	});

}