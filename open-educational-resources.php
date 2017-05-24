<?php
/*
 Plugin Name:  OER Management
 Plugin URI:   https://www.wp-oer.com
 Description:  Open Educational Resource management and curation, metadata publishing, and alignment to Common Core State Standards.
 Version:      0.3.0
 Author:       Navigation North
 Author URI:   http://www.navigationnorth.com
 Text Domain:  wp-oer
 License:      GPL3
 License URI:  https://www.gnu.org/licenses/gpl-3.0.html

 Copyright (C) 2017 Navigation North

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

//defining the url,path and slug for the plugin
define( 'OER_URL', plugin_dir_url(__FILE__) );
define( 'OER_PATH', plugin_dir_path(__FILE__) );
define( 'OER_SLUG','open-educational-resource' );
define( 'OER_FILE',__FILE__);
// Plugin Name and Version
define( 'OER_PLUGIN_NAME', 'WordPress OER Management Plugin' );
define( 'OER_VERSION', '0.3.0' );

include_once(OER_PATH.'includes/oer-functions.php');
include_once(OER_PATH.'includes/init.php');
include_once(OER_PATH.'includes/shortcode.php');
include_once(OER_PATH.'widgets/class-subject-area-widget.php');

//define global variable $debug_mode and get value from settings
global $_debug, $_bootstrap, $_css, $_subjectarea;
$_debug = get_option('oer_debug_mode');
$_bootstrap = get_option('oer_use_bootstrap');
$_css = get_option('oer_additional_css');
$_subjectarea = get_option('oer_display_subject_area');

register_activation_hook(__FILE__, 'create_csv_import_table');
function create_csv_import_table()
{
	global $wpdb;
	//Change hard-coded table prefix to $wpdb->prefix
	$table_name = $wpdb->prefix . "core_standards";
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
	$table_name = $wpdb->prefix . "sub_standards";
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
	$table_name = $wpdb->prefix . "standard_notation";
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

   //CreatePage('Resource Form','[oer_resource_form]','resource_form'); // creating page
   //create_template();
   update_option('setup_notify', true);
   //enqueue_activation_script();
   
   //Trigger CPT and Taxonomy creation
   oer_postcreation();
   create_resource_taxonomies();
   
   //Trigger permalink reset
   flush_rewrite_rules();
}

//Enqueue activation script
function enqueue_activation_script() {
	if ( is_admin()) {

		// Adds our JS file to the queue that WordPress will load
		wp_enqueue_script( 'wp_ajax_oer_admin_script', OER_URL . 'js/oer-admin.js', array( 'jquery' ), null, true );

		// Make some data available to our JS file
		wp_localize_script( 'wp_ajax_oer_admin_script', 'wp_ajax_oer_admin', array(
			'wp_ajax_oer_admin_nonce' => wp_create_nonce( 'wp_ajax_oer_admin_nonce' ),
		));
	}
}

add_action( 'wp_ajax_oer_activation_notice', 'dismiss_activation_notice' );
function dismiss_activation_notice() {

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
	?>
		<div id="oer-dismissible-notice" class="updated notice is-dismissible" style="padding-top:5px;padding-bottom:5px;overflow:hidden;">
			<p style="width:75%;float:left;">Thank you for installing the <a href="https://www.wp-oer.com/" target="_blank">WP-OER</a> plugin. If you need support, please visit our site or the forums. <?php echo $setup_button; ?></p>
		</div>
	<?php
	}
}

register_deactivation_hook( __FILE__, "deactivate_oer_plugin" );
function deactivate_oer_plugin() {
	delete_option('setup_notify');
}

//Load localization directory
add_action('plugins_loaded', 'load_oer_textdomain');
function load_oer_textdomain() {
	load_plugin_textdomain( 'open-educational-resource', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

//Create Page Templates
include_once(OER_PATH.'oer_template/oer_template.php');

function CreatePage($title,$content,$slug)
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
add_filter( 'plugin_action_links' , 'add_oer_settings_link' , 10 , 2 );
/** Add Settings Link function **/
function add_oer_settings_link( $links, $file ){
	if ( $file == plugin_basename(dirname(__FILE__).'/open-educational-resources.php') ) {
		/** Insert settings link **/
		$link = "<a href='edit.php?post_type=resource&page=oer_settings'>".__('Settings','oer')."</a>";
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
	$template_slug = rtrim( $template , '.php' );
	$template = $template_slug . '.php';

	//Check if custom template exists in theme folder
	if ($theme_file = locate_template( array( 'oer_template/' . $template ) )) {
		$file = $theme_file;
	} else {
		$file = OER_PATH . '/oer_template/' . $template;
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
		return oer_get_template_hierarchy('single-resource');
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
	if ($resource_term && !is_wp_error( $resource_term )) {
		return oer_get_template_hierarchy('resource-subject-area');
	} else {
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
	} else {
		return $template;
	}
 }

add_action( 'pre_get_posts', 'oer_cpt_tags' );
function oer_cpt_tags( $query ) {

	if ( $query->is_tag() && $query->is_main_query() ) {
	    $query->set( 'post_type', array( 'post', 'resource' ) );
	}
}

function query_post_type($query) {
   //Limit to main query, tag queries and frontend
   if($query->is_main_query() && $query->is_tag() ) {

        $query->set( 'post_type', 'resource' );

   }

}

 /**
  * Load Resource Categories on home page
  **/
 /*add_filter( 'the_content', 'load_front_page_resources' );
 function load_front_page_resources( $content = ""  ) {
	global $wpdb;
	$args = array(
		'type'                     => 'post',
		'parent'                   => 0,
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 0,
		'hierarchical'             => 0,
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'resource-category',
		'pad_counts'               => false );

	$categories = get_categories( $args );

	$home_content =  '<div class="oer-cntnr"><div class="oer-ctgry-cntnr row">';
			$cnt = 1;
			$lepcnt = 1;

			foreach($categories as $category)
			{
				$getimage = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.'postmeta'." WHERE meta_key='category_image' AND meta_value='$category->term_id'");
				$getimage_hover = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.'postmeta'." WHERE meta_key='category_image_hover' AND meta_value='$category->term_id'");
				$icn_guid = "";
				$icn_hover_guid = "";

				if(empty($getimage) && empty($getimage_hover)){

					$attach_icn = array();
					$attach_icn_hover = array();
					$icn_guid = get_default_category_icon($category->name);
					$icn_hover_guid = get_default_category_icon($category->name, true);

				} else {
					//Checks if icon is empty
					if (!empty($getimage)) {
						$attach_icn = get_post($getimage[0]->post_id);
						$icn_guid = $attach_icn->guid;
					} else {
						$icn_guid = get_default_category_icon($category->name);
					}

					if (!empty($getimage_hover)) {
						$attach_icn_hover = get_post($getimage_hover[0]->post_id);
						$icn_hover_guid = $attach_icn_hover->guid;
					} else {
						$icn_hover_guid = get_default_category_icon($category->name, true);
					}
				}

				$count = get_oer_post_count($category->term_id, "resource-category");
				$count = $count + $category->count;

				$home_content .= '<div class="oer_snglctwpr col-md-3"><div class="oer-cat-div" data-ownback="'.get_template_directory_uri().'/img/top-arrow.png" onMouseOver="changeonhover(this)" onMouseOut="changeonout(this);" onclick="togglenavigation(this);" data-id="'.$cnt.'" data-class="'.$lepcnt.'" data-normalimg="'.$icn_guid.'" data-hoverimg="'.$icn_hover_guid.'">
					<div class="oer-cat-icn" style="background: url('.$icn_guid.') no-repeat scroll center center; "></div>
					<div class="oer-cat-txt-btm-cntnr">
						<ul>
							<li><label class="oer-mne-sbjct-ttl" ><a href="'. site_url() .'/resource-category/'. $category->slug .'">'. $category->name .'</a></label><span>'. $count .'</span></li>
						</ul>
					</div>';

					$children = get_term_children($category->term_id, 'resource-category');
					if( !empty( $children ) )
					{
						$home_content .= '<div class="oer-child-category">'. oer_front_child_category($category->term_id) .'</div>';
					}
				$home_content .= '</div>';
				//if(($cnt % 4) == 0){
					$home_content .= '<div class="oer_child_content_wpr" data-id="'.$lepcnt.'"></div>';
					$lepcnt++;
				//}
			$cnt++;
			$home_content .= '</div>';

		}
	$home_content .= '</div></div>';

	if (is_home() || is_front_page()) {
		if (!$content)
			$content = $home_content;
		else
			$content .= $home_content;
	}
	return $content;
 }*/

 /** get default category icon **/
 function get_default_category_icon($category_name, $hover = false) {

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
	global $_bootstrap;

	if ($_bootstrap) {
		wp_enqueue_style('bootstrap-style', OER_URL.'css/bootstrap.min.css');
		wp_enqueue_script('bootstrap-script', OER_URL.'js/bootstrap.min.js');
	}
}

//Initialize settings page
add_action( 'admin_init' , 'oer_settings_page' );
function oer_settings_page() {
	//Create General Section
	add_settings_section(
		'oer_general_settings',
		'',
		'general_settings_callback',
		'oer_settings'
	);

	//Add Settings Fields - Assign Page Template
	/*add_settings_field(
		'oer_category_template',
		__("Assign Page Template to Category Pages", OER_SLUG),
		'setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_category_template',
			'type' => 'selectbox',
			'description' => __('Assign page template to subject area pages', OER_SLUG)
		)
	);*/

	//Add Settings field for Disable Screenshots
	add_settings_field(
		'oer_disable_screenshots',
		'',
		'setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_disable_screenshots',
			'type' => 'radio',
			'class' => 'screenshot_option',
			'name' =>  __('Disable Screenshots', OER_SLUG),
			'value' => '1'
		)
	);

	//Add Settings field for Server Side Screenshots
	add_settings_field(
		'oer_enable_screenshot',
		'',
		'setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_enable_screenshot',
			'type' => 'radio',
			'class' => 'screenshot_option',
			'name' =>  __('Enable Server-side screenshots', OER_SLUG),
			'value' => '1'
		)
	);

	//Add Settings field for Using XvFB
	add_settings_field(
		'oer_use_xvfb',
		'',
		'setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_use_xvfb',
			'type' => 'checkbox',
			'indent' => true,
			'name' =>  __('Use xvfb -- typically necessary on Linux installations', OER_SLUG)
		)
	);

	//Set Path for Python Installation
	add_settings_field(
		'oer_python_install',
		__("Python executable", OER_SLUG),
		'setup_settings_field',
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
		'setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_python_path',
			'type' => 'textbox',
			'indent' => true,
			'title' => __('Python Screenshot script', OER_SLUG)
		)
	);

	//Add Settings field for Disable Screenshots
	add_settings_field(
		'oer_external_screenshots',
		'',
		'setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_external_screenshots',
			'type' => 'radio',
			'class' => 'screenshot_option',
			'name' =>  __('Use external screenshot service', OER_SLUG),
			'value' => '1'
		)
	);

	//Set Path for Python Executable Script
	add_settings_field(
		'oer_service_url',
		__("Service URL", OER_SLUG),
		'setup_settings_field',
		'oer_settings',
		'oer_general_settings',
		array(
			'uid' => 'oer_service_url',
			'type' => 'textbox',
			'indent' => true,
			'title' => __("Service URL", OER_SLUG),
			'description' => __('use $url for where the Resource URL parameter should be placed', OER_SLUG)
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
function general_settings_callback() {

}

//Initialize Style Settings Tab
add_action( 'admin_init' , 'oer_styles_settings' );
function oer_styles_settings(){
	//Create Styles Section
	add_settings_section(
		'oer_styles_settings',
		'',
		'styles_settings_callback',
		'styles_settings_section'
	);

	//Add Settings field for Importing Bootstrap CSS & JS Libraries
	add_settings_field(
		'oer_use_bootstrap',
		'',
		'setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_use_bootstrap',
			'type' => 'checkbox',
			'name' =>  __('Import Bootstrap CSS & JS libraries', OER_SLUG),
			'description' => __('uncheck if your WP theme already included Bootstrap', OER_SLUG)
		)
	);

	//Add Settings field for displaying Subject Area sidebar
	add_settings_field(
		'oer_display_subject_area',
		'',
		'setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_display_subject_area',
			'type' => 'checkbox',
			'value' => '1',
			'default' => true,
			'name' =>  __('Display Subjects menu on Subject Area pages', OER_SLUG),
			'description' => __('Lists all subject areas in left column of Subject Area pages - may conflict with themes using left navigation.', OER_SLUG)
		)
	);

	//Add Settings field for hiding Page title on Subject Area pages
	add_settings_field(
		'oer_hide_subject_area_title',
		'',
		'setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_hide_subject_area_title',
			'type' => 'checkbox',
			'name' =>  __('Subject Area pages', OER_SLUG),
			'pre_html' => '<h3>Hide Page Titles</h3><p class="description hide-description">Some themes have built-in display of page titles.</p>'		)
	);

	//Add Settings field for hiding Page title on Resource pages
	add_settings_field(
		'oer_hide_resource_title',
		'',
		'setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_hide_resource_title',
			'type' => 'checkbox',
			'name' =>  __('Resource pages', OER_SLUG),
			'class' => 'hide-title-setting'
		)
	);

	//Add Settings field for Importing Bootstrap CSS & JS Libraries
	add_settings_field(
		'oer_additional_css',
		'',
		'setup_settings_field',
		'styles_settings_section',
		'oer_styles_settings',
		array(
			'uid' => 'oer_additional_css',
			'type' => 'textarea',
			'name' =>  __('Additional CSS', OER_SLUG),
			'inline_description' => __('easily customize the look and feel with your own CSS', OER_SLUG)
		)
	);

	register_setting( 'oer_styles_settings' , 'oer_use_bootstrap' );
	register_setting( 'oer_styles_settings' , 'oer_display_subject_area' );
	register_setting( 'oer_styles_settings' , 'oer_hide_subject_area_title' );
	register_setting( 'oer_styles_settings' , 'oer_hide_resource_title' );
	register_setting( 'oer_styles_settings' , 'oer_additional_css' );
}

