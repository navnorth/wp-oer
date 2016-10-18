<?php
require_once OER_PATH.'includes/oer-functions.php';

//Filter Texonomy and Resource Texonomy According Categories Assignment
function wpa_filter_term_args( $args, $taxonomies )
{
    global $pagenow;
	$user_id = get_current_user_id();
    $client_terms = get_user_meta($user_id, 'oer_userasgnctgries', true);
  	$client_terms = unserialize($client_terms);

	if( $pagenow == $pagenow && 'resource-category' == $taxonomies[0] )
	{
         $args['include'] = $client_terms;
    }
	elseif( $pagenow == $pagenow && 'category' == $taxonomies[0] )
	{
         $args['include'] = $client_terms;
    }
	return $args;
}
add_filter( 'get_terms_args', 'wpa_filter_term_args', 10, 2 );

//Filter Posts , Pages and Resources According Categories Assignment
add_action('load-edit.php', 'my_load_edit_php_action');
function my_load_edit_php_action()
{
  if(isset($_GET['post_type']))
  {
	  if ($_GET['post_type'] == 'page')
	  {
		add_filter('posts_where', 'oer_page_where_filter');
	  }
	  elseif($_GET["post_type"] == 'post')
	  {
		add_filter('posts_where', 'oer_posts_where_filter');
	  }
	  elseif($_GET["post_type"] == 'resource')
	  {
		add_filter('posts_where', 'oer_resource_where_filter');
	  }
	  else
	  {
	  	return;
	  }
  }
  else
  {
  	add_filter('posts_where', 'oer_posts_where_filter');
  }

}

function oer_page_where_filter($sql)
{
  $user_id = get_current_user_id();
  $oer_userasgnpages = get_user_meta($user_id, 'oer_userasgnpages', true);

  if(!empty($oer_userasgnpages))
  {
  	$oer_userasgnpages = unserialize($oer_userasgnpages);
	$oer_userasgnpages = implode(",",$oer_userasgnpages);
    global $wpdb;
    $sql = " AND $wpdb->posts.ID IN ($oer_userasgnpages)" . $sql;
  }
  return $sql;
}

function oer_posts_where_filter($sql)
{
  $user_id = get_current_user_id();
  $oer_userasgnblog_post = get_user_meta($user_id, 'oer_userasgnblog_post', true);
  if(!empty($oer_userasgnblog_post))
  {
  	$oer_userasgnblog_post = unserialize($oer_userasgnblog_post);
	$oer_userasgnblog_post = implode(",",$oer_userasgnblog_post);
    global $wpdb;
    $sql = " AND $wpdb->posts.ID IN ($oer_userasgnblog_post)" . $sql;
  }
  return $sql;
}

function oer_resource_where_filter($sql)
{
  $user_id = get_current_user_id();
  $oer_userasgnrsrc_post = get_user_meta($user_id, 'oer_userasgnrsrc_post', true);
  if(!empty($oer_userasgnrsrc_post))
  {
  	$oer_userasgnrsrc_post = unserialize($oer_userasgnrsrc_post);
	$oer_userasgnrsrc_post = implode(",",$oer_userasgnrsrc_post);
    global $wpdb;
    $sql = " AND $wpdb->posts.ID IN ($oer_userasgnrsrc_post)" . $sql;
  }
  return $sql;
}
//Filter Posts , Pages and Resources According Categories Assignment


//scripts and styles on backend
add_action('admin_enqueue_scripts', 'oer_backside_scripts');
function oer_backside_scripts()
{
	wp_enqueue_style('jqueryui-styles', OER_URL.'css/jquery-ui.css');
	wp_enqueue_style('back-styles', OER_URL.'css/back_styles.css');
	wp_enqueue_style( 'thickbox' );

	wp_enqueue_script('jquery');
	wp_enqueue_script('min_jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');

	wp_enqueue_script('jqueryui-scripts', OER_URL.'js/jquery-ui.js');
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script('back-scripts', OER_URL.'js/back_scripts.js',array( 'jquery','media-upload','thickbox','set-post-thumbnail' ));
}

