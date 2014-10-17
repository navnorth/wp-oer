jQuery(document).ready(function(e) {
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
