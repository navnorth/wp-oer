jQuery(document).ready(function(){
    jQuery('#content-resources').on("click", ".resource-load-more-button", function(){
        var page_num = parseInt(jQuery(this).attr('data-page-number'));
        var terms = jQuery(this).attr('data-subject-ids');
        var sort = 0;
        
        if (jQuery(this).attr('data-sort')) {
            sort = jQuery(this).attr('data-sort');
        }
        
        var data = {
            action: 'load_more',
            post_var: page_num,
            subjects:terms,
            sort: sort
        };
        
    jQuery.post(oer_ajax_object.ajaxurl, data).done(function(response) {
        var btn_load = jQuery('.resource-load-more-button');
        var next_page = page_num + 1;
        var base_url = btn_load.attr('data-base-url');
        var max_page = btn_load.attr('data-max-page');
        
        history.pushState({}, '', base_url + jQuery('.resource-load-more-button').attr("href"));
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
    
    /** Sorting of List of Resources **/
    jQuery('.sort-selectbox').change(function(){
        var sort = jQuery(this).val();
        var terms = jQuery(this).attr('data-subject-ids');
        var page_num = parseInt(jQuery('.resource-load-more-button').attr('data-page-number'));

        var data = {
            action: 'sort_resources',
            sort: sort,
            post_var: page_num-1,
            subjects:terms
        };
        
        jQuery.post(oer_ajax_object.ajaxurl, data).done(function(response) {
            var button = jQuery('#content-resources .resourcecloud');
            jQuery('#content-resources').html('');
            jQuery('#content-resources').html(response);
            jQuery('#content-resources').append(button);
            
            if (jQuery('.resource-load-more-button').is(':visible')) {
                var btn_load = jQuery('.resource-load-more-button');
                
                btn_load.attr('data-sort', sort)
                
            } 
        });
    });
});