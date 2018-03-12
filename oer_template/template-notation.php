<?php
/*
 * Template Name: Notation Page Template
 */
add_filter( 'body_class','standards_body_classes' );
function standards_body_classes( $classes ) {
 
    $classes[] = 'notation-template';
     
    return $classes;
     
}

get_header();

global $wp_query;

$notation_slug = $wp_query->query_vars['notation'];
$notation = get_substandard_by_notation($notation_slug);
$standard = get_standard_by_notation($notation_slug);

?>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">
		    <div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng"><?php printf(__("%s", OER_SLUG), $standard->standard_name); ?></div>
			<div class="oer-allftrdrsrccntr">
			    <ul class="oer-standard">
				<li><?php echo $standard->standard_title; ?>
				    <?php if ($sub_standards) {  ?>
				    <ul class="oer-substandards">
					<?php foreach($sub_standards as $sub_standard) {
					    $slug = "resource/standards/".sanitize_title($standard->standard_name)."/".sanitize_title($sub_standard->standard_title);
					?>
					<li><a href="<?php echo home_url($slug); ?>"><?php echo $sub_standard->standard_title; ?></a></li>
					<?php } ?>
				    </ul>
				    <?php } ?>
				    <?php if ($notation) {  ?>
				    <h4><strong><?php echo $notation->standard_notation; ?></strong> <?php echo $notation->description; ?></h4>
				    <?php } ?>
				</li>
			    </ul>
			</div>
		    </div>
		</div><!-- #content -->
	</section><!-- #primary -->
</div>
<?php
get_footer();
?>