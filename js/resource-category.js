jQuery(document).ready(function(){
    content_height = jQuery('.oer-cntnr').height();
    if ( content_height > 0 ) {
        jQuery('.oer_resource_category_sidebar').height(content_height);
    }
});

/** Toggle Sub Categories **/
function toggleparent(ref)
{
	jQuery(ref).parent(".oer-sub-category").toggleClass("activelist");
	jQuery(ref).next(".oer_resource_category").slideToggle();
}

/** Slide Toggole in Subject Button **/
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
    jQuery(".oer_resource_category_sidebar").slideToggle("slow");
}