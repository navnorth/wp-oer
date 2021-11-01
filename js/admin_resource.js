const oer__ = wp.i18n.__;
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
            title: oer__('Select or upload local resource', 'open-educational-resource'),
            button: {
                text: oer__("Use this resource","open-educational-resource")
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