jQuery(document).ready(function(){
    content_height = jQuery('.oer-cntnr').height();
    if ( content_height > 0 ) {
        jQuery('.oer_resource_category_sidebar').height(content_height);
    }
    jQuery('.featuredwpr_bxslider').bxSlider({
		minSlides: 3,
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
    
    jQuery('.btn-load-more').click(function(){
        var page_num = parseInt(jQuery(this).attr('data-page-number'));
        var post_ids = jQuery(this).attr('data-posts');
        var page = jQuery(this).attr('data-page');
        
        var data = {
            action: 'load_more',
            post_var: page_num,
            post_ids:  post_ids,
            page: page
        };
        
        /*$.post(the_ajax_script.ajaxurl, data).done(function(response) {*/
        jQuery.post(sajaxurl, data).done(function(response) {
            var btn_load = jQuery('.btn-load-more');
            var next_page = page_num + 1;
            var base_url = btn_load.attr('data-base-url');
            var max_page = btn_load.attr('data-max-page');
            
            history.pushState({}, '', base_url + $('.btn-load-more').attr("href"));
            $('#content-stories').append(response);
            if (next_page<=max_page) {
                if (post_ids) {
                    btn_load
                       .attr('data-page-number',next_page)
                       .attr('href', '?page='  + next_page.toString());
                } else {
                    btn_load
                       .attr('data-page-number',next_page)
                       .attr('href', '&page='  + next_page.toString());
                }
            }else {
                btn_load.addClass('btn-hidden');
            }
        });
        return false;
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