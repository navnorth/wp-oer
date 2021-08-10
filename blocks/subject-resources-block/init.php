<?php
global $pagenow;

function wp_oer_enqueue_subject_resources_frontend_script(){
	wp_enqueue_script( 'wp_oer_block-front-js', plugins_url( '/subject-resources-block/build/front.js', dirname( __FILE__ ) ), array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),'0.1' , true );
 	wp_localize_script( 'wp_oer_block-front-js', 'wp_oer_block', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'wp_oer_enqueue_subject_resources_frontend_script');

// Add checking if current page is on add new/edit page,post or resource before loading bootstrap
if ($pagenow=="post.php" || $pagenow=="edit.php" || $pagenow=="post-new.php"){
	add_action('admin_enqueue_scripts', 'wp_oer_enqueue_subject_resources_frontend_script');
}

/** Subject Resources block version 2.0 **/
function wp_oer_register_resources_block(){
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "create-block/my-oer-block" block first.'
		);
	}

	// Main Script for the Block
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'wp-oer-resources-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_localize_script('wp-oer-resources-block-editor', 'oerapi', array( 'domain' => site_url() ));
	wp_enqueue_script('wp-oer-resources-block-editor');
	wp_set_script_translations( 'wp-oer-resources-block', OER_SLUG );

	// Backend styles
	$editor_css = 'build/index.css';
	wp_register_style(
		'wp-oer-resources-block-editor-style',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	// Frontend styles
	$style_css = 'build/style-index.css';
	wp_register_style(
		'wp-oer-resources-block-frontend-style',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'wp-oer-plugin/wp-oer-resources-block', array(
		'script'		=> 'wp_oer_block-front-js',
		'editor_script' => 'wp-oer-resources-block-editor',
		'editor_style'  => 'wp-oer-resources-block-editor-style',
		'style'         => 'wp-oer-resources-block-frontend-style',
		'render_callback' => 'wp_oer_display_subject_resources'
	) );
}
add_action( 'init', 'wp_oer_register_resources_block' );

function register_api_routes(){
	register_rest_route(
		'oer/v2',
		'subjects',
		array(  'methods'=>'GET',
			'callback'=>'wp_oer_get_subject_areas',
			'permission_callback' => function(){
				return current_user_can('edit_posts');
			}
		)
	);
	register_rest_route(
		'oer/v2',
		'resources',
		array(  'methods'=>'GET',
			'callback'=>'wp_oer_get_resources',
			'permission_callback' => function(){
				return current_user_can('edit_posts');
			}
		)
	);
}
add_action( 'rest_api_init' , 'register_api_routes' );

