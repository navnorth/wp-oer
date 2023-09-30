<?php
/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */
function wp_oer_subject_resources_block_init() {
    $dir = dirname(__FILE__);
    $version_58 = is_version_58();

	$script_asset_path = "$dir/build/index.asset.php";
    if ( ! file_exists( $script_asset_path ) ) {
        throw new Error(
            'You need to run `npm start` or `npm run build` for the "create-block/oer-object-resources-block" block first.'
        );
    }
    $index_js     = 'build/index.js';
    $script_asset = require( $script_asset_path );
    wp_register_script(
        'oer-subject-resources-block-editor',
        plugins_url( $index_js, __FILE__ ),
        $script_asset['dependencies'],
        $script_asset['version']
    );
    //$admin_ajax_url = oer_is_subject_ajax_url_accessible(admin_url('admin-ajax.php'))?admin_url('admin-ajax.php'):OER_URL.'ajax.php';
    $admin_ajax_url = OER_URL.'ajax.php';
    wp_localize_script( 'oer-subject-resources-block-editor', 'oer_subject_resources', array( 'home_url' => home_url(), 'ajax_url' => $admin_ajax_url, 'version_58' => $version_58 ) );
    wp_set_script_translations('oer-subject-resources-block-editor', 'oer-subject-resources-block', OER_PATH . '/lang/js');

    $front_asset_path = "$dir/build/front.asset.php";
    if ( ! file_exists( $front_asset_path ) ) {
        throw new Error(
            'You need to run `npm start` or `npm run build` for the "create-block/oer-object-resources-block" block first.'
        );
    }

    $frontend_js = 'build/front.js';
    $front_asset = require( $front_asset_path );
    wp_register_script(
        'oer-subject-resources-block-frontend',
        plugins_url( $frontend_js, __FILE__ ),
        $front_asset['dependencies'],
        $front_asset['version']
    );
    wp_localize_script( 'oer-subject-resources-block-frontend', 'wp_oer_block', array( 'ajax_url' => $admin_ajax_url ) );
    wp_set_script_translations('oer-subject-resources-block-frontend', 'oer-subject-resources-block', OER_PATH . '/lang/js');

    $editor_css = 'build/index.css';
    wp_register_style(
        'oer-subject-resources-block-editor',
        plugins_url( $editor_css, __FILE__ ),
        array(),
        filemtime( "$dir/$editor_css" )
    );

    $style_css = 'build/style-index.css';
    wp_register_style(
        'oer-subject-resources-block-css',
        plugins_url( $style_css, __FILE__ ),
        array(),
        filemtime( "$dir/$style_css" )
    );

    register_block_type( 'oer-block/subject-resources-block', array(
        'editor_script' => 'oer-subject-resources-block-editor',
        'editor_style'  => 'oer-subject-resources-block-editor',
        'script'        => 'oer-subject-resources-block-frontend',
        'style'         => 'oer-subject-resources-block-css',
        'render_callback' => 'oer_display_subject_resources_block'
    ) );
}

/** Checks if AJAX url is accessible **/
function oer_is_subject_ajax_url_accessible($url){
    $headers = @get_headers($url);
    if($headers && strpos( $headers[0], '200')) {
        return true;
    } else {
        return false;
    }
}

