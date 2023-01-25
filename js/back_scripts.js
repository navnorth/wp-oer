const __oer = wp.i18n.__;

var formfield, invoker;
var frm_error;
jQuery(document).ready(function(e) {
	jQuery( ".oer_datepicker" ).datepicker( { dateFormat: 'MM d, yy' } );
	jQuery( ".oer_datepicker" ).datepicker( "option", "showAnim", "slideDown" );
	
	jQuery( "input.screenshot_option").on("change",function(){
		jQuery( "input.screenshot_option" ).not(this).attr("checked",false);
	});
	
	/* Set Subject Area Main Icon */
	jQuery('#main_icon_button').click(function() {
		invoker = jQuery(this).attr('id');
		formfield = jQuery('#mainIcon').attr('name');
		
		showMediaUpload(invoker, formfield);
	});
	
	/* Set Subject Area Hover Icon */
	jQuery('#hover_icon_button').click(function() {
		invoker = jQuery(this).attr('id');
		formfield = jQuery('#hoverIcon').attr('name');

		showMediaUpload(invoker, formfield);
	});
	
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
	
	jQuery(document).on('click','.remove-standard', function(){
		var std = jQuery(this);
		var std_id = std.attr('data-id');
		std.parent().remove();
		jQuery("ul.oer-standard-list input[value='"+std_id+"']").prop('checked',false);
		var $stds=[];
		jQuery('span.standard-label').each(function(){
			$std = jQuery(this).children('a.remove-standard').attr('data-id');
			$stds.push($std);
		});
		jQuery('input[name="oer_standard"]').val($stds.join());
	});

	jQuery(document).on('click','.remove-related_resource', function(){
		var std = jQuery(this);
		var std_id = std.attr('data-id');
		std.parent().remove();
		jQuery("#oer_related_resources_list input[value='"+std_id+"']").prop('checked',false);
		jQuery("#oer_related_resources_list label[rid='"+std_id+"']").removeClass('selected');
		updateRelatedResourceListToHidden();
	});

	jQuery(document).on('input','input[name="searchRelatedResources"]', function(e) {
		var searchTerm = jQuery.trim(this.value).toLowerCase();
		jQuery('#oer-related-resources-dynahide').remove();
		if(searchTerm > ''){
		  jQuery('#oer-related-resources-dynahide').remove();
		  css = '#oer_related_resources_list ul li:not([data_name*="'+searchTerm+'"]){display:none;}'
		  style = jQuery('<style type="text/css" id="oer-related-resources-dynahide">').text(css)
		  jQuery('head').append(style);
		}
	});

	jQuery('#add-new-standard').on('click', function(e){
		e.preventDefault();
		jQuery('#standardModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
								
		//Align standards inside modal
		jQuery("ul.oer-standard-list input[name='oer_standard[]']").prop('checked',false);
		jQuery('span.standard-label').each(function(){
			$std = jQuery(this).children('a.remove-standard').attr('data-id');
			jQuery("ul.oer-standard-list input[value='"+$std+"']").prop('checked',true);
		});
		
	});
	jQuery('#standardModal').on('click', "#btnSaveStandards", function(e){
		e.preventDefault();
		var selected = [];
                var selectedHtml = "";
		jQuery('#add-new-standard').prevAll('.standard-label').remove();
		jQuery.each(jQuery('#standardModal input[type=checkbox]:checked'), function(){
			var sId = jQuery(this).val();
			var title = jQuery(this).next('.oer_stndrd_desc').text();
			console.log(sId);
			console.log(title);
			displaySelectedStandard(sId, title);
			selected.push(sId);
		});
		var standards = selected.join();
		jQuery(".oer_metainrwpr input[name='oer_standard']").val(standards);
		jQuery(".search-standard-text").val("");
		displaydefaultStandards();
		jQuery("#standardModal").modal('hide');
	});
	jQuery('.search_close_btn').on("click", function(e){
		displaydefaultStandards();
	});
	
	if (typeof wp.data !== "undefined") {
		wp.data.subscribe(function(){
			if (wp.data.select('core/editor')){
				var isSavingPost = wp.data.select('core/editor').isSavingPost();
				var isAutosavingPost = wp.data.select('core/editor').isAutosavingPost();
				
				if (isSavingPost && !isAutosavingPost) {
					if (typeof window.tinyMCE !== "undefined")
						window.tinyMCE.triggerSave();
				}
			}
		});
	}

	jQuery('#add-new-related-resource').on('click', function(e){
		e.preventDefault();
		
		var delim = jQuery('input[name="oer_related_resource"]').val();
		if(delim > ''){
			jQuery('#oer_related_resources_list ul li label').removeClass('selected');
			jQuery('.relatedResourceNode').prop('checked', false);
			var array = delim.split(",");
			jQuery.each(array,function(i){
					$_node = array[i];
					jQuery('#oer_related_resources_list ul li label[rid="'+$_node+'"]').addClass('selected').children('input[value="'+$_node+'"]').prop('checked', true);
			});
		}
		
		jQuery('#relatedResourcesModal').modal('show');
	});

	jQuery('input.relatedResourceNode').on('change', function(evt) {
			var allchecked = jQuery('input.relatedResourceNode:checked');
			var id = jQuery(this).val();
	   	if(allchecked.length > 3) {
			 	this.checked = false;
	   	}else{
			var chkbool = jQuery(this).is(':checked')
				if(chkbool){
					jQuery('label[rid="'+id+'"]').addClass('selected');
				}else{
					jQuery('label[rid="'+id+'"]').removeClass('selected');
				}	
			}
	});

	jQuery('#btnAddRelatedResources').on('click',function(e){
		e.preventDefault();
		jQuery('#relatedResourcesModal').modal('hide');
		updateRelatedResourceListToHidden();
	});


	/** Move Loader Background **/
    if (jQuery('.loader').length>0){
        var loader = jQuery('.loader');
        jQuery('#wpcontent').append(loader);
    }

    /** Validate Resource URL **/
    var frm = jQuery('.oer_settings_form');
    if (frm.length){
    	jQuery(document).on('submit','.oer_settings_form', function(e){
    		var path = jQuery(this).find('#oer_configurable_resource_path').val();
    		var pathRegEx = /^[^\/](?:.*[^\/])?$/;
    		var validPath = pathRegEx.test(path);
    		if (validPath) {
    			window.frm_error = false;
    			jQuery(this).find('.form-inline-error').remove();
    		} else {
    			e.preventDefault();
    			jQuery(this).find('#oer_configurable_resource_path').after('<span class="form-inline-error">Invalid path! Please enter a valid path.</span>');
    			jQuery(this).find('#oer_configurable_resource_path').focus();
    			window.frm_error = true;
    			return false;
    		}
    	});
    }
});

jQuery(document).ajaxComplete(function(event, xhr, settings) {
	var queryStringArr;
	if (typeof queryStringArr !== 'undefined')
		queryStringArr = settings.data.split('&');
    if( jQuery.inArray('action=add-tag', queryStringArr) !== -1){
        var xml = xhr.responseXML;
        $response = jQuery(xml).find('term_id').text();
        if($response!=""){
        	if (jQuery('#remove_main_icon_button').length>0)
        		jQuery('#remove_main_icon_button').trigger("click");
        	if (jQuery('#remove_hover_icon_button').length>0)
        		jQuery('#remove_hover_icon_button').trigger("click");
        }
    }
});

function showMediaUpload(invoker, formfield){
	var button = jQuery(this),
	custom_uploader = wp.media({
	    title: __oer('Insert image','open-educational-resource'),
	    library : {
	        type : 'image'
	    },
	    button: {
	        text: __oer('Use this image','open-educational-resource') // button label text
	    },
	    multiple: false // multiple image selection set to false
	}).on('select', function() { // it also has "open" and "close" events 
	    var attachment = custom_uploader.state().get('selection').first().toJSON();
	    let html = '<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />';
	    
	    imgurl = attachment.url;
		jQuery("#"+formfield).val(imgurl);
		if (jQuery("."+invoker+"_img").length>0) {
			jQuery("."+invoker+"_img").remove();
		}
		jQuery("#"+invoker).before('<div class="' + invoker + '_img">'+html+'</div>');
		jQuery("#remove_"+invoker).removeClass("hidden");
	})
	.open();
}

function updateRelatedResourceListToHidden(){
	var elm = jQuery('input.relatedResourceNode:checked');
	var ret = ''; var sep = ''; var lthm = '';
	elm.each(function() {
		 if(ret != '') sep = ',';
		 ret += sep + jQuery(this).val();

		 lthm += '<span class="standard-label">'+jQuery(this).attr('data_name')+'<a href="javascript:void(0)" class="remove-related_resource" data-id="'+jQuery(this).val()+'"><span class="dashicons dashicons-no-alt"></span></a></span>';

	});
	jQuery('input[name="oer_related_resource"]').val(ret);
	jQuery('.oer_related_resource_display').html(lthm);

}

function displaySelectedStandard(sId, title) {
	jQuery('#add-new-standard').before("<span class='standard-label'>" + title + "<a href='javascript:void(0)' class='remove-standard' data-id='" + sId + "'><span class='dashicons dashicons-no-alt'></span></a></span>");
}

function displaydefaultStandards() {
	jQuery('#standardModal #oer_standards_list').show();
	jQuery('#standardModal #oer_search_results_list').hide();
}

//adding author
function oer_addauthor(ref)
{
	var author_label = 'URL:';
	var author_name_label = 'Name:';
	var author_type_label = 'Type:';
	var author_email_label = 'Email Address:';
	var img_url = jQuery(ref).attr('data-url');
	var author_url = jQuery(ref).attr('data-authorurl-label');
	var author_name = jQuery(ref).attr('data-authorname-label');
	var author_type = jQuery(ref).attr('data-authortype-label');
	var author_email = jQuery(ref).attr('data-authoremail-label');
	if (typeof author_url !== 'undefined') {
		author_label = author_url + ':';
	}
	if (typeof author_name !== 'undefined') {
		author_name_label = author_name + ':';
	}
	if (typeof author_type !== 'undefined') {
		author_type_label = author_type + ':';
	}
	if (typeof author_email !== 'undefined') {
		author_email_label = author_email + ':';
	}
	var show_author = '<div class="oer_authrcntr"><div class="oer_cls" onClick="oer_removeauthor(this);"><img src="'+img_url+'" /></div><div class="oer_snglfld"><div class="oer_txt">' + author_type_label + '</div><div class="oer_fld"><select name="oer_authortype2"><option value="person">Person</option><option value="organization">Organization</option></select></div></div><div class="oer_snglfld"><div class="oer_txt">' + author_name_label + '</div><div class="oer_fld"><input type="text" name="oer_authorname2" value="" /></div></div><div class="oer_snglfld"><div class="oer_txt">' + author_label + '</div><div class="oer_fld"><input type="text" name="oer_authorurl2" value="" /></div></div><div class="oer_snglfld"><div class="oer_txt">' + author_email_label + '</div><div class="oer_fld"><input type="text" name="oer_authoremail2" value="" /></div></div></div>';
	jQuery(ref).parent('.oer_hdngsngl').after(show_author);
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
		if (!window.frm_error){
			var Top = document.documentElement.scrollTop || document.body.scrollTop;
			jQuery('.loader .loader-img').css({'padding-top':Top + 'px'});
			jQuery('.loader').show();
		}
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

//Import LR Resources
function processLRImport(btn, input) {
	var max_time = jQuery(btn).attr('data-max-time');
	
	if ( document.getElementById(input).value === "" ) {
		return false;
	}
	if (jQuery('.notice-red').length>0) {
		jQuery('.notice-red').remove();
	}
	
	jQuery(btn).prop('value','Processing...');
	
	setTimeout(function() {
		var Top = document.documentElement.scrollTop || document.body.scrollTop;
		jQuery('.loader .loader-img').css({'padding-top':Top + 'px'});
		jQuery('.loader .loader-img > div').append("<div class='loader-notice'>LR Import Execution Timeout: " + max_time + " seconds</div>");
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
	setTimeout(function() {
		var Top = document.documentElement.scrollTop || document.body.scrollTop;
		jQuery('.loader .loader-img').css({'padding-top':Top + 'px'});
		jQuery('.loader').show();
		} ,1000);
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

function getFileExtension(filename) {
	return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}