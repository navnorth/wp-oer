jQuery(document).ready(function(){
    content_height = jQuery('.oer-cntnr').height();
    if ( content_height > 0 ) {
        jQuery('.oer_resource_category_sidebar').height(content_height);
    }
    
    //Responsive BX SLider
    jQuery('.featuredwpr_bxslider').bxSlider({
		minSlides: 1,
  		maxSlides: 3,
		moveSlides: 1,
  		slideWidth: 320,
  		slideMargin: 10,
		pager: false
	});

    jQuery('.allftrdpst_slider').bxSlider({
	    pager: false
    });
    if (jQuery('.oer_right_featuredwpr .bx-wrapper').is(':visible')) {
	jQuery('.bx-loading').hide();
	var slider_width = jQuery('.oer_right_featuredwpr .bx-wrapper').css('max-width');
	var swidth = parseInt(slider_width)-10;
	jQuery('.oer_right_featuredwpr .bx-viewport').css('width',swidth+'px');
	jQuery('.oer_right_featuredwpr .bx-wrapper').css( { 'max-width':'100%', 'width':'100%' } );
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