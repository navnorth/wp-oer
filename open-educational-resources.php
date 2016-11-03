<?php
/*
 Plugin Name: OER Management
 Plugin URI: http://www.navigationnorth.com/wordpress/oer-management
 Description: Open Educational Resource management and curation, metadata publishing, and alignment to Common Core State Standards. Developed in collaboration with Monad Infotech (http://monadinfotech.com)
 Version: 0.2.7
 Author: Navigation North
 Author URI: http://www.navigationnorth.com

 Copyright (C) 2014 Navigation North

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
define( 'OER_VERSION', '0.2.7' );

include_once(OER_PATH.'includes/oer-functions.php');
include_once(OER_PATH.'includes/init.php');
include_once(OER_PATH.'includes/shortcode.php');

//define global variable $debug_mode and get value from settings
global $_debug, $_bootstrap, $_css;
$_debug = get_option('oer_debug_mode');
$_bootstrap = get_option('oer_use_bootstrap');
$_css = get_option('oer_additional_css');

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
	
	$home_content =  '<div class="cntnr"><div class="ctgry-cntnr row">';
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
					
				$home_content .= '<div class="snglctwpr col-md-3"><div class="cat-div" data-ownback="'.get_template_directory_uri().'/img/top-arrow.png" onMouseOver="changeonhover(this)" onMouseOut="changeonout(this);" onclick="togglenavigation(this);" data-id="'.$cnt.'" data-class="'.$lepcnt.'" data-normalimg="'.$icn_guid.'" data-hoverimg="'.$icn_hover_guid.'">
					<div class="cat-icn" style="background: url('.$icn_guid.') no-repeat scroll center center; "></div>
					<div class="cat-txt-btm-cntnr">
						<ul>
							<li><label class="mne-sbjct-ttl" ><a href="'. site_url() .'/resource-category/'. $category->slug .'">'. $category->name .'</a></label><span>'. $count .'</span></li>
						</ul>
					</div>';
					
					$children = get_term_children($category->term_id, 'resource-category');
					if( !empty( $children ) )
					{
						$home_content .= '<div class="child-category">'. oer_front_child_category($category->term_id) .'</div>';
					}
				$home_content .= '</div>';
				//if(($cnt % 4) == 0){
					$home_content .= '<div class="child_content_wpr" data-id="'.$lepcnt.'"></div>';
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
	register_setting( 'oer_styles_settings' , 'oer_additional_css' );
}

//Styles Setting Callback
function styles_settings_callback(){
	
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
	
	switch($arguments['type']){
		case "textbox":
			$size = 'size="50"';
			if (isset($arguments['title']))
				$title = $arguments['title'];
			echo '<label for="'.$arguments['uid'].'"><strong>'.$title.'</strong></label><input name="'.$arguments['uid'].'" id="'.$arguments['uid'].'" type="'.$arguments['type'].'" value="' . $value . '" ' . $size . ' ' .  $selected . ' />';
			break;
		case "checkbox":
		case "radio":
			if ($value==1 || $value=="on")
				$selected = "checked='checked'";
			else{
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
//front side shortcode
//include_once(OER_PATH.'includes/resource_front.php');
?>