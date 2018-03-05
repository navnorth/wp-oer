<?php
/*
 * Template Name: Default Tag Page Template
 */

add_filter( 'body_class','standards_body_classes' );
function standards_body_classes( $classes ) {
 
    $classes[] = 'standars-template';
     
    return $classes;
     
}
 
get_header();
?>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">
		    <h1>Standards</h1>

		</div><!-- #content -->
	</section><!-- #primary -->
</div>
<?php get_footer(); ?>