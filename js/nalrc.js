jQuery(function($){
	$('.nalrc-search-filters #keyword').on("blur", function(){
		var data = {
			action: 'search_resources',
			keyword: $(this).val();
		}
		$.ajax({
			type: "POST",
			url: nalrc_object.ajaxurl,
			data: data,
			success: function(msg){
				console.log(msg);
			}
		});
	});
});