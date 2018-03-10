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
$notation = get_substandard_by_slug($notation_slug);

?>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">
		    <div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng"><?php printf(__("Browse %s", OER_SLUG), $core_standard->standard_name); ?></div>
			<div class="oer-allftrdrsrccntr">
			    <ul class="oer-standard">
				<li><?php echo $standard->standard_title; ?>
				    <?php if ($sub_standards) {  ?>
				    <ul class="oer-substandards">
					<?php foreach($sub_standards as $sub_standard) {
					    $slug = "resource/standards/".sanitize_title($core_standard->standard_name)."/".sanitize_title($sub_standard->standard_title);
					?>
					<li><a href="<?php echo home_url($slug); ?>"><?php echo $sub_standard->standard_title; ?></a></li>
					<?php } ?>
				    </ul>
				    <?php } ?>
				    <?php if ($notations) {  ?>
				    <ul class="oer-notations">
					<?php foreach($notations as $notation) {
					    $slug = "resource/standards/".sanitize_title($core_standard->standard_name)."/".$standard_name_slug."/".$notation->standard_notation;
					?>
					<li><a href="<?php echo home_url($slug); ?>"><strong><?php echo $notation->standard_notation; ?></strong> <?php echo $notation->description; ?></a></li>
					<?php } ?>
				    </ul>
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