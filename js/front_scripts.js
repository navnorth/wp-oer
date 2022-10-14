jQuery(document).ready(function(e) {
	jQuery(".oer_snglctwpr").each(function(index, element) {
		var hght = jQuery(this).children(".oer-cat-div,.oer-cat-div-large,.oer-cat-div-medium,.oer-cat-div-small").children(".oer-child-category").height();
			jQuery(this).children(".oer-cat-div,.oer-cat-div-large,.oer-cat-div-medium,.oer-cat-div-small").children(".oer-child-category").attr("data-height", hght);
			jQuery(this).children(".oer-cat-div,.oer-cat-div-large,.oer-cat-div-medium,.oer-cat-div-small").children(".oer-child-category").hide();
			//alert(hght);
	    });
	
	if (jQuery( ".oer_datepicker" ).length) {
		jQuery( ".oer_datepicker" ).datepicker();
		jQuery( ".oer_datepicker" ).datepicker( "option", "showAnim", "slideDown" );
	}
	
	jQuery(document).on("show.bs.collapse", '.lp-subject-hidden.collapse', function (){
        var more_count = jQuery('.see-more-subjects').attr('data-count');
        jQuery('.see-more-subjects').text("SEE " + more_count + " LESS -");
    });
    
    jQuery(document).on("hide.bs.collapse", '.lp-subject-hidden.collapse', function (){
        var more_count = jQuery('.see-more-subjects').attr('data-count');
        jQuery('.see-more-subjects').text("SEE " + more_count + " MORE +");
    });
    
    jQuery(document).on("show.bs.collapse", '.tc-lp-details-standard.collapse', function (){
        jQuery(this).parent().find('.lp-standard-toggle i').removeClass('fa-caret-right').addClass('fa-caret-down');
    });
    
    jQuery(document).on("hide.bs.collapse", '.tc-lp-details-standard.collapse', function (){
        jQuery(this).parent().find('.lp-standard-toggle i').removeClass('fa-caret-down').addClass('fa-caret-right');
    });
	
	jQuery(document).on("show.bs.collapse", '#tcHiddenFields.collapse', function (){
        jQuery('#oer-see-more-link').text("SEE LESS -");
    });
    
    jQuery(document).on("hide.bs.collapse", '#tcHiddenFields.collapse', function (){
        jQuery('#oer-see-more-link').text("SEE MORE +");
    });
	
	jQuery(document).on("click", '.oer-lp-excerpt .lp-read-more', function (){
        jQuery('.oer-lp-excerpt').hide();
        jQuery('.oer-lp-full-content').show();
    });
    
    jQuery(document).on("click", '.oer-lp-full-content .lp-read-less', function (){
        jQuery('.oer-lp-excerpt').show();
        jQuery('.oer-lp-full-content').hide();
    });
	
	jQuery(document).on("click", '.oer-lp-value-excerpt .lp-read-more', function (){
        jQuery(this).closest('.oer-lp-value-excerpt').hide();
        jQuery(this).closest('.oer-lp-value-excerpt').parent().find('.oer-lp-value-full').show();
    });
    
    jQuery(document).on("click", '.oer-lp-value-full .lp-read-less', function (){
		jQuery(this).closest('.oer-lp-value-full').hide();
        jQuery(this).closest('.oer-lp-value-full').parent().find('.oer-lp-value-excerpt').show();
    });
	
	jQuery('.tc-hidden-fields .form-field .oer-lp-value .oer-lp-value-full p').each(function(){
		var read_less = jQuery(this).parent().find('.lp-read-less');
		jQuery(this).append(read_less);
	});

	jQuery(document).on('keydown','.wp-block-wp-oer-plugin-wp-oer-subjects-index .oer_snglctwpr .oer-cat-div',function(e){
		if (e.which==13 || e.which==32)
			jQuery(this).trigger('click');
	});

	setTimeout(function(){ 
        jQuery('[data-toggle="collapse"]').removeAttr('data-parent');
        jQuery('[data-bs-toggle="collapse"]').removeAttr('data-parent');
    }, 1000);
});

//adding author
function oer_addauthor(ref)
{
	var img_url = jQuery(ref).attr('data-url');
	jQuery(ref).parent('.oer_hdngsngl').after('<div class="oer_authrcntr"><div class="oer_cls" onClick="oer_removeauthor(this);"><img src="'+img_url+'" /></div><div class="oer_snglfld"><div class="oer_txt">Type:</div><div class="oer_fld"><select name="oer_authortype2"><option value="person">Person</option><option value="organization">Organization</option></select></div></div><div class="oer_snglfld"><div class="oer_txt">Name:</div><div class="oer_fld"><input type="text" name="oer_authorname2" value="" /></div></div><div class="oer_snglfld"><div class="oer_txt">URL:</div><div class="oer_fld"><input type="text" name="oer_authorurl2" value="" /></div></div><div class="oer_snglfld"><div class="oer_txt">Email Address:</div><div class="oer_fld"><input type="text" name="oer_authoremail2" value="" /></div></div></div>');
	jQuery(ref).parent('.oer_hdngsngl').html('Author Information:');
}

//removing author
function oer_removeauthor(ref)
{
	var img_url = jQuery(ref).children('img').attr('src');
	jQuery(ref).parent('.oer_authrcntr').prev('.oer_hdngsngl').html('<input type="button" class="button button-primary" value="Add Author" onClick="oer_addauthor(this);" data-url="'+img_url+'" />');
	jQuery(ref).parent(".oer_authrcntr").remove();
}