//Styles Setting Callback
function styles_settings_callback(){

}

//Initialize Setup Settings Tab
add_action( 'admin_init' , 'oer_setup_settings' );
function oer_setup_settings(){
	//Create Setup Section
	add_settings_section(
		'oer_setup_settings',
		'',
		'setup_settings_callback',
		'setup_settings_section'
	);

	//Add Settings field for Importing Example Set of Resources
	add_settings_field(
		'oer_import_sample_resources',
		'',
		'setup_settings_field',
		'setup_settings_section',
		'oer_setup_settings',
		array(
			'uid' => 'oer_import_sample_resources',
			'type' => 'checkbox',
			'name' =>  __('Import Example Set of Resources', OER_SLUG),
			'description' => __('A collection of 40 Open Education Resources has been provided as a base - you can easily remove these later.', OER_SLUG)
		)
	);

	//Add Settings field for Import Default Subject Areas
	add_settings_field(
		'oer_import_default_subject_areas',
		'',
		'setup_settings_field',
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

	//Add Settings field for Importing Common Core State Standards
	add_settings_field(
		'oer_import_ccss',
		'',
		'setup_settings_field',
		'setup_settings_section',
		'oer_setup_settings',
		array(
			'uid' => 'oer_import_ccss',
			'type' => 'checkbox',
			'name' =>  __('Import Common Core State Standards', OER_SLUG),
			'description' => __('Enable use of CCSS as an optional alignment option for resources.', OER_SLUG)
		)
	);

	//Add Settings field for Importing Bootstrap CSS & JS Libraries
	add_settings_field(
		'oer_use_bootstrap',
		'',
		'setup_settings_field',
		'setup_settings_section',
		'oer_setup_settings',
		array(
			'uid' => 'oer_use_bootstrap',
			'type' => 'checkbox',
			'name' =>  __('Import Bootstrap CSS & JS libraries', OER_SLUG),
			'description' => __('uncheck if your WP theme already included Bootstrap', OER_SLUG)
		)
	);

	register_setting( 'oer_setup_settings' , 'oer_import_sample_resources' );
	register_setting( 'oer_setup_settings' , 'oer_import_default_subject_areas' );
	register_setting( 'oer_setup_settings' , 'oer_import_ccss' );
	register_setting( 'oer_setup_settings' , 'oer_setup_bootstrap' );
}

//Setup Setting Callback
function setup_settings_callback(){

}


//Initialize Import Academic Standards
add_action( 'admin_init' , 'oer_import_standards' );
function oer_import_standards(){
	//Create Standards Section
	add_settings_section(
		'oer_import_standards',
		'',
		'import_standards_callback',
		'import_standards_section'
	);

	//Add Common Core Mathematics field
	add_settings_field(
		'oer_common_core_mathematics',
		'',
		'setup_settings_field',
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
		'setup_settings_field',
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

function import_standards_callback() {

}

function setup_settings_field( $arguments ) {
	$selected = "";
	$size = "";
	$class = "";

	$value = get_option($arguments['uid']);

	if (isset($arguments['indent'])){
		echo '<div class="indent">';
	}

	if (isset($arguments['class'])) {
		$class = $arguments['class'];
		$class = " class='".$class."' ";
	}

	if (isset($arguments['pre_html'])) {
		echo $arguments['pre_html'];
	}

	switch($arguments['type']){
		case "textbox":
			$size = 'size="50"';
			if (isset($arguments['title']))
				$title = $arguments['title'];
			echo '<label for="'.$arguments['uid'].'"><strong>'.$title.'</strong></label><input name="'.$arguments['uid'].'" id="'.$arguments['uid'].'" type="'.$arguments['type'].'" value="' . $value . '" ' . $size . ' ' .  $selected . ' />';
			break;
		case "checkbox":
		case "radio":
			if (isset($arguments['default'])) {
				$selected = "checked='checked'";
			}
			if ($value==1 || $value=="on")
				$selected = "checked='checked'";
			else{
				$selected = "";
				$value = 1;
			}

			echo '<input name="'.$arguments['uid'].'" id="'.$arguments['uid'].'" '.$class.' type="'.$arguments['type'].'" value="' . $value . '" ' . $size . ' ' .  $selected . ' /><label for="'.$arguments['uid'].'"><strong>'.$arguments['name'].'</strong></label>';
			break;
		case "textarea":
			echo '<label for="'.$arguments['uid'].'"><h3><strong>'.$arguments['name'];
			if (isset($arguments['inline_description']))
				echo '<span class="inline-desc">'.$arguments['inline_description'].'</span>';
			echo '</strong></h3></label>';
			echo '<textarea name="'.$arguments['uid'].'" id="'.$arguments['uid'].'" rows="10">' . $value . '</textarea>';
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
add_filter( 'body_class', 'add_body_class');
function add_body_class($classes) {
	$cur_theme = wp_get_theme();
	$theme_class = $cur_theme->get('Name');

	return array_merge( $classes, array( str_replace( ' ', '-', strtolower($theme_class) ) ) );
}

/* Ajax Callback */
function load_more_resources() {
	global $wpdb, $wp_query;

	if (isset($_POST["post_var"])) {
		$page_num = $_POST["post_var"];
		$terms = json_decode($_POST["subjects"]);
		$args = array(
				'post_type' => 'resource',
				'posts_per_page' => 20,
				'post_status' => 'publish',
				'paged' => $page_num,
				'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => $terms))
				);

		$args = apply_sort_args($args);

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
				$img_path = $_SERVER['DOCUMENT_ROOT'] . $img_path['path'];
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
						$new_image_url = str_replace($_SERVER['DOCUMENT_ROOT'], "{$new_img_path['scheme']}://{$new_img_path['host']}{$new_port}", $dest_file_name);

						if ( !file_exists($dest_file_name) ){
							$image_file = $image_editor->save($dest_file_name);
						}
					}
				}
			?>
			<div class="oer-snglrsrc">
				<?php
				echo '<a href="'.get_permalink($post->ID).'" class="oer-resource-link"><div class="oer-snglimglft"><img src="'.$new_image_url.'"></div></a>';
				?>
				<div class="oer-snglttldscrght <?php if(empty($img_url)){ echo 'snglttldscrghtfull';}?>">
					<div class="ttl"><a href="<?php echo get_permalink($post->ID);?>"><?php echo $title;?></a></div>
					<div class="desc"><?php echo $content; ?></div>
				</div>
			</div>
		<?php
			}
		}
		die();
	}
}
add_action('wp_ajax_load_more', 'load_more_resources');
add_action('wp_ajax_nopriv_load_more', 'load_more_resources');

/** Sort Resources **/
function sort_resources(){
	global $wpdb;

	if (isset($_POST["sort"])) {

		$_SESSION['resource_sort'] = $_POST['sort'];

		$terms = json_decode($_POST["subjects"]);

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
			$paged = (int)$_POST['post_var'];
		}

		if ($_REQUEST['page'])
			$paged = (int)$_REQUEST['page'];

		$args = array(
				'post_type' => 'resource',
				'post_status' => 'publish',
				'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => $terms))
				);

		$args = apply_sort_args($args);

		$args['posts_per_page'] = 20 * $paged;

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
				$img_path = $_SERVER['DOCUMENT_ROOT'] . $img_path['path'];
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
						$new_image_url = str_replace($_SERVER['DOCUMENT_ROOT'], "{$new_img_path['scheme']}://{$new_img_path['host']}{$new_port}", $dest_file_name);

						if ( !file_exists($dest_file_name) ){
							$image_file = $image_editor->save($dest_file_name);
						}
					}
				}
			?>
			<div class="oer-snglrsrc">
				<?php
				echo '<a href="'.get_permalink($post->ID).'" class="oer-resource-link"><div class="oer-snglimglft"><img src="'.$new_image_url.'"></div></a>';
				?>
				<div class="oer-snglttldscrght <?php if(empty($img_url)){ echo 'snglttldscrghtfull';}?>">
					<div class="ttl"><a href="<?php echo get_permalink($post->ID);?>"><?php echo $title;?></a></div>
					<div class="desc"><?php echo $content; ?></div>
				</div>
			</div>
		<?php
			}
		}

		die();
	}
}
add_action('wp_ajax_sort_resources', 'sort_resources');
add_action('wp_ajax_nopriv_sort_resources', 'sort_resources');

