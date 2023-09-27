<?php
/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */
function oer_create_block_wp_oer_resource_block_init() {
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
        'wp-oer-resource-block-editor',
        plugins_url( $index_js, __FILE__ ),
        $script_asset['dependencies'],
        $script_asset['version']
    );
    //$admin_ajax_url = oer_is_resource_ajax_url_accessible(admin_url('admin-ajax.php'))?admin_url('admin-ajax.php'):OER_URL.'ajax.php';
    $admin_ajax_url = OER_URL.'ajax.php';
    wp_localize_script( 'wp-oer-resource-block-editor', 'oer_resource', array( 'home_url' => home_url(), 'ajaxurl' => $admin_ajax_url ) );
    wp_set_script_translations( 'wp-oer-resource-block-editor', 'wp-oer-resource-block', OER_PATH.'/lang/js' );

    register_block_type(
        __DIR__,
        array(
            'editor_script' => 'wp-oer-resource-block-editor',
            'render_callback' => 'oer_display_resource_block',
        )
    );
}
add_action( 'init', 'oer_create_block_wp_oer_resource_block_init' );

/** Checks if AJAX url is accessible **/
function oer_is_resource_ajax_url_accessible($url){
    $headers = @get_headers($url);
    if($headers && strpos( $headers[0], '200')) {
        return true;
    } else {
        return false;
    }
}

// Get Resource API to retrieve resources to add options to Resource Select Box
function oer_get_resources_api(){
    register_rest_route( 'oer-resource-block/v1', 'resources', array(
        'methods' => 'GET',
        'callback' => 'oer_get_resources_for_options',
        'permission_callback' => function(){
            return current_user_can('edit_posts');
        }
    ));
}
add_action( 'rest_api_init' , 'oer_get_resources_api' );

// Get Only ID, and Title of Resource
function oer_get_resources_for_options(){
    $resources = [];
    $args = array(
        'post_type' => 'resource',
        'posts_per_page' => -1
    );
    $query = new WP_Query( $args );

    foreach($query->posts as $resource){
        $rs = array();
        $rs['title'] = $resource->post_title;
        $rs['id'] = $resource->ID;
        $resources[] = $rs;
    }
    $response = new WP_REST_Response($resources, 200);
    $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
    return $response;
}

// Display OER Resource Block both preview and frontend display
function oer_display_resource_block( $attributes, $ajax = false ){
    $html = "";
    $selectedResource = "";
    $alignment = "";
    $width = "";
    $showThumbnail = false;
    $showTitle = false;
    $showDescription = false;
    $showSubjects = false;
    $showGrades = false;
    $withBorder = false;
    $className = "";
    $style = "";
    $empty = false;

    if (!empty($attributes))
        extract($attributes);

    if (!empty($blockWidth))
        $style.="width:".$blockWidth."px;";

    if (!empty($alignment))
        $style.="text-align:".$alignment.";";

    if ($withBorder==true)
        $style.="border:1px solid #cdcdcd;";
    
    if (!empty($style))
        $style ="style='".$style."'";

    if ($showThumbnail==false && $showTitle==false && $showDescription==false && $showSubjects==false && $showGrades==false && $withBorder==false)
        $empty = true; 

    if (!empty($selectedResource)){
        $resource = get_post($selectedResource);
        ob_start();
        ?>

        <div class="wp-block-wp-oer-resource-block" <?php echo esc_attr($style); ?>>
            <?php if ($empty): ?>
                <div class="oer-empty-block"><?php esc_html_e( 'Empty display. Please enable some options.' , 'wp-oer' ); ?></div> 
            <?php endif; ?>
            <?php if ($showTitle=="true"): ?>
            <h4><a href="<?php echo esc_url($resource->guid); ?>"><?php echo esc_html($resource->post_title); ?></a></h4>
            <?php endif; ?>

            <?php if ($showThumbnail=="true"):
            if (has_post_thumbnail($resource->ID)): 
                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($resource->ID));
            ?>
            <div class="oer-resource-block-featured-image">
                <a href="<?php echo esc_url_raw($resource->guid); ?>"><img src="<?php echo esc_url($featured_image[0]); ?>" alt="<?php echo esc_attr($resource->post_title); ?>"></a>
            </div>
            <?php endif;
            endif; ?>

            <?php if ($showDescription=="true"): ?>
            <div class="oer-resource-block-description">
                <p><?php echo oer_resource_content_excerpt($resource->post_content); ?></p>
            </div>
            <?php endif; ?>

            <?php if ($showSubjects=="true"): 
                $subjects = oer_resource_block_subjects($resource->ID);
            ?>
            <div class="oer-resource-block-subjects oer-rsrcctgries tagcloud">
                <?php if (count($subjects)>0): ?>
                    <ul>
                        <?php foreach($subjects as $subject): ?>
                            <li><a href="<?php echo esc_url($subject['term_link']); ?>"><?php echo esc_html($subject['name']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($showGrades=="true"): 
                $grade_levels = oer_resource_block_grade_levels($resource->ID);
                if ($grade_levels):
                ?>
                <div class="oer-resource-block-grade-levels">
                    <strong><?php esc_html_e('Grade Levels', OER_SLUG); ?>: </strong> <?php echo esc_html($grade_levels); ?>
                </div>
            <?php endif;
            endif; ?>
        </div>

        <?php
        $html = ob_get_contents();
        ob_end_clean();
    }
    return $html;
}

