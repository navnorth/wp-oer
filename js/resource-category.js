jQuery(document).ready(function(){
    content_height = jQuery('.cntnr').height();
    if ( content_height > 0 ) {
        jQuery('.resource_category_sidebar').height(content_height);
    }
});

function toggleparent(ref)
{
	jQuery(ref).parent(".sub-category").toggleClass("activelist");
	jQuery(ref).next(".resource_category").slideToggle();
}