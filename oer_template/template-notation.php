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
$subnotations = get_child_notations($notation->id);
$substandards = get_substandards_by_notation($notation_slug);
$standard = get_standard_by_notation($notation_slug);
$resources = get_resources_by_notation($notation->id);

?>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">
		    <div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng"><a href="<?php echo home_url("resource/standards/".sanitize_title($standard->standard_name)); ?>"><?php printf(__("%s", OER_SLUG), $standard->standard_name); ?></a></div>
			<div class="oer-allftrdrsrccntr-notation">
			    <ul class="oer-substandards">
			    <?php if ($substandards) {  ?>
				<?php foreach($substandards as $substandard) {
				    $slug = "resource/standards/".sanitize_title($standard->standard_name)."/".sanitize_title($substandard['standard_title']);
				?>
				<li><a href="<?php echo home_url($slug); ?>"><?php echo $substandard['standard_title']; ?></a></li>
				<?php } ?>
			    <?php } ?>
			    <?php if ($notation) {  ?>
				<li>
				    <ul class="oer-notations">
					<li>
					    <h4><strong><?php echo $notation->standard_notation; ?></strong> <?php echo $notation->description; ?></h4>
					</li>
					<?php
					if (!empty($subnotations)) {
					?>
					<li>
					    <ul class="oer-subnotations">
						<?php
						foreach($subnotations as $subnotation) {
						    $cnt = get_resource_count_by_notation($subnotation->id);
						    $subnote_slug = $subnotation->standard_notation;
						?>
						<li>
						    <a href="<?php echo $subnote_slug; ?>"><strong><?php echo $subnotation->standard_notation; ?></strong> <?php echo $subnotation->description; ?></a>  <span class="res-count"><?php echo $cnt; ?></span>
						</li>
						<?php } ?>
					    </ul>
					</li>    
					<?php } ?>
				    </ul>
				</li>
			    <?php } ?>
			    </ul>
			</div>
			<div class="oer_standard_resources">
			    <?php if ($resources) { ?>
				<h4><?php _e("Resources:", OER_SLUG); ?></h4>
				<ul class="oer-resources">
				    <?php foreach($resources as $resource) { ?>
				    <li><a href="<?php echo get_the_permalink($resource->ID); ?>"><?php echo $resource->post_title; ?></a></li>
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