function wp_oer_subject_resources_block_json_init() {
    wp_enqueue_script("wp-api");
    $dir = dirname(__FILE__);
    $version_58 = is_version_58();

    $script_asset_path = "$dir/build/index.asset.php";
    if ( ! file_exists( $script_asset_path ) ) {
        throw new Error(
            'You need to run `npm start` or `npm run build` for the "create-block/oer-object-resources-block" block first.'
        );
    }
    $index_js     = 'build/index.js';
    $script_asset = require( $script_asset_path );
    wp_register_script(
        'oer-subject-resources-block-editor',
        plugins_url( $index_js, __FILE__ ),
        $script_asset['dependencies'],
        $script_asset['version']
    );
    //$admin_ajax_url = oer_is_subject_ajax_url_accessible(admin_url('admin-ajax.php'))?admin_url('admin-ajax.php'):OER_URL.'ajax.php';
    $admin_ajax_url = OER_URL.'ajax.php';
    wp_localize_script( 'oer-subject-resources-block-editor', 'oer_subject_resources', array( 'home_url' => home_url(), 'ajax_url' => $admin_ajax_url, 'version_58' => $version_58 ) );
    wp_set_script_translations('oer-subject-resources-block-editor', 'oer-subject-resources-block', OER_PATH . '/lang/js');

    $front_asset_path = "$dir/build/front.asset.php";
    if ( ! file_exists( $front_asset_path ) ) {
        throw new Error(
            'You need to run `npm start` or `npm run build` for the "create-block/oer-object-resources-block" block first.'
        );
    }

    $frontend_js = 'build/front.js';
    $front_asset = require( $front_asset_path );
    wp_register_script(
        'oer-subject-resources-block-frontend',
        plugins_url( $frontend_js, __FILE__ ),
        $front_asset['dependencies'],
        $front_asset['version']
    );
    wp_localize_script( 'oer-subject-resources-block-frontend', 'wp_oer_block', array( 'ajax_url' => $admin_ajax_url ) );
    wp_set_script_translations('oer-subject-resources-block-frontend', 'oer-subject-resources-block', OER_PATH . '/lang/js');

    register_block_type(
        __DIR__,
        array(
            'editor_script' => 'oer-subject-resources-block-editor',
            'script'        => 'oer-subject-resources-block-frontend',
            'render_callback' => 'oer_display_subject_resources_block',
        )
    );
}

if ( version_compare( $GLOBALS['wp_version'], '5.8-alpha-1', '<' ) ) {
    add_action( 'init', 'wp_oer_subject_resources_block_init' );
} else {
    add_action( 'init', 'wp_oer_subject_resources_block_json_init' );
}

function is_version_58(){
    if ( version_compare( $GLOBALS['wp_version'], '5.8-alpha-1', '<' ) ) {
        return false;
    } else {
        return true;
    }
}

function oer_register_subject_resources_api_routes(){
    register_rest_route(
        'oer/v2',
        'subjects',
        array(  'methods'=>'GET',
            'callback'=>'oer_srb_get_subject_areas',
            'permission_callback' => function(){
                return current_user_can('edit_posts');
            }
        )
    );
    register_rest_route(
        'oer/v2',
        'resources',
        array(  'methods'=>'GET',
            'callback'=>'oer_srb_get_resources',
            'permission_callback' => function(){
                return current_user_can('edit_posts');
            }
        )
    );
}
add_action( 'rest_api_init' , 'oer_register_subject_resources_api_routes' );

