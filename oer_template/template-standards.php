<?php
/*
 * Template Name: Default Tag Page Template
 */
get_header();

add_filter( 'body_class','standards_body_classes' );
function standards_body_classes( $classes ) {
 
    $classes[] = 'standars-template';
     
    return $classes;
     
}
 
$std_count = get_standards_count();
?>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">
		    <div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng"><?php printf(__("Browse All %d Standards", OER_SLUG), $std_count); ?></div>
		    </div>
		</div><!-- #content -->
	</section><!-- #primary -->
</div>