<?php
/*
 * Template Name: Notation Page Template
 */
add_filter( 'body_class','oer_standard_notation_body_classes' );
function oer_standard_notation_body_classes( $classes ) {
 
    $classes[] = 'notation-template';
     
    return $classes;
     
}

get_header();

global $wp_query;
$upnotations = null;
$upstandards = null;
$end_upnote = "";
$end_html = "";

$notation_slug = $wp_query->query_vars['notation'];
$notation = oer_get_substandard_by_notation($notation_slug);

if (strpos($notation->parent_id,"standard_notation")!==false){
    $upnotations = oer_get_hierarchical_notations($notation->parent_id);
}

if ($upnotations){
    foreach($upnotations as $upnotation) {
	if (strpos($upnotation['parent_id'],"sub_standards")!==false){
	    $upstandards = oer_get_hierarchical_substandards($upnotation['parent_id']);
	    $upstandards = array_reverse($upstandards);
	}
    }
} else {
    if (strpos($notation->parent_id,"sub_standards")!==false){
	$upstandards = oer_get_hierarchical_substandards($notation->parent_id);
	$upstandards = array_reverse($upstandards);
    }
}

$subnotations = oer_get_child_notations($notation->id);
$substandards = get_substandards_by_notation($notation_slug);
$standard = oer_get_standard_by_notation($notation_slug);
$resources = oer_get_resources_by_notation($notation->id);

oer_display_custom_styles();
?>
<div class="oer-backlink">
    <a href="<?php echo home_url('resource/standards'); ?>"><?php esc_html_e("< Back to Standards",OER_SLUG); ?></a>
</div>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">
		    <div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng"><?php printf(__("Browse %s", OER_SLUG), '<a href="'.home_url("resource/standards/".sanitize_title($standard->standard_name)).'">'.esc_html($standard->standard_name).'</a>'); ?></div>
			<div class="oer-allftrdrsrccntr-notation">
			    <ul class="oer-standard">
			    <?php  if ($upstandards){
				foreach($upstandards as $upstandard) {
				    $slug = "resource/standards/".sanitize_title($standard->standard_name)."/".sanitize_title($upstandard['standard_title']);
				?>
				<li>
				    <ul class="oer-hsubstandards">
					<li><a href="<?php echo home_url($slug); ?>"><?php echo esc_html($upstandard['standard_title']); ?></a></li>
				<?php
				$end_html .= '</ul>
					</li>';
				}
			    }
			    if ($upnotations) {
				foreach($upnotations as $upnotation){
				    $upnote_slug = $upnotation['standard_notation'];
				    ?>
				    <li class="upnotation">
					<ul class="oer-notations">
					    <li><a href="<?php echo esc_url($upnote_slug); ?>"><strong><?php echo esc_html($upnotation['standard_notation']); ?></strong> <?php echo wp_kses_post($upnotation['description']); ?></a></li>
					
				    <?php
				    $end_upnote .= '</ul>
				    </li>';
				}
			    }
			    if ($notation) {  ?>
				<li>
				    <ul class="oer-notations">
					<li>
					    <h4><strong><?php echo esc_html($notation->standard_notation); ?></strong> <?php echo wp_kses_post($notation->description); ?></h4>
					</li>
					<?php if (!empty($subnotations)) { ?>
					<li>
					    <ul class="oer-subnotations">
						<?php
						foreach($subnotations as $subnotation) {
						    $cnt = oer_get_resource_count_by_notation($subnotation->id);
						    $subnote_slug = $subnotation->standard_notation;
						?>
						<li>
						    <a href="<?php echo esc_url($subnote_slug); ?>"><strong><?php echo esc_html($subnotation->standard_notation); ?></strong> <?php echo wp_kses_post($subnotation->description); ?></a>  <span class="res-count"><?php echo esc_html($cnt); ?></span>
						</li>
						<?php } ?>
					    </ul>
					</li>    
					<?php } ?>
				    </ul>
				</li>
			    <?php }
			    if ($end_html)
				echo wp_kses_post($end_html);
			    if ($end_upnote)
				echo wp_kses_post($end_upnote);
			    ?>
			    </ul>
			</div>
			<div class="oer_standard_resources">
			    <?php if ($resources) { ?>
				<h4><?php _e("Resources:", OER_SLUG); ?></h4>
				<ul class="oer-resources">
				    <?php foreach($resources as $resource) { ?>
				    <li><a href="<?php echo esc_url(get_the_permalink($resource->ID)); ?>"><?php echo esc_html($resource->post_title); ?></a></li>
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