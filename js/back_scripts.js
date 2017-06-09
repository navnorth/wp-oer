jQuery(document).ready(function(e) {
	jQuery( ".oer_datepicker" ).datepicker();
	jQuery( ".oer_datepicker" ).datepicker( "option", "showAnim", "slideDown" );
	
	jQuery( "input.screenshot_option").on("change",function(){
		jQuery( "input.screenshot_option" ).not(this).attr("checked",false);
	});
	
	/* Set Subject Area Main Icon */
	jQuery('#main_icon_button').click(function() {
		invoker = jQuery(this).attr('id');
		formfield = jQuery('#mainIcon').attr('name');
		tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
		return false;
	});
	
	/* Set Subject Area Hover Icon */
	jQuery('#hover_icon_button').click(function() {
		invoker = jQuery(this).attr('id');
		formfield = jQuery('#hoverIcon').attr('name');
		tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
		return false;
	});

	/* Callback after calling media upload */
	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery("#"+formfield).val(imgurl);
		if (jQuery("."+invoker+"_img").length>0) {
			jQuery("."+invoker+"_img").remove();
		}
		jQuery("#"+invoker).before('<div class="' + invoker + '_img">'+html+'</div>');
		jQuery("#remove_"+invoker).removeClass("hidden");
		tb_remove();
	}
	
	/** Remove Main Icon **/
	jQuery('#remove_main_icon_button').click(function() {
		jQuery('#mainIcon').val('');
		jQuery('.main_icon_button_img').remove();
		jQuery(this).addClass('hidden');
	});
	
	/** Remove Hover Icon **/
	jQuery('#remove_hover_icon_button').click(function() {
		jQuery('#hoverIcon').val('');
		jQuery('.hover_icon_button_img').remove();
		jQuery(this).addClass('hidden');
	});
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


//
function oer_check_all(ref)
{
	if(ref.checked)
	{
		jQuery(ref).parent('div').parent('li').children('ul').find("input:checkbox").each(function() {
			jQuery(this).prop('checked', true);
		});
	}
	else
	{
		jQuery(ref).parent('div').parent('li').children('ul').find("input:checkbox").each(function() {
			jQuery(this).prop('checked', false);
		});
	}
}


function oer_check_myChild(ref)
{
	if(jQuery(ref).parent('div').parent('li').has('ul'))
	{
		if(ref.checked)
		{
			jQuery(ref).parent('div').parent('li').children('ul').children('li').find("input:checkbox").each(function() {
				jQuery(this).prop('checked', true);
			});
		}
		else
		{
			/*jQuery(ref).parent('div').parent('li').parent('ul').parent('li').children("div").find("input:checkbox").each(function() {
				jQuery(this).prop('checked', false);

			});*/
			jQuery(ref).parent('div').parent('li').children('ul').children('li').find("input:checkbox").each(function() {
				jQuery(this).prop('checked', false);

			});
		}
	}
}

function oer_select_all()
{
	jQuery('.oer_cats').find("input:checkbox").each(function(){
		  jQuery(this).prop('checked', true);
	});
}

function oer_unselect_all()
{
	jQuery('.oer_cats').find("input:checkbox").each(function(){
		  jQuery(this).prop('checked', false);
	});
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
			if(msg == "empty")
			{
				jQuery(".oer_lstofstandrd").html("");
				jQuery(".oer_lstofstandrd").removeClass("oer_lstovrflwstndrd");
			}
			else
			{
				jQuery(".oer_lstofstandrd").html(msg);
				jQuery(".oer_lstofstandrd").addClass("oer_lstovrflwstndrd");
			}
		}
	});
}

//Process Initial Setup
function processInitialSettings(form) {
	setTimeout(function() {
		var Top = document.documentElement.scrollTop || document.body.scrollTop;
		jQuery('.loader .loader-img').css({'padding-top':Top + 'px'});
		jQuery('.loader').show();
	} ,1000);
	return true;
}

//Confirm Deletion
function confirm_deletion(form) {
	var validate = false;
	jQuery('.reset-form input[type=checkbox]').each(function(){
		if(jQuery(this).is(':checked'))
			validate = true;
	});
	if(validate){
		if (confirm('Are you sure you want to continue? You will delete data from your system!')==true) {
			return true;
		} else {
			return false;
		}
	} else { return false; }
}

//Import Resource/Subject Areas
function processImport(btn, file) {
	if ( document.getElementById(file).files.length == 0 ) {
		return false;
	}
	if (jQuery('.notice-red').length>0) {
		jQuery('.notice-red').remove();
	}
	if (getFileExtension(document.getElementById(file).value)!=="xls") {
		jQuery('#'+file).closest('div').find('.resource-upload-notice').html('<span class="notice-red">Import file must be in Excel format with .xls extension</span>');
		return false;
	}
	jQuery(btn).prop('value','Processing...');
	setTimeout(function() {
		var Top = document.documentElement.scrollTop || document.body.scrollTop;
		jQuery('.loader .loader-img').css({'padding-top':Top + 'px'});
		jQuery('.loader').show();
		} ,1000);
	jQuery('.oer_imprtrwpr .oer-import-row input[type=submit]').prop('disabled',true);
	return(true); 
}

//Import Standards
function importStandards(frm,btn) {
	if (jQuery(frm).find(':checkbox:checked').length==0){
		return(false);
	}
	jQuery(btn).prop('value','Processing...');
	jQuery('.oer_imprtrwpr .oer-import-row input[type=submit]').prop('disabled',true);
	return(true);
}

//Set image in span
function setimage(ref)
{
	var obj = jQuery("#currntspan").val();

	jQuery("#"+obj).html(ref);
	jQuery("#a_"+obj).html("Save");
	jQuery("#a_"+obj).attr("title","save");

	if(obj.match(/spn_hover/))
	{
		jQuery("#a_"+obj).attr("title","save_hover");
	}
}

function remove_img(ref)   // remove image
{
	var plugin_path= jQuery("#plugin_path").val();
	var obj= jQuery(ref).attr("alt");

	var confirmpopup = window.confirm("Are you sure tha you want to delete this image?");
	if(confirmpopup == true)   // if user is sure to delete the image
	{
		var action="";
		var splitdata="";

		if(ref.title == "remove_hover")
		{
			action="remove_image_hover";
			splitdata = obj.split("er");
		}
		else
		{
			action="remove_image";
			splitdata = obj.split("n");
		}
		//alert(action);
		var term_id=splitdata[1];
		$.post(plugin_path+"/ajax/ajax_category.php",
		{
		   term_id :term_id,
		   action:action
		},
		function(data,status)
		{
			jQuery("#"+obj).html("");
			jQuery(ref).html("Upload");
			jQuery(ref).attr("title","upload");
	   });
	}
}

function save_image(ref)  // save image as post
{

	var action="";
	var splitspnid="";
	if(ref.attr("title")=="save_hover")
	{
		action="insert_hover_image";
		splitspnid=  ref.prev("span").attr("id").split("er");
	}
	else
	{
		action="insert_image";
		splitspnid=  ref.prev("span").attr("id").split("n");
	}


	var term_id = splitspnid[1];
	var image_path = ref.prev("span").find("img").attr("src");
	var plugin_path= jQuery("#plugin_path").val();

	$.post(plugin_path+"/ajax/ajax_category.php",
    {
	   term_id :term_id,
	   image_path:image_path,
	   action:action
    },
    function(data,status)
    {
		ref.html("Update");  // changing save to update
		ref.attr("title","upload");
		alert("Image Saved Successfully");
    });
}

function getFileExtension(filename) {
	return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}
