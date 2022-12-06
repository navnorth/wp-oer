<?php
/*
 Plugin Name:        WP OER
 Plugin URI:         https://www.wp-oer.com
 Description:        Open Educational Resource management and curation, metadata publishing, and alignment to Common Core State Standards.
 Version:            0.9.2
 Requires at least:  4.4
 Requires PHP:       7.0
 Author:             Navigation North
 Author URI:         https://www.navigationnorth.com
 Text Domain:        wp-oer
 License:            GPL3
 License URI:        https://www.gnu.org/licenses/gpl-3.0.html

 Copyright (C) 2021 Navigation North

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//defining the url,path and slug for the plugin
define( 'OER_URL', plugin_dir_url(__FILE__) );
define( 'OER_PATH', plugin_dir_path(__FILE__) );
define( 'OER_SLUG','open-educational-resource' );
define( 'OER_FILE',__FILE__);
// Plugin Name and Version
define( 'OER_PLUGIN_NAME', 'WP OER Plugin' );
define( 'OER_ADMIN_PLUGIN_NAME', 'WP OER Plugin');
define( 'OER_VERSION', '0.9.2' );
define( 'OER_SITE_PATH', ABSPATH );

include_once(OER_PATH.'includes/oer-functions.php');
include_once(OER_PATH.'includes/template-functions.php');
include_once(OER_PATH.'includes/init.php');
include_once(OER_PATH.'includes/shortcode.php');
require_once(OER_PATH.'blocks/subject-resources-block-v2/init.php');
require_once(OER_PATH.'blocks/subjects-index-block/init.php');
require_once(OER_PATH.'blocks/resource-block/init.php');
include_once(OER_PATH.'widgets/class-subject-area-widget.php');

//define global variable $debug_mode and get value from settings
global $_debug, $_bootstrap, $_fontawesome, $_css, $_css_oer, $_subjectarea, $_search_post_ids, $_w_bootstrap, $_oer_prefix, $oer_session, $_gutenberg, $_use_gutenberg;

if( ! defined( 'WP_SESSION_COOKIE' ) )
	define( 'WP_SESSION_COOKIE', '_oer_session' );

if ( ! class_exists( 'OER_Recursive_ArrayAccess' ) ) {
	require_once( OER_PATH.'/classes/class-recursive-arrayaccess.php' );
}

// Only include the functionality if it's not pre-defined.
if ( ! class_exists( 'OER_WP_Session' ) ) {
	require_once( OER_PATH.'/classes/class-wp-session.php' );
	require_once( OER_PATH.'/classes/wp-session.php' );
}

$_debug = get_option('oer_debug_mode');
$_bootstrap = get_option('oer_use_bootstrap');
$_fontawesome = get_option('oer_use_fontawesome');
$_use_gutenberg = get_option('oer_use_gutenberg');
$_css = get_option('oer_additional_css');
$_css_oer = get_option('oer_only_additional_css');
$_subjectarea = get_option('oer_display_subject_area');
$_oer_prefix = "oer_";

register_activation_hook(__FILE__, 'oer_create_csv_import_table');
function oer_create_csv_import_table()
{
	global $wpdb;
	$subprefix = "oer_";

	//Change hard-coded table prefix to $wpdb->prefix
	$table_name = $wpdb->prefix . $subprefix . "core_standards";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name)
	{
	  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			    id int(20) NOT NULL AUTO_INCREMENT,
			    standard_name varchar(255) NOT NULL,
			    standard_url varchar(255) NOT NULL,
			    PRIMARY KEY (id)
			    );";
	  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	  dbDelta($sql);
       }

	//Change hard-coded table prefix to $wpdb->prefix
	$table_name = $wpdb->prefix . $subprefix . "sub_standards";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name)
	{
	  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			    id int(20) NOT NULL AUTO_INCREMENT,
			    parent_id varchar(255) NOT NULL,
			    standard_title varchar(255) NOT NULL,
			    url varchar(255) NOT NULL,
			    PRIMARY KEY (id)
			    );";
	  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	  dbDelta($sql);
	}

	//Change hard-coded table prefix to $wpdb->prefix
	$table_name = $wpdb->prefix . $subprefix . "standard_notation";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name)
	 {
	   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			     id int(20) NOT NULL AUTO_INCREMENT,
			     parent_id varchar(255) NOT NULL,
			     standard_notation varchar(255) NOT NULL,
			     description varchar(255) NOT NULL,
			     comment varchar(255) NOT NULL,
			     url varchar(255) NOT NULL,
			     PRIMARY KEY (id)
			     );";
	   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	   dbDelta($sql);
	}

	//Change hard-coded table prefix to $wpdb->prefix
	$table_name = $wpdb->prefix . "category_page";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name)
	{
	   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			     id int(20) NOT NULL AUTO_INCREMENT,
			     blog_category_id varchar(255) NOT NULL,
			     resource_category_id varchar(255) NOT NULL,
			     page_id varchar(255) NOT NULL,
			     page_name varchar(255) NOT NULL,
			     PRIMARY KEY (id));";
	   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	   dbDelta($sql);
	}

   update_option('setup_notify', true);
   update_option( "oer_rewrite_rules", false );
   update_option('oer_metadata_firstload', true);
   update_option('oer_setup', true);

   //Trigger CPT and Taxonomy creation
   oer_postcreation();
   oer_create_resource_taxonomies();

   oer_add_rewrites();
   //Trigger permalink reset
   flush_rewrite_rules();
}

//Enqueue activation script
function oer_enqueue_activation_script() {
	if ( is_admin()) {
		// Adds our JS file to the queue that WordPress will load
		wp_enqueue_script( 'wp_ajax_oer_admin_script', OER_URL . 'js/oer-admin.js', array( 'jquery' ), null, true );

		// Make some data available to our JS file
		wp_localize_script( 'wp_ajax_oer_admin_script', 'wp_ajax_oer_admin', array(
			'wp_ajax_oer_admin_nonce' => wp_create_nonce( 'wp_ajax_oer_admin_nonce' ),
		));
	}
}

//Dismiss Activation Notice
add_action( 'wp_ajax_oer_activation_notice', 'oer_dismiss_activation_notice' );
function oer_dismiss_activation_notice() {

	update_option('setup_notify', false);

	// Send success message
	wp_send_json( array(
		'status' => 'success',
		'message' => __( 'Your request was successful.', OER_SLUG )
	) );
}

add_action( 'admin_notices', 'oer_plugin_activation_notice' );
// Plugin activation notice
function oer_plugin_activation_notice() {
	if (isset($_POST['oer_setup'])) {
		update_option('setup_notify', false);
	}
	if (get_option('setup_notify') && (get_option('setup_notify')==true)) {
		$setup_button = '<form class="inline-form" style="display:inline;text-align: right; float: right; width: 20%; margin-top: 3px;" method="post" action="'.admin_url( 'edit.php?post_type=resource&page=oer_settings&tab=setup').'"><input type="hidden" name="oer_setup" value="1" /><input type="submit" class="button-primary" value="Setup" /></form>';
		$allowed_tags = oer_allowed_html();
	?>
		<div id="oer-dismissible-notice" class="updated notice is-dismissible" style="padding-top:5px;padding-bottom:5px;overflow:hidden;">
			<p style="width:75%;float:left;">Thank you for installing the <a href="https://www.wp-oer.com/" target="_blank">WP-OER</a> plugin. If you need support, please visit our site or the forums. <?php echo wp_kses($setup_button,$allowed_tags); ?></p>
		</div>
	<?php
	}
}

register_deactivation_hook( __FILE__, "oer_deactivate_oer_plugin" );
function oer_deactivate_oer_plugin() {
	delete_option('setup_notify');
	delete_option('oer_setup');
}

//Load localization directory
add_action('plugins_loaded', 'oer_load_textdomain');
function oer_load_textdomain() {
	load_plugin_textdomain( 'open-educational-resource', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
	// Disable concatenate_scripts on admin side
	if ( is_user_logged_in() ) {
        if ( ! defined( 'CONCATENATE_SCRIPTS' ) ) {
	        define( 'CONCATENATE_SCRIPTS', false );
        }
        $GLOBALS['concatenate_scripts'] = false;
    }
}

add_action( 'wp_default_scripts', 'oer_remove_default_jquery_migrate', -1 );
function oer_remove_default_jquery_migrate( $scripts ){
   	if ( is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
		$jquery_dependencies = $scripts->registered['jquery']->deps;
		$script = $scripts->query( 'jquery-migrate', 'registered' );
		if ($script){
			$script->src  = OER_URL.'js/oer-wp-jquery-migrate-3.3.2.js';
			$script->deps = array();
			$script->ver  = '3.3.2';

			unset( $script->extra['group'] );
		}
	}
}

//Create Page Templates
include_once(OER_PATH.'oer_template/oer_template.php');

function oer_CreatePage($title,$content,$slug)
{
	$resultFlag = false;
	$args = array(
	   'sort_column' => 'post_title',
	   'post_type' => 'page',
	   'post_status' => 'publish'
	);

	$pages = get_pages($args);
	foreach ($pages as $page)
	{
		if ($page->post_title == $title)
		{
			$resultFlag = true;
			break;
		}
		else
		{
			continue;
		}
	}//first foreach ends
	if (!$resultFlag)
	{
		$post = array(
			'comment_status' => 'closed',
			'ping_status' =>  'closed' ,
			'post_author' => 1,
			'post_date' => date('Y-m-d H:i:s'),
			'post_name' => $slug,
			'post_status' => 'publish' ,
			'post_title' => $title,
			'post_type' => 'page',
			'post_content' =>$content,
	 	);
		$newvalue = wp_insert_post( $post, false );
	}
ob_clean();
}

/** Add Settings Link on Plugins page **/
add_filter( 'plugin_action_links' , 'oer_add_settings_link' , 10 , 2 );
/** Add Settings Link function **/
function oer_add_settings_link( $links, $file ){
	if ( $file == plugin_basename(dirname(__FILE__).'/open-educational-resources.php') ) {
		/** Insert settings link **/
		$link = "<a href='edit.php?post_type=resource&page=oer_settings'>".__('Settings',OER_SLUG)."</a>";
		array_unshift($links, $link);
		/** End of Insert settings link **/
	}
	return $links;
}
/** End of Add Settings Link on Plugins page **/

