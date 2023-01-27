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
		if ($('.nalrc-search-filters #gradeLevel').val()!=='')
			data.gradeLevel = $('.nalrc-search-filters #gradeLevel').val();
		if ($('.nalrc-search-filters #product').val()!=='')
			data.product = $('.nalrc-search-filters #product').val();
		
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

	//multiple select dropdown
	var gradeWrapper = $('.nalrc-search-grade-level .nalrc-select-wrapper');
	if (gradeWrapper.length){
		gradeWrapper.find('select').selectpicker();
	}

	$('.nalrc-search-grade-level .nalrc-select-wrapper .bootstrap-select .dropdown-toggle').on('keydown', function(e){
		var code = e.keyCode || e.which;
		if (code==32){
			$(this).trigger('click');
		}
	});

	$(document).on('focus', '.nalrc-search-grade-level .nalrc-select-wrapper .bootstrap-select .dropdown-menu li a.dropdown-item', function(){
		$('.nalrc-search-grade-level .nalrc-select-wrapper .bootstrap-select .dropdown-menu li').removeClass('active');
		$('.nalrc-search-grade-level .nalrc-select-wrapper .bootstrap-select .dropdown-menu li a.dropdown-item').removeClass('active');
		
		$(this).addClass('active');
		$(this).closest('li').addClass('active');
	});

	/** Topic Search **/
	$('.nalrc-search-button').on('click', searchResources);

	/** Keyword search **/
	$('.nalrc-search-keyword #keyword').on('keydown', function(e){
		var code = e.keyCode || e.which;
		if (code==13){
			searchResources();
		}
	});
	
	/** Move Resource Featured Image below description on small devices **/
	window.addEventListener("resize", moveResourceImage);

	moveResourceImage();
});