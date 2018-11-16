jQuery(document).ready(function($) {
    var frame,
        metabox = jQuery("#oer_metaboxid.postbox"),
        btn = metabox.find('button#oer_local_resource_button'),
        input = metabox.find('#oer_resourceurl');
    
    btn.on("click", function( e ){
        e.preventDefault();
        
        if (frame) {
            frame.open();
            return;
        }
        
        frame = wp.media({
            title: 'Select or upload local resource',
            button: {
                text: "Use this resource"
            },
            multiple:false
        });
        
        frame.on("select", function(){
            var attachment = frame.state().get("selection").first().toJSON();
            
            input.val(attachment.url);
        });
        
        frame.open();
    });
});