/* Adding Auto Update Functionality*/

/**
 * Get the Custom Template if set
 **/
function oer_get_template_hierarchy( $template ) {

	//get template file
	if ($template=="search"){
		$template = $template . '.php';
	} else {
		$template_slug = rtrim( $template , '.php' );
		$template = $template_slug . '.php';
	}

	//Check if custom template exists in theme folder
	if ($theme_file = locate_template( array( 'oer_template/' . $template ) )) {
		$file = $theme_file;
	} elseif ($theme_file = locate_template( array( $template ) )) {
		$file = $theme_file;
	} else {
		$file = OER_PATH . 'oer_template/' . $template;
	}

	return apply_filters( 'oer_repl_template' . $template , $file  );
}

/**
 * Add Filter to use plugin default template
 **/
add_filter( 'template_include' , 'oer_template_choser' );

/**
 * Function to choose template for the resource post type
 **/
function oer_template_choser( $template ) {

	//Post ID
	$post_id = get_the_ID();

	if ( get_post_type($post_id) != 'resource' ) {
		return $template;
	}

	if ( is_single($post_id) ){
		if (!get_option('wp_oese_theme_nalrc_header')){
			return oer_get_template_hierarchy('single-resource');
		} else {
			return oer_get_template_hierarchy('single-resource-nalrc');
		}
	}

}

/**
 * Add filter to use plugin default category template
 **/
add_filter( 'template_include' , 'oer_category_template' );

/**
 * Function to choose template for the resource category
 **/
function oer_category_template( $template ) {
	global $wp_query;

	//Post ID
	$_id = $wp_query->get_queried_object_id();

	// Get Current Object if it belongs to Resource Category taxonomy
	$resource_term = get_term_by( 'id' , $_id , 'resource-subject-area' );

	//Check if the loaded resource is a category
	if (is_tax() && $resource_term && !is_wp_error( $resource_term )) {
		return oer_get_template_hierarchy('resource-subject-area');
	} else {
		if ($wp_query->is_search)
			return oer_get_template_hierarchy("search");
		return $template;
	}
 }

 /**
 * Add filter to use plugin default category template
 **/
add_filter( 'template_include' , 'oer_tag_template' );

/**
 * Function to choose template for the resource category
 **/
function oer_tag_template( $template ) {
	global $wp_query;

	//Post ID
	$_id = $wp_query->get_queried_object_id();

	$resource_tag = is_tag($_id);

	//Check if the loaded resource is a category
	if ($resource_tag && !is_wp_error( $resource_tag )) {
		return oer_get_template_hierarchy('tag-resource');
	} elseif (is_post_type_archive('resource')) {
		return oer_get_template_hierarchy('archive-resource');
	} else {
		return $template;
	}
 }

 /**
 * Add filter to use plugin default archive template
 **/
add_filter( 'archive_template' , 'oer_custom_archive_template' );

/**
 * Function to choose template for the resource archive
 **/
function oer_custom_archive_template( $template ) {
	global $wp_query;

	if (is_post_type_archive('resource')) {
		$template = realpath(oer_get_template_hierarchy('archive-resource'));
	}

	return $template;
 }

function oer_get_search_posts($search_text) {
	// Search Query
	$args = array(
			'post_type' => array( 'post', 'resource' ),
			'post_status' => 'publish',
			'posts_per_page' => -1,
			's' => $search_text
	);
	$search_query = new WP_Query($args);

	return $search_query->posts;
}

function oer_get_search_meta($search_text) {
	// Meta Query
	$args = array(
		'post_type' => 'resource',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'oer_authorname',
				'value'   => $search_text,
				'compare' => 'LIKE'
			),
			array(
				'key'     => 'oer_publishername',
				'value'   => $search_text,
				'compare' => 'LIKE'
			)
		),
	);
	$meta_query = new WP_Query($args);

	return $meta_query->posts;
}

function oer_get_search_taxonomies($search_text) {
	//Tax Query
	$args = array(
		'post_type' => 'resource',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'resource-subject-area',
				'field'   => 'slug',
				'terms' => $search_text
			)
		)
	);
	$tax_query = new WP_Query($args);

	return $tax_query->posts;
}

function oer_get_search_tags($search_text){
	//Tag Query
	$args = array(
		'post_type' => 'resource',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'tag' => $search_text
	);

	$tag_query = new WP_Query($args);

	return $tag_query->posts;
}

function oer_query_post_type($query) {
   //Limit to main query, tag queries and frontend
   if($query->is_main_query() && $query->is_tag() ) {

        $query->set( 'post_type', 'resource' );

   }

}

 /** get default category icon **/
 function oer_get_default_category_icon($category_name, $hover = false) {

	$default_icon_path = "";
	$default_icon_dir = OER_URL . "images/category_icons/";
	$default_icon_ext = ".png";
	$default_icon_name = "";

	switch($category_name){

		case "Career and Technical Education":
			$default_icon_name = "cte";
			break;

		case "Educational Leadership":
			$default_icon_name = "edu-leadership";
			break;

		case "English Language Arts":
			$default_icon_name = "language";
			break;

		case "English Language Development":
			$default_icon_name = "eng-lng-dev";
			break;

		case "History/Social Studies":
			$default_icon_name = "us-hstry";
			break;

		case "Math":
			$default_icon_name = "math";
			break;

		case "Physical Education":
			$default_icon_name = "phy-edu";
			break;

		case "Science":
			$default_icon_name = "science";
			break;

		case "STEM":
			$default_icon_name = "stem";
			break;

		case "Visual and Performing Arts":
			$default_icon_name = "arts1";
			break;

		case "World Languages":
			$default_icon_name = "world-lang";
			break;

		default:
			$default_icon_path = "";
			break;
	}
	if ($default_icon_name!=""){
		$default_icon_path = $default_icon_dir . $default_icon_name .(($hover==true)?"-hover":""). $default_icon_ext;
	}
	return $default_icon_path;
 }

// Add scripts and styles to frontend
add_action('wp_enqueue_scripts', 'oer_front_scripts');
function oer_front_scripts()
{
	global $_bootstrap, $_fontawesome;

	if ($_bootstrap) {
		wp_enqueue_style('bootstrap-style', OER_URL.'css/bootstrap.min.css');
		wp_enqueue_script('bootstrap-script', OER_URL.'js/bootstrap.min.js');
	}

	if ($_fontawesome) {
		wp_enqueue_style('fontawesome-style', OER_URL.'css/fontawesome.css');
	}

}

//Initialize settings page
add_action( 'admin_init' , 'oer_settings_page' );
function oer_settings_page() {
	//Create Embed Section
	add_settings_section(
		'oer_embed_settings',
		'',
		'oer_embed_settings_callback',
		'embed_settings'
	);

	//Add Settings field for Local PDF Resources Viewer
	add_settings_field(
		'oer_local_pdf_viewer',
		'',
		'oer_setup_settings_field',
		'embed_settings',
		'oer_embed_settings',
		array(
			'uid' => 'oer_local_pdf_viewer',
			'type' => 'select',
			'class' => 'local_pdf_viewer',
			'name' =>  __('Local PDF Resources', OER_SLUG),
			'options' =>  array(
					    "0" => "embed disabled(download only)",
					    "1" => "Google Viewer",
					    "2" => "Mozilla PDFJS",
					    "3" => "Wonderplugin PDF Embed",
					    "4" => "PDF Embedder",
					    "5" => "PDF Viewer"
					    ),
			'default' => '1'
		)
	);

	//Add Settings field for External PDF Resources Viewer
	add_settings_field(
		'oer_external_pdf_viewer',
		'',
		'oer_setup_settings_field',
		'embed_settings',
		'oer_embed_settings',
		array(
			'uid' => 'oer_external_pdf_viewer',
			'type' => 'select',
			'class' => 'external_pdf_viewer',
			'name' =>  __('External PDF Resources', OER_SLUG),
			'options' => array(
					"0" => "embed disabled (download only)",
					"1" => "Google Viewer"
					),
			'default' => '1'
		)
	);

	register_setting( 'oer_general_settings' , 'oer_local_pdf_viewer' );
	register_setting( 'oer_general_settings' , 'oer_external_pdf_viewer' );

	//Create General Section
	add_settings_section(
		'oer_general_settings',
		'',
		'oer_general_settings_callback',
		'oer_settings'
	);

	//Add Settings field for Disable Screenshots
	add_settings_field(
		'oer_disable_screenshots',
		'',
		'oer_setup_radio_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_disable_screenshots',
			'type' => 'radio',
			'class' => 'screenshot_option',
			'name' =>  __('Disable screenshots', OER_SLUG),
			'value' => '0'
		)
	);

	//Add Settings field for Server Side Screenshots
	add_settings_field(
		'oer_enable_screenshot',
		'',
		'oer_setup_radio_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_enable_screenshot',
			'type' => 'radio',
			'class' => 'screenshot_option',
			'name' =>  __('Enable server-side screenshots', OER_SLUG),
			'value' => '1'
		)
	);

	//Add Settings field for Using XvFB
	add_settings_field(
		'oer_use_xvfb',
		'',
		'oer_setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_use_xvfb',
			'type' => 'checkbox',
			'indent' => true,
			'name' =>  __('Use xvfb&mdash;typically necessary on Linux installations', OER_SLUG)
		)
	);

	//Set Path for Python Installation
	add_settings_field(
		'oer_python_install',
		__("Python executable", OER_SLUG),
		'oer_setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_python_install',
			'type' => 'textbox',
			'indent' => true,
			'title' => __('Python executable', OER_SLUG)
		)
	);

	//Set Path for Python Executable Script
	add_settings_field(
		'oer_python_path',
		__("Python Screenshot script", OER_SLUG),
		'oer_setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_python_path',
			'type' => 'textbox',
			'indent' => true,
			'title' => __('Python screenshot script', OER_SLUG)
		)
	);

	//Add Settings field for Disable Screenshots
	add_settings_field(
		'oer_external_screenshots',
		'',
		'oer_setup_radio_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_external_screenshots',
			'type' => 'radio',
			'class' => 'screenshot_option',
			'name' =>  __('Use an external screenshot service', OER_SLUG),
			'value' => '2'
		)
	);

	//Set Path for Python Executable Script
	add_settings_field(
		'oer_service_url',
		__("Service URL", OER_SLUG),
		'oer_setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_service_url',
			'type' => 'textbox',
			'indent' => true,
			'title' => __("Service URL", OER_SLUG),
			'description' => __('Use $url for where the Resource URL parameter should be placed.', OER_SLUG)
		)
	);

	register_setting( 'oer_general_settings' , 'oer_disable_screenshots' );
	register_setting( 'oer_general_settings' , 'oer_enable_screenshot' );
	register_setting( 'oer_general_settings' , 'oer_use_xvfb' );
	register_setting( 'oer_general_settings' , 'oer_python_path' );
	register_setting( 'oer_general_settings' , 'oer_python_install' );
	register_setting( 'oer_general_settings' , 'oer_external_screenshots' );
	register_setting( 'oer_general_settings' , 'oer_service_url' );
}

