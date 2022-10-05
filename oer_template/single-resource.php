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

$youtube = oer_is_youtube_url($url);
$isSSLResource = oer_is_sll_resource($url);
$isSLLCollection = oer_is_sll_collection($url);
$isPDF = oer_is_pdf_resource($url);
$isExternal = oer_is_external_url($url);

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
$oer_sensitive_material = get_post_meta($post->ID, 'oer_sensitive_material', true);
$oer_resource_type = get_post_meta($post->ID, 'oer_mediatype', true);

$post_meta_data = get_post_meta($post->ID );
$author_set = (get_option('oer_authorname_label'))?true:false;
$author_enabled = (get_option('oer_authorname_enabled'))?true:false;
$standards_set = (get_option('oer_standard_label'))?true:false;
$standards_enabled = (get_option('oer_standard_enabled'))?true:false;
$oer_standard = get_post_meta($post->ID, 'oer_standard', true);
$age_levels_set = (get_option('oer_age_levels_label'))?true:false;
$age_levels_enabled = (get_option('oer_age_levels_enabled'))?true:false;
$suggested_time_set = (get_option('oer_instructional_time_label'))?true:false;
$suggested_time_enabled = (get_option('oer_instructional_time_enabled'))?true:false;
$cc_license_set = (get_option('oer_creativecommons_license_label'))?true:false;
$cc_license_enabled = (get_option('oer_creativecommons_license_enabled'))?true:false;
$external_repository_set = (get_option('oer_creativecommons_license_label'))?true:false;
$external_repository_enabled = (get_option('oer_creativecommons_license_enabled'))?true:false;
$repository_record_set = (get_option('oer_repository_recordurl_label'))?true:false;
$repository_record_enabled = (get_option('oer_repository_recordurl_enabled'))?true:false;
$citation_set = (get_option('oer_citation_label'))?true:false;
$citation_enabled = (get_option('oer_citation_enabled'))?true:false;
$transcription_set = (get_option('oer_transcription_label'))?true:false;
$transcription_enabled = (get_option('oer_transcription_enabled'))?true:false;
$sensitive_material_set = (get_option('oer_sensitive_material_label'))?true:false;
$sensitive_material_enabled = (get_option('oer_sensitive_material_enabled'))?true:false;

$display_see_more = false;

// Get Post Meta
$age_levels = (isset($post_meta_data['oer_age_levels'][0]) ? $post_meta_data['oer_age_levels'][0] : "");
$grades =  trim(get_post_meta($post->ID, "oer_grade", true),",");
$suggested_time = (isset($post_meta_data['oer_instructional_time'][0]) ? $post_meta_data['oer_instructional_time'][0] : "");
$cc_license = (isset($post_meta_data['oer_creativecommons_license'][0]) ? $post_meta_data['oer_creativecommons_license'][0] : "");
$external_repository = (isset($post_meta_data['oer_external_repository'][0]) ? $post_meta_data['oer_external_repository'][0] : "");
$repository_record = (isset($post_meta_data['oer_repository_recordurl'][0]) ? $post_meta_data['oer_repository_recordurl'][0] : "");
$citation = (isset($post_meta_data['oer_citation'][0]) ? $post_meta_data['oer_citation'][0] : "");
$transcription = (isset($post_meta_data['oer_transcription'][0]) ? $post_meta_data['oer_transcription'][0] : "");
$sensitive_material = (isset($post_meta_data['oer_sensitive_material'][0]) ? $post_meta_data['oer_sensitive_material'][0] : "");


/** Template Arguments for content-details partial template **/
$meta_args = array(
	'type' => $oer_resource_type,
	'age_levels' => $age_levels,
	'age_levels_set' => $age_levels_set,
	'age_levels_enabled' => $age_levels_enabled,
	'grades' => $grades,
	'suggested_time' => $suggested_time,
	'suggested_time_set' => $suggested_time_set,
	'suggested_time_enabled' => $suggested_time_enabled,
	'cc_license' => $cc_license,
	'cc_license_set' => $cc_license_set,
	'cc_license_enabled' => $cc_license_enabled
);

