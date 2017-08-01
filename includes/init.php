<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once OER_PATH.'includes/oer-functions.php';

//Filter Texonomy and Resource Texonomy According Categories Assignment
function oer_filter_term_args( $args, $taxonomies )
{
    global $pagenow;
    
	$user_id = get_current_user_id();
	
	$client_terms = get_user_meta($user_id, 'oer_userasgnctgries', true);
  	$client_terms = unserialize($client_terms);

	if( $pagenow == $pagenow && 'resource-subject-area' == $taxonomies[0] )
	{
	    $args['include'] = $client_terms;
	}
	elseif( $pagenow == $pagenow && 'category' == $taxonomies[0] )
	{
	    $args['include'] = $client_terms;
	}
	return $args;
}
add_filter( 'get_terms_args', 'oer_filter_term_args', 10, 2 );

//Filter Posts , Pages and Resources According Categories Assignment
add_action('load-edit.php', 'oer_load_edit_php_action');
function oer_load_edit_php_action()
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
function oer_backside_scripts($hook)
{
    global $post;
    
    if ((isset($_GET['post_type']) && $_GET['post_type']=='resource') || (isset($post->post_type) && $post->post_type=='resource')) {
	wp_enqueue_style('jqueryui-styles', OER_URL.'css/jquery-ui.css');
	wp_enqueue_style('back-styles', OER_URL.'css/back_styles.css');
	wp_enqueue_style( 'thickbox' );

	if (isset($post->post_type) && $post->post_type!=='resource') {
	    wp_enqueue_script('jquery');
	    /*wp_enqueue_script('min_jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');*/
	}
	
	/*wp_enqueue_script('jqueryui-scripts', OER_URL.'js/jquery-ui.js');*/
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-widgets' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script('back-scripts', OER_URL.'js/back_scripts.js',array( 'jquery','media-upload','thickbox','set-post-thumbnail' ));
    }
    
    // Adds our JS file to the queue that WordPress will load
    wp_enqueue_script( 'wp_ajax_oer_admin_script', OER_URL . 'js/oer_admin.js', array( 'jquery' ), null, true );

    // Make some data available to our JS file
    wp_localize_script( 'wp_ajax_oer_admin_script', 'wp_ajax_oer_admin', array(
	    'wp_ajax_oer_admin_nonce' => wp_create_nonce( 'wp_ajax_oer_admin_nonce' ),
    ));
}

//scripts and styles on front end
add_action('wp_enqueue_scripts', 'oer_frontside_scripts');
function oer_frontside_scripts()
{
	wp_enqueue_style('jqueryui-styles', OER_URL.'css/jquery-ui.css');
	wp_enqueue_style('front-styles', OER_URL.'css/front_styles.css');
	wp_enqueue_style( "resource-category-styles", OER_URL . "css/resource-category-style.css" );

	wp_enqueue_script('jquery');
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-widgets' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	/*wp_enqueue_script('jqueryui-scripts', OER_URL.'js/jquery-ui.js');*/
	wp_enqueue_script('front-scripts', OER_URL.'js/front_scripts.js');
	wp_enqueue_style( "resource-category-styles", OER_URL . "css/resource-category-style.css" );
}

//Add style block
add_action( 'wp_head' , 'oer_add_style_block', 99  );
function oer_add_style_block(){
    global $_css;
    
    if ($_css) {
	$output = "<style>"."\n";
	$output .= $_css."\n";
	$output .="</style>"."\n";
	echo $output;
    }
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
	'publicly_queryable'        => true,
	'exclude_from_search' => false,
	'query_var' => true,
        'menu_position' => '',
	'menu_icon' => 'dashicons-welcome-learn-more',
        'supports'      => array(  'title', 'editor', 'thumbnail', 'revisions',  ),
	'taxonomies' => array('post_tag'),
        'has_archive'   => true,
	'register_meta_box_cb' => 'oer_resources_custom_metaboxes'
    );
	register_post_type( 'resource', $args);
}