function wp_oer_display_subject_resources( $attributes ){
	if (!empty($attributes))
		extract($attributes);
	
	if (!isset($displayCount)){
		$displayCount = "5";
	}

	$sort_display = "Date Updated";
	if (isset($sort)){
		switch($sort){
			case "modified":
				$sort_display = 'Date Updated';
				break;
			case "date":
				$sort_display = 'Date Added';
				break;
			case "title":
				$sort_display = 'Title A-Z';
				break;
		}
	}

	if (isset($selectedSubjects) && is_array($selectedSubjects))
		$selectedSubjects = implode(",", $selectedSubjects);
	$heading = '<div class="oer-snglrsrchdng" data-sort="'.$sort.'" data-count="'.$displayCount.'" data-subjects="'.$selectedSubjects.'">';
	$heading .= '	Browse '.$displayCount.' resources';
	$heading .= '	<div class="sort-box">';
	$heading .= '		<span class="sortoption-label">Sorted by</span>: <span class="sortoption">'.$sort_display.'</span>';
	$heading .= '		<span class="sort-resources" title="Sort resources" tabindex="0" role="button"><i class="fa fa-sort" aria-hidden="true"></i></span>';
	$heading .= '		<div class="sort-options">';
	$heading .= '			<ul class="sortList">';
	$heading .= '				<li value="modified" '.($sort=='modified'?'class="selected"':'').'>Date Updated</li>';
	$heading .= '				<li value="date" '.($sort=='date'?'class="selected"':'').'>Date Added</li>';
	$heading .= '				<li value="title" '.($sort=='title'?'class="selected"':'').'>Title A-Z</li>';
	$heading .= '			</ul>';
	$heading .= '		</div>';
	$heading .= '		<div class="components-base-control sort-selectbox">';
	$heading .= '			<div class="components-base-control__field">';
	$heading .= '				<select id="inspector-select-control-1" class="components-select-control__input">';
	$heading .= '					<option value="modified" '.selected($sort,'modified',false).'>Date Updated</option>';
	$heading .= '					<option value="date" '.selected($sort,'date',false).'>Date Added</option>';
	$heading .= '					<option value="title" '.selected($sort,'title',false).'>Title A-Z</option>';
	$heading .= '				</select>';
	$heading .= '			</div>';
	$heading .= '		</div>';
	$heading .= '	</div>';
	$heading .= '</div>';

	$html = '<div class="oer-subject-resources-list">';
	$html .= $heading;

	if (is_array($selectedSubjectResources)){
		foreach ($selectedSubjectResources as $subject){
			$html .= '<div class="post oer-snglrsrc">';
			$html .= '	<a href="'.esc_url($subject['link']).'" class="oer-resource-link">';
			$html .= '		<div class="oer-snglimglft">';
			$html .= '			<img src="'.esc_url($subject['fimg_url']).'">';
			$html .= '		</div>';
			$html .= '	</a>';
			$html .= '	<div class="oer-snglttldscrght">';
			$html .= '		<div class="ttl">';
			$html .= '			<a href="'.esc_url($subject['link']).'">'.$subject['post_title'].'</a>';
			$html .= '		</div>';
			$html .= '		<div class="post-meta">';
			$html .= '			<span class="post-meta-box post-meta-grades">';
			$html .= '				<strong>Grades: </strong>'.$subject['oer_grade'];
			$html .= '			</span>';
			$html .= '			<span class="post-meta-box post-meta-domain">';
			$html .= '				<strong>Domain: </strong><a href="'.esc_url($subject['oer_resourceurl']).'">'.$subject['domain'].'</a>';
			$html .= '			</span>';
			$html .= '		</div>';
			$html .= '		<div class="desc">';
			$html .= '			<div>';
			$html .=  $subject['resource_excerpt'];
			$html .= '			</div>';
			$html .= '		</div>';
			$html .= '		<div class="tagcloud">';
			if (is_array($subject['subject_details'])){
				foreach($subject['subject_details'] as $subj){
					$html .= '			<span><a href="'.esc_url($subj['link']).'">'.$subj['name'].'</a></span>';
				}
			}
			$html .= '		</div>';
			$html .= '	</div>';
			$html .= '</div>';
		}
	}
	$html .= '</div>';

	return $html;
}

function wp_oer_get_subject_areas() {
	$subjects_areas = array();

	// Get Top Level Subject Areas
	$subject_args = array(
		'taxonomy' => 'resource-subject-area',
		'hide_empty' => false,
		'parent' => 0,
		'number' => 0
	);

	$subject_query = new WP_Term_Query($subject_args);

	if (!empty($subject_query->terms)){
		$index = 0;
		foreach($subject_query->terms as $subject) {
			$subject_areas[$index]['term_id'] = $subject->term_id;
			$subject_areas[$index]['name'] = $subject->name;
			$subject_areas[$index]['type'] = 'parent';
			$subject_areas[$index]['parent'] = $subject->parent;
			$subject_areas[$index]['slug'] = $subject->slug;
			$subject_areas[$index]['count'] = $subject->count;
			$index++;

			// Get Child Subject Areas
			$child_subject_args = array(
				'taxonomy' => 'resource-subject-area',
				'parent' => $subject->term_id,
				'hide_empty' => false,
				'number' => 0
			);

			$child_subject_query = new WP_Term_Query($child_subject_args);
			if (!empty($child_subject_query->terms)) {
				foreach($child_subject_query->terms as $childSubject){
					$subject_areas[$index]['term_id'] = $childSubject->term_id;
					$subject_areas[$index]['name'] = $childSubject->name;
					$subject_areas[$index]['type'] = 'child';
					$subject_areas[$index]['parent'] = $childSubject->parent;
					$subject_areas[$index]['slug'] = $childSubject->slug;
					$subject_areas[$index]['count'] = $childSubject->count;
					$index++;
				}
			}
		}
	} 
	$response = new WP_REST_Response($subject_areas, 200);
	$response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
	return $response;
}

