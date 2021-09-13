<?php
/**
 * Plugin Name:     WP OER Subjects Index
 * Description:     Example block written with ESNext standard and JSX support – build step required.
 * Version:         0.1.0
 * Author:          The WordPress Contributors
 * License:         GPL-3.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     wp-oer-subjects-index
 *
 * @package         create-block
 */
global $pagenow;

function wp_oer_subjects_index_block_enqueue_bootstrap(){
	wp_enqueue_style('bootstrap-style', OER_URL.'css/bootstrap.min.css');
	wp_enqueue_script( 'bootstrap-js', OER_URL.'js/bootstrap.min.js', array('jquery'));wp_enqueue_script( 'bootstrap-js', OER_URL.'js/bootstrap.min.js', array('jquery'));
}

// Add checking if current page is on add new/edit page,post or resource before loading bootstrap
if ($pagenow=="post.php" || $pagenow=="edit.php" || $pagenow=="post-new.php"){
	add_action('admin_enqueue_scripts', 'wp_oer_subjects_index_block_enqueue_bootstrap');
}
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function create_wp_oer_subjects_index_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "create-block/wp-oer-subjects-index" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'wp-oer-subjects-index-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_localize_script( 'wp-oer-subjects-index-block-editor', 'wp_oer', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_set_script_translations( 'wp-oer-subjects-index-block-editor', 'wp-oer-subjects-index' );

	$editor_css = 'build/index.css';
	wp_register_style(
		'wp-oer-subjects-index-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'wp-oer-subjects-index-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'wp-oer-plugin/wp-oer-subjects-index', array(
		'editor_script' => 'wp-oer-subjects-index-block-editor',
		'editor_style'  => 'wp-oer-subjects-index-block-editor',
		'style'         => 'wp-oer-subjects-index-block',
		'render_callback' => 'oer_display_subjects_index'
	) );
}
add_action( 'init', 'create_wp_oer_subjects_index_block_init' );


function oer_display_subjects_index( $attributes, $ajax = false ){
	$html = "";

	$shortcode = "[oer_subjects_index";
	if (!empty($attributes))
		extract($attributes);

	if (isset($size))
		$shortcode .= " size=".$size;
	if (isset($columns))
		$shortcode .= " columns=".$columns;
	if (isset($showCount)){
		if ($showCount=="true")
			$shortcode .= " show_counts='yes'";
		else
			$shortcode .= " show_counts='no'";
	}
	if (isset($showSublevels)){
		if ($showSublevels=="true")
			$shortcode .= " sublevels='yes'";
		else
			$shortcode .= " sublevels='no'";
	}

	if (!$ajax)
		$html .= "<div class='wp-block-wp-oer-plugin-wp-oer-subjects-index'>";
	$shortcode .= "]";

	$html .= do_shortcode($shortcode);
	if (!$ajax)
		$html .= "</div>";

	if ($ajax) {
		$html .= '<script type="text/javascript">
					function changeonhover(ref) {
						var img = jQuery(ref).attr("data-hoverimg")
						jQuery(ref).addClass("change_mouseover");
						jQuery(ref).children(".oer-cat-icn").css("background", "url("+img+") no-repeat scroll center center transparent");
					}

					function changeonout(ref){
						var img = jQuery(ref).attr("data-normalimg")
						jQuery(ref).removeClass("change_mouseover");
						jQuery(ref).children(".oer-cat-icn").css("background", "url("+img+") no-repeat scroll center center transparent");
					}
				</script>';
	}
	if ($ajax)
		$html = str_replace("togglenavigation(this)","admin_togglenavigation(this)",$html);
	return $html;
}

function wp_ajax_oer_display_subjects_index(){
	$shortcode = oer_display_subjects_index($_POST, true);
	echo $shortcode;
	die();
}
add_action( 'wp_ajax_display_subjects_index', 'wp_ajax_oer_display_subjects_index' );
add_action( 'wp_ajax_nopriv_display_subjects_index', 'wp_ajax_oer_display_subjects_index' );
