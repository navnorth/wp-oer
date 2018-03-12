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
$substandards = get_substandards_by_notation($notation_slug);
$standard = get_standard_by_notation($notation_slug);

?>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">
		    <div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng"><a href="<?php echo home_url("resource/standards/".sanitize_title($standard->standard_name)); ?>"><?php printf(__("%s", OER_SLUG), $standard->standard_name); ?></a></div>
			<div class="oer-allftrdrsrccntr">
			    <ul class="oer-substandards">
			    <?php if ($substandards) {  ?>
				<?php foreach($substandards as $substandard) {
				    $slug = "resource/standards/".sanitize_title($standard->standard_name)."/".sanitize_title($substandard['standard_title']);
				?>
				<li><a href="<?php echo home_url($slug); ?>"><?php echo $substandard['standard_title']; ?></a></li>
				<?php } ?>
			    <?php } ?>
			    <?php if ($notation) {  ?>
				<li><ul class="oer-notations"><li><h4><strong><?php echo $notation->standard_notation; ?></strong> <?php echo $notation->description; ?></h4></li></li></ul>
			    <?php } ?>
			    </ul>
			</div>
		    </div>
		</div><!-- #content -->
	</section><!-- #primary -->
</div>
<?php
get_footer();
?>