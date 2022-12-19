jQuery(function($){
	$('.nalrc-search-filters #keyword').on("blur", function(){
		console.log(nalrc_object.ajaxurl);
		var data = {
			action: 'search_resources',
			keyword: $(this).val()
		}
		$.ajax({
			type: "POST",
			url: nalrc_object.ajaxurl,
			data: data,
			success: function(msg){
				$('.oer_resource_posts').html('');
				$('.oer_resource_posts').html(msg);
			}
		});
	});
});