//scripts and styles on front end
add_action('wp_enqueue_scripts', 'oer_frontside_scripts');
function oer_frontside_scripts()
{
	wp_enqueue_style('jqueryui-styles', OER_URL.'css/jquery-ui.css');
	wp_enqueue_style('front-styles', OER_URL.'css/front_styles.css');

	wp_enqueue_script('jquery');
	wp_enqueue_script('jqueryui-scripts', OER_URL.'js/jquery-ui.js');
	wp_enqueue_script('front-scripts', OER_URL.'js/front_scripts.js');
}

//register custom post
add_action( 'init' , 'oer_postcreation' );
function oer_postcreation(){
	$labels = array(
        'name'               => _x( 'Resource', 'post type general name' ),
        'singular_name'      => _x( 'Resource', 'post type singular name' ),
        'add_new'            => _x( 'Add Resource', 'book' ),
        'add_new_item'       => __( 'Add Resource' ),
        'edit_item'          => __( 'Edit Resource' ),
        'new_item'           => __( 'New Resource' ),
        'all_items'          => __( 'All Resources' ),
        'view_item'          => __( 'View Resource' ),
        'search_items'       => __( 'Search' ),
        'menu_name'          => 'OER'
    );

    $args = array(
        'labels'        => $labels,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
        'description'   => 'Create Resources',
        'public'        => true,
        'menu_position' => '',
		'menu_icon' => OER_URL.'images/plugicon.png',
        'supports'      => array(  'title', 'editor', 'thumbnail', 'revisions',  ),
		'taxonomies' => array('post_tag'),
        'has_archive'   => true,
		'register_meta_box_cb' => 'resources_custom_metaboxes'
    );
	register_post_type( 'resource', $args);
}

function resources_custom_metaboxes(){
	add_meta_box('oer_metaboxid','Open Resource Meta Fields','oermeta_callback','resource','advanced');
}

//metafield callback
function oermeta_callback()
{
	include_once(OER_PATH.'includes/resource_metafields.php');
}
//register custom post

