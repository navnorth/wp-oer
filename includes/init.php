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
//add_filter( 'get_terms_args', 'oer_filter_term_args', 10, 2 );

//Filter Posts , Pages and Resources According Categories Assignment
add_action('load-edit.php', 'oer_load_edit_php_action');
function oer_load_edit_php_action()
{
  if(isset($_GET['post_type']))
  {
	  if (sanitize_text_field($_GET['post_type']) == 'page')
	  {
		add_filter('posts_where', 'oer_page_where_filter');
	  }
	  elseif(sanitize_text_field($_GET["post_type"]) == 'post')
	  {
		add_filter('posts_where', 'oer_posts_where_filter');
	  }
	  elseif(sanitize_text_field($_GET["post_type"]) == 'resource')
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
    if ((isset($_GET['post_type']) && sanitize_text_field($_GET['post_type'])=='resource') || (isset($post->post_type) && $post->post_type=='resource')) {
      wp_enqueue_style('jqueryui-styles', OER_URL.'css/jquery-ui.css');
      wp_enqueue_style('back-styles', OER_URL.'css/back_styles.css');
      wp_enqueue_style( 'thickbox' );
    
	    if ( ! did_action( 'wp_enqueue_media' ) ) {
	        wp_enqueue_media();
	    }
      
      wp_enqueue_script('jquery');
      wp_enqueue_script('jquery-ui-core' );
      wp_enqueue_script('jquery-ui-widgets' );
      wp_enqueue_script('jquery-ui-tabs' );
      wp_enqueue_script('jquery-ui-datepicker' );
      wp_enqueue_script( 'media-upload' );
      wp_enqueue_script( 'thickbox' );
      wp_enqueue_script( 'bootstrap-js', OER_URL.'js/bootstrap.min.js', array('jquery'));
      wp_enqueue_script('back-scripts', OER_URL.'js/back_scripts.js',array( 'jquery','media-upload','thickbox','set-post-thumbnail' ));
      wp_enqueue_script('admin-resource', OER_URL.'js/admin_resource.js', array('wp-i18n'));
      wp_set_script_translations('admin-resource', OER_SLUG, OER_PATH . '/lang/js');
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
	
	wp_enqueue_script('front-scripts', OER_URL.'js/front_scripts.js');
	wp_enqueue_style( "resource-category-styles", OER_URL . "css/resource-category-style.css" );
}

//Add style block
add_action( 'wp_head' , 'oer_add_style_block', 99  );
function oer_add_style_block(){
    global $_css;
    $allowed_tags = oer_allowed_html();
    
    if ($_css) {
	$output = "<style>"."\n";
	$output .= $_css."\n";
	$output .="</style>"."\n";
	echo wp_kses($output,$allowed_tags);
    }
}

//register custom post
add_action( 'init' , 'oer_postcreation' );
function oer_postcreation(){
    global $_use_gutenberg, $_resources_path;

    $resources_slug = ($_resources_path?$_resources_path:true);

	$labels = array(
        'name'               => __( 'Resources', OER_SLUG ),
        'singular_name'      => __( 'Resource', OER_SLUG ),
        'add_new'            => __( 'Add Resource', OER_SLUG ),
        'add_new_item'       => __( 'Add Resource', OER_SLUG ),
        'edit_item'          => __( 'Edit Resource', OER_SLUG ),
        'new_item'           => __( 'New Resource', OER_SLUG ),
        'all_items'          => __( 'All Resources', OER_SLUG ),
        'view_item'          => __( 'View Resource', OER_SLUG ),
        'search_items'       => __( 'Search', OER_SLUG ),
        'menu_name'          => 'OER'
    );
    
    $args = array(
        'labels'        => $labels,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
        'description'   => __('Create Resources',OER_SLUG),
        'public'        => true,
		'publicly_queryable'        => true,
		'exclude_from_search' => false,
		'query_var' => true,
        'menu_position' => '',
		'menu_icon' => 'dashicons-welcome-learn-more',
        'supports'      => array(  'title', 'editor', 'thumbnail', 'revisions',  ),
		'taxonomies' => array('post_tag'),
		'rewrite'	=> array( 'slug' => 'resource' ),
        'has_archive'   => $resources_slug,
		'register_meta_box_cb' => 'oer_resources_custom_metaboxes'
    );
    
    if ($_use_gutenberg=="on" or $_use_gutenberg=="1")
		$args['show_in_rest'] = true;
	
    register_post_type( 'resource', $args);
    flush_rewrite_rules();
}

function oer_resources_custom_metaboxes(){
	add_meta_box('oer_metaboxid',__('Open Resource Meta Fields',OER_SLUG),'oermeta_callback','resource','normal','high');
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
    global $_use_gutenberg, $_nalrc;
    $singular = "Subject Area";
    $plural = "Subject Areas";
    $grade_singular = "Grade Level";
    $grade_plura = "Grade Levels";

    // NALRC - change Subject Area label to topic area
    if ($_nalrc){
    	$singular = "Topic Area";
    	$plural = "Topic Areas";
    	$grade_singular = "Grade Level";
    	$grade_plural = "Grade Levels";
    }

    $arr_tax = array(
    	array("slug"=>"resource-subject-area","singular_name"=>$singular, "plural_name"=>$plural),
    	array("slug"=>"resource-grade-level","singular_name"=>$grade_singular, "plural_name"=>$grade_plural)
    );
    
    foreach($arr_tax as $tax){
    	$labels = array(
	      	'name'              => esc_html__( $tax['plural_name'],OER_SLUG ),
		    'singular_name'     => esc_html_x( $tax['singular_name'], 'taxonomy singular name', OER_SLUG ),
		    'search_items'      => esc_html__( "Search ".$tax['plural_name'],OER_SLUG ),
		    'all_items'         => esc_html__( 'All '.$tax['plural_name'], OER_SLUG ),
		    'parent_item'       => esc_html__( 'Parent '.$tax['singular_name'],OER_SLUG ),
		    'parent_item_colon' => esc_html__( 'Parent '.$tax['singular_name'].':',OER_SLUG ),
		    'edit_item'         => esc_html__( 'Edit '.$tax['singular_name'], OER_SLUG ),
		    'update_item'       => esc_html__( 'Update '.$tax['singular_name'], OER_SLUG ),
		    'add_new_item'      => esc_html__( 'Add New '.$tax['singular_name'],OER_SLUG),
		    'new_item_name'     => esc_html__( 'New Genre '.$tax['singular_name'],OER_SLUG),
		    'menu_name'         => esc_html__( $tax['plural_name'],OER_SLUG ),
	    );
	    
	    $args = array(
		    'hierarchical'      => true,
		    'labels'            => $labels,
		    'show_ui'           => true,
		    'show_admin_column' => true,
		    'show_in_rest'		=> true,
		    'query_var'         => true,
		    'rewrite'           => array( 'slug' => $tax['slug'] ),
	    );

	    if ($tax['slug']=='resource-grade-level'){
	    	$args['sort'] 		= true;
	    	$args['args']		= array('orderby'=> 'term_order');
	    }
	    register_taxonomy( $tax['slug'], array( 'resource' ), $args );
    }
}
//register custom category

// Display grade levels according to term_order in block editor sidebar
add_filter('rest_resource-grade-level_query','oer_sort_grade_levels', 10, 2);
function oer_sort_grade_levels($args, $request){
	$args['orderby'] = "term_order";
	return $args;
}

// Change order of grade level display on both edit tags page and in classic editor
add_filter( 'get_terms_args', 'oer_sort_grade_level_terms', 10, 2 );
function oer_sort_grade_level_terms( $args, $taxonomies ) 
{
	global $pagenow;
	if (is_admin() && ($pagenow=='edit-tags.php' || $pagenow == 'post-new.php' || $pagenow == 'post.php') && in_array('resource-grade-level',$taxonomies) ){
		$args['orderby'] = 'term_order';
    	$args['order'] = 'ASC';
	}

    return $args;
}

/**
 * Add Image meta data
 **/
add_action( 'resource-subject-area_add_form_fields', 'oer_add_upload_image_fields', 10 );
function oer_add_upload_image_fields($taxonomy) {
    global $feature_groups, $_nalrc;
    $label = "Subject Area";
    if ($_nalrc)
    	$label = "Topic Area";
    ?>
    <?php wp_nonce_field( 'oer_add_upload_image_action', 'oer_add_upload_image_action_nonce_field' ); ?>
    <div class="form-field term-group">
        <label for="main-icon-group"><?php _e($label.' Main Icon', OER_SLUG); ?></label>
	<a id="main_icon_button" href="javascript:void(0);" class="button"><?php _e('Set Main Icon', OER_SLUG); ?></a>
	<a id="remove_main_icon_button" href="javascript:void(0);" class="button hidden"><?php _e('Remove Main Icon', OER_SLUG); ?></a>
	<input id="mainIcon" type="hidden" size="36" name="mainIcon" value="" />
    </div>
    <div class="form-field term-group">
        <label for="hover-icon-group"><?php _e($label.' Hover Icon', OER_SLUG); ?></label>
	<a id="hover_icon_button" href="javascript:void(0);" class="button"><?php _e('Set Hover Icon', OER_SLUG); ?></a>
	<a id="remove_hover_icon_button" href="javascript:void(0);" class="button hidden"><?php _e('Remove Hover Icon', OER_SLUG); ?></a>
	<input id="hoverIcon" type="hidden" size="36" name="hoverIcon" value="" />
    </div>
    <?php
}

/**
 * Edit Image meta data
 **/
add_action( 'resource-subject-area_edit_form_fields', 'oer_edit_upload_image_fields', 10, 2 );
function oer_edit_upload_image_fields( $term, $taxonomy ) {
    global $_nalrc;
    $label = "Subject Area";
    if ($_nalrc)
    	$label = "Topic Area";
    $mainIcon = get_term_meta( $term->term_id, 'mainIcon', true );
     ?>
     <?php wp_nonce_field( 'oer_edit_upload_image_action', 'oer_edit_upload_image_action_nonce_field' ); ?>
     <tr class="form-field term-group-wrap">
        <th scope="row"><label for="feature-group"><?php _e($label.' Main Icon', OER_SLUG); ?></label></th>
        <td>
	    <div class="main_icon_button_img"><img src="<?php echo esc_url($mainIcon); ?>" /></div>
	    <a id="main_icon_button" href="javascript:void(0);" class="button"><?php esc_html_e('Set Main Icon', OER_SLUG); ?></a>
	    <a id="remove_main_icon_button" href="javascript:void(0);" class="button<?php if (!$mainIcon):?> hidden<?php endif; ?>"><?php esc_html_e('Remove Main Icon', OER_SLUG); ?></a>
	    <input id="mainIcon" type="hidden" size="36" name="mainIcon" value="<?php echo esc_attr($mainIcon); ?>" />
	</td>
    </tr><?php
    
    $hoverIcon = get_term_meta( $term->term_id, 'hoverIcon', true );
    ?><tr class="form-field term-group-wrap">
        <th scope="row"><label for="feature-group"><?php esc_html_e($label.' Hover Icon', OER_SLUG); ?></label></th>
        <td>
	    <div class="hover_icon_button_img"><img src="<?php echo esc_url($hoverIcon); ?>" /></div>
	    <a id="hover_icon_button" href="javascript:void(0);" class="button"><?php esc_html_e('Set Hover Icon', OER_SLUG); ?></a>
	    <a id="remove_hover_icon_button" href="javascript:void(0);" class="button<?php if (!$hoverIcon):?> hidden<?php endif; ?>"><?php esc_html_e('Remove Hover Icon', OER_SLUG); ?></a>
	    <input id="hoverIcon" type="hidden" size="36" name="hoverIcon" value="<?php echo esc_attr($hoverIcon); ?>" />
	</td>
    </tr><?php
}

/**
 * Save Image meta data
 **/
add_action( 'created_resource-subject-area', 'oer_save_subject_area_meta', 10, 2 );
function oer_save_subject_area_meta( $term_id, $tt_id ){
    if (isset($_POST['mainIcon']) || isset($_POST['hoverIcon'])) {
	if (!isset($_POST['oer_add_upload_image_action_nonce_field']) || !wp_verify_nonce( $_POST['oer_add_upload_image_action_nonce_field'], 'oer_add_upload_image_action' )) {
	    wp_die('Nonce verification failed');
	}
	if( '' !== $_POST['mainIcon'] ){
	    add_term_meta( $term_id, 'mainIcon', sanitize_url($_POST['mainIcon']), true );
	}
	 if( '' !== $_POST['hoverIcon'] ){
	    add_term_meta( $term_id, 'hoverIcon', sanitize_url($_POST['hoverIcon']), true );
	}
    }
}

/** Update Subject Area Meta **/
add_action( 'edited_resource-subject-area', 'oer_update_subject_area_meta', 10, 2 );
function oer_update_subject_area_meta( $term_id, $tt_id ){
    if (!isset($_POST['oer_edit_upload_image_action_nonce_field']) || !wp_verify_nonce( $_POST['oer_edit_upload_image_action_nonce_field'], 'oer_edit_upload_image_action' )) {
	wp_die('Nonce verification failed');
    }
   if( isset( $_POST['mainIcon'] ) && '' !== $_POST['mainIcon'] ){
        update_term_meta( $term_id, 'mainIcon', sanitize_url($_POST['mainIcon']) );
    } else {
	//If Main Icon is existing, remove it
	$mainIcon = get_term_meta( $term_id, 'mainIcon', true );
	if ($mainIcon)
	    delete_term_meta( $term_id, 'mainIcon' );
    }
    if( isset( $_POST['hoverIcon'] ) && '' !== $_POST['hoverIcon'] ){
        update_term_meta( $term_id, 'hoverIcon', sanitize_url($_POST['hoverIcon']) );
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
		if($post->post_type == 'resource'){
		    if (isset($_GET['action']) && (sanitize_text_field($_GET['action'])=="trash" || sanitize_text_field($_GET['action'])=="untrash")){
			return; 
	    }
	    if (!isset($_POST['oer_metabox_nonce_field']) || !wp_verify_nonce( $_POST['oer_metabox_nonce_field'], 'oer_metabox_action' )) {
		    wp_die('Nonce verification failed');
	    }
		if(isset($_POST['oer_resourceurl']))
		{
			$oer_resourceurl = sanitize_url($_POST['oer_resourceurl']);
			if( !empty($oer_resourceurl) )
			{
				if ( !preg_match('/http/',$oer_resourceurl) )
				{
					$oer_resourceurl = sanitize_url('http://'.$oer_resourceurl);
				}
			}
			update_post_meta( $post->ID , 'oer_resourceurl' , $oer_resourceurl);
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
    	
    	// Save Age Levels
		if(isset($_POST['oer_age_levels']))
		{
		    update_post_meta( $post->ID , 'oer_age_levels' , sanitize_text_field($_POST['oer_age_levels']));
		}
    
    	// Save Instructional Time
		if(isset($_POST['oer_instructional_time']))
		{
		    update_post_meta( $post->ID , 'oer_instructional_time' , sanitize_text_field($_POST['oer_instructional_time']));
		}
    
    	// Save Creative Commons License
		if(isset($_POST['oer_creativecommons_license']))
		{
		    update_post_meta( $post->ID , 'oer_creativecommons_license' , sanitize_text_field($_POST['oer_creativecommons_license']));
		}
		
		// Save Format
		if(isset($_POST['oer_format']))
		{
			update_post_meta( $post->ID , 'oer_format' , sanitize_text_field($_POST['oer_format']));
		}
		
		if(isset($_POST['oer_datecreated']))
		{
			update_post_meta( $post->ID , 'oer_datecreated' , sanitize_text_field($_POST['oer_datecreated']));
		}
		
		// Save Date Created Estimate
		if(isset($_POST['oer_datecreated_estimate']))
		{
			update_post_meta( $post->ID , 'oer_datecreated_estimate' , sanitize_text_field($_POST['oer_datecreated_estimate']));
		}

		if(isset($_POST['oer_datemodified']))
		{
			update_post_meta( $post->ID , 'oer_datemodified' , sanitize_text_field($_POST['oer_datemodified']));
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
			$oer_userightsurl = sanitize_url($_POST['oer_userightsurl']);
			if( !empty($oer_userightsurl) )
			{
				if ( !preg_match('/http/',$oer_userightsurl) )
				{
					$oer_userightsurl = sanitize_url('http://'.$oer_userightsurl);
				}
			}
			update_post_meta( $post->ID , 'oer_userightsurl', $oer_userightsurl);
		}

		if(isset($_POST['oer_isbasedonurl']))
		{
			$oer_isbasedonurl = sanitize_url($_POST['oer_isbasedonurl']);
			if( !empty($oer_isbasedonurl) )
			{
				if ( !preg_match('/http/',$oer_isbasedonurl) )
				{
					$oer_isbasedonurl = sanitize_url('http://'.$oer_isbasedonurl);
				}
			}
			update_post_meta( $post->ID , 'oer_isbasedonurl' , $oer_isbasedonurl);
		}

		// Save Resource Notice
		if(isset($_POST['oer_resource_notice']))
		{
			// Sanitize wp_editor content
			$oer_resource_notice = sanitize_post_field('post_content', $_POST['oer_resource_notice'], $post->ID, 'db');
			update_post_meta( $post->ID , 'oer_resource_notice' , $oer_resource_notice);
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
			update_post_meta( $post->ID , 'oer_standard' , sanitize_text_field($_POST['oer_standard']));
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
			$oer_authorurl = sanitize_url($_POST['oer_authorurl']);
			if( !empty($oer_authorurl) )
			{
				if ( !preg_match('/http/',$oer_authorurl) )
				{
					$oer_authorurl = sanitize_url('http://'.$oer_authorurl);
				}
			}
			update_post_meta( $post->ID , 'oer_authorurl' , $oer_authorurl);
		}

		if(isset($_POST['oer_authoremail']))
		{
			update_post_meta( $post->ID , 'oer_authoremail' , sanitize_email($_POST['oer_authoremail']));
		}

		if(isset($_POST['oer_authortype2']))
		{
			update_post_meta( $post->ID , 'oer_authortype2' , sanitize_text_field($_POST['oer_authortype2']));
		} else {
			if (get_post_meta($post->ID, 'oer_authortype2'))
				delete_post_meta($post->ID, 'oer_authortype2');
		}

		if(isset($_POST['oer_authorname2']))
		{
			update_post_meta( $post->ID , 'oer_authorname2' , sanitize_text_field($_POST['oer_authorname2']));
		} else {
			if (get_post_meta($post->ID, 'oer_authorname2'))
				delete_post_meta($post->ID, 'oer_authorname2');
		}

		if(isset($_POST['oer_authorurl2']))
		{
			$oer_authorurl2 = sanitize_url($_POST['oer_authorurl2']);
			if( !empty($oer_authorurl2) )
			{
				if ( !preg_match('/http/',$oer_authorurl2) )
				{
					$oer_authorurl2 = sanitize_url('http://'.$oer_authorurl2);
				}
			}
			update_post_meta( $post->ID , 'oer_authorurl2' , $oer_authorurl2);
		} else {
			if (get_post_meta($post->ID, 'oer_authorurl2'))
				delete_post_meta($post->ID, 'oer_authorurl2');
		}

		if(isset($_POST['oer_authoremail2']))
		{
			update_post_meta( $post->ID , 'oer_authoremail2' , sanitize_email($_POST['oer_authoremail2']));
		} else {
			if (get_post_meta($post->ID, 'oer_authoremail2'))
				delete_post_meta($post->ID, 'oer_authoremail2');
		}

		if(isset($_POST['oer_publishername']))
		{
			update_post_meta( $post->ID , 'oer_publishername' , sanitize_text_field($_POST['oer_publishername']));
		}

		if(isset($_POST['oer_publisherurl']))
		{
			$oer_publisherurl = sanitize_url($_POST['oer_publisherurl']);
			if( !empty($oer_publisherurl) )
			{
				if ( !preg_match('/http/',$oer_publisherurl) )
				{
					$oer_publisherurl = sanitize_url('http://'.$oer_publisherurl);
				}
			}
			update_post_meta( $post->ID , 'oer_publisherurl' , $oer_publisherurl);
		}

		if(isset($_POST['oer_publisheremail']))
		{
			update_post_meta( $post->ID , 'oer_publisheremail' , sanitize_email($_POST['oer_publisheremail']));
		}
    
    if(isset($_POST['oer_external_repository']))
		{
			update_post_meta( $post->ID , 'oer_external_repository' , sanitize_text_field($_POST['oer_external_repository']));
		}

		if(isset($_POST['oer_repository_recordurl']))
		{
			$oer_repositoryurl = sanitize_url($_POST['oer_repository_recordurl']);
			if( !empty($oer_repositoryurl) )
			{
				if ( !preg_match('/http/',$oer_repositoryurl) )
				{
					$oer_repositoryurl = sanitize_url('http://'.$oer_repositoryurl);
				}
			}
			update_post_meta( $post->ID , 'oer_repository_recordurl' , $oer_repositoryurl);
		}
		
		if(!empty($_POST['oer_resourceurl']))
		{
			$url = sanitize_url($_POST['oer_resourceurl']);
			
			$youtube = oer_is_youtube_url($url);
			
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
				
			    if ( $youtube )
				$file = oer_get_youtube_thumbnail($url);
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
		
		// Save Citation
		if(isset($_POST['oer_citation']))
		{
			// Sanitize wp_editor content
			$oer_citation = sanitize_post_field('post_content', $_POST['oer_citation'], $post->ID, 'db');
		    update_post_meta( $post->ID , 'oer_citation' , $oer_citation);
		}
		
		// Save Sensitive Material
		if(isset($_POST['oer_sensitive_material']))
		{
			// Sanitize wp_editor content
			$oer_sensitive_material = sanitize_post_field('post_content', $_POST['oer_sensitive_material'], $post->ID, 'db');
		    update_post_meta( $post->ID , 'oer_sensitive_material' , $oer_sensitive_material);
		}
		
		// Save Transcription
		if(isset($_POST['oer_transcription']))
		{
			// Sanitize wp_editor content
			$oer_transcription = sanitize_post_field('post_content', $_POST['oer_transcription'], $post->ID, 'db');
			update_post_meta( $post->ID , 'oer_transcription' , $oer_transcription);
		}

    	// Save Related Resource
		if(isset($_POST['oer_related_resource']))
		{
			update_post_meta( $post->ID , 'oer_related_resource' , sanitize_text_field($_POST['oer_related_resource']));
		}
	}
    }
}

add_action('admin_menu','oer_rsrcimprtr');
function oer_rsrcimprtr(){
	add_submenu_page('edit.php?post_type=resource',__('Import',OER_SLUG),__('Import',OER_SLUG),'add_users','oer_import','oer_import');
	add_submenu_page('edit.php?post_type=resource',__('Settings',OER_SLUG),__('Settings',OER_SLUG),'add_users','oer_settings','oer_setngpgfn');
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

/**
 * Process Import Resources form
 **/
add_action("admin_action_import_resources","oer_process_import_resources");
function oer_process_import_resources(){
    $message = null;
    $type = null;

    if (!current_user_can('manage_options')) {
	    wp_die( "You don't have permission to access this page!" );
    }
    
    //Resource Import
    if(isset($_POST['resrc_imprt']))
    {
		check_admin_referer('oer_resources_nonce_field');
	    
	    $import_response = oer_importResources();
	    if ($import_response){
			$message = urlencode($import_response["message"]);
			$type = urlencode($import_response["type"]);
	    }
    }
    
    wp_safe_redirect( admin_url("edit.php?post_type=resource&page=oer_import&message=$message&type=$type"));
    exit;
}

add_action("admin_action_import_lr_resources","oer_process_import_lr_resources");
function oer_process_import_lr_resources(){
    $message = null;
    $type = null;

    if (!current_user_can('manage_options')) {
	    wp_die( "You don't have permission to access this page!" );
    }
    
    //Resource Import
    if(isset($_POST['lr_resrc_imprt'])){
	check_admin_referer('oer_lr_nonce_field');
	
	$resources = oer_importLRResources();
	
	if ($resources){
	    $cnt = 0;
	    foreach($resources as $resource) {
		if (!oer_resource_exists($resource)){
		    oer_add_resource($resource);
		    $cnt++;
		}
	    }
	    $message = $cnt;
	    $type = "lr";
	}
	
    }
    wp_safe_redirect( admin_url("edit.php?post_type=resource&page=oer_import&message=$message&type=$type"));
    exit;
}

/**
 * Process Import Subject Areas
 **/
add_action("admin_action_import_subjects","oer_process_import_subjects");
function oer_process_import_subjects(){
    $message = null;
    $type = null;

    if (!current_user_can('manage_options')) {
	    wp_die( "You don't have permission to access this page!" );
    }
    
    //Subject Areas Bulk Import
    if(isset($_POST['bulk_imprt']))
    {
	    check_admin_referer('oer_subject_area_nonce_field');
	    
	$import_response = oer_importSubjectAreas();
	if ($import_response){
	    $message = urlencode($import_response["message"]);
	    $type = urlencode($import_response["type"]);
	}
    }
    
    wp_safe_redirect( admin_url("edit.php?post_type=resource&page=oer_import&message=$message&type=$type"));
    exit;
}

/**
 * Process Import Standards
 **/
add_action("admin_action_import_standards","oer_process_import_standards");
function oer_process_import_standards(){
    $message = null;
    $type = null;

    if (!current_user_can('manage_options')) {
	    wp_die( "You don't have permission to access this page!" );
    }
    
    //Standards Bulk Import
    if(isset($_POST['standards_import']))
    {
	check_admin_referer('oer_standards_nonce_field');
	    
	$files = array();
    
	if (isset($_POST['oer_common_core_mathematics'])){
	       $files[] = OER_PATH."samples/CCSS_Math.xml";
	}
    
	if (isset($_POST['oer_common_core_english'])){
	       $files[] = OER_PATH."samples/CCSS_ELA.xml";
	}
    
	if (isset($_POST['oer_next_generation_science'])){
	       $files[] = OER_PATH."samples/NGSS.xml";
	}
	    
	foreach ($files as $file) {
	    $import = oer_importStandards($file);
	    if ($import['type']=="success") {
		if (strpos($file,'Math')) {
		    $message .= "Successfully imported Common Core Mathematics Standards. \n";
		} elseif (strpos($file,'ELA')) {
		    $message .= "Successfully imported Common Core English Language Arts Standards. \n";
		} else {
		    $message .= "Successfully imported Next Generation Science Standards. \n";
		}
	    }
	    $type = urlencode($import['type']);
	}
	$message = urlencode($message);
    }
    
    wp_safe_redirect( admin_url("edit.php?post_type=resource&page=oer_import&message=$message&type=$type"));
    exit;
}

// Add Standard Modal
add_action( "admin_footer" , "oer_add_modal" );
function oer_add_modal(){
  if (oer_installed_standards_plugin()) {
    $screen = get_current_screen();
    if ( 'post' == $screen->base && 'resource' == $screen->id ){
      include_once(OER_PATH."oer_template/modals/standard_modal.php");
    }
  }
}

// Add Related Resource Modal
add_action( "admin_footer" , "oer_add_related_resource_modal" );
function oer_add_related_resource_modal(){
  if (oer_installed_standards_plugin()) {
    $screen = get_current_screen();
    if ( 'post' == $screen->base && 'resource' == $screen->id ){
      include_once(OER_PATH."oer_template/modals/related_resource_modal.php");
    }
  }
}

// tinymce fix for firefox
add_action( "admin_footer" , "oer_reinitialize_tinymce_on_firefox", 999999 );
function oer_reinitialize_tinymce_on_firefox(){
  ?>
  <script type="text/javascript">
  	if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1){
	  	jQuery(document).ready(function($){
	  		var init, id, $wrap;

			if ( typeof tinymce !== 'undefined' ) {
				if ( tinymce.Env.ie && tinymce.Env.ie < 11 ) {
					tinymce.$( '.wp-editor-wrap ' ).removeClass( 'tmce-active' ).addClass( 'html-active' );
					return;
				}

				for ( id in tinyMCEPreInit.mceInit ) {
					init = tinyMCEPreInit.mceInit[id];
					$wrap = tinymce.$( '#wp-' + id + '-wrap' );

					if ( ( $wrap.hasClass( 'tmce-active' ) || ! tinyMCEPreInit.qtInit.hasOwnProperty( id ) ) && ! init.wp_skip_init ) {
						
						tinymce.init( init );

						tinymce.execCommand( 'mceRemoveEditor', false, id );
	                	tinymce.execCommand( 'mceAddEditor', false, id );

						if ( ! window.wpActiveEditor ) {
							window.wpActiveEditor = id;
						}
					}
				}
			}

			if ( typeof quicktags !== 'undefined' ) {
				for ( id in tinyMCEPreInit.qtInit ) {
					quicktags( tinyMCEPreInit.qtInit[id] );

					quicktags({ id: id });

					if ( ! window.wpActiveEditor ) {
						window.wpActiveEditor = id;
					}
				}
			}
	  	});
  	}
  </script>
  <?php
}
?>