// Get Grade Levels
function oer_resource_block_grade_levels($resource_id){
    $grades = trim(get_post_meta($resource_id, "oer_grade", true),",");
    $grades = explode(",",$grades);

    return oer_grade_levels($grades);
}

// Get Resource Subjects
function oer_resource_block_subjects($resource_id){
    $subjects = array();
    $subj_arr = get_the_terms($resource_id, 'resource-subject-area');
    
    if (is_array($subj_arr)){
        foreach ($subj_arr as $subj){
            $subjects[] = array(
                            "term_id" => $subj->term_id,
                            "name" => $subj->name,
                            "term_link" => get_term_link($subj->term_id,'resource-subject-area')
                            );
        }
    }
    return $subjects;
}

function oer_ajax_display_resource_block(){
    $allowed_tags = oer_allowed_html();

    // Sanitize POST parameters
    $params = array();
    $params['selectedResource'] = sanitize_text_field($_POST['params']['selectedResource']);
    $params['alignment'] = sanitize_text_field($_POST['params']['alignment']);
    $params['showThumbnail'] = sanitize_text_field($_POST['params']['showThumbnail']);
    $params['showTitle'] = sanitize_text_field($_POST['params']['showTitle']);
    $params['showDescription'] = sanitize_text_field($_POST['params']['showDescription']);
    $params['showSubjects'] = sanitize_text_field($_POST['params']['showSubjects']);
    $params['showGrades'] = sanitize_text_field($_POST['params']['showGrades']);
    $params['withBorder'] = sanitize_text_field($_POST['params']['withBorder']);
    $params['blockId'] = sanitize_text_field($_POST['params']['blockId']);
    $params['firstLoad'] = sanitize_text_field($_POST['params']['firstLoad']);
    $params['isChanged'] = sanitize_text_field($_POST['params']['isChanged']);
    $params['resources'] = $_POST['params']['resources'];
    array_walk($params['resources'], function(&$value, &$key){
        $value['title'] = sanitize_text_field($value['title']);
        $value['id'] = sanitize_text_field($value['id']);
    });
    
    $resource = oer_display_resource_block($params, true);
    echo wp_kses($resource,$allowed_tags);
    die();
}
add_action( 'wp_ajax_oer_display_resource_block', 'oer_ajax_display_resource_block' );
add_action( 'wp_ajax_nopriv_oer_display_resource_block', 'oer_ajax_display_resource_block' );

function oer_resource_content_excerpt($content) {
    return wp_kses_post( wp_trim_words($content, 45) );
}