//General settings callback
function oer_general_settings_callback() {

}

function oer_embed_settings_callback(){

}

//Initialize Style Settings Tab
add_action( 'admin_init' , 'oer_styles_settings' );
function oer_styles_settings(){

	//Create Styles Section
	add_settings_section(
		'oer_styles_settings',
		'',
		'oer_styles_settings_callback',
		'styles_settings_section'
	);

	//Add Settings field for Importing Bootstrap CSS & JS Libraries
	add_settings_field(
		'oer_use_bootstrap',
		'',
		'oer_setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_use_bootstrap',
			'type' => 'checkbox',
			'value' => '1',
			'name' =>  __('Import Bootstrap CSS & JS libraries', OER_SLUG),
			'description' => __('Uncheck if your WP theme already includes Bootstrap.', OER_SLUG)
		)
	);

	//Add Settings field for displaying Subject Area sidebar
	add_settings_field(
		'oer_display_subject_area',
		'',
		'oer_setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_display_subject_area',
			'type' => 'checkbox',
			'value' => '1',
			'default' => true,
			'name' =>  __('Display Subjects menu on Subject Area pages', OER_SLUG),
			'description' => __('Lists all subject areas in the left column of Subject Area pages—may conflict with themes using left navigation.', OER_SLUG)
		)
	);

	//Add Settings field for Importing Fontawesome CSS
	add_settings_field(
		'oer_use_fontawesome',
		'',
		'oer_setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_use_fontawesome',
			'type' => 'checkbox',
			'value' => '1',
			'name' =>  __('Import Fontawesome CSS', OER_SLUG),
			'description' => __('Uncheck if your WP theme already includes Font Awesome.', OER_SLUG)
		)
	);

	//Add Settings field for hiding Page title on Subject Area pages
	add_settings_field(
		'oer_hide_subject_area_title',
		'',
		'oer_setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_hide_subject_area_title',
			'type' => 'checkbox',
			'value' => '1',
			'name' =>  __('Subject Area pages', OER_SLUG),
			'pre_html' => __('<h3>Hide Page Titles</h3><p class="description hide-description">Some themes have a built-in display of page titles.</p>', OER_SLUG))
	);

	//Add Settings field for hiding Page title on Resource pages
	add_settings_field(
		'oer_hide_resource_title',
		'',
		'oer_setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_hide_resource_title',
			'type' => 'checkbox',
			'value' => '1',
			'name' =>  __('Resource pages', OER_SLUG),
			'class' => 'hide-title-setting'
		)
	);

	//Add Settings field for Importing Bootstrap CSS & JS Libraries
	add_settings_field(
		'oer_additional_css',
		'',
		'oer_setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_additional_css',
			'type' => 'textarea',
			'name' =>  __('Additional CSS', OER_SLUG),
			'inline_description' => __('Easily customize the look and feel with your own CSS (sitewide).', OER_SLUG)
		)
	);

	add_settings_field(
		'oer_only_additional_css',
		'',
		'oer_setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_only_additional_css',
			'type' => 'textarea',
			'name' =>  __('Additional CSS for Plugin Pages', OER_SLUG),
			'inline_description' => __('Apply custom CSS to plugin pages only.', OER_SLUG)
		)
	);

	register_setting( 'oer_styles_settings' , 'oer_use_bootstrap' );
	register_setting( 'oer_styles_settings' , 'oer_display_subject_area' );
	register_setting( 'oer_styles_settings' , 'oer_use_fontawesome' );
	register_setting( 'oer_styles_settings' , 'oer_hide_subject_area_title' );
	register_setting( 'oer_styles_settings' , 'oer_hide_resource_title' );
	register_setting( 'oer_styles_settings' , 'oer_additional_css' );
	register_setting( 'oer_styles_settings' , 'oer_only_additional_css' );
}

//Styles Setting Callback
function oer_styles_settings_callback(){

}

//Initialize Setup Settings Tab
add_action( 'admin_init' , 'oer_setup_settings' );
function oer_setup_settings(){
	global $_w_bootstrap, $_gutenberg;

	if ((isset($_REQUEST['post_type']) && $_REQUEST['post_type']=="resource") && (isset($_REQUEST['page']) && $_REQUEST['page']=="oer_settings")){
		if (oer_is_bootstrap_loaded())
			$_w_bootstrap = true;
	}

	$bootstrap_disabled = false;
	$load_bootstrap = true;

	//Create Setup Section
	add_settings_section(
		'oer_setup_settings',
		'',
		'oer_setup_settings_callback',
		'setup_settings_section'
	);

	//Add Settings field for Importing Example Set of Resources
	add_settings_field(
		'oer_import_sample_resources',
		'',
		'oer_setup_settings_field',
		'setup_settings_section',
		'oer_setup_settings',
		array(
			'uid' => 'oer_import_sample_resources',
			'type' => 'checkbox',
			'value' => '1',
			'default' => true,
			'name' =>  __('Import Example Set of Resources', OER_SLUG),
			'description' => __('A collection of over 50 Open Educational Resources has been provided as a base - you can easily remove these later.', OER_SLUG)
		)
	);

	//Add Settings field for Import Default Subject Areas
	add_settings_field(
		'oer_import_default_subject_areas',
		'',
		'oer_setup_settings_field',
		'setup_settings_section',
		'oer_setup_settings',
		array(
			'uid' => 'oer_import_default_subject_areas',
			'type' => 'checkbox',
			'value' => '1',
			'default' => true,
			'name' =>  __('Import Default Subject Areas', OER_SLUG),
			'description' => __('A general listing of the most common subject areas.', OER_SLUG)
		)
	);

	//Add Settings field for Import Default Grade Levels
	add_settings_field(
		'oer_import_default_grade_levels',
		'',
		'oer_setup_settings_field',
		'setup_settings_section',
		'oer_setup_settings',
		array(
			'uid' => 'oer_import_default_grade_levels',
			'type' => 'checkbox',
			'value' => '1',
			'default' => true,
			'name' =>  __('Import Default Grade Levels', OER_SLUG),
			'description' => __('A general listing of K-12 grade levels.', OER_SLUG)
		)
	);

	//Add Settings field for Importing Common Core State Standards
	add_settings_field(
		'oer_import_ccss',
		'',
		'oer_setup_settings_field',
		'setup_settings_section',
		'oer_setup_settings',
		array(
			'uid' => 'oer_import_ccss',
			'type' => 'checkbox',
			'value' => '1',
			'default' => true,
			'name' =>  __('Import Common Core State Standards', OER_SLUG),
			'description' => __('Enable use of CCSS as an optional alignment option for resources.', OER_SLUG)
		)
	);

	//Add Settings field for Importing Bootstrap CSS & JS Libraries
	add_settings_field(
		'oer_use_bootstrap',
		'',
		'oer_setup_settings_field',
		'setup_settings_section',
		'oer_setup_settings',
		array(
			'uid' => 'oer_use_bootstrap',
			'type' => 'checkbox',
			'value' => 1,
			'default' => $load_bootstrap,
			/*'disabled' => $bootstrap_disabled,*/
			'name' =>  __('Import Bootstrap CSS & JS libraries', OER_SLUG),
			'description' => __('Your theme does not appear to have bootstrap. Uncheck if your WP theme already included Bootstrap', OER_SLUG)
		)
	);

	if ( function_exists( 'register_block_type' ) ) {
		$_gutenberg = true;
	}

	if ($_gutenberg)
		//Add Settings field to Enabled Gutenberg editor
		add_settings_field(
			'oer_use_gutenberg',
			'',
			'oer_setup_settings_field',
			'setup_settings_section',
			'oer_setup_settings',
			array(
				'uid' => 'oer_use_gutenberg',
				'type' => 'checkbox',
				'value' => '1',
				'default' => true,
				'name' =>  __('Use Gutenberg Editor', OER_SLUG)
			)
		);

	register_setting( 'oer_setup_settings' , 'oer_import_sample_resources' );
	register_setting( 'oer_setup_settings' , 'oer_import_default_subject_areas' );
	register_setting( 'oer_setup_settings' , 'oer_import_default_grade_levels' );
	register_setting( 'oer_setup_settings' , 'oer_import_ccss' );
	register_setting( 'oer_setup_settings' , 'oer_setup_bootstrap' );
	if ($_gutenberg)
		register_setting( 'oer_setup_settings' , 'oer_use_gutenberg' );
}

