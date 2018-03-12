<?php
/*
 * Template Name: Standard Page Template
 */
add_filter( 'body_class','standards_body_classes' );
function standards_body_classes( $classes ) {
 
    $classes[] = 'standards-template';
     
    return $classes;
     
}

get_header();

global $wp_query;
$standard_name_slug = $wp_query->query_vars['standard'];
$standard = get_standard_by_slug($standard_name_slug);
$sub_standards = get_substandards($standard->id);
?>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">
		    <div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng"><?php printf(__("Browse %s", OER_SLUG), $standard->standard_name); ?></div>
			<div class="oer-allftrdrsrccntr">
			    <?php if ($sub_standards) {  ?>
			    <ul class="oer-standards">
				<?php foreach($sub_standards as $sub_standard) {
				    $slug = "resource/standards/".$standard_name_slug."/".sanitize_title($sub_standard->standard_title);
				?>
				<li><a href="<?php echo home_url($slug); ?>"><?php echo $sub_standard->standard_title; ?></a></li>
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