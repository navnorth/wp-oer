jQuery(function($){
	function searchResources(){
		$('.oer_resource_posts').html('');
		var loader = '<div class="resource-loader"><img src="'+resources.plugin_url +'/images/load.gif" /></div>';
		$('.oer_resource_posts').html(loader);
		var data = {
			action: 'search_resources',
		}
		if ($('.resource-search-filters #keyword').val()!=='')
			data.keyword = $('.resource-search-filters #keyword').val();
		if ($('.resource-search-filters #gradeLevel').val()!=='')
			data.gradeLevel = $('.resource-search-filters #gradeLevel').val();
		if ($('.resource-search-filters #product').val()!=='')
			data.product = $('.resource-search-filters #product').val();
		
		$.ajax({
			type: "POST",
			url: resources.ajaxurl,
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
	var gradeWrapper = $('.resource-search-grade-level .resource-select-wrapper');
	if (gradeWrapper.length){
		gradeWrapper.find('select').selectpicker();
	}

	var resWrapper = $('.resource-search-product .resource-select-wrapper');
	if (resWrapper.length){
		resWrapper.find('select').selectpicker();
	}

	$(document).on('keydown', '.resource-search-grade-level .resource-select-wrapper .bootstrap-select button.dropdown-toggle,.resource-search-product .resource-select-wrapper .bootstrap-select button.dropdown-toggle', function(e){
		var code = e.keyCode || e.which;
		if (code==32 || code==13){
			$(this).trigger('click.bs.dropdown.data-api');
		}
	});

	// up and arrow key press
	if ($('.resource-select-filter').length){
		var focusedIndex = -1;
		var itemFocus = 0;
	    var optionCount = $('.resource-select-filter .dropdown-menu li').length;
	    $(document).on('keydown', '.resource-select-filter .dropdown-menu', function(e){
	    	e.preventDefault();
			var code = e.keyCode || e.which;
			if (code==9){
				$(this).closest('.bootstrap-select').find('.selectpicker').selectpicker('toggle');
			filter_index = 0;
			} else if (code==38) {
				focusedIndex--;
			} else if (code==40) {
				focusedIndex++;
			} else if (code==27) {
				itemFocus = 0;
				focusedIndex = -1;
			} else if (code==32 || code==13){
				$(this).trigger('click');
				focusedIndex = -1;
			}
			if (code==38 || code==40){
			  itemFocus = focusedIndex+1;
			  $(this).closest('.dropdown-menu').find('li').removeClass('active');
			  $(this).closest('.dropdown-menu').find('li a').removeClass('active');
			  var curItem = $(this);
			  var index = $(this).closest('.dropdown-menu').find('li').index(curItem);
			  $(this).closest('.dropdown-menu').find('ul').scrollTop(index*32);
			  var next = $(this).closest('.dropdown-menu').find('li:nth-child('+itemFocus+')'); 
			  if (next.length>=0){
			  	next.addClass('active');
			  	next.find('a').addClass('active');
			  }
			  $(this).closest('.dropdown-menu').find('li:nth-child('+itemFocus+') a').focus();
			}
	    });
	}

	/** Topic Search **/
	$('.resource-search-button').on('click', searchResources);

	/** Keyword search **/
	$('.resource-search-keyword #keyword').on('keydown', function(e){
		var code = e.keyCode || e.which;
		if (code==13){
			searchResources();
		}
	});

	/** Add Aria Label to Bootstrap Select button **/
	$('.selectpicker').each(function() {
		var button = $('button[data-id="'+this.id+'"]').first();
		if(button.length > 0) {
			button.attr('aria-label', $(this).attr('aria-label'));
		}
	});

	// Keyboard navigation of filter dropdown on Resources page
	$(document).on('keydown', '.resource-select-filter button.dropdown-toggle', function(e){
		var code = e.keyCode || e.which;
		if (code==13 || code==32){
			$(this).closest('.bootstrap-select').find('.selectpicker').selectpicker('toggle');
		} else if (code==38) {
			$(this).closest('.bootstrap-select').find('.dropdown-menu li:last-child a').focus();
		} else if (code==40) {
			$(this).closest('.bootstrap-select').find('.dropdown-menu li:first-child a').focus();
		}
	});

	$(document).on('keydown', '.resource-select-filter .dropdown-menu li a', function(e){
      var code = e.keyCode || e.which;
      if (code==9){
        $(this).closest('.bootstrap-select').find('.selectpicker').selectpicker('toggle');
        filter_index = 0;
      } else if (code==13 || code==32) {
      	$(this).trigger('click');
      } else if (code==40) {
        //console.log($(this).closest('ul.dropdown-menu').find('li.active').index());
        //$(this).closest("ul.dropdown-menu").scrollTop = ($(this).closest('ul.dropdown-menu').find('li.active').index())*32;
      }
	});

	$(document).on('click', '.resource-select-wrapper .bootstrap-select .dropdown-toggle', function(e){
		$(this).next('.dropdown-menu').toggleClass('open');
		$(this).next('.dropdown-menu').toggleClass('show');
		$(this).next('.dropdown-menu').find('.inner').toggleClass('open');
		$(this).next('.dropdown-menu').find('.inner').toggleClass('show');
	});

	document.addEventListener('click', (event) => { 
		if (!$(event.target).hasClass('dropdown-item') 
			&& !$(event.target).hasClass('dropdown-toggle')
			&& !$(event.target).hasClass('filter-option-inner-inner')){
	    	if ($(".resource-select-wrapper .dropdown-menu").hasClass('show')){
	    		$(".resource-select-wrapper .dropdown-menu").removeClass('show');
	    	} 
    	}
	}, true);

	// Print resource list
	if ($('#oer_print_resource_list').length){
		$(document).on('click','#oer_print_resource_list', function(e){
			e.preventDefault();
			var keyword, gradeLevel, product, filter='';
			if ($('.resource-search-filters #keyword').val()!=='')
				keyword = $('.resource-search-filters #keyword').val();
			let grades = $('.resource-search-filters #gradeLevel').val();
			if (typeof grades != 'undefined' && grades.length>0)
				gradeLevel = $('.resource-search-filters #gradeLevel').val();
			let products = $('.resource-search-filters #product').val();
			if (typeof products != 'undefined' && products.length>0)
				product = $('.resource-search-filters #product').val();
			var res_url = window.location.href;
			if (keyword){
				console.log(keyword);
				filter += '&keyword='+keyword;
			}
			if (gradeLevel){
				if (Array.isArray(gradeLevel)){
					gradeLevel = gradeLevel.join(",");
				}
				filter += '&gradelevel='+gradeLevel;
			}
			if (product){
				console.log(product);
				filter += '&product='+product;	
			}
			var printWindow = window.open(res_url+'?action=print&view=list'+filter,'printWindow');
			setTimeout(function(){
				printWindow.print();
				printWindow.onafterprint = printWindow.close;
			},500);
		});
		$(document).on('keydown','#oer_print_resource_list', function(e){
			var code = e.keyCode || e.which;
			if (code==32 || code==13){
				$(this).trigger('click');
			}
		});
	}

	// Print resource table
	if ($('#oer_print_resource_table').length){
		$(document).on('click','#oer_print_resource_table', function(e){
			e.preventDefault();
			var keyword, gradeLevel, product, filter='';
			if ($('.resource-search-filters #keyword').val()!=='')
				keyword = $('.resource-search-filters #keyword').val();
			let grades = $('.resource-search-filters #gradeLevel').val();
			if (typeof grades != 'undefined' && grades.length>0)
				gradeLevel = $('.resource-search-filters #gradeLevel').val();
			let products = $('.resource-search-filters #product').val();
			if (typeof products != 'undefined' && products.length>0)
				product = $('.resource-search-filters #product').val();
			var res_url = window.location.href;
			if (keyword){
				console.log(keyword);
				filter += '&keyword='+keyword;
			}
			if (gradeLevel){
				if (Array.isArray(gradeLevel)){
					gradeLevel = gradeLevel.join(",");
				}
				filter += '&gradelevel='+gradeLevel;
			}
			if (product){
				filter += '&product='+product;	
			}
			var res_url = window.location.href;
			var printWindow = window.open(res_url+'?action=print&view=table'+filter,'printWindow');
			setTimeout(function(){
				printWindow.print();
				printWindow.onafterprint = printWindow.close;
			},500);
		});
		$(document).on('keydown','#oer_print_resource_table', function(e){
			var code = e.keyCode || e.which;
			if (code==32 || code==13){
				$(this).trigger('click');
			}
		});
	}
	
	/** Move Resource Featured Image below description on small devices **/
	window.addEventListener("resize", moveResourceImage);

	moveResourceImage();
});