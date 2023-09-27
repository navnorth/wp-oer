<?php
/**
 * OER Subjects Index Block
 **/
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
	//$admin_ajax_url = oer_is_subjects_index_ajax_url_accessible(admin_url('admin-ajax.php'))?admin_url('admin-ajax.php'):OER_URL.'ajax.php';
	$admin_ajax_url = OER_URL.'ajax.php';
	wp_localize_script( 'wp-oer-subjects-index-block-editor', 'wp_oer', array( 'ajaxurl' => $admin_ajax_url ) );
	wp_set_script_translations( 'wp-oer-subjects-index-block-editor', 'wp-oer-subjects-index', OER_PATH . '/lang/js');

	register_block_type(
        __DIR__,
        array(
            'editor_script' => 'wp-oer-subjects-index-block-editor',
            'render_callback' => 'oer_display_subjects_index',
        )
    );
}
add_action( 'init', 'create_wp_oer_subjects_index_block_init' );

/** Checks if AJAX url is accessible **/
function oer_is_subjects_index_ajax_url_accessible($url){
	$headers = @get_headers($url);
	if($headers && strpos( $headers[0], '200')) {
    	return true;
	} else {
    	return false;
	}
}

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

					/** Hide Subjects Index Block Child Category Div on load **/
					jQuery(".wp-block-wp-oer-plugin-wp-oer-subjects-index .oer_snglctwpr").each(function(index, element) {
						var childCat = jQuery(this).find(".oer-cat-div,.oer-cat-div-large,.oer-cat-div-medium,.oer-cat-div-small").children(".oer-child-category");
						var hght = childCat.height();
						childCat.attr("data-height", hght);
						childCat.hide();
				    });
				</script>';
	}
	if ($ajax)
		$html = str_replace("togglenavigation(this)","admin_togglenavigation(this)",$html);
	return $html;
}

function wp_ajax_oer_display_subjects_index(){
	$allowed_tags = oer_allowed_html();
	
	// Sanitize POST parameters
	$params = array();
	$params['action'] = sanitize_text_field($_POST['action']);
	$params['size'] = sanitize_text_field($_POST['size']);
	$params['columns'] = sanitize_text_field($_POST['columns']);
	$params['showCount'] = sanitize_text_field($_POST['showCount']);
	$params['showSublevels'] = sanitize_text_field($_POST['showSublevels']);

	$shortcode = oer_display_subjects_index($params, true);
	echo wp_kses($shortcode,$allowed_tags);
	die();
}
add_action( 'wp_ajax_display_subjects_index', 'wp_ajax_oer_display_subjects_index' );
add_action( 'wp_ajax_nopriv_display_subjects_index', 'wp_ajax_oer_display_subjects_index' );