//Setup Setting Callback
function oer_setup_settings_callback(){

}


//Initialize Import Academic Standards
add_action( 'admin_init' , 'oer_import_standards' );
function oer_import_standards(){
	//Create Standards Section
	add_settings_section(
		'oer_import_standards',
		'',
		'oer_import_standards_callback',
		'import_standards_section'
	);

	//Add Common Core Mathematics field
	add_settings_field(
		'oer_common_core_mathematics',
		'',
		'oer_setup_settings_field',
		'import_standards_section',
		'oer_import_standards',
		array(
			'uid' => 'oer_common_core_mathematics',
			'type' => 'checkbox',
			'name' =>  __('Common Core Mathematics', OER_SLUG)
		)
	);

	//Add Common Core English Language Arts
	add_settings_field(
		'oer_common_core_english',
		'',
		'oer_setup_settings_field',
		'import_standards_section',
		'oer_import_standards',
		array(
			'uid' => 'oer_common_core_english',
			'type' => 'checkbox',
			'name' =>  __('Common Core English Language Arts', OER_SLUG)
		)
	);

	register_setting( 'oer_import_standards' , 'oer_common_core_mathematics' );
	register_setting( 'oer_import_standards' , 'oer_common_core_english' );
}

function oer_import_standards_callback() {

}

//Initialize Reset Settings Tab
add_action( 'admin_init' , 'oer_reset_settings' );
function oer_reset_settings(){
	//Create Reset Section
	add_settings_section(
		'oer_reset_settings',
		'',
		'oer_reset_settings_callback',
		'reset_settings_section'
	);

	//Add Settings field for Deleting Standards data
	add_settings_field(
		'oer_delete_standards_data',
		'',
		'oer_setup_settings_field',
		'reset_settings_section',
		'oer_reset_settings',
		array(
			'uid' => 'oer_delete_standards_data',
			'type' => 'checkbox',
			'name' =>  __('Delete standards data', OER_SLUG)
		)
	);

	//Add Settings field for Deleting resource subject area taxonomies
	add_settings_field(
		'oer_delete_subject_areas_taxonomies',
		'',
		'oer_setup_settings_field',
		'reset_settings_section',
		'oer_reset_settings',
		array(
			'uid' => 'oer_delete_subject_areas_taxonomies',
			'type' => 'checkbox',
			'name' =>  __('Delete all resource subject area taxonomies', OER_SLUG)
		)
	);

	//Add Settings field for deleting resources
	add_settings_field(
		'oer_delete_resources',
		'',
		'oer_setup_settings_field',
		'reset_settings_section',
		'oer_reset_settings',
		array(
			'uid' => 'oer_delete_resources',
			'type' => 'checkbox',
			'name' =>  __('Delete all resources', OER_SLUG)
		)
	);

	//Add Settings field for deleting media associated with resources
	add_settings_field(
		'oer_delete_resource_media',
		'',
		'oer_setup_settings_field',
		'reset_settings_section',
		'oer_reset_settings',
		array(
			'uid' => 'oer_delete_resource_media',
			'type' => 'checkbox',
			'name' =>  __('Delete media associated with resources(screenshots)', OER_SLUG)
		)
	);

	//Add Settings field for removing all OER plugin settings
	add_settings_field(
		'oer_remove_all_settings',
		'',
		'oer_setup_settings_field',
		'reset_settings_section',
		'oer_reset_settings',
		array(
			'uid' => 'oer_remove_all_settings',
			'type' => 'checkbox',
			'name' =>  __('Remove all OER plugin settings', OER_SLUG)
		)
	);

	//Add Settings field for deactivating plugin
	add_settings_field(
		'oer_deactivate_plugin',
		'',
		'oer_setup_settings_field',
		'reset_settings_section',
		'oer_reset_settings',
		array(
			'uid' => 'oer_deactivate_plugin',
			'type' => 'checkbox',
			'name' =>  __('Deactivate OER plugin', OER_SLUG)
		)
	);

	//Add Settings field for deleting plugin files
	add_settings_field(
		'oer_delete_plugin_files',
		'',
		'oer_setup_settings_field',
		'reset_settings_section',
		'oer_reset_settings',
		array(
			'uid' => 'oer_delete_plugin_files',
			'type' => 'checkbox',
			'name' =>  __('Delete the OER plugin files', OER_SLUG)
		)
	);

	register_setting( 'oer_reset_settings' , 'oer_delete_standards_data' );
	register_setting( 'oer_reset_settings' , 'oer_delete_subject_areas_taxonomies' );
	register_setting( 'oer_reset_settings' , 'oer_delete_resources' );
	register_setting( 'oer_reset_settings' , 'oer_delete_resource_media' );
	register_setting( 'oer_reset_settings' , 'oer_remove_all_settings' );
	register_setting( 'oer_reset_settings' , 'oer_deactivate_plugin' );
	register_setting( 'oer_reset_settings' , 'oer_delete_plugin_files' );
}


function oer_reset_settings_callback() {

}

function oer_setup_settings_field( $arguments ) {
	$selected = "";
	$size = "";
	$class = "";
	$disabled = "";
	$allowed_tags = oer_allowed_html();

	$value = get_option($arguments['uid']);

	if (isset($arguments['indent'])){
		echo '<div class="indent">';
	}

	if (isset($arguments['class'])) {
		$class = $arguments['class'];
		$class = " class='".$class."' ";
	}

	if (isset($arguments['pre_html'])) {
		echo wp_kses($arguments['pre_html'],$allowed_tags);
	}

	switch($arguments['type']){
		case "textbox":
			$size = 'size="50"';
			if (isset($arguments['title']))
				$title = $arguments['title'];
			echo '<label for="'.esc_attr($arguments['uid']).'"><strong>'.esc_html($title).'</strong></label><input name="'.esc_attr($arguments['uid']).'" id="'.esc_attr($arguments['uid']).'" type="'.esc_attr($arguments['type']).'" value="' . esc_attr($value) . '" ' . esc_attr($size) . ' ' .  esc_attr($selected) . ' />';
			break;
		case "checkbox":
			$display_value = "";
			$selected = "";

			if ($value=="1" || $value=="on"){
				$selected = "checked='checked'";
				$display_value = "value='1'";
			} elseif ($value===false){
				$selected = "";
				if (isset($arguments['default'])) {
					if ($arguments['default']==true){
						$selected = "checked='checked'";
					}
				}
			} else {
				$selected = "";
			}

			if (isset($arguments['disabled'])){
				if ($arguments['disabled']==true)
					$disabled = " disabled";
			}

			echo '<input name="'.esc_attr($arguments['uid']).'" id="'.esc_attr($arguments['uid']).'" '.esc_attr($class).' type="'.esc_attr($arguments['type']).'" ' . esc_attr($display_value) . ' ' . esc_attr($size) . ' ' .  esc_attr($selected) . ' ' . esc_attr($disabled) . '  /><label for="'.esc_attr($arguments['uid']).'"><strong>'.esc_html($arguments['name']).'</strong></label>';
			break;
		case "select":
			if (isset($arguments['name']))
				$title = $arguments['name'];
			echo '<label for="'.esc_attr($arguments['uid']).'"><strong>'.esc_html($title).'</strong></label>';
			echo '<select name="'.esc_attr($arguments['uid']).'" id="'.esc_attr($arguments['uid']).'">';

			if (isset($arguments['options']))
				$options = $arguments['options'];

			foreach($options as $key=>$desc){
				$selected = "";
				if ($value===false){
					if ($key==$arguments['default'])
						$selected = " selected";
				} else {
					if ($key==$value)
						$selected = " selected";
				}
				$disabled = "";
				switch ($key){
					case 3:
						if(!shortcode_exists('wonderplugin_pdf'))
							$disabled = " disabled";
						break;
					case 4:
						if (!shortcode_exists('pdf-embedder'))
							$disabled = " disabled";
						break;
					case 5:
						if(!shortcode_exists('pdfviewer'))
							$disabled = " disabled";
						break;
					default:
						break;
				}
				echo '<option value="'.esc_attr($key).'"'.esc_attr($selected).''.esc_attr($disabled).'>'.esc_html($desc).'</option>';
			}

			echo '<select>';
			break;
		case "textarea":
			echo '<label for="'.esc_attr($arguments['uid']).'"><h3><strong>'.esc_html($arguments['name']);
			if (isset($arguments['inline_description']))
				echo '<span class="inline-desc">'.esc_html($arguments['inline_description']).'</span>';
			echo '</strong></h3></label>';
			echo '<textarea name="'.esc_attr($arguments['uid']).'" id="'.esc_attr($arguments['uid']).'" rows="10">' . esc_textarea($value) . '</textarea>';
			break;
		default:
			break;
	}

	//Show Helper Text if specified
	if (isset($arguments['helper'])) {
		printf( '<span class="helper"> %s</span>' , $arguments['helper'] );
	}

	//Show Description if specified
	if( isset($arguments['description']) ){
		printf( '<p class="description">%s</p>', $arguments['description'] );
	}

	if (isset($arguments['indent'])){
		echo '</div>';
	}
}

