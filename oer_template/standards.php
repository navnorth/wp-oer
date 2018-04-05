<?php
/*
 * Template Name: Main Standards Page Template
 */
add_filter( 'body_class','standards_body_classes' );
function standards_body_classes( $classes ) {
 
    $classes[] = 'oer-standards';
     
    return $classes;
     
}

get_header();

$std_count = get_standards_count();
$standards = get_standards();
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
				    $cnt = get_resource_count_by_standard($standard->id);
				    $slug = "resource/standards/".sanitize_title($standard->standard_name);
				?>
				<li><a href="<?php echo home_url($slug); ?>"><?php echo $standard->standard_name; ?></a> <span class="res-count"><?php echo $cnt; ?></span></li>
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