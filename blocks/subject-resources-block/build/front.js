jQuery(document).ready(function($){
	$(document).on("click", '.sort-box .sort-resources', function(e){
		$(this).next('.sort-options').toggle();
	});
	$(document).on('click', '.sort-box .sort-options .sortList li', function(e){
		let val = $(this).attr('value');
		let text = $(this).text();
		let count = $('.oer-subject-resources-list .oer-snglrsrchdng').attr('data-count');
		let subjects = $('.oer-subject-resources-list .oer-snglrsrchdng').attr('data-subjects');
		let args = { 'sort': val, 'count': count, 'subjects': subjects };

		$(this).next('.sort-selectbox select').val(val);
		$('.sort-box .sortoption').text(text);
		$(this).closest('.sort-options').hide();

		updateResourcesDisplay(args);
	});

	/* Update resource display via ajax */
	function updateResourcesDisplay(args){
		var data = {
			action : 'get_subject_resources',
			sort: args['sort'],
			displayCount: args['count'],
			selectedSubjects: args['subjects']
		};

		$.ajax({ 
			url:wp_oer_block.ajaxurl, 
			type:'POST',
			data: data,
			success:function(response){
				$('.oer-subject-resources-list').html('');
				$('.oer-subject-resources-list').html(response);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				console.log(errorThrown);
			}
		})
	}
	
	$(document).on("change", '.sort-selectbox select', function(e){
		let sort = $(this).val();
		let count = $('.oer-subject-resources-list .oer-snglrsrchdng').attr('data-count');
		let subjects = $('.oer-subject-resources-list .oer-snglrsrchdng').attr('data-subjects');


		let args = { 'sort': sort, 'count': count, 'subjects': subjects };	

		updateResourcesDisplay(args);
	});
});