function oer_setup_radio_field($arguments){
	$class="";

	if (isset($arguments['class'])) {
		$class = $arguments['class'];
		$class = " class='".esc_attr($class)."' ";
	}

	$val = get_option($arguments['uid']);

	echo '<input name="'.esc_attr($arguments['uid']).'" value="'.esc_attr($arguments['value']).'" id="'.esc_attr($arguments['uid']).'" '.esc_attr($class).' type="'.esc_attr($arguments['type']).'" ' . checked($arguments['value'], $val, false) . ' /><label for="'.esc_attr($arguments['uid']).'"><strong>'.esc_html($arguments['name']).'</strong></label>';
}

/** Initialize Subject Area Sidebar widget **/
function oer_widgets_init() {

	register_sidebar( array(
		'name' => 'Subject Area Sidebar',
		'id' => 'subject_area_sidebar',
		'before_widget' => '<div id="oer-subject-area-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="rounded">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'oer_widgets_init' );

//Add body class on oer emplates for themes without default font color
add_filter( 'body_class', 'oer_add_body_class');
function oer_add_body_class($classes) {

	$cur_theme = wp_get_theme();
	$theme_class = $cur_theme->get('Name');

	return array_merge( $classes, array( str_replace( ' ', '-', strtolower($theme_class) ) ) );
}

/* Ajax Callback */
function oer_load_more_resources() {
	global $wpdb, $wp_query;
	$root_path = oer_get_root_path();

	if (isset($_POST["post_var"])) {
		$page_num = intval(sanitize_text_field($_POST["post_var"]));
		$terms = json_decode(sanitize_text_field($_POST["subjects"]));

		if (is_array($terms)){
			$terms = array_map("oer_sanitize_subject", $terms);
		} else {
			$terms = intval($terms);
		}

		$args = array(
				'post_type' => 'resource',
				'posts_per_page' => 20,
				'post_status' => 'publish',
				'paged' => $page_num,
				'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => $terms))
				);

		$args = oer_apply_sort_args($args);

		$postquery = get_posts($args);

		if(!empty($postquery)) {
			foreach($postquery as $post) {

				$w_image = true;
				//set new_image_url to empty to reset on every loop
				$new_image_url = "";

				$img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , "full" );

				if (empty($img_url)) {
					$w_image = false;
					$new_image_url = OER_URL . 'images/default-icon-220x180.png';
				}

				$title =  $post->post_title;
				$content =  $post->post_content;
				$ellipsis = "...";
				if (strlen($post->post_content)<180)
					$ellipsis = "";

				$content = substr($content, 0, 180).$ellipsis;

				$img_path = $new_img_path = parse_url($img_url[0]);
				$img_path = sanitize_url($root_path . $img_path['path']);
				if(!empty($img_url))
				{
					//Resize Image using WP_Image_Editor
					$image_editor = wp_get_image_editor($img_path);
					if ( !is_wp_error($image_editor) ) {
						$new_image = $image_editor->resize( 220, 180, true );
						$suffix = "220x180";

						//Additional info of file
						$info = pathinfo( $img_path );
						$dir = $info['dirname'];
						$ext = $info['extension'];
						$name = wp_basename( $img_path, ".$ext" );
						$dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";
						$new_port = ($new_img_path['port']==80)?'':':'.$new_img_path['port'];
						$new_image_url = str_replace($root_path, "{$new_img_path['scheme']}://{$new_img_path['host']}{$new_port}", $dest_file_name);

						if ( !file_exists($dest_file_name) ){
							$image_file = $image_editor->save($dest_file_name);
						}
					}
				}
			?>
			<div class="oer-snglrsrc">
				<?php
				echo '<a href="'.esc_url(get_permalink($post->ID)).'" class="oer-resource-link"><div class="oer-snglimglft"><img src="'.esc_url($new_image_url).'"></div></a>';
				?>
				<div class="oer-snglttldscrght <?php if(empty($img_url)){ echo 'snglttldscrghtfull';}?>">
					<div class="ttl"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($title);?></a></div>
					<div class="desc"><?php echo wp_kses_post($content); ?></div>
				</div>
			</div>
		<?php
			}
		}
		die();
	}
}
add_action('wp_ajax_load_more', 'oer_load_more_resources');
add_action('wp_ajax_nopriv_load_more', 'oer_load_more_resources');

/** Sort Resources **/
function oer_sort_resources(){
	global $wpdb, $oer_session;
	$root_path = oer_get_root_path();

	if (!isset($oer_session))
		$oer_session = OER_WP_Session::get_instance();

	if (isset($_POST["sort"])) {

		$oer_session['resource_sort'] = intval(sanitize_text_field($_POST['sort']));

		$terms = json_decode(sanitize_text_field($_POST["subjects"]));

		if (is_array($terms)){
			$terms = array_map("oer_sanitize_subject",$terms);
		} else {
			$terms = intval($terms);
		}

		$args = array(
				'post_type' => 'resource',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => $terms))
				);

		$resources = get_posts($args);
		$post_count = count($resources);

		$args = array(
				'post_type' => 'resource',
				'posts_per_page' => 20,
				'post_status' => 'publish',
				'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => $terms))
				);

		$max_stories = new WP_Query($args);
		$max_page = $max_stories->max_num_pages;

		$paged = 1;
		if ($_POST['post_var']){
			$paged = intval(sanitize_text_field($_POST['post_var']));
		}

		if ($_REQUEST['page'])
			$paged = intval($_REQUEST['page']);

		$args = array(
				'post_type' => 'resource',
				'post_status' => 'publish',
				'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => $terms))
				);

		$args = oer_apply_sort_args($args);

		if ($paged>0)
			$args['posts_per_page'] = 20 * $paged;
		else
			$args['posts_per_page'] = -1;

		$postquery = get_posts($args);

		if(!empty($postquery)) {
			foreach($postquery as $post) {

				$w_image = true;
				//set new_image_url to empty to reset on every loop
				$new_image_url = "";

				$img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , "full" );

				if (empty($img_url)) {
					$w_image = false;
					$new_image_url = OER_URL . 'images/default-icon-220x180.png';
				}

				$title =  $post->post_title;
				$content =  $post->post_content;
				$ellipsis = "...";
				if (strlen($post->post_content)<180)
					$ellipsis = "";

				$content = substr($content, 0, 180).$ellipsis;

				$img_path = $new_img_path = parse_url($img_url[0]);
				$img_path = sanitize_url($root_path . $img_path['path']);
				if(!empty($img_url))
				{
					//Resize Image using WP_Image_Editor
					$image_editor = wp_get_image_editor($img_path);
					if ( !is_wp_error($image_editor) ) {
						$new_image = $image_editor->resize( 220, 180, true );
						$suffix = "220x180";

						//Additional info of file
						$info = pathinfo( $img_path );
						$dir = $info['dirname'];
						$ext = $info['extension'];
						$name = wp_basename( $img_path, ".$ext" );
						$dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";
						$new_port = ($new_img_path['port']==80)?'':':'.$new_img_path['port'];
						$new_image_url = str_replace($root_path, "{$new_img_path['scheme']}://{$new_img_path['host']}{$new_port}", $dest_file_name);

						if ( !file_exists($dest_file_name) ){
							$image_file = $image_editor->save($dest_file_name);
						}
					}
				}
			?>
			<div class="oer-snglrsrc">
				<?php
				echo '<a href="'.esc_url(get_permalink($post->ID)).'" class="oer-resource-link"><div class="oer-snglimglft"><img src="'.esc_url($new_image_url).'"></div></a>';
				?>
				<div class="oer-snglttldscrght <?php if(empty($img_url)){ echo 'snglttldscrghtfull';}?>">
					<div class="ttl"><a href="<?php echo esc_url(get_permalink($post->ID));?>"><?php echo esc_html($title);?></a></div>
					<div class="desc"><?php echo wp_kses_post($content); ?></div>
				</div>
			</div>
		<?php
			}
		}

		die();
	}
}
add_action('wp_ajax_sort_resources', 'oer_sort_resources');
add_action('wp_ajax_nopriv_sort_resources', 'oer_sort_resources');

/* Load More Highlights Ajax Callback */
function oer_load_more_highlights() {
	global $wpdb, $wp_query;

	if (isset($_POST["post_var"])) {
		$page_num = intval(sanitize_text_field(["post_var"]));
		$items_per_load = 4;
		$term_id = intval(sanitize_text_field($_POST['term_id']));

		$args = array(
			'meta_key' => 'oer_highlight',
			'meta_value' => 1,
			'post_type'  => 'resource',
			'orderby'	 => 'rand',
			'posts_per_page' => $items_per_load*$page_num,
			'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($term_id)))
		);

		$postquery = get_posts($args);
		$style="";

		if(!empty($postquery)) {
			foreach($postquery as $post) {
				setup_postdata( $post );
				$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				$title =  $post->post_title;

				$offset = 0;
				$ellipsis = "...";
				if (strlen($post->post_content)>150) {
					$offset = strpos($post->post_content, ' ', 150);
				} else
					$ellipsis = "";

				$length = 150;

				$content =  trim(substr($post->post_content,0,$length)).$ellipsis;

				if (isset($_POST['style']))
					$style = ' style="'.esc_attr($_POST['style']).'"';
				?>
				<li<?php echo esc_attr($style); ?>>
					<div class="frtdsnglwpr">
						<?php
						if(empty($image)){
							$image = OER_URL.'images/default-icon.png';
						}
						$new_image_url = oer_resize_image( $image, 220, 180, true );
						?>
						<a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><div class="img"><img src="<?php echo esc_url($new_image_url);?>" alt="<?php echo esc_html($title);?>"></div></a>
						<div class="ttl"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($title);?></a></div>
						<div class="desc"><?php echo apply_filters('the_content',$content); ?></div>
					</div>
				</li>
			<?php
			}
		}
		die();
	}
}
add_action('wp_ajax_load_highlights', 'oer_load_more_highlights');
add_action('wp_ajax_nopriv_load_highlights', 'oer_load_more_highlights');