function oer_resources_custom_metaboxes(){
	add_meta_box('oer_metaboxid','Open Resource Meta Fields','oermeta_callback','resource','advanced');
}

//metafield callback
function oermeta_callback()
{
	include_once(OER_PATH.'includes/resource_metafields.php');
}
//register custom post

//register custom category
add_action( 'init', 'oer_create_resource_taxonomies', 0 );
function oer_create_resource_taxonomies() {
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
		'rewrite'           => array( 'slug' => 'resource-subject-area' ),
	);
	register_taxonomy( 'resource-subject-area', array( 'resource' ), $args );
}
//register cutsom category

/**
 * Add Image meta data
 **/
add_action( 'resource-subject-area_add_form_fields', 'oer_add_upload_image_fields', 10 );
function oer_add_upload_image_fields($taxonomy) {
    global $feature_groups;
    ?><div class="form-field term-group">
        <label for="main-icon-group"><?php _e('Subject Area Main Icon', OER_SLUG); ?></label>
	<a id="main_icon_button" href="javascript:void(0);" class="button">Set Main Icon</a>
	<a id="remove_main_icon_button" href="javascript:void(0);" class="button hidden">Remove Main Icon</a>
	<input id="mainIcon" type="hidden" size="36" name="mainIcon" value="" />
    </div>
    <div class="form-field term-group">
        <label for="hover-icon-group"><?php _e('Subject Area Hover Icon', OER_SLUG); ?></label>
	<a id="hover_icon_button" href="javascript:void(0);" class="button">Set Hover Icon</a>
	<a id="remove_hover_icon_button" href="javascript:void(0);" class="button hidden">Remove Hover Icon</a>
	<input id="hoverIcon" type="hidden" size="36" name="hoverIcon" value="" />
    </div>
    <?php
}

/**
 * Edit Image meta data
 **/
add_action( 'resource-subject-area_edit_form_fields', 'oer_edit_upload_image_fields', 10, 2 );
function oer_edit_upload_image_fields( $term, $taxonomy ) {
    
    $mainIcon = get_term_meta( $term->term_id, 'mainIcon', true );
     ?><tr class="form-field term-group-wrap">
        <th scope="row"><label for="feature-group"><?php _e('Subject Area Main Icon', OER_SLUG); ?></label></th>
        <td>
	    <div class="main_icon_button_img"><img src="<?php echo $mainIcon; ?>" /></div>
	    <a id="main_icon_button" href="javascript:void(0);" class="button">Set Main Icon</a>
	    <a id="remove_main_icon_button" href="javascript:void(0);" class="button<?php if (!$mainIcon):?> hidden<?php endif; ?>">Remove Main Icon</a>
	    <input id="mainIcon" type="hidden" size="36" name="mainIcon" value="<?php echo $mainIcon; ?>" />
	</td>
    </tr><?php
    
    $hoverIcon = get_term_meta( $term->term_id, 'hoverIcon', true );
    ?><tr class="form-field term-group-wrap">
        <th scope="row"><label for="feature-group"><?php _e('Subject Area Hover Icon', OER_SLUG); ?></label></th>
        <td>
	    <div class="hover_icon_button_img"><img src="<?php echo $hoverIcon; ?>" /></div>
	    <a id="hover_icon_button" href="javascript:void(0);" class="button">Set Hover Icon</a>
	    <a id="remove_hover_icon_button" href="javascript:void(0);" class="button<?php if (!$hoverIcon):?> hidden<?php endif; ?>">Remove Hover Icon</a>
	    <input id="hoverIcon" type="hidden" size="36" name="hoverIcon" value="<?php echo $hoverIcon; ?>" />
	</td>
    </tr><?php
}

/**
 * Save Image meta data
 **/