function oer_display_subject_resources_block( $attributes , $ajax = false){
    $selectedSubjects  = [];
    $html = "";
    $sort_display = "Date Updated";
    $sort = "modified";
    $displayOptions = [ 5,10,15,20,25,30 ];
    $displaySelection = "";

    if (!empty($attributes))
        extract($attributes);
    
    if (!isset($displayCount)){
        $displayCount = "5";
    }

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

    // Browse # of resources
    $displaySelection .='<div class="count-option">';
    $displaySelection .= '<span class="countoption">'.$displayCount.'</span>';
    $displaySelection .= '<span class="resource-count" title="Display resource count" tabindex="0" role="button"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
    $displaySelection .= '<div class="count-options"><ul class="countList">';
    foreach($displayOptions as $option){
        $displaySelection .= '<li value="'.$option.'" '.($option==$displayCount?'class="selected"':'').'>'.$option.'</li>';
    }
    $displaySelection .= '</ul></div>';
    $displaySelection .= '       <div class="components-base-control count-selectbox">';
    $displaySelection .= '           <div class="components-base-control__field">';
    $displaySelection .= '               <select id="inspector-select-control-count" class="components-select-control__input">';
    foreach($displayOptions as $option){
        $displaySelection .= '                   <option value="'.$option.'" '.selected($displayCount,$option,false).'>'.$option.'</option>';
    }
    $displaySelection .= '               </select>';
    $displaySelection .= '           </div>';
    $displaySelection .= '       </div>';
    $displaySelection .= '</div>';
    
    if (isset($selectedSubjects) && is_array($selectedSubjects))
        $selectedSubjects = implode(",", $selectedSubjects);
    
    $heading = '<div class="oer-snglrsrchdng" data-sort="'.$sort.'" data-count="'.$displayCount.'" data-subjects="'.$selectedSubjects.'">';
    $heading .= '   <div class="browse-box">'.sprintf(__('Browse %s resources', OER_SLUG), $displaySelection).'</div>';
    $heading .= '   <div class="sort-box">';
    $heading .= '       <span class="sortoption-label">'.__('Sorted by:',OER_SLUG).'</span> <span class="sortoption">'.__($sort_display,OER_SLUG).'</span>';
    $heading .= '       <span class="sort-resources" title="Sort resources" tabindex="0" role="button"><i class="fa fa-sort" aria-hidden="true"></i></span>';
    $heading .= '       <div class="sort-options">';
    $heading .= '           <ul class="sortList">';
    $heading .= '               <li value="modified" '.($sort=='modified'?'class="selected"':'').'>'.__('Date Updated',OER_SLUG).'</li>';
    $heading .= '               <li value="date" '.($sort=='date'?'class="selected"':'').'>'.__('Date Added',OER_SLUG).'</li>';
    $heading .= '               <li value="title" '.($sort=='title'?'class="selected"':'').'>'.__('Title A-Z',OER_SLUG).'</li>';
    $heading .= '           </ul>';
    $heading .= '       </div>';
    $heading .= '       <div class="components-base-control sort-selectbox">';
    $heading .= '           <div class="components-base-control__field">';
    $heading .= '               <select id="inspector-select-control-1" class="components-select-control__input">';
    $heading .= '                   <option value="modified" '.selected($sort,'modified',false).'>'.__('Date Updated','oer-subject-resources-block').'</option>';
    $heading .= '                   <option value="date" '.selected($sort,'date',false).'>'.__('Date Added','oer-subject-resources-block').'</option>';
    $heading .= '                   <option value="title" '.selected($sort,'title',false).'>'.__('Title A-Z','oer-subject-resources-block').'</option>';
    $heading .= '               </select>';
    $heading .= '           </div>';
    $heading .= '       </div>';
    $heading .= '   </div>';
    $heading .= '</div>';

    $html = '<div class="oer-subject-resources-list">';
    $html .= $heading;
    
    $attributes['sort'] = $sort;
    $attributes['displayCount'] = $displayCount;
    $attributes['selectedSubjects'] = $selectedSubjects;

    if (empty($selectedSubjectResources))
        $selectedSubjectResources = oer_srb_get_resources($attributes,true);
    if (is_array($selectedSubjectResources)){
        $selectedSubjectResources = (object)$selectedSubjectResources;
        if (!empty((array)$selectedSubjectResources)){
            foreach ($selectedSubjectResources as $subject){
                $html .= '<div class="post oer-snglrsrc">';
                $html .= '  <a href="'.esc_url((is_object($subject))?$subject->link:$subject['link']).'" class="oer-resource-link">';
                $html .= '      <div class="oer-snglimglft">';
                $html .= '          <img src="'.esc_url((is_object($subject))?$subject->fimg_url:$subject['fimg_url']).'" alt="'.((is_object($subject))?$subject->post_title:$subject['title']['rendered']).'">';
                $html .= '      </div>';
                $html .= '  </a>';
                $html .= '  <div class="oer-snglttldscrght">';
                $html .= '      <div class="ttl">';
                $html .= '          <a href="'.esc_url((is_object($subject))?$subject->link:$subject['link']).'">'.((is_object($subject))?$subject->post_title:$subject['title']['rendered']).'</a>';
                $html .= '      </div>';
                $html .= '      <div class="post-meta">';
                $html .= '          <span class="post-meta-box post-meta-grades">';
                $html .= '              <strong>Grades: </strong>'.((is_object($subject))?$subject->oer_grade:$subject['oer_grade']);
                $html .= '          </span>';
                $subject_domain = (is_object($subject)?$subject->domain:$subject['domain']);
                if ($subject_domain){
                    $html .= '          <span class="post-meta-box post-meta-domain">';
                    $html .= '              <strong>Domain: </strong><a href="'.esc_url((is_object($subject))?$subject->oer_resourceurl:$subject['oer_resourceurl']).'">'.$subject_domain.'</a>';
                    $html .= '          </span>';
                }
                $html .= '      </div>';
                $html .= '      <div class="desc">';
                $html .= '          <div>';
                $html .=  (is_object($subject))?$subject->resource_excerpt:$subject['resource_excerpt'];
                $html .= '          </div>';
                $html .= '      </div>';
                $html .= '      <div class="tagcloud">';
                $subject_details = (is_object($subject)?$subject->subject_details:$subject['subject_details']);
                if (is_array($subject_details)){
                    foreach($subject_details as $subj){
                        $html .= '          <span><a href="'.esc_url($subj['link']).'">'.$subj['name'].'</a></span>';
                    }
                }
                $html .= '      </div>';
                $html .= '  </div>';
                $html .= '</div>';
            }
        } else {
            $html .= '<div class="empyt-resources">'.__('No resources found','oer-subject-resources-block').'</div>';
        }
    }
    $html .= '</div>';
    
    return $html;
}