/* Load Highlighted Resource based on ID Ajax Callback */
function oer_load_highlight() {
	global $wpdb, $wp_query;

	if (isset($_POST["post_var"])) {
		$resource_id = intval(sanitize_text_field(["post_var"]));

		$args = array(
			'p' => $resource_id,
			'meta_key' => 'oer_highlight',
			'meta_value' => 1,
			'post_type'  => 'resource',
			'orderby'	 => 'rand',
			'posts_per_page' => -1,
			'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($term_id)))
		);

		$postquery = get_posts($args);
		$style="";

		if(!empty($postquery)) {
			foreach($postquery as $post) {
				setup_postdata( $post );
				$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				$title =  $post->post_title;

				$offset = 0;
				$ellipsis = "...";
				if (strlen($post->post_content)>150) {
					$offset = strpos($post->post_content, ' ', 150);
				} else
					$ellipsis = "";

				$length = 150;

				$content =  trim(substr($post->post_content,0,$length)).$ellipsis;

				if (isset($_POST['style']))
					$style = ' style="'.esc_attr($_POST['style']).'"';
				?>
				<div class="frtdsnglwpr">
					<?php
					if(empty($image)){
						$image = OER_URL.'images/default-icon.png';
					}
					$new_image_url = oer_resize_image( $image, 220, 180, true );
					?>
					<a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><div class="img"><img src="<?php echo esc_url($new_image_url); ?>" alt="<?php echo esc_html($title);?>"></div></a>
					<div class="ttl"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($title);?></a></div>
					<div class="desc"><?php echo apply_filters('the_content',$content); ?></div>
				</div><?php
			}
		}
		die();
	}
}
add_action('wp_ajax_load_highlight', 'oer_load_highlight');
add_action('wp_ajax_nopriv_load_highlight', 'oer_load_highlight');

add_action('wp_ajax_load_searched_standards', 'oer_load_searched_standards');
function oer_load_searched_standards(){

	$post_id = null;
	$keyword = null;
	$meta_key = "oer_standard";

	if (isset($_POST['post_id'])){
		$post_id = sanitize_text_field($_POST['post_id']);
	}
	if (isset($_POST['keyword'])){
		$keyword = sanitize_text_field($_POST['keyword']);
	}

	if (!$post_id){
		echo "Invalid Post ID";
		die();
	}

	if (!$keyword){
		was_selectable_admin_standards($post_id);
		die();
	}

	if (function_exists('was_search_standards')){
		was_search_standards($post_id,$keyword,$meta_key);
	}
	die();
}

add_action('wp_ajax_load_default_standards', 'oer_load_default_standards');
function oer_load_default_standards(){
	$post_id = null;

	if (isset($_POST['post_id'])){
		$post_id = sanitize_text_field($_POST['post_id']);
	}

	if (!$post_id){
		echo "Invalid Post ID";
		die();
	}

	if (function_exists('was_selectable_admin_standards')){
		was_selectable_admin_standards($post_id);
	}
	die();
}

/** Parse Request **/
function oer_parse_request( $obj ) {
	$taxes = get_taxonomies( array( 'show_ui' => true, '_builtin' => false ), 'objects' );
	foreach ( $taxes as $key => $tax ) {
		if ( isset( $obj->query_vars[ $tax->name ] ) and is_string( $obj->query_vars[ $tax->name ] ) ) {
			if ( false !== strpos( $obj->query_vars[ $tax->name ], '/' ) ) {
				$query_vars = explode( '/', $obj->query_vars[ $tax->name ] );
				if ( is_array( $query_vars ) ) {
					$obj->query_vars[ $tax->name ] = array_pop( $query_vars );
				}
			}
		}
	}
}
add_action( 'parse_request', 'oer_parse_request' );

/** Register Post Type Rewrite Rules **/
function oer_register_post_type_rules( $post_type, $args ) {

	if ($post_type=="resource") {
		/** @var WP_Rewrite $wp_rewrite */
		global $wp_rewrite;

		if ( $args->_builtin or ! $args->publicly_queryable ) {
			return;
		}

		if ( false === $args->rewrite ) {
			return;
		}

		$permalink = oer_get_permalink_structure( $post_type );

		if ( ! $permalink ) {
			$permalink = '/%postname%/';
		}

		$permalink = '%' . $post_type . '_slug%' . $permalink;
		$permalink = str_replace( '%postname%', '%' . $post_type . '%', $permalink );
		
		if(!empty($args->rewrite['slug']))
			add_rewrite_tag( '%' . $post_type . '_slug%', '(' . $args->rewrite['slug'] . ')', 'post_type=' . $post_type . '&slug=' );

		$taxonomies = get_taxonomies( array( 'show_ui' => true, '_builtin' => false ), 'objects' );
		foreach ( $taxonomies as $taxonomy => $objects ) :
			$wp_rewrite->add_rewrite_tag( "%$taxonomy%", '(.+?)', "$taxonomy=" );
		endforeach;

		$rewrite_args = $args->rewrite;
		if ( ! is_array( $rewrite_args ) ) {
			$rewrite_args = array( 'with_front' => $args->rewrite );
		}
		
		$slug = '';
		
		if(!empty($args->rewrite['slug']))
			$slug = $args->rewrite['slug'];

		if ( $args->has_archive ) {
			if ( is_string( $args->has_archive ) ) {
				$slug = $args->has_archive;
			};
			
			if ( !empty($args->rewrite['with_front']) ) {
				$slug = substr( $wp_rewrite->front, 1 ) . $slug;
			}

			$date_front = oer_get_date_front( $post_type );

			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/([0-9]{1,2})/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?year=$matches[1]&feed=$matches[2]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$', 'index.php?year=$matches[1]&feed=$matches[2]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/page/?([0-9]{1,})/?$', 'index.php?year=$matches[1]&paged=$matches[2]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . $date_front . '/([0-9]{4})/?$', 'index.php?year=$matches[1]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . '/author/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?author_name=$matches[1]&paged=$matches[2]&post_type=' . $post_type, 'top' );
			add_rewrite_rule( $slug . '/author/([^/]+)/?$', 'index.php?author_name=$matches[1]&post_type=' . $post_type, 'top' );

			if ( in_array( 'category', $args->taxonomies ) ) {

				$category_base = get_option( 'category_base' );
				if ( ! $category_base ) {
					$category_base = 'category';
				}

				add_rewrite_rule( $slug . '/' . $category_base . '/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?category_name=$matches[1]&paged=$matches[2]&post_type=' . $post_type, 'top' );
				add_rewrite_rule( $slug . '/' . $category_base . '/([^/]+)/?$', 'index.php?category_name=$matches[1]&post_type=' . $post_type, 'top' );

			}

			do_action( 'OER_registered_' . $post_type . '_rules', $args, $slug );
		}

		$rewrite_args['walk_dirs'] = false;
		add_permastruct( $post_type, $permalink, $rewrite_args );
	}

}
add_action( 'registered_post_type', 'oer_register_post_type_rules', 10, 2 );

/** Post Type Permalink **/
function oer_post_type_link( $post_link, $post, $leavename ) {
	global $wp_rewrite;

	if ( ! $wp_rewrite->permalink_structure ) {
		return $post_link;
	}

	$draft_or_pending = isset( $post->post_status ) && in_array( $post->post_status, array(
			'draft',
			'pending',
			'auto-draft',
	) );
	if ( $draft_or_pending and ! $leavename ) {
		return $post_link;
	}

	$post_type = $post->post_type;
	$pt_object = get_post_type_object( $post_type );

	if ( false === $pt_object->rewrite ) {
		return $post_link;
	}

	$permalink = $wp_rewrite->get_extra_permastruct( $post_type );

	$permalink = str_replace( '%post_id%', $post->ID, $permalink );
	$permalink = str_replace( '%' . $post_type . '_slug%', $pt_object->rewrite['slug'], $permalink );

	// has parent.
	$parentsDirs = '';
	if ( $pt_object->hierarchical ) {
		if ( ! $leavename ) {
			$postId = $post->ID;
			while ( $parent = get_post( $postId )->post_parent ) {
				$parentsDirs = get_post( $parent )->post_name . '/' . $parentsDirs;
				$postId      = $parent;
			}
		}
	}

	$permalink = str_replace( '%' . $post_type . '%', $parentsDirs . '%' . $post_type . '%', $permalink );

	if ( ! $leavename ) {
		$permalink = str_replace( '%' . $post_type . '%', $post->post_name, $permalink );
	}

	// %post_id%/attachment/%attachement_name%;
	if ( isset( $_GET['post'] ) && sanitize_text_field($_GET['post']) != $post->ID ) {
		$parent_structure = trim( oer_get_permalink_structure( $post->post_type ), '/' );
		$parent_dirs      = explode( '/', $parent_structure );
		if ( is_array( $parent_dirs ) ) {
			$last_dir = array_pop( $parent_dirs );
		} else {
			$last_dir = $parent_dirs;
		}

		if ( '%post_id%' == $parent_structure or '%post_id%' == $last_dir ) {
			$permalink = $permalink . '/attachment/';
		}
	}

	$search  = array();
	$replace = array();

	$replace_tag = oer_create_taxonomy_replace_tag( $post->ID, $permalink );
	$search      = $search + $replace_tag['search'];
	$replace     = $replace + $replace_tag['replace'];

	// from get_permalink.
	$category = '';
	if ( false !== strpos( $permalink, '%category%' ) ) {
		$categories = get_the_category( $post->ID );
		if ( $categories ) {
			$categories = oer_sort_terms( $categories );

			$category_object = apply_filters( 'post_link_category', $categories[0], $categories, $post );
			$category_object = get_term( $category_object, 'category' );
			$category        = $category_object->slug;
			if ( $parent = $category_object->parent ) {
				$category = get_category_parents( $parent, false, '/', true ) . $category;
			}
		}
		// show default category in permalinks, without
		// having to assign it explicitly
		if ( empty( $category ) ) {
			$default_category = get_term( get_option( 'default_category' ), 'category' );
			$category         = is_wp_error( $default_category ) ? '' : $default_category->slug;
		}
	}

	$author = '';
	if ( false !== strpos( $permalink, '%author%' ) ) {
		$authordata = get_userdata( $post->post_author );
		$author     = $authordata->user_nicename;
	}

	$post_date = strtotime( $post->post_date );
	$permalink = str_replace(
		array(
			'%year%',
			'%monthnum%',
			'%day%',
			'%hour%',
			'%minute%',
			'%second%',
			'%category%',
			'%author%',
		),
		array(
			date( 'Y', $post_date ),
			date( 'm', $post_date ),
			date( 'd', $post_date ),
			date( 'H', $post_date ),
			date( 'i', $post_date ),
			date( 's', $post_date ),
			$category,
			$author,
		),
		$permalink
	);
	$permalink = str_replace( $search, $replace, $permalink );
	$permalink = home_url( $permalink );
	return $permalink;
}
add_filter( 'post_type_link', 'oer_post_type_link', 10, 3 );

