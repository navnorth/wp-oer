jQuery(document).ready(function(){
    content_height = jQuery('.oer-cntnr').height();
    if ( content_height > 0 ) {
        jQuery('.oer_resource_category_sidebar').height(content_height);
    }
    
    var highlights_slider_config = {
		minSlides: 1,
  		maxSlides: 3,
		moveSlides: 1,
  		slideWidth: 320,
  		slideMargin: 10,
		pager: false,
		onSlideBefore: function($slideElement, oldIndex, newIndex){
		    var next_resource = $slideElement.next().next().next();
		    var resource_id = next_resource.attr('data-id');
		    if (next_resource.find('.frtdsnglwpr').length==0) {
			var data = {
			    action: 'load_highlight',
			    post_var: resource_id
			};
			
			jQuery.post(sajaxurl, data).done(function(response) {
			    next_resource.append(jQuery.trim(response));
			});
		    }
		},
		onSliderLoad: function(currentIndex) {
		    jQuery('.featuredwpr_bxslider').css({'visibility':'visible','height':'auto'});
		}
	};
    
    //Responsive BX SLider
    var highlight_slider = jQuery('.featuredwpr_bxslider').bxSlider(highlights_slider_config);
    
    jQuery(".featuredwpr_bxslider a.bx-prev, .featuredwpr_bxslider a.bx-next").bind("click", function() {
	setTimeout(function(e) { jQuery(window).trigger("scroll"); }, 10); //handle the lazy load
	e.preventDefault();
    });

    jQuery('.allftrdpst_slider').bxSlider({
	    pager: false,
	    onSliderLoad: function(currentIndex) {
		jQuery('.allftrdpst_slider').css({'visibility':'visible','height':'auto'});
	    }
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
    	if (jQuery('.sort-options').is(":visible"))
      	jQuery('.sort-options').fadeIn('fast');
      else
      	jQuery('.sort-options').fadeOut('fast');
    });
    jQuery('.sort-resources').keydown(function(e){
			if (e.which==13 || e.which==32) {
				if (jQuery('.sort-options').is(":visible"))
	      	jQuery('.sort-options').fadeIn('fast');
	      else
	      	jQuery('.sort-options').fadeOut('fast');
				}
    });
    
    jQuery('.sort-options ul li a').click(function(){
      jQuery('.sort-options ul li').removeClass('cs-selected');
      jQuery(this).parent().addClass('cs-selected');
      jQuery('.sortoption').text(jQuery(this).text());
      jQuery('.sort-selectbox').val(jQuery(this).parent().attr('data-value'));
      jQuery('.sort-options').fadeIn('fast');
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