add_action( 'created_resource-subject-area', 'oer_save_subject_area_meta', 10, 2 );
function oer_save_subject_area_meta( $term_id, $tt_id ){
    if( isset( $_POST['mainIcon'] ) && '' !== $_POST['mainIcon'] ){
        add_term_meta( $term_id, 'mainIcon', esc_url_raw($_POST['mainIcon']), true );
    }
     if( isset( $_POST['hoverIcon'] ) && '' !== $_POST['hoverIcon'] ){
        add_term_meta( $term_id, 'hoverIcon', esc_url_raw($_POST['hoverIcon']), true );
    }
}

/** Update Subject Area Meta **/
add_action( 'edited_resource-subject-area', 'oer_update_subject_area_meta', 10, 2 );
function oer_update_subject_area_meta( $term_id, $tt_id ){

   if( isset( $_POST['mainIcon'] ) && '' !== $_POST['mainIcon'] ){
        update_term_meta( $term_id, 'mainIcon', esc_url_raw($_POST['mainIcon']) );
    } else {
	//If Main Icon is existing, remove it
	$mainIcon = get_term_meta( $term_id, 'mainIcon', true );
	if ($mainIcon)
	    delete_term_meta( $term_id, 'mainIcon' );
    }
    if( isset( $_POST['hoverIcon'] ) && '' !== $_POST['hoverIcon'] ){
        update_term_meta( $term_id, 'hoverIcon', esc_url_raw($_POST['hoverIcon']) );
    } else {
	// If Icon is existing, remove it
	$hoverIcon = get_term_meta( $term_id, 'hoverIcon', true );
	if ($hoverIcon)
	    delete_term_meta( $term_id, 'hoverIcon' );
    }
}