/** Template Arguments for content-meta partial template **/
$template_args = array(
    'type' => $oer_resource_type,
    'external_repository' => $external_repository,
    'external_repository_set' => $external_repository_set,
    'external_repository_enabled' => $external_repository_enabled,
    'repository_record' => $repository_record,
    'repository_record_set' => $repository_record_set,
    'repository_record_enabled' => $repository_record_enabled,
    'citation' => $citation,
    'citation_set' => $citation_set,
    'citation_enabled' => $citation_enabled,
    'transcription' => $transcription,
    'transcription_set' => $transcription_set,
    'transcription_enabled' => $transcription_enabled, 
    'sensitive_material' => $sensitive_material,
    'sensitive_material_set' => $sensitive_material_set,
    'sensitive_material_enabled' => $sensitive_material_enabled
);
?>
<!--<div id="primary" class="content-area">-->
    <main id="oer_main" class="site-main" role="main">
    <?php echo oer_breadcrumb_display(); ?>
    <article id="oer-resource-<?php the_ID(); ?>" class="oer_sngl_resource_wrapper post-content">
        <div id="sngl-resource" class="entry-content oer-cntnr post-content oer_sngl_resource_wrapper row">
	<?php //if (!$hide_title): ?>
        <header class="entry-header">
            <h1 class="entry-title col-md-8"><?php echo esc_html($post->post_title); ?></h1>
			<?php if (!empty($oer_sensitive_material)): ?>
			<span class="sensitive-resource col-md-4"><i class="fas fa-exclamation-triangle"></i> Potentially Sensitive Material</span>
			<?php endif; ?>
        </header>
	<?php //endif; ?>
    	
	<?php
	if ($youtube || $isSSLResource || $isSLLCollection)
		include(OER_PATH.'oer_template/single-resource-youtube.php');
	else {
        $resource_template = OER_PATH.'oer_template/single-resource-standard.php';
        switch($oer_resource_type) {
			case "website":
			case "document":
			case "other":
			case "image":
				$oer_type=oer_get_resource_file_type($url);
				if (is_array($oer_type)){
					if ($oer_type['name']=="PDF")
					    $resource_template = OER_PATH.'oer_template/single-resource-pdf.php';
					else
					    $resource_template = OER_PATH.'oer_template/single-resource-website.php';
				}
				break;
			case "audio":
				$oer_type=oer_get_resource_file_type($url);
				if ($oer_type['name']=="PDF")
					$resource_template = OER_PATH.'oer_template/single-resource-pdf.php';
				else {
					$file_type=oer_get_resource_file_type($url);
					if ($file_type['name']=="Audio")
						$resource_template = OER_PATH.'oer_template/single-resource-audio.php';
					else
						$resource_template = OER_PATH.'oer_template/single-resource-website.php';
				}
				break;
			case "video":
				$oer_type=oer_get_resource_file_type($url);
				if ($oer_type['name']=="PDF")
					$resource_template = OER_PATH.'oer_template/single-resource-pdf.php';
				elseif ($oer_type['name']=="Video") {
					if (oer_html_video_supported_format($url))
						$resource_template = OER_PATH.'oer_template/single-resource-video.php';
					else
						$resource_template = OER_PATH.'oer_template/single-resource-website.php';
				}
			    break;
			default:
			    break;
        };
        include($resource_template);
    }
    ?>

        </div><!-- .single resource wrapper -->

    </article>
	
	<!-- RELATED RESOURCES -->
	<?php include_once OER_PATH.'includes/related-resources.php';?>
</main>
<!--</div>-->


<?php
if ($theme == "Eleganto"){
	get_template_part( 'template-part', 'footernav' );
}

function oer_display_default_thumbnail($post){
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
function oer_get_embed_code_frame($url){
	$embed_code = '<iframe class="oer-pdf-viewer" width="100%" src="'.esc_url_raw($url).'"></iframe>';
	return $embed_code;
}

get_footer();
?>