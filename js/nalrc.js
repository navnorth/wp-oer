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
		if ($('.nalrc-search-filters #product').val()!=='')
			data.product = $('.nalrc-search-filters #product').val();
		if ($('.nalrc-search-filters #year').val()!=='')
			data.year = $('.nalrc-search-filters #year').val();
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

	function moveResourceImage() {
		var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	    if (width<768){
			let thumbnail = $('.nalrc-featured-thumbnail');
			$('.nalrc-resource-details .nalrc-resource-desc').after(thumbnail);
		} else {
			let thumbnail = $('.nalrc-featured-thumbnail');
			$('.nalrc-resource-details').prepend(thumbnail);
		}
	}

	/** Keyword search **/
	$('.nalrc-search-filters #keyword').on("blur", searchResources);
	$('.nalrc-search-filters #keyword').on("keydown", function(e){
		if (e.keyCode==13)
			$(this).trigger('blur');
	});

	/** Topic Search **/
	$('.nalrc-search-filters #topic').on("change", searchResources);

	/** Product Type Search **/
	$('.nalrc-search-filters #product').on("change", searchResources);

	/** Publication Year Search **/
	$('.nalrc-search-filters #year').on("change", searchResources);

	/** Move Resource Featured Image below description on small devices **/
	window.addEventListener("resize", moveResourceImage);

	moveResourceImage();
});