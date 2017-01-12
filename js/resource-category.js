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
    /** Sort Script **/
    jQuery('.sortoption').text(jQuery('.sort-options').find('li.cs-selected').text());
    
    jQuery('.sort-resources').click(function(){
      jQuery('.sort-options').fadeToggle('fast');
    });
    
    jQuery('.sort-options ul li a').click(function(){
      jQuery('.sort-options ul li').removeClass('cs-selected');
      jQuery(this).parent().addClass('cs-selected');
      jQuery('.sortoption').text(jQuery(this).text());
      jQuery('.sort-selectbox').val(jQuery(this).parent().attr('data-value'));
      jQuery('.sort-options').fadeToggle('fast');
      jQuery('.sort-selectbox').trigger("change");
    });
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