/** Register Taxonomy Rules **/
function oer_register_taxonomy_rules( $taxonomy, $object_type, $args ) {
	global $wp_rewrite;

	/* for 4.7 */
	$args = (array) $args;

	if ( ! empty( $args['_builtin'] ) ) {
		return;
	}

	if ( false === $args['rewrite'] ) {
		return;
	}

	$post_types = $args['object_type'];
	foreach ( $post_types as $post_type ) :
		$post_type_obj = get_post_type_object( $post_type );
		if ( ! empty( $post_type_obj->rewrite['slug'] ) ) {
			$slug = $post_type_obj->rewrite['slug'];
		} else {
			$slug = $post_type;
		}

		if ( ! empty( $post_type_obj->has_archive ) && is_string( $post_type_obj->has_archive ) ) {
			$slug = $post_type_obj->has_archive;
		};

		if ( ! empty( $post_type_obj->rewrite['with_front'] ) ) {
			$slug = substr( $wp_rewrite->front, 1 ) . $slug;
		}

		if ( 'category' == $taxonomy ) {
			$taxonomy_slug = ( $cb = get_option( 'category_base' ) ) ? $cb : $taxonomy;
			$taxonomy_key  = 'category_name';
		} else {
			// Edit by [Xiphe]
			if ( isset( $args['rewrite']['slug'] ) ) {
				$taxonomy_slug = $args['rewrite']['slug'];
			} else {
				$taxonomy_slug = $taxonomy;
			}
			// [Xiphe] stop
			$taxonomy_key = $taxonomy;
		}

		$rules = array(
			// feed.
			array(
				'regex'    => '%s/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]&feed=\$matches[2]",
			),
			array(
				'regex'    => '%s/(.+?)/(feed|rdf|rss|rss2|atom)/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]&feed=\$matches[2]",
			),
			// year
			array(
				'regex'    => '%s/(.+?)/date/([0-9]{4})/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]&year=\$matches[2]",
			),
			array(
				'regex'    => '%s/(.+?)/date/([0-9]{4})/page/?([0-9]{1,})/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]&year=\$matches[2]&paged=\$matches[3]",
			),
			// monthnum
			array(
				'regex'    => '%s/(.+?)/date/([0-9]{4})/([0-9]{1,2})/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]&year=\$matches[2]&monthnum=\$matches[3]",
			),
			array(
				'regex'    => '%s/(.+?)/date/([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]&year=\$matches[2]&monthnum=\$matches[3]&paged=\$matches[4]",
			),
			// day
			array(
				'regex'    => '%s/(.+?)/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]&year=\$matches[2]&monthnum=\$matches[3]&day=\$matches[4]",
			),
			array(
				'regex'    => '%s/(.+?)/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]&year=\$matches[2]&monthnum=\$matches[3]&day=\$matches[4]&paged=\$matches[5]",
			),
			// paging
			array(
				'regex'    => '%s/(.+?)/page/?([0-9]{1,})/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]&paged=\$matches[2]",
			),
			// tax archive.
			array(
				'regex'    => '%s/(.+?)/?$',
				'redirect' => "index.php?{$taxonomy_key}=\$matches[1]",
			),
		);

		// no post_type slug.
		foreach ( $rules as $rule ) {
			$regex    = sprintf( $rule['regex'], "{$taxonomy_slug}" );
			$redirect = $rule['redirect'];
			add_rewrite_rule( $regex, $redirect, 'top' );
		}

		if ( get_option( 'add_post_type_for_tax' ) ) {
			foreach ( $rules as $rule ) {
				$regex    = sprintf( $rule['regex'], "{$slug}/{$taxonomy_slug}" );
				$redirect = $rule['redirect'] . "&post_type={$post_type}";
				add_rewrite_rule( $regex, $redirect, 'top' );
			}
		} else {
			foreach ( $rules as $rule ) {
				$regex    = sprintf( $rule['regex'], "{$slug}/{$taxonomy_slug}" );
				$redirect = $rule['redirect'];
				add_rewrite_rule( $regex, $redirect, 'top' );
			}
		}

		do_action( 'OER_registered_' . $taxonomy . '_rules', $object_type, $args, $taxonomy_slug );

	endforeach;
}
add_action( 'registered_taxonomy', 'oer_register_taxonomy_rules' , 10, 3 );

