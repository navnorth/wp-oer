jQuery(function($){

	function searchResources(){
		$('.oer_resource_posts').html('');
		var loader = '<div class="resource-loader"><img src="'+nalrc_object.plugin_url +'/images/load.gif" /></div>';
		$('.oer_resource_posts').html(loader);
		var data = {
			action: 'search_resources',
		}
		if ($('.nalrc-search-filters #keyword').val()!=='')
			data.keyword = $('.nalrc-search-filters #keyword').val();
		if ($('.nalrc-search-filters #topic').val()!=='')
			data.topic = $('.nalrc-search-filters #topic').val();
		console.log(data);
		$.ajax({
			type: "POST",
			url: nalrc_object.ajaxurl,
			data: data,
			success: function(msg){
				$('.oer_resource_posts').html(msg);
			}
		});
	}

	/** Keyword search **/
	$('.nalrc-search-filters #keyword').on("blur", searchResources);
	$('.nalrc-search-filters #keyword').on("keydown", function(e){
		if (e.keyCode==13)
			$(this).trigger('blur');
	});

	/** Topic Search **/
	$('.nalrc-search-filters #topic').on("change", searchResources);
});