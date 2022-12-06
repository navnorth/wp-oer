<?php
/**
 * The Template for displaying all single resource
 */

/** Add default stylesheet for Resource page **/
wp_register_style( "resource-styles", OER_URL . "css/resource-style.css" );
wp_enqueue_style( "resource-styles" );

get_header();

//Add this hack to display top nav and head section on Eleganto theme
$cur_theme = wp_get_theme();
$theme = $cur_theme->get('Name');
if ($theme == "Eleganto"){
    get_template_part( 'template-part', 'topnav' );
    get_template_part( 'template-part', 'head' );
}

global $post;
global $wpdb, $_oer_prefix;
global $_css_oer;
$allowed_tags = oer_allowed_html();

if ($_css_oer) {
$output = "<style>"."\n";
$output .= $_css_oer."\n";
$output .="</style>"."\n";
    echo wp_kses($output, $allowed_tags);
}

$url = get_post_meta($post->ID, "oer_resourceurl", true);
$url_domain = oer_getDomainFromUrl($url);

$hide_title = get_option('oer_hide_resource_title');

// Resource Subject Areas
$subject_areas = array();
$post_terms = get_the_terms( $post->ID, 'resource-subject-area' );

if(!empty($post_terms))
{
    $subjects = array();
    foreach($post_terms as $term)
    {
        if($term->parent != 0)
        {
            $parent[] = oer_get_parent_term_list($term->term_id);
            $subjects[] = $term;
        }
        else
        {
            $subject_areas[] = $term;
        }
    }
    
    if(!empty($parent) && array_filter($parent))
    {
        $recur_multi_dimen_arr_obj =  new RecursiveArrayIterator($parent);
        $recur_flat_arr_obj =  new RecursiveIteratorIterator($recur_multi_dimen_arr_obj);
        $flat_arr = iterator_to_array($recur_flat_arr_obj, false);

        $flat_arr = array_values(array_unique($flat_arr));
        
        for($k=0; $k < count($flat_arr); $k++)
        {
            //$idObj = get_category_by_slug($flat_arr[$k]);
            $idObj = get_term_by( 'slug' , $flat_arr[$k] , 'resource-subject-area' );
            
            if(!empty($idObj->name))
                $subject_areas[] = $idObj;
        }
    }
    if (count($subjects)>0)
        $subject_areas = array_merge($subject_areas,$subjects);
}
$embed_disabled = false;

// Resource Meta Data
$post_meta_data = get_post_meta($post->ID );

// Get Post Meta
$oer_resource_url = (isset($post_meta_data['oer_resourceurl'][0])?$post_meta_data['oer_resourceurl'][0]:false); 
?>
<main id="oer_main" class="site-main nalrc-main" role="main">
    <section id="sngl-resource" class="entry-content oer-cntnr post-content oer_sngl_resource_wrapper nalrc-resource-content row">
        <div class="nalrc-resource-row">
            <h1 class="entry-title col-md-12"><?php echo esc_html($post->post_title); ?></h1>
            <div class="nalrc-resource-desc nalrc-resource-value"><?php the_content(); ?></div>
        </div>
        <?php if ($oer_resource_url): ?>
        <div class="nalrc-resource-row">
            <label><?php _e('Resource Url', WP_OESE_THEME_SLUG); ?></label>
            <div class="nalrc-resource-url nalrc-resource-value"><a href="<?php echo esc_html($oer_resource_url); ?>"><?php echo esc_html($oer_resource_url); ?></a></div>
        </div>
        <?php endif; ?>
    </section><!-- .single resource wrapper -->
</main>


<?php
function oer_nalrc_display_default_thumbnail($post){
    $root_path = oer_get_root_path();
    
    $html = '<a class="oer-featureimg" href="'.esc_url(get_post_meta($post->ID, "oer_resourceurl", true)).'" target="_blank" >';
        $img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , "full" );
        if ($img_url){
            $img_path = $new_img_path = parse_url($img_url[0]);
            $img_path = sanitize_url($root_path . $img_path['path']);
            $new_image_url = OER_URL.'images/default-icon-528x455.png';
            $img_width = oer_get_image_width('large');
            $img_height = oer_get_image_height('large');
        }
        $media_type = get_post_meta($post->ID,"oer_mediatype")[0];
        
    if(!empty($img_url))
    {
        if ( is_wp_error($img_url) ) {
            oer_debug_log("Can't get Image editor to resize Resource screenshot.");
        } else {
            $new_image_url = oer_resize_image($img_url[0], $img_width, $img_height, true);
            $html .= '<img src="'.esc_url($new_image_url).'" alt="'.esc_attr(get_the_title()).'"/>';
        }
    }else{
    global $url; 
        $html .= '<span class="dashicons '.oer_getResourceIcon($media_type,$url).'"></span>';
    }
    
        
    $html .= '</a>';
    return $html;
}

get_footer();
?>