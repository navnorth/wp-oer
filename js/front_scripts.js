jQuery(document).ready(function(e) {
	jQuery(".snglctwpr").each(function(index, element) {
		var hght = jQuery(this).children(".cat-div,.cat-div-large,.cat-div-medium,.cat-div-small").children(".child-category").height();
			jQuery(this).children(".cat-div,.cat-div-large,.cat-div-medium,.cat-div-small").children(".child-category").attr("data-height", hght);
			jQuery(this).children(".cat-div,.cat-div-large,.cat-div-medium,.cat-div-small").children(".child-category").hide();
			//alert(hght);
	    });
	jQuery( ".oer_datepicker" ).datepicker();
	jQuery( ".oer_datepicker" ).datepicker( "option", "showAnim", "slideDown" );
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
	jQuery(ref).parent(".sub-category").toggleClass("activelist");
	jQuery(ref).next(".category").slideToggle();
}

function togglenavigation(ref)
{
	jQuery(".cat-div,.cat-div-large,.cat-div-medium,.cat-div-small").each(function(index, value)
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
	var htmldata = jQuery(ref).children(".child-category").html();
	var datcls = jQuery(ref).attr("data-class");
	var datid = jQuery(ref).attr("data-id");
	jQuery(".child_content_wpr").each(function(index, element) {
		if(jQuery(this).attr("data-id") == datcls)
		{
			var dspl = jQuery(this).css("display");
			if(dspl == "block")
			{
				if(jQuery(this).attr("data-class") == datid)
				{
					jQuery(this).slideUp("slow");
					jQuery(this).parent(".snglctwpr").height("auto");
				}
				else
				{
					jQuery(this).html("");
					jQuery(this).slideUp("slow");
					jQuery(this).html(htmldata);
					jQuery(this).attr("data-class", datid);
					jQuery(this).slideDown("slow");

					var hght_upr = jQuery(ref).height();
					var hght_lwr = jQuery(ref).children(".child-category").attr("data-height");
					var ttl_hght = parseInt(hght_upr) + parseInt(hght_lwr) + parseInt(80);
					jQuery(ref).parent(".snglctwpr").height(ttl_hght);
				}
			}
			else
			{
				jQuery(this).html(htmldata);
				jQuery(this).attr("data-class", datid);
				jQuery(this).slideDown("slow");

				var hght_upr = jQuery(ref).height();
				var hght_lwr = jQuery(ref).children(".child-category").attr("data-height");
				var ttl_hght = parseInt(hght_upr) + parseInt(hght_lwr) + parseInt(80);
				jQuery(ref).parent(".snglctwpr").height(ttl_hght);
			}

		}
		else
		{
			jQuery(this).slideUp("slow");
			jQuery(this).parent(".snglctwpr").height("auto");
		}
	});

}

function togglenavigation_mobile(ref)
{
	var dspl = jQuery(ref).next(".child-category-mobile").css("display");
	jQuery(".cat-div-mobile").each(function(){
		jQuery(this).next(".child-category-mobile").slideUp("slow");
		jQuery(this).removeClass("child_mobileactive");
	});
	if(dspl == 'none')
	{
		jQuery(ref).next(".child-category-mobile").slideDown("slow");
		jQuery(ref).addClass("child_mobileactive");
	}
	else
	{
		jQuery(ref).next(".child-category-mobile").slideUp("slow");
		jQuery(ref).removeClass("child_mobileactive");
	}
}

function changeonhover(ref)
{
	var img = jQuery(ref).attr("data-hoverimg")
	jQuery(ref).addClass("change_mouseover");
	jQuery(ref).children(".cat-icn").css("background", "url("+img+") no-repeat scroll center center transparent");
}

function changeonout(ref)
{
	var img = jQuery(ref).attr("data-normalimg")
	jQuery(ref).removeClass("change_mouseover");
	/*jQuery(".cat-div").each(function(){
		jQuery(this).removeClass("change_mouseover");
	});*/
	jQuery(ref).children(".cat-icn").css("background", "url("+img+") no-repeat scroll center center transparent");
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
	jQuery(".category_sidebar").slideToggle(500, function () {
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
    jQuery(".category_sidebar").slideToggle("slow");
}
