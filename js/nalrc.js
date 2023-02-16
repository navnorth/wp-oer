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

	/**--$(document).on('focus', '.nalrc-search-grade-level .nalrc-select-wrapper .bootstrap-select .dropdown-menu li a.dropdown-item', function(){
		$('.nalrc-search-grade-level .nalrc-select-wrapper .bootstrap-select .dropdown-menu li').removeClass('active');
		$('.nalrc-search-grade-level .nalrc-select-wrapper .bootstrap-select .dropdown-menu li a.dropdown-item').removeClass('active');
		
		$(this).addClass('active');
		$(this).closest('li').addClass('active');
	});

	$(document).on('focus', '.nalrc-search-product .nalrc-select-wrapper .bootstrap-select .dropdown-menu li a.dropdown-item', function(){
		$('.nalrc-search-product .nalrc-select-wrapper .bootstrap-select .dropdown-menu li').removeClass('active');
		$('.nalrc-search-product .nalrc-select-wrapper .bootstrap-select .dropdown-menu li a.dropdown-item').removeClass('active');
		
		$(this).addClass('active');
		$(this).closest('li').addClass('active');
	});--**/

	// up and arrow key press
	if ($('.nalrc-select-filter').length){
		var focusedIndex = -1;
	  	var itemFocus = 0;
	    var optionCount = $('.nalrc-select-filter .dropdown-menu li').length;
	    $(document).on('keydown', '.nalrc-select-filter .dropdown-menu', function(e){
	      e.preventDefault();
	      var code = e.keyCode || e.which;
	      if (code==9){
	        $(this).closest('.bootstrap-select').find('.selectpicker').selectpicker('toggle');
	        filter_index = 0;
	      } else if (code==38) {
	        focusedIndex--;
	      } else if (code==40) {
	        focusedIndex++;
	      } else if (code==32 || code==13){
	      	$(this).trigger('click');
	      }
	      itemFocus = focusedIndex+1;
	      $('.nalrc-select-filter .dropdown-menu li').removeClass('active');
	      $('.nalrc-select-filter .dropdown-menu li a').removeClass('active');
	      var curItem = $(this);
	      var index = $('.dropdown-menu li').index(curItem);
	      $('.nalrc-select-filter .dropdown-menu ul').scrollTop(index*32);
	      var next = $('.nalrc-select-filter .dropdown-menu li:nth-child('+itemFocus+')'); 
	      if (next.length>=0){
	      	next.addClass('active');
	      	next.find('a').addClass('active');
	      }
	      $('.nalrc-select-filter .dropdown-menu li:nth-child('+itemFocus+') a').focus();
	    });
	}

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