function get_standardlist(ref)
{
	var path = jQuery(ref).attr("data-path");
	var imag = jQuery(ref).attr("img-path");
	var standard_id = ref.value;
	jQuery(".oer_lstofstandrd").html('<img width="100" src="'+imag+'">');
	jQuery.ajax({
		type: "POST",
		url: path,
		data: "standard_id="+standard_id+"&task=get_standards",
		success: function(msg){
			jQuery(".oer_lstofstandrd").html(msg);
		}
	});
}

function oer_check_all(ref)
{
	if(ref.checked)
	{
		jQuery(ref).parent('li').children('ul').find("input:checkbox").each(function() {
			jQuery(this).prop('checked', true);
		});
	}
	else
	{
		jQuery(ref).parent('li').children('ul').find("input:checkbox").each(function() {
			jQuery(this).prop('checked', false);
		});
	}
}

function oer_check_myChild(ref)
{
	if(jQuery(ref).parent('li').has('ul'))
	{
		if(ref.checked)
		{
			jQuery(ref).parent('li').children('ul').children('li').find("input:checkbox").each(function() {
				jQuery(this).prop('checked', true);

			});
		}
		else
		{
			jQuery(ref).parent('li').children('ul').children('li').find("input:checkbox").each(function() {
				jQuery(this).prop('checked', false);

			});
		}
	}
}

function set_new_source(source, sourceElement) {
    new_src = source;
    sourceElement.attr("src", new_src);
}

function toggleparent(ref)
{
	jQuery(ref).parent(".oer-sub-category").toggleClass("activelist");
	jQuery(ref).next(".oer-category").slideToggle();
}

function togglenavigation(ref)
{
	jQuery(".oer-cat-div,.oer-cat-div-large,.oer-cat-div-medium,.oer-cat-div-small").each(function(index, value)
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
	jQuery(".oer_child_content_wpr").each(function(index, element) {
		if(jQuery(this).attr("data-id") == datcls)
		{
			var dspl = jQuery(this).css("display");
			if(dspl == "block")
			{
				if(jQuery(this).attr("data-class") == datid)
				{
					jQuery(this).slideUp({
						duration:"slow",
						complete: function(){
							jQuery(this).parent(".oer_snglctwpr").height("auto");
						}
					});
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
				var hght_lwr = jQuery(ref).children(".oer-child-category").attr("data-height");
				var ttl_hght = parseInt(hght_upr) + parseInt(hght_lwr) + parseInt(80);
				jQuery(ref).parent(".oer_snglctwpr").height(ttl_hght);
			}

		}
		else
		{
			jQuery(this).slideUp({
				duration:"slow",
				complete: function(){
					jQuery(this).parent(".oer_snglctwpr").height("auto");
				}
			});
		}
	});

	if (jQuery(ref).find('.oer-cat-txt-btm-cntnr li .oer-expand-subject i').hasClass('fa-expand'))
		jQuery(ref).find('.oer-cat-txt-btm-cntnr li .oer-expand-subject i').removeClass('fa-expand').addClass('fa-compress');
	else
		jQuery(ref).find('.oer-cat-txt-btm-cntnr li .oer-expand-subject i').removeClass('fa-compress').addClass('fa-expand');

}

function togglenavigation_mobile(ref)
{
	var dspl = jQuery(ref).next(".oer-child-category-mobile").css("display");
	jQuery(".oer-cat-div-mobile").each(function(){
		jQuery(this).next(".oer-child-category-mobile").slideUp("slow");
		jQuery(this).removeClass("child_mobileactive");
	});
	if(dspl == 'none')
	{
		jQuery(ref).next(".oer-child-category-mobile").slideDown("slow");
		jQuery(ref).addClass("child_mobileactive");
	}
	else
	{
		jQuery(ref).next(".oer-child-category-mobile").slideUp("slow");
		jQuery(ref).removeClass("child_mobileactive");
	}
}

function changeonhover(ref)
{
	var img = jQuery(ref).attr("data-hoverimg")
	jQuery(ref).addClass("change_mouseover");
	jQuery(ref).children(".oer-cat-icn").css("background", "url("+img+") no-repeat scroll center center transparent");
}

function changeonout(ref)
{
	var img = jQuery(ref).attr("data-normalimg")
	jQuery(ref).removeClass("change_mouseover");
	/*jQuery(".oer-cat-div").each(function(){
		jQuery(this).removeClass("change_mouseover");
	});*/
	jQuery(ref).children(".oer-cat-icn").css("background", "url("+img+") no-repeat scroll center center transparent");
}

//tab functionality at single resource page
function rsrc_tabs(ref)
{
	var dataid = jQuery(ref).attr("data-id");
	var arrClass = [ "tags", "alignedStandards", "keyword", "moreLikeThis" ];
	jQuery.each( arrClass, function( index, value )
	{
	  if(value == dataid)
	  {
		jQuery( "." + value ).css("display", "block");
	  }
	  else
	  {
		jQuery( "." + value ).css("display", "none");
	  }
	});
}

function load_onScroll(ref)
{
	var path = jQuery(ref).attr("file-path");
	var dataId = jQuery(ref).attr("data-id");

	if(jQuery(ref).scrollTop() >= 15)
	{
		jQuery.ajax({
			type: "POST",
			url: path,
			data: "termid="+dataId+"&task=dataScroll",
			success: function (res)
			{
           		jQuery(ref).html(res);
        	}
        });
	}
}
function collapse(ref)
{
	jQuery(".oer_category_sidebar").slideToggle(500, function () {
        jQuery(ref).text(function () {
            return jQuery(ref).is(":visible") ? "Collapse" : "Expand";
        });
    });
}
// Slide Toggole in Subject Button
function tglcategories(ref)
{
	if(jQuery(ref).hasClass("open"))
	{
		jQuery(ref).removeClass("open")
	}
	else
	{
		jQuery(ref).addClass("open")
	}
    jQuery(".oer_category_sidebar").slideToggle("slow");
}