function oer_srb_get_subject_areas() {
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

function oer_srb_get_resources($request_data, $ajax=false) {
    $params = null;
    $subs = null;
    $subjects = "";
    $sort = "modified";
    $count = "5";
    $resource_posts = array();
    if ($ajax){
        $params = $request_data;
        if (isset($params['selectedSubjects']))
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
            'post_type'         => 'resource',
            'post_status'       => 'publish',
            'posts_per_page'    => $count,
            'orderby'           => $sort,
            'order'             => 'asc'
            );
    if ($subjects!=="undefined" && !empty($subjects)){
        if (!is_array($subjects))
            $subs = explode(",",$subjects);
        else 
            $subs = $subjects;
        $args['tax_query'] = array( 'relation'=> 'OR', array('taxonomy' => 'resource-subject-area', 'field'=>'term_id', 'terms' => $subs, 'operator' => 'IN') );
    } 
    $resources = new WP_Query($args);
    
    if (count($resources->posts)>0){
        $resource_posts = [];
        foreach($resources->posts as $resource){
            $featured_image_id = get_post_thumbnail_id($resource->ID);
            $resource->link = get_permalink($resource->ID);
            $resource->oer_resourceurl = get_post_meta($resource->ID, "oer_resourceurl", true);
            $resource->fimg_url = oer_get_resource_featured_image($featured_image_id);
            $resource->resource_excerpt = oer_get_resource_excerpt($resource->post_content);
            $resource->domain = oer_get_resource_domain($resource->ID);
            $resource->oer_grade = oer_get_resource_grade($resource->ID);
            $resource->subject_details = oer_get_resource_subjects($resource->ID);
            $resource->subjects = $subjects;
            $resource_posts[] = $resource;
        }
    } 
    return $resource_posts;
}

function oer_get_resource_featured_image($resource_id){
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

function oer_get_resource_excerpt($resource_content) {
    return wp_kses_post( wp_trim_words($resource_content, 45) );
}

function oer_get_resource_domain($resource_id) {
    $url = get_post_meta($resource_id, "oer_resourceurl", true);
    $url_domain = oer_getDomainFromUrl($url);
    if (oer_isExternalUrl($url)) {
        return  $url_domain;
    }
    return null;
}

function oer_get_resource_grade($resource_id){
    $grades = trim(get_post_meta($resource_id, "oer_grade", true),",");
    $grades = explode(",",$grades);

    if (empty($grades)){
        $grade_terms = get_the_terms( $resource_id, 'resource-grade-level' );
        
        if (is_array($grade_terms)){
            foreach($grade_terms as $grade){
                $grades[] = $grade->slug;
            }
        }
    }

    return oer_grade_levels($grades);
}

function oer_get_resource_subjects($resource_id){
    $subject_details = null;
    $rsubjects = get_the_terms($resource_id,"resource-subject-area");
    if (is_array($rsubjects)){    
        foreach($rsubjects as $rsubject) {
            $subject_details[] = array("id" => $rsubject->term_id, "link" => get_term_link($rsubject->term_id), "name" => $rsubject->name);
        }
    }

    return $subject_details;
}

function oer_get_subject_resources($args, $ajax=false){
    $html = "";
    $sort_display = "Date Updated";
    $sort = "modified";
    $selectedSubjects  = [];
    $displayCount = 5;
    $displayOptions = [ 5,10,15,20,25,30 ];
    $displaySelection = "";

    if (!empty($args)){
        if (!$ajax)
            extract($args);
        else{
            if (isset($args['attributes'])){
                $displayCount = $args['attributes']['displayCount'];
                $sort = $args['attributes']['sort'];
                if (isset($args['attributes']['selectedSubjects']))
                    $selectedSubjects = $args['attributes']['selectedSubjects'];
            } else {
                $displayCount = $args['displayCount'];
                $sort = $args['sort'];
                $selectedSubjects = $args['selectedSubjects'];
            }
        }
    }
    
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
    
    // Browse # of resources
    $displaySelection .='<div class="count-option">';
    $displaySelection .= '<span class="countoption">'.$displayCount.'</span>';
    $displaySelection .= '<span class="resource-count" title="Display resource count" tabindex="0" role="button"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
    $displaySelection .= '<div class="count-options"><ul class="countList">';
    foreach($displayOptions as $option){
        $displaySelection .= '<li value="'.$option.'" '.($option==$displayCount?'class="selected"':'').'>'.$option.'</li>';
    }
    $displaySelection .= '</ul></div>';
    $displaySelection .= '       <div class="components-base-control count-selectbox">';
    $displaySelection .= '           <div class="components-base-control__field">';
    $displaySelection .= '               <select id="inspector-select-control-count" class="components-select-control__input">';
    foreach($displayOptions as $option){
        $displaySelection .= '                   <option value="'.$option.'" '.selected($displayCount,$option,false).'>'.$option.'</option>';
    }
    $displaySelection .= '               </select>';
    $displaySelection .= '           </div>';
    $displaySelection .= '       </div>';
    $displaySelection .= '</div>';

    if (is_array($selectedSubjects))
        $selectedSubjects = implode(",", $selectedSubjects);
    $heading = '<div class="oer-snglrsrchdng" data-sort="'.$sort.'" data-count="'.$displayCount.'" data-subjects="'.$selectedSubjects.'">';
    $heading .= '   <div class="browse-box">'.sprintf(__('Browse %s resources', OER_SLUG), $displaySelection).'</div>';
    $heading .= '   <div class="sort-box">';
    $heading .= '       <span class="sortoption-label">'.__('Sorted by:',OER_SLUG).'</span> <span class="sortoption">'.__($sort_display,OER_SLUG).'</span>';
    $heading .= '       <span class="sort-resources" title="Sort resources" tabindex="0" role="button"><i class="fa fa-sort" aria-hidden="true"></i></span>';
    $heading .= '       <div class="sort-options">';
    $heading .= '           <ul class="sortList">';
    $heading .= '               <li value="modified" '.($sort=='modified'?'class="selected"':'').'>'.__('Date Updated',OER_SLUG).'</li>';
    $heading .= '               <li value="date" '.($sort=='date'?'class="selected"':'').'>'.__('Date Added',OER_SLUG).'</li>';
    $heading .= '               <li value="title" '.($sort=='title'?'class="selected"':'').'>'.__('Title A-Z',OER_SLUG).'</li>';
    $heading .= '           </ul>';
    $heading .= '       </div>';
    $heading .= '       <div class="components-base-control sort-selectbox">';
    $heading .= '           <div class="components-base-control__field">';
    $heading .= '               <select id="inspector-select-control-1" class="components-select-control__input">';
    $heading .= '                   <option value="modified" '.selected($sort,'modified',false).'>'.__('Date Updated',OER_SLUG).'</option>';
    $heading .= '                   <option value="date" '.selected($sort,'date',false).'>'.__('Date Added',OER_SLUG).'</option>';
    $heading .= '                   <option value="title" '.selected($sort,'title',false).'>'.__('Title A-Z',OER_SLUG).'</option>';
    $heading .= '               </select>';
    $heading .= '           </div>';
    $heading .= '       </div>';
    $heading .= '   </div>';
    $heading .= '</div>';

    //$html = '<div class="oer-subject-resources-list">';
    $html .= $heading;

    if ($ajax && isset($args['attributes']))
        $args = $args['attributes'];
    $resources = oer_srb_get_resources($args,true);
    
    if (is_array($resources)){
        foreach ($resources as $resource){
            $html .= '<div class="post oer-snglrsrc">';
            $html .= '  <a href="'.esc_url($resource->link).'" class="oer-resource-link">';
            $html .= '      <div class="oer-snglimglft">';
            $html .= '          <img src="'.esc_url($resource->fimg_url).'">';
            $html .= '      </div>';
            $html .= '  </a>';
            $html .= '  <div class="oer-snglttldscrght">';
            $html .= '      <div class="ttl">';
            $html .= '          <a href="'.esc_url($resource->link).'">'.esc_html($resource->post_title).'</a>';
            $html .= '      </div>';
            $html .= '      <div class="post-meta">';
            $html .= '          <span class="post-meta-box post-meta-grades">';
            $html .= '              <strong>Grades: </strong>'.$resource->oer_grade;
            $html .= '          </span>';
            $html .= '          <span class="post-meta-box post-meta-domain">';
            $html .= '              <strong>Domain: </strong><a href="'.esc_url($resource->oer_resourceurl).'">'.$resource->domain.'</a>';
            $html .= '          </span>';
            $html .= '      </div>';
            $html .= '      <div class="desc">';
            $html .= '          <div>';
            $html .=                $resource->resource_excerpt;
            $html .= '          </div>';
            $html .= '      </div>';
            $html .= '      <div class="tagcloud">';
            if (is_array($resource->subject_details)){
                foreach($resource->subject_details as $subj){
                    $html .= '          <span><a href="'.esc_url($subj['link']).'">'.esc_html($subj['name']).'</a></span>';
                }
            }
            $html .= '      </div>';
            $html .= '  </div>';
            $html .= '</div>';
        }
    }
    //$html .= '</div>';

    return $html;
}

function oer_ajax_get_subject_resources(){
    $allowed_tags = oer_allowed_html();

    // Sanitize POST parameters
    $params = array();
    $params['action'] = sanitize_text_field($_POST['action']);
    if (isset($_POST['attributes']))
        $params['attributes'] = $_POST['attributes'];
    else
        $params['attributes'] = $_POST;

    array_walk($params['attributes'], function(&$value, $key){
        $value = sanitize_text_field($value);
    });
    
    $resources = oer_get_subject_resources($params, true);
    echo wp_kses($resources,$allowed_tags);
    die();
}
add_action( 'wp_ajax_oer_get_subject_resources', 'oer_ajax_get_subject_resources' );
add_action( 'wp_ajax_nopriv_oer_get_subject_resources', 'oer_ajax_get_subject_resources' );

/*
* Add OER Block Category
*/
if (!function_exists('wp_oer_block_category')) {
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

  // Supporting older version of Wordpress - WP_Block_Editor_Context is only introduced in WP 5.8
  if ( class_exists( 'WP_Block_Editor_Context' ) ) {
      add_filter( 'block_categories_all', 'wp_oer_block_category', 10, 2);
  } else {
      add_filter( 'block_categories', 'wp_oer_block_category', 10, 2);
  }
}