//saving meta fields
add_action('save_post', 'oer_save_customfields');
function oer_save_customfields()
{
    global $post, $wpdb, $_oer_prefix;
    
    //Check first if screenshot is enabled
    $screenshot_enabled = get_option( 'oer_enable_screenshot' );
    $external_screenshot = get_option('oer_external_screenshots');
    
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
			update_post_meta( $post->ID , 'oer_resourceurl' , esc_url_raw($oer_resourceurl));
		}

		if(isset($_POST['oer_highlight']))
		{
			update_post_meta( $post->ID , 'oer_highlight' , sanitize_text_field($_POST['oer_highlight']));
		}

		if(isset($_POST['oer_grade']))
		{
			$oer_grade = implode(",",$_POST['oer_grade']);
			update_post_meta( $post->ID , 'oer_grade' , sanitize_text_field($oer_grade));
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
			update_post_meta( $post->ID , 'oer_mediatype' , sanitize_text_field($_POST['oer_mediatype']));
		}

		if(isset($_POST['oer_lrtype']))
		{
			update_post_meta( $post->ID , 'oer_lrtype' , sanitize_text_field($_POST['oer_lrtype']));
		}

		if(isset($_POST['oer_interactivity']))
		{
			update_post_meta( $post->ID , 'oer_interactivity' , sanitize_text_field($_POST['oer_interactivity']));
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
			update_post_meta( $post->ID , 'oer_userightsurl' , esc_url_raw($oer_userightsurl));
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
			update_post_meta( $post->ID , 'oer_isbasedonurl' , esc_url_raw($oer_isbasedonurl));
		}
		
		if(isset($_POST['oer_standard_alignment']))
		{
			update_post_meta( $post->ID , 'oer_standard_alignment' , sanitize_text_field($_POST['oer_standard_alignment']));
		}
		else
		{
			update_post_meta( $post->ID , 'oer_standard_alignment' , '');
		}

		if(isset($_POST['oer_standard']))
		{
			$gt_oer_standard = $_POST['oer_standard'];
			
			if(!empty($gt_oer_standard)) {
			    for($l = 0; $l < count($gt_oer_standard); $l++)
			    {
				$results = $wpdb->get_row( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where standard_notation =%s" , $gt_oer_standard[$l] ),ARRAY_A);
				if(!empty($results))
				{
				    $gt_oer_standard_notation .= "standard_notation-".$results['id'].",";
				    $table = explode("-", $results['parent_id']);
				    
				    if(!empty($table))
				    {
					$stndrd_algn = $wpdb->get_row( $wpdb->prepare( "SELECT * from  " . $wpdb->prefix. $_oer_prefix.$table[0] . " where id =%s" , $table[1] ),ARRAY_A);
					if($stndrd_algn['parent_id'])
					{
						oer_fetch_stndrd($stndrd_algn['parent_id'], $post_id);
					}
				    }
				}
			    }
			}
			$oer_standard = implode(",", $_POST['oer_standard']);
			update_post_meta( $post->ID , 'oer_standard' , sanitize_text_field($oer_standard));
		}
		else
		{
			update_post_meta( $post->ID , 'oer_standard' , '');
		}

		if(isset($_POST['oer_authortype']))
		{
			update_post_meta( $post->ID , 'oer_authortype' , sanitize_text_field($_POST['oer_authortype']));
		}

		if(isset($_POST['oer_authorname']))
		{
			update_post_meta( $post->ID , 'oer_authorname' , sanitize_text_field($_POST['oer_authorname']));
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
			update_post_meta( $post->ID , 'oer_authorurl' , esc_url_raw($oer_authorurl));
		}

		if(isset($_POST['oer_authoremail']))
		{
			update_post_meta( $post->ID , 'oer_authoremail' , sanitize_email($_POST['oer_authoremail']));
		}

		if(isset($_POST['oer_authortype2']))
		{
			update_post_meta( $post->ID , 'oer_authortype2' , sanitize_text_field($_POST['oer_authortype2']));
		}

		if(isset($_POST['oer_authorname2']))
		{
			update_post_meta( $post->ID , 'oer_authorname2' , sanitize_text_field($_POST['oer_authorname2']));
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
			update_post_meta( $post->ID , 'oer_authorurl2' , esc_url_raw($oer_authorurl2));
		}

		if(isset($_POST['oer_authoremail2']))
		{
			update_post_meta( $post->ID , 'oer_authoremail2' , sanitize_email($_POST['oer_authoremail2']));
		}

		if(isset($_POST['oer_publishername']))
		{
			update_post_meta( $post->ID , 'oer_publishername' , sanitize_text_field($_POST['oer_publishername']));
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
			update_post_meta( $post->ID , 'oer_publisherurl' , esc_url_raw($oer_publisherurl));
		}

		if(isset($_POST['oer_publisheremail']))
		{
			update_post_meta( $post->ID , 'oer_publisheremail' , sanitize_email($_POST['oer_publisheremail']));
		}
		
		if(!empty($_POST['oer_resourceurl']))
		{
			$url = esc_url_raw($_POST['oer_resourceurl']);
			$upload_dir = wp_upload_dir();
			$file = '';

			//Change $post_id as it is undefined to $post->ID
			if(!has_post_thumbnail( $post->ID ))
			{
			    if ( $screenshot_enabled )
				$file = oer_getScreenshotFile($url);
			    
			    // if external screenshot utility enabled
			    if ( $external_screenshot )
				$file = oer_getImageFromExternalURL($url);
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

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				wp_update_attachment_metadata( $attach_id, $attach_data );
			}

		}//Create Screeenshot

	}
    }
}

add_action('admin_menu','oer_rsrcimprtr');
function oer_rsrcimprtr(){
	add_submenu_page('edit.php?post_type=resource','Import','Import','add_users','oer_import','oer_import');
	add_submenu_page('edit.php?post_type=resource','Settings','Settings','add_users','oer_settings','oer_setngpgfn');
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
function oer_import() {
	global $wpdb;
	include_once(OER_PATH.'includes/import.php');
}
function oer_setngpgfn()
{
	global $wpdb;
	include_once(OER_PATH.'includes/settings.php');
}
?>