//register custom category
add_action( 'init', 'create_resource_taxonomies', 0 );
function create_resource_taxonomies() {
	$labels = array(
		'name'              => _x( 'Subject Area', 'taxonomy general name' ),
		'singular_name'     => _x( 'Subject Area', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Subject Areas' ),
		'all_items'         => __( 'All Subject Areas' ),
		'parent_item'       => __( 'Parent Subject Area' ),
		'parent_item_colon' => __( 'Parent Subject Area:' ),
		'edit_item'         => __( 'Edit Subject Area' ),
		'update_item'       => __( 'Update Subject Area' ),
		'add_new_item'      => __( 'Add New Subject Area' ),
		'new_item_name'     => __( 'New Genre Subject Area' ),
		'menu_name'         => __( 'Subject Areas' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'resource-category' ),
	);
	register_taxonomy( 'resource-category', array( 'resource' ), $args );
}
//register cutsom category

//saving meta fields
add_action('save_post', 'oer_save_customfields');
function oer_save_customfields()
{
    global $post;
    //Check first if screenshot is enabled
    $screenshot_enabled = get_option( 'oer_enable_screenshot' );
    
    //Check first if $post is not empty
    if ($post) {
	if($post->post_type == 'resource')
	{
		if(isset($_POST['oer_resourceurl']))
		{
			$oer_resourceurl = $_POST['oer_resourceurl'];
			if( !empty($_POST['oer_resourceurl']) )
			{
				if ( preg_match('/http/',$oer_resourceurl) )
				{
					$oer_resourceurl = $_POST['oer_resourceurl'];
				}
				else
				{
					$oer_resourceurl = 'http://'.$_POST['oer_resourceurl'];
				}
			}
			update_post_meta( $post->ID , 'oer_resourceurl' , $oer_resourceurl);
		}

		if(isset($_POST['oer_highlight']))
		{
			update_post_meta( $post->ID , 'oer_highlight' , $_POST['oer_highlight']);
		}

		if(isset($_POST['oer_grade']))
		{
			$oer_grade = implode(",",$_POST['oer_grade']);
			update_post_meta( $post->ID , 'oer_grade' , $oer_grade);
		}
		else
		{
			update_post_meta( $post->ID , 'oer_grade' , '');
		}

		if(isset($_POST['oer_datecreated']))
		{
			update_post_meta( $post->ID , 'oer_datecreated' , $_POST['oer_datecreated']);
		}

		if(isset($_POST['oer_datemodified']))
		{
			update_post_meta( $post->ID , 'oer_datemodified' , $_POST['oer_datemodified']);
		}

		if(isset($_POST['oer_mediatype']))
		{
			update_post_meta( $post->ID , 'oer_mediatype' , $_POST['oer_mediatype']);
		}

		if(isset($_POST['oer_lrtype']))
		{
			update_post_meta( $post->ID , 'oer_lrtype' , $_POST['oer_lrtype']);
		}

		if(isset($_POST['oer_interactivity']))
		{
			update_post_meta( $post->ID , 'oer_interactivity' , $_POST['oer_interactivity']);
		}

		if(isset($_POST['oer_userightsurl']))
		{
			$oer_userightsurl = $_POST['oer_userightsurl'];
			if( !empty($_POST['oer_userightsurl']) )
			{
				if ( preg_match('/http/',$oer_userightsurl) )
				{
					$oer_userightsurl = $_POST['oer_userightsurl'];
				}
				else
				{
					$oer_userightsurl = 'http://'.$_POST['oer_userightsurl'];
				}
			}
			update_post_meta( $post->ID , 'oer_userightsurl' , $oer_userightsurl);
		}

		if(isset($_POST['oer_isbasedonurl']))
		{
			$oer_isbasedonurl = $_POST['oer_isbasedonurl'];
			if( !empty($_POST['oer_isbasedonurl']) )
			{
				if ( preg_match('/http/',$oer_isbasedonurl) )
				{
					$oer_isbasedonurl = $_POST['oer_isbasedonurl'];
				}
				else
				{
					$oer_isbasedonurl = 'http://'.$_POST['oer_isbasedonurl'];
				}
			}
			update_post_meta( $post->ID , 'oer_isbasedonurl' , $oer_isbasedonurl);
		}

		if(isset($_POST['oer_standard_alignment']))
		{
			update_post_meta( $post->ID , 'oer_standard_alignment' , $_POST['oer_standard_alignment']);
		}
		else
		{
			update_post_meta( $post->ID , 'oer_standard_alignment' , '');
		}

		if(isset($_POST['oer_standard']))
		{
			$oer_standard = implode(",", $_POST['oer_standard']);
			update_post_meta( $post->ID , 'oer_standard' , $oer_standard);
		}
		else
		{
			update_post_meta( $post->ID , 'oer_standard' , '');
		}

		if(isset($_POST['oer_authortype']))
		{
			update_post_meta( $post->ID , 'oer_authortype' , $_POST['oer_authortype']);
		}

		if(isset($_POST['oer_authorname']))
		{
			update_post_meta( $post->ID , 'oer_authorname' , $_POST['oer_authorname']);
		}

		if(isset($_POST['oer_authorurl']))
		{
			$oer_authorurl = $_POST['oer_authorurl'];
			if( !empty($_POST['oer_authorurl']) )
			{
				if ( preg_match('/http/',$oer_authorurl) )
				{
					$oer_authorurl = $_POST['oer_authorurl'];
				}
				else
				{
					$oer_authorurl = 'http://'.$_POST['oer_authorurl'];
				}
			}
			update_post_meta( $post->ID , 'oer_authorurl' , $oer_authorurl);
		}

		if(isset($_POST['oer_authoremail']))
		{
			update_post_meta( $post->ID , 'oer_authoremail' , $_POST['oer_authoremail']);
		}

		if(isset($_POST['oer_authortype2']))
		{
			update_post_meta( $post->ID , 'oer_authortype2' , $_POST['oer_authortype2']);
		}

		if(isset($_POST['oer_authorname2']))
		{
			update_post_meta( $post->ID , 'oer_authorname2' , $_POST['oer_authorname2']);
		}

		if(isset($_POST['oer_authorurl2']))
		{
			$oer_authorurl2 = $_POST['oer_authorurl2'];
			if( !empty($_POST['oer_authorurl2']) )
			{
				if ( preg_match('/http/',$oer_authorurl2) )
				{
					$oer_authorurl2 = $_POST['oer_authorurl2'];
				}
				else
				{
					$oer_authorurl2 = 'http://'.$_POST['oer_authorurl2'];
				}
			}
			update_post_meta( $post->ID , 'oer_authorurl2' , $oer_authorurl2);
		}

		if(isset($_POST['oer_authoremail2']))
		{
			update_post_meta( $post->ID , 'oer_authoremail2' , $_POST['oer_authoremail2']);
		}

		if(isset($_POST['oer_publishername']))
		{
			update_post_meta( $post->ID , 'oer_publishername' , $_POST['oer_publishername']);
		}

		if(isset($_POST['oer_publisherurl']))
		{
			$oer_publisherurl = $_POST['oer_publisherurl'];
			if( !empty($_POST['oer_publisherurl']) )
			{
				if ( preg_match('/http/',$oer_publisherurl) )
				{
					$oer_publisherurl = $_POST['oer_publisherurl'];
				}
				else
				{
					$oer_publisherurl = 'http://'.$_POST['oer_publisherurl'];
				}
			}
			update_post_meta( $post->ID , 'oer_publisherurl' , $oer_publisherurl);
		}

		if(isset($_POST['oer_publisheremail']))
		{
			update_post_meta( $post->ID , 'oer_publisheremail' , $_POST['oer_publisheremail']);
		}

		if(isset($_POST['oer_resourceurl']))
		{
			$url = $_POST['oer_resourceurl'];
			$upload_dir = wp_upload_dir();
			$file = '';

			//Change $post_id as it is undefined to $post->ID
			if(!has_post_thumbnail( $post->ID ))
			{
			    if ( $screenshot_enabled )
				$file = getScreenshotFile($url);
			}

			if(file_exists($file))
			{
				$filetype = wp_check_filetype( basename( $file ), null );
				$wp_upload_dir = wp_upload_dir();

				$attachment = array(
					'guid'           => $wp_upload_dir['url'] . '/' . basename( $file ),
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				$attach_id = wp_insert_attachment( $attachment, $file, $post->ID );
				update_post_meta($post->ID, "_thumbnail_id", $attach_id);

				// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				wp_update_attachment_metadata( $attach_id, $attach_data );
			}

		}//Create Screeenshot

	}
    }
}

//Update Split Shared Term
function resource_split_shared_term( $term_id, $new_term_id, $term_taxonomy_id, $taxonomy ) {
    // Checking if taxonomy is a resource category
    if ( 'resource-category' == $taxonomy ) {
	$resource_posts = get_posts(array(
	    'posts_per_page' => -1,
	    'post_type' => 'fabric_building',
	    'tax_query' => array(
		array(
		    'taxonomy' => $taxonomy,
		    'field' => 'term_id',
		    'terms' => $term_id,
		)
	    )
	));
	var_dump($term_id);
	var_dump($new_term_id);
       var_dump($resource_posts);
       exit();
    }
}
add_action( 'split_shared_term', 'resource_split_shared_term', 10, 4 );

add_action('admin_menu','oer_rsrcimprtr');
function oer_rsrcimprtr(){
	add_submenu_page('edit.php?post_type=resource','Set Subject Area Images','Set Subject Area Images','add_users','oer_setcatimage','oer_setcatimage');
	add_submenu_page('edit.php?post_type=resource','Resources Import','Import Resources','add_users','oer_rsrcimprt','oer_rsrcimprtrfn');
	add_submenu_page('edit.php?post_type=resource','Subject Areas Import','Import Subject Areas','add_users','oer_catsimprt','oer_catsimprtrfn');
	add_submenu_page('edit.php?post_type=resource','Standards Import','Import Standards','add_users','oer_stndrdsimprt','oer_stndrdsimprtfn');
	add_submenu_page('edit.php?post_type=resource','Assign Subject Areas','Assign Subject Areas','add_users','oer_assign_categories','oer_assign_categories');
	add_submenu_page('edit.php?post_type=resource','Settings','Settings','add_users','oer_settings','oer_setngpgfn');
}

function oer_setcatimage()
{
	global $wpdb;
	include_once(OER_PATH."includes/set-category-images.php");
}
function oer_rsrcimprtrfn()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "resource_csv";
	include_once(OER_PATH.'includes/resources-importer.php');
}
function oer_catsimprtrfn()
{
	global $wpdb;
	include_once(OER_PATH.'includes/categories-importer.php');
}
function oer_stndrdsimprtfn()
{
	global $wpdb;
	include_once(OER_PATH.'includes/standards-importer.php');
}
function oer_assign_categories()
{
	global $wpdb;
	include_once(OER_PATH.'includes/assign_categories.php');
}
function oer_setngpgfn()
{
	global $wpdb;
	include_once(OER_PATH.'includes/settings.php');
}
?>
