<?php
/*
 Plugin Name: OER Management
 Plugin URI: http://www.navigationnorth.com/wordpress/oer-management
 Description: Open Educational Resource management and curation, metadata publishing, and alignment to Common Core State Standards. Developed in collaboration with Monad Infotech (http://monadinfotech.com)
 Version: 0.2
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

include_once(OER_PATH.'includes/oer-functions.php');
include_once(OER_PATH.'includes/init.php');

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

//front side shortcode
//include_once(OER_PATH.'includes/resource_front.php');
?>
