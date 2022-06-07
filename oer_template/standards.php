<?php
/*
 * Template Name: Main Standards Page Template
 */
add_filter( 'body_class','oer_standards_body_classes' );
function oer_standards_body_classes( $classes ) {
 
    $classes[] = 'oer-standards';
     
    return $classes;
     
}

get_header();

$std_count = oer_get_standards_count();
$standards = oer_get_standards();
?>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">
		    <div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng"><?php printf(__("Browse All %d Standards", OER_SLUG), $std_count); ?></div>
			<div class="oer-allftrdrsrccntr">
			    <?php if ($standards) {  ?>
			    <ul class="oer-standards">
				<?php foreach($standards as $standard) {
				    $cnt = oer_get_resource_count_by_standard($standard->id);
				    $slug = "resource/standards/".sanitize_title($standard->standard_name);
				?>
				<li><a href="<?php echo home_url($slug); ?>"><?php echo esc_html($standard->standard_name); ?></a> <span class="res-count"><?php echo esc_html($cnt); ?></span></li>
				<?php } ?>
			    </ul>
			    <?php } ?>
			</div>
		    </div>
		</div><!-- #content -->
	</section><!-- #primary -->
</div>
<?php
get_footer();
?>