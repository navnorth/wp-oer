jQuery(document).ready(function(){
    content_height = jQuery('.cntnr').height();
    if ( content_height > 0 ) {
        jQuery('.resource_category_sidebar').height(content_height);
    }
});

/** Toggle Sub Categories **/
function toggleparent(ref)
{
	jQuery(ref).parent(".sub-category").toggleClass("activelist");
	jQuery(ref).next(".resource_category").slideToggle();
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
    jQuery(".resource_category_sidebar").slideToggle("slow");
}