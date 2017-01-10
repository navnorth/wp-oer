jQuery(document).ready(function(){
    jQuery('.resource-load-more-button').click(function(){
        var page_num = parseInt(jQuery(this).attr('data-page-number'));
        var terms = jQuery(this).attr('data-subject-ids');
        
        var data = {
            action: 'load_more',
            post_var: page_num,
            subjects:terms
        };
        
        /*$.post(the_ajax_script.ajaxurl, data).done(function(response) {*/
        jQuery.post(sajaxurl, data).done(function(response) {
            var btn_load = jQuery('.resource-load-more-button');
            var next_page = page_num + 1;
            var base_url = btn_load.attr('data-base-url');
            var max_page = btn_load.attr('data-max-page');
            
            /*history.pushState({}, '', base_url + jQuery('.resource-load-more-button').attr("href"));*/
            jQuery('#content-resources .resourcecloud').before(response);
            if (next_page<=max_page) {
                btn_load
                   .attr('data-page-number',next_page)
                   .attr('href', '&page='  + next_page.toString());
            }else {
                btn_load.addClass('btn-hidden');
            }
        });
        return false;
    });
});