/** Deactivate plugin **/
function oer_deactivate_plugin(){
	delete_option('setup_notify');
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/** Delete plugin **/
function oer_delete_plugin_files(){
	if (!is_plugin_active(plugin_basename( __FILE__ )))
		delete_plugins( array( plugin_basename( __FILE__ ) ) );
}

/** Extend Search **/
 /* Filter to modify search query */
add_filter( 'posts_search', 'oer_custom_query', 500, 2 );
function oer_custom_query($search, $wp_query){
	global $wpdb;

	if ( empty( $search ) || !empty($wp_query->query_vars['suppress_filters']) ) {
            return $search; // skip processing - If no search term in query or suppress_filters is true
        }

	$q = $wp_query->query_vars;
	$n = !empty($q['exact']) ? '' : '%';
	$search = $searchand = '';
	$terms_relation_type = 'OR';

	//Checks each term
	foreach ((array)$q['search_terms'] as $term ) {

		$term = $n . $wpdb->esc_like( $term ) . $n;

		$OR = '';

		$search .= "{$searchand} (";

		//Search in title
		$search .= $wpdb->prepare("($wpdb->posts.post_title LIKE %s)", $term);
                $OR = ' OR ';

		//Search in content
		$search .= $OR;
                $search .= $wpdb->prepare("($wpdb->posts.post_content LIKE %s)", $term);
                $OR = ' OR ';

		//Search by meta keys
		$meta_keys = array(
				   'oer_authoremail',
				   'oer_authorname',
				   'oer_authortype',
				   'oer_authorurl',
				   'oer_datecreated',
				   'oer_datemodified',
				   'oer_grade',
				   'oer_highlight',
				   'oer_interactivity',
				   'oer_isbasedonurl',
				   'oer_lrtype',
				   'oer_mediatype',
				   'oer_publisheremail',
				   'oer_publishername',
				   'oer_publisherurl',
				   'oer_resourceurl',
				   'oer_standard',
				   'oer_standard_alignment',
				   'oer_userightsurl'
				   );

		$meta_key_OR = '';
		foreach ($meta_keys as $key_slug) {
                        $search .= $OR;
                        $search .= $wpdb->prepare("$meta_key_OR (pm.meta_key = %s AND pm.meta_value LIKE %s)", $key_slug, $term);
                        $OR = '';
                        $meta_key_OR = ' OR ';
                }

		$OR = ' OR ';

		//Search By Taxonomy
		$taxonomies = array("post_tag","resource-subject-area");
		$tax_OR = '';
		foreach($taxonomies as $tax) {
			$search .= $OR;
                        $search .= $wpdb->prepare("$tax_OR (tt.taxonomy = %s AND t.name LIKE %s)", $tax, $term);
                        $OR = '';
                        $tax_OR = ' OR ';
		}

		$search .= ")";

		$searchand = " $terms_relation_type ";
	}

	if ( ! empty( $search ) ) {
		$search = " AND ({$search}) ";
	}

	add_filter('posts_join_request', 'oer_join_table');

	/* Request distinct results */
	add_filter('posts_distinct_request', 'oer_distinct');

	return $search;
}

/** Join Table for Custom Search **/
function oer_join_table($join){
	global $wpdb;

	// Meta keys join
	$join .= " LEFT JOIN $wpdb->postmeta pm ON ($wpdb->posts.ID = pm.post_id) ";

	// Taxomomies join
	$join .= " LEFT JOIN $wpdb->term_relationships tr ON ($wpdb->posts.ID = tr.object_id) ";
        $join .= " LEFT JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) ";
        $join .= " LEFT JOIN $wpdb->terms t ON (tt.term_id = t.term_id) ";

	return $join;
}

/** Get Distinct Result **/
function oer_distinct($distinct){
	$distinct = 'DISTINCT';
        return $distinct;
}

/** Include Post Type Tag **/
function oer_resource_taxonomy_queries( $query ) {
    if ( $query->is_tag() && $query->is_main_query() ) {
            $query->set( 'post_type', array( 'post', 'resource' ) );
    }
}
add_action( 'pre_get_posts', 'oer_resource_taxonomy_queries' );

function oer_custom_search_template($template){
    global $wp_query;
    if (!$wp_query->is_search)
        return $template;

	$current_theme = wp_get_theme();

	if ($current_theme=="Avada")
		return OER_PATH . 'oer_template/avada-search.php';
	else
		return OER_PATH . 'oer_template/search.php';
}
add_filter('template_include','oer_custom_search_template');

function oer_assign_standard_template($template) {
	global $wp_query;

	$url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');

	status_header(200);
	//if ( $url_path === 'openk12xchange/resource/standards' ){
//	if ( $url_path === 'resource/standards' ) {
	if ( strpos( $url_path,'resource/standards' ) !== false && !get_query_var('standard') && !get_query_var('substandard') && !get_query_var('notation') ) {
		// load the file if exists
		$wp_query->is_404 = false;
		$template = locate_template('oer_template/standards.php', true);
		if (!$template) {
			$template = dirname(__FILE__) . '/oer_template/standards.php';
		}
	} elseif (get_query_var('standard') && !get_query_var('substandard') && !get_query_var('notation')){
		$wp_query->is_404 = false;
		$template = locate_template('oer_template/template-standard.php', true);
		if (!$template) {
			$template = dirname(__FILE__) . '/oer_template/template-standard.php';
		}
	} elseif (get_query_var('standard') && get_query_var('substandard') && !get_query_var('notation')){
		$wp_query->is_404 = false;
		$template = locate_template('oer_template/template-substandard.php', true);
		if (!$template) {
			$template = dirname(__FILE__) . '/oer_template/template-substandard.php';
		}
	} elseif (get_query_var('standard') && get_query_var('substandard') && get_query_var('notation')){
		$wp_query->is_404 = false;
		$template = locate_template('oer_template/template-notation.php', true);
		if (!$template) {
			$template = dirname(__FILE__) . '/oer_template/template-notation.php';
		}
	}
	return $template;
}
add_action( 'template_include' , 'oer_assign_standard_template' );

// Assign template
function oer_template_redirect(){
	global $wp, $wp_query;

	$template = $wp->query_vars;

	if ( array_key_exists( 'resource', $template ) && 'standards' == $template['name'] ) {
		$wp_query->is_404 = false;
		status_header(200);
		include( dirname(__FILE__) . '/oer_template/standards.php' );
		exit;
	}
}
//add_action( 'template_redirect' , 'oer_template_redirect' );

// Add rewrite rule for substandards
function oer_add_rewrites()
{
	global $wp_rewrite;
	add_rewrite_tag( '%standard%', '([^/]*)' );
	add_rewrite_tag( '%substandard%' , '([^&]+)' );
	add_rewrite_tag( '%notation%' , '([^&]+)' );
	add_rewrite_rule( '^resource/standards/([^/]*)/?$', 'index.php?pagename=standards&standard=$matches[1]', 'top' );
	add_rewrite_rule( '^resource/standards/([^/]*)/([^/]*)/?$', 'index.php?pagename=standards&standard=$matches[1]&substandard=$matches[2]', 'top' );
	add_rewrite_rule( '^resource/standards/([^/]*)/([^/]*)/([^/]*)/?$', 'index.php?pagename=standards&standard=$matches[1]&substandard=$matches[2]&notation=$matches[3]', 'top' );
	add_rewrite_endpoint( 'standard', EP_PERMALINK | EP_PAGES );
	add_rewrite_endpoint( 'substandard', EP_PERMALINK | EP_PAGES );
	add_rewrite_endpoint( 'notation', EP_PERMALINK | EP_PAGES );

	$flush_rewrite = get_option('oer_rewrite_rules');
	if ($flush_rewrite==false) {
		$wp_rewrite->init();
		$wp_rewrite->flush_rules();
		update_option('oer_rewrite_rules', true);
	}
}
add_action( 'init', 'oer_add_rewrites', 10, 0 );

function oer_add_query_vars( $vars ){
	$vars[] = "standard";
	$vars[] = "substandard";
	$vars[] = "notation";
	return $vars;
}
add_filter( 'query_vars', 'oer_add_query_vars' );

// Quick fix for the headers already sent error after submitting setup tab on the settings page
add_action( 'init', function () {
	if (isset($_REQUEST['tab']) && $_REQUEST['tab']=="setup") {
		ob_start();
	}
} );

/* Enqueue script and css for Gutenberg Resource block */
/**--function oer_enqueue_resource_block(){
	wp_enqueue_script(
		'resource-block-js',
		OER_URL . "/js/oer_resource_block.build.js",
		array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor', 'wp-api')
	);
	wp_enqueue_style(
		'resource-block-css',
		OER_URL . "/css/oer_resource_block.css",
		array('wp-edit-blocks')
	);

	register_block_type('wp-oer-plugin/oer-resource-block', array(
		'editor_script' => 'resource-block-js',
		'editor_style' => 'resource-block-css'
	));
}--**/
//add_action('enqueue_block_editor_assets', 'oer_enqueue_resource_block');

function oer_add_resources_rest_args() {
    global $wp_post_types, $wp_taxonomies;

    $wp_post_types['resource']->show_in_rest = true;
    $wp_post_types['resource']->rest_base = 'resource';
    $wp_post_types['resource']->rest_controller_class = 'WP_REST_Posts_Controller';

    $wp_taxonomies['resource-subject-area']->show_in_rest = true;
    $wp_taxonomies['resource-subject-area']->rest_base = 'resource-subject-area';
    $wp_taxonomies['resource-subject-area']->rest_controller_class = 'WP_REST_Terms_Controller';
}
add_action( 'init', 'oer_add_resources_rest_args', 30 );

function oer_add_meta_to_api() {
	// Register Grade Levels to REST API
	register_rest_field( 'resource',
			    'oer_grade',
			    array(
				'get_callback' => 'oer_rest_get_meta_field',
				'update_callback' => null,
				'schema' => null
				  ) );
	// Register Resource URL to REST API
	register_rest_field( 'resource',
			    'oer_resourceurl',
			    array(
				'get_callback' => 'oer_rest_get_meta_field',
				'update_callback' => null,
				'schema' => null
				  ) );
	// Register Featured Image to REST API
	register_rest_field( 'resource',
			'fimg_url',
			array(
			    'get_callback'    => 'oer_get_rest_featured_image',
			    'update_callback' => null,
			    'schema'          => null,
			) );

	// Register Domain to REST API
	register_rest_field( 'resource',
			'domain',
			array(
			    'get_callback'    => 'oer_get_rest_domain',
			    'update_callback' => null,
			    'schema'          => null,
			) );

	// Register Subject Area Details to REST API
	register_rest_field( 'resource',
			'subject_details',
			array(
			    'get_callback'    => 'oer_get_subject_details',
			    'update_callback' => null,
			    'schema'          => null,
			) );
	// Register Excerpt to REST API
	register_rest_field( 'resource',
			'resource_excerpt',
			array(
			    'get_callback'    => 'oer_get_rest_resource_excerpt',
			    'update_callback' => null,
			    'schema'          => null,
			) );
	// Register End Point
	register_rest_route( 'oer/v2',
						'subjects',
						array(
							'methods' => 'GET',
							'callback' => 'wp_oer_get_subject_areas',
							'permission_callback' => function(){
								return current_user_can('edit_posts');
							}
						)
	);

}
add_action( 'rest_api_init', 'oer_add_meta_to_api');

function oer_rest_get_meta_field($resource, $field, $request){
	if ($field=="oer_grade") {
		$grades = trim(get_post_meta($resource['id'], $field, true),",");
		$grades = explode(",",$grades);

		return oer_grade_levels($grades);
	} else
		return get_post_meta($resource['id'], $field, true);
}

function oer_get_rest_featured_image($resource, $field, $request) {
	$new_image_url="";
	if( $resource['featured_media'] ){
		$img = wp_get_attachment_image_src( $resource['featured_media'], 'app-thumb' );
		$new_image_url = oer_resize_image( $img[0], 220, 180, true );
	} else {
		$img = OER_URL.'images/default-icon.png';
		$new_image_url = oer_resize_image( $img, 220, 180, true );
	}
	return $new_image_url;
}

function oer_get_rest_domain($resource, $field, $request) {
	$url = get_post_meta($resource['id'], "oer_resourceurl", true);
	$url_domain = oer_getDomainFromUrl($url);
	if (oer_isExternalUrl($url)) {
		return  $url_domain;
	}
	return null;
}

function oer_get_subject_details($resource, $field, $request){
	$subject_details = null;
	$rsubjects = $resource['resource-subject-area'];
	foreach($rsubjects as $rsubject) {
		$subject_name = get_term($rsubject);
		$subject_details[] = array("id" => $rsubject, "link" => get_term_link($rsubject), "name" => $subject_name->name);
	}
	return $subject_details;
}

function oer_get_rest_resource_excerpt($resource, $field, $request) {
	return wp_kses_post( wp_trim_words($resource['content']['raw'], 45) );
}

function oer_get_root_path() {
	$site_path = OER_SITE_PATH;
	$spath = explode("/",OER_SITE_PATH);
	$site_dir = ($spath[count($spath)-1]=="")?$spath[count($spath)-2]:$spath[count($spath)];
	$site_dir_path = "/".$site_dir."/";
	$image_path = "";

	$root_path = $site_path;

	// get root path
	$rpos = strrpos($site_path,"/".$site_dir);
	if ($rpos!==false)
		$root_path = substr_replace($site_path, "", $rpos, strlen("/".$site_dir));

	$rpos = strrpos($root_path,"/");
	if ($rpos==strlen($root_path)-1){
		$root_path = substr_replace($root_path, "", $rpos, strlen("/"));
	}

	return $root_path;
}