/* Load More Highlights Ajax Callback */
function load_more_highlights() {
	global $wpdb, $wp_query;

	if (isset($_POST["post_var"])) {
		$page_num = $_POST["post_var"];
		$items_per_load = 4;
		$term_id = $_POST['term_id'];
		
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
					$style = ' style="'.$_POST['style'].'"';
				?>
				<li<?php echo $style; ?>>
					<div class="frtdsnglwpr">
						<?php
						if(empty($image)){
							$image = site_url().'/wp-content/plugins/wp-oer/images/default-icon.png';
						}
						$new_image_url = oer_resize_image( $image, 220, 180, true );
						?>
						<a href="<?php echo get_permalink($post->ID);?>"><div class="img"><img src="<?php echo $new_image_url;?>" alt="<?php echo $title;?>"></div></a>
						<div class="ttl"><a href="<?php echo get_permalink($post->ID);?>"><?php echo $title;?></a></div>
						<div class="desc"><?php echo apply_filters('the_content',$content); ?></div>
					</div>
				</li>
			<?php
			}
		}
		die();
	}
}
add_action('wp_ajax_load_highlights', 'load_more_highlights');
add_action('wp_ajax_nopriv_load_highlights', 'load_more_highlights');

/* Load Highlighted Resource based on ID Ajax Callback */
function load_highlight() {
	global $wpdb, $wp_query;

	if (isset($_POST["post_var"])) {
		$resource_id = $_POST["post_var"];
		
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
					$style = ' style="'.$_POST['style'].'"';
				?>
				<div class="frtdsnglwpr">
					<?php
					if(empty($image)){
						$image = site_url().'/wp-content/plugins/wp-oer/images/default-icon.png';
					}
					$new_image_url = oer_resize_image( $image, 220, 180, true );
					?>
					<a href="<?php echo get_permalink($post->ID);?>"><div class="img"><img src="<?php echo $new_image_url;?>" alt="<?php echo $title;?>"></div></a>
					<div class="ttl"><a href="<?php echo get_permalink($post->ID);?>"><?php echo $title;?></a></div>
					<div class="desc"><?php echo apply_filters('the_content',$content); ?></div>
				</div><?php
			}
		}
		die();
	}
}
add_action('wp_ajax_load_highlight', 'load_highlight');
add_action('wp_ajax_nopriv_load_highlight', 'load_highlight');

/*Enqueue ajax url on frontend*/
add_action('wp_head','resource_ajaxurl', 8);
function resource_ajaxurl()
{
	?>
    <script type="text/javascript">
    /* workaround to only use SSL when on SSL (avoid self-signed cert issues) */
    <?php if (!strpos($_SERVER['REQUEST_URI'],"wp-admin")): ?>
	var sajaxurl = '<?php echo OER_URL ?>ajax.php';
    <?php else: ?>
	var sajaxurl = '<?php echo admin_url("admin-ajax.php", (is_ssl() ? "https": "http") ); ?>
    <?php endif; ?>
    </script>
<?php
}

/** Start session to store sort option **/
add_action( 'init', 'initSession', 1 );
function initSession(){
	if(!session_id()) {
		session_start();
	}
}
//front side shortcode
//include_once(OER_PATH.'includes/resource_front.php');
?>
