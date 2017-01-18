jQuery(document).ready(function(){

	jQuery('li').children('.stndrd_ttl').children('img').click(function(e)
	{
		var plgnpth = jQuery(this).attr('data-pluginpath');
		if( jQuery(this).parent('.stndrd_ttl').next('.oer_stndrd_desc').next('ul').hasClass('active') )
		{
			jQuery(this).attr('src', plgnpth+'images/closed_arrow.png');
			jQuery(this).parent('.stndrd_ttl').next('.oer_stndrd_desc').next('ul').removeClass('active').children('li').slideToggle();
			e.stopPropagation();
		}
		else
		{
			jQuery(this).attr('src', plgnpth+'images/open_arrow.png')
			jQuery(this).parent('.stndrd_ttl').next('.oer_stndrd_desc').next('ul').addClass('active').children('li').slideToggle();
			e.stopPropagation();
		}

		if( jQuery(this).parent('.stndrd_ttl').next('.oer_stndrd_desc').next('ul').next('ul').hasClass('active') )
		{
			jQuery(this).parent('.stndrd_ttl').next('.oer_stndrd_desc').next('ul').next('ul').removeClass('active').children('li').slideToggle();
			e.stopPropagation();
		}
		else
		{
			jQuery(this).parent('.stndrd_ttl').next('.oer_stndrd_desc').next('ul').next('ul').addClass('active').children('li').slideToggle();
			e.stopPropagation();
		}

	});

	var path = jQuery('.tglimg').attr('data-pluginpath');
	jQuery( "li.selected" ).parents( "li" ).css('display', 'list-item');
	jQuery( "li.selected" ).parents( "li" ).children("ul").addClass('active');
	jQuery( "li.selected" ).parents( "li" ).children("ul.active").each(function(index, element) {
        jQuery(this).children("li").css('display', 'list-item');
    });
 	jQuery( "li.selected" ).parents( "li" ).each(function(index, element) {
        if(jQuery(this).children('.stndrd_ttl').children('img').attr('src') != 'undefined')
		{
			jQuery(this).children('.stndrd_ttl').children('img').attr('src', path+'images/open_arrow.png')
		}
    });

	/*jQuery( '.selected' ).each(function( index ){

		var path = jQuery('.tglimg').attr('data-pluginpath');

		jQuery(this).css('display', 'list-item');
		jQuery(this).parents('li').last('.oer_main').css('display', 'list-item');
		jQuery(this).parents('li').last('.oer_main').children('.stndrd_ttl').children('img').attr('src', path+'images/open_arrow.png')
		jQuery(this).parents('li').last('.oer_main').children('ul').addClass('active oer_slctstandard');

		if(jQuery(this).children('.stndrd_ttl').children('img').attr('src') != 'undefined')
		{
			jQuery(this).children('.stndrd_ttl').children('img').attr('src', path+'images/open_arrow.png')
		}

		if(jQuery(this).parent('ul').parent('li').children('.stndrd_ttl').children('img').attr('src') != 'undefined')
		{
			jQuery(this).parent('ul').parent('li').children('.stndrd_ttl').children('img').attr('src', path+'images/open_arrow.png')
		}

		if(jQuery(this).parent('ul').parent('li').parent('ul').parent('li').children('.stndrd_ttl').children('img').attr('src') != 'undefined')
		{
			jQuery(this).parent('ul').parent('li').parent('ul').parent('li').children('.stndrd_ttl').children('img').attr('src', path+'images/open_arrow.png')
		}

		if(jQuery(this).parent('ul').parent('li').parent('ul').parent('li').parent('ul').parent('li').children('.stndrd_ttl').children('img').attr('src') != 'undefined')
		{
			jQuery(this).parent('ul').parent('li').parent('ul').parent('li').parent('ul').parent('li').children('.stndrd_ttl').children('img').attr('src', path+'images/open_arrow.png')
		}

		if(jQuery(this).parent('ul').parent('li').parent('ul').parent('li').parent('ul').parent('li').children('.stndrd_ttl').children('img').attr('src') != 'undefined')
		{
			jQuery(this).parent('ul').parent('li').parent('ul').parent('li').parent('ul').parent('li').children('.stndrd_ttl').children('img').attr('src', path+'images/open_arrow.png')
		}

		var ref = jQuery(this).parent('ul');
		jQuery(ref).each(function( index ) {
			jQuery(ref).addClass('active');
			jQuery(ref).children('li').css('display', 'list-item');
		});

		var ref = jQuery(this).parent('ul').parent('li').parent('ul');
		jQuery(ref).each(function( index ) {
			jQuery(ref).addClass('active');
			jQuery(ref).children('li').css('display', 'list-item');
		});

		var ref = jQuery(this).parent('ul').parent('li').parent('ul').parent('li').parent('ul');
		jQuery(ref).each(function( index ) {
			jQuery(ref).addClass('active');
			jQuery(ref).children('li').css('display', 'list-item');
		});

		var ref = jQuery(this).parent('ul').parent('li').parent('ul').parent('li').parent('ul').parent('li').parent('ul');
		jQuery(ref).each(function( index ) {
			jQuery(ref).addClass('active');
			jQuery(ref).children('li').css('display', 'list-item');
		});

		var ref = jQuery(this).parent('ul').parent('li').parent('ul').parent('li').parent('ul').parent('li').parent('ul').parent('li').parent('ul');
		jQuery(ref).each(function( index ) {
			jQuery(ref).addClass('active');
			jQuery(ref).children('li').css('display', 'list-item');
		});

	});*/
});