function wp_oer_get_resources($request_data, $ajax=false) {
	$params = null;
	$subjects = "";
	$sort = "modified";
	$count = "5";
	$resource_posts = array();
	if ($ajax){
		$params = $request_data;
		$subjects = $params['selectedSubjects'];
		$sort = $params['sort'];
		$count = $params['displayCount'];
	} else {
		$params = $request_data->get_params();
		$subjects = $params['subjects'];
		$sort = $params['sort'];
		$count = $params['count'];
	}

	$args = array(
			'post_type' 		=> 'resource',
			'post_status'		=> 'publish',
			'posts_per_page'	=> $count,
			'orderby'			=> $sort,
			'order'				=> 'asc'
			);
	if ($subjects!=="undefined" && !empty($subjects)){
		$subs = explode(",",$subjects);
		$args['tax_query'] = array( 'relation'=> 'OR', array('taxonomy' => 'resource-subject-area', 'field'=>'term_id', 'terms' => $subs, 'operator' => 'IN') );
	} 
	$resources = new WP_Query($args);
	if (count($resources->posts)>0){
		$resource_posts = [];
		foreach($resources->posts as $resource){
			$featured_image_id = get_post_thumbnail_id($resource->ID);
			$resource->link = get_permalink($resource->ID);
			$resource->oer_resourceurl = get_post_meta($resource->ID, "oer_resourceurl", true);
			$resource->fimg_url = wp_oer_get_resource_featured_image($featured_image_id);
			$resource->resource_excerpt = wp_oer_get_resource_excerpt($resource->post_content);
			$resource->domain = wp_oer_get_resource_domain($resource->ID);
			$resource->oer_grade = wp_oer_get_resource_grade($resource->ID);
			$resource->subject_details = wp_oer_get_resource_subjects($resource->ID);
			$resource->subjects = $subjects;
			$resource_posts[] = $resource;
		}
	} 
	return $resource_posts;
}

function wp_oer_get_resource_featured_image($resource_id){
	$new_image_url="";
	if( $resource_id ){
		$img = wp_get_attachment_image_src( $resource_id, 'app-thumb' );
		$new_image_url = oer_resize_image( $img[0], 220, 180, true );
	} else {
		$img = OER_URL.'images/default-icon.png';
		$new_image_url = oer_resize_image( $img, 220, 180, true );
	}
	return $new_image_url;
}

function wp_oer_get_resource_excerpt($resource_content) {
	return wp_kses_post( wp_trim_words($resource_content, 45) );
}

function wp_oer_get_resource_domain($resource_id) {
	$url = get_post_meta($resource->ID, "oer_resourceurl", true);
	$url_domain = oer_getDomainFromUrl($url);
	if (oer_isExternalUrl($url)) {
		return  $url_domain;
	}
	return null;
}

function wp_oer_get_resource_grade($resource_id){
	$grades = trim(get_post_meta($resource_id, "oer_grade", true),",");
	$grades = explode(",",$grades);

	return oer_grade_levels($grades);
}

function wp_oer_get_resource_subjects($resource_id){
	$subject_details = null;
	$rsubjects = get_the_terms($resource_id,"resource-subject-area");
	foreach($rsubjects as $rsubject) {
		$subject_details[] = array("id" => $rsubject->term_id, "link" => get_term_link($rsubject->term_id), "name" => $rsubject->name);
	}
	return $subject_details;
}

