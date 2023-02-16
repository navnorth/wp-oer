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

	var resWrapper = $('.nalrc-search-product .nalrc-select-wrapper');
	if (resWrapper.length){
		resWrapper.find('select').selectpicker();
	}

	$('.nalrc-search-grade-level .nalrc-select-wrapper .bootstrap-select .dropdown-toggle,.nalrc-search-product .nalrc-select-wrapper .bootstrap-select .dropdown-toggle').on('keydown', function(e){
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

	/**--$(document).on('focus', '.nalrc-search-product .nalrc-select-wrapper .bootstrap-select .dropdown-menu li a.dropdown-item', function(){
		$('.nalrc-search-product .nalrc-select-wrapper .bootstrap-select .dropdown-menu li').removeClass('active');
		$('.nalrc-search-product .nalrc-select-wrapper .bootstrap-select .dropdown-menu li a.dropdown-item').removeClass('active');
		
		$(this).addClass('active');
		$(this).closest('li').addClass('active');
	});--**/
	$('.nalrc-select-filter div.dropdown-menu.show li').on('keydown', function (e) {
	    if (e.keyCode == 38) { // Up
	      	/**--var previousEle = $(this).prev();
	      	if (previousEle.length == 0) {
	        	previousEle = $(this).nextAll().last();
	      	}
		    var selVal = $('.selectpicker option').filter(function () {
		        return $(this).text() == previousEle.text();
		    }).val();
		    $('.selectpicker').selectpicker('val', selVal);

		     return;--**/
	    }
	    if (e.keyCode == 40) { // Down
	      	/**--var nextEle = $(this).next();
	      	if (nextEle.length == 0) {
	        	nextEle = $(this).prevAll().last();
	      	}
	      	var selVal = $('.selectpicker option').filter(function () {
	        	return $(this).text() == nextEle.text();
	      	}).val();
	      	$('.selectpicker').selectpicker('val', selVal);

	      	return;--**/
	      	e.preventDefault();
	      	var e = jQuery.Event("keydown");
			e.which = 9; // # Some key code value
			e.keyCode = 9;
			$(this).trigger(e);
	    }
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