function wp_oer_get_subject_resources($args){
	if (!empty($args))
		extract($args);
	
	$sort_display = "Date Updated";
	switch($sort){
		case "modified":
			$sort_display = 'Date Updated';
			break;
		case "date":
			$sort_display = 'Date Added';
			break;
		case "title":
			$sort_display = 'Title A-Z';
			break;
	}

	if (is_array($selectedSubjects))
		$selectedSubjects = implode(",", $selectedSubjects);
	$heading = '<div class="oer-snglrsrchdng" data-sort="'.$sort.'" data-count="'.$displayCount.'" data-subjects="'.$selectedSubjects.'">';
	$heading .= '	Browse '.$displayCount.' resources';
	$heading .= '	<div class="sort-box">';
	$heading .= '		<span class="sortoption-label">Sorted by</span>: <span class="sortoption">'.$sort_display.'</span>';
	$heading .= '		<span class="sort-resources" title="Sort resources" tabindex="0" role="button"><i class="fa fa-sort" aria-hidden="true"></i></span>';
	$heading .= '		<div class="sort-options">';
	$heading .= '			<ul class="sortList">';
	$heading .= '				<li value="modified" '.($sort=='modified'?'class="selected"':'').'>Date Updated</li>';
	$heading .= '				<li value="date" '.($sort=='date'?'class="selected"':'').'>Date Added</li>';
	$heading .= '				<li value="title" '.($sort=='title'?'class="selected"':'').'>Title A-Z</li>';
	$heading .= '			</ul>';
	$heading .= '		</div>';
	$heading .= '		<div class="components-base-control sort-selectbox">';
	$heading .= '			<div class="components-base-control__field">';
	$heading .= '				<select id="inspector-select-control-1" class="components-select-control__input">';
	$heading .= '					<option value="modified" '.selected($sort,'modified',false).'>Date Updated</option>';
	$heading .= '					<option value="date" '.selected($sort,'date',false).'>Date Added</option>';
	$heading .= '					<option value="title" '.selected($sort,'title',false).'>Title A-Z</option>';
	$heading .= '				</select>';
	$heading .= '			</div>';
	$heading .= '		</div>';
	$heading .= '	</div>';
	$heading .= '</div>';

	//$html = '<div class="oer-subject-resources-list">';
	$html .= $heading;

	$resources = wp_oer_get_resources($args,true);
	
	if (is_array($resources)){
		foreach ($resources as $resource){
			$html .= '<div class="post oer-snglrsrc">';
			$html .= '	<a href="'.esc_url($resource->link).'" class="oer-resource-link">';
			$html .= '		<div class="oer-snglimglft">';
			$html .= '			<img src="'.esc_url($resource->fimg_url).'">';
			$html .= '		</div>';
			$html .= '	</a>';
			$html .= '	<div class="oer-snglttldscrght">';
			$html .= '		<div class="ttl">';
			$html .= '			<a href="'.esc_url($resource->link).'">'.$resource->post_title.'</a>';
			$html .= '		</div>';
			$html .= '		<div class="post-meta">';
			$html .= '			<span class="post-meta-box post-meta-grades">';
			$html .= '				<strong>Grades: </strong>'.$resource->oer_grade;
			$html .= '			</span>';
			$html .= '			<span class="post-meta-box post-meta-domain">';
			$html .= '				<strong>Domain: </strong><a href="'.esc_url($resource->oer_resourceurl).'">'.$resource->domain.'</a>';
			$html .= '			</span>';
			$html .= '		</div>';
			$html .= '		<div class="desc">';
			$html .= '			<div>';
			$html .=  				$resource->resource_excerpt;
			$html .= '			</div>';
			$html .= '		</div>';
			$html .= '		<div class="tagcloud">';
			if (is_array($resource->subject_details)){
				foreach($resource->subject_details as $subj){
					$html .= '			<span><a href="'.esc_url($subj['link']).'">'.$subj['name'].'</a></span>';
				}
			}
			$html .= '		</div>';
			$html .= '	</div>';
			$html .= '</div>';
		}
	}
	//$html .= '</div>';

	return $html;
}
function wp_oer_ajax_get_subject_resources(){
	$resources = wp_oer_get_subject_resources($_POST);
	echo $resources;
	die();
}
add_action( 'wp_ajax_get_subject_resources', 'wp_oer_ajax_get_subject_resources' );
add_action( 'wp_ajax_nopriv_get_subject_resources', 'wp_oer_ajax_get_subject_resources' );

/*
* Add OER Block Category
*/
function wp_oer_block_category( $categories ) {
	$category_slugs = wp_list_pluck( $categories, 'slug' );
	return in_array( 'oer-block-category', $category_slugs, true ) ? $categories : array_merge(
        array(
            array(
				'slug' => 'oer-block-category',
				'title' => __( 'OER Blocks', 'oer-block-category' ),
			),
        ),
        $categories
    );
}
add_filter( 'block_categories_all', 'wp_oer_block_category', 10, 2);