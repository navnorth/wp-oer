<?php
/**
 * Contains all shortcodes used by OER Plugin
 **/
add_shortcode( 'oer_subjects_index', 'show_oer_subjects' );
function show_oer_subjects($atts) {
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
	
	$content =  '<div class="cntnr"><div class="ctgry-cntnr row">';
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
					
				$content .= '<div class="snglctwpr col-md-3"><div class="cat-div" data-ownback="'.get_template_directory_uri().'/img/top-arrow.png" onMouseOver="changeonhover(this)" onMouseOut="changeonout(this);" onclick="togglenavigation(this);" data-id="'.$cnt.'" data-class="'.$lepcnt.'" data-normalimg="'.$icn_guid.'" data-hoverimg="'.$icn_hover_guid.'">
					<div class="cat-icn" style="background: url('.$icn_guid.') no-repeat scroll center center; "></div>
					<div class="cat-txt-btm-cntnr">
						<ul>
							<li><label class="mne-sbjct-ttl" ><a href="'. site_url() .'/resource-category/'. $category->slug .'">'. $category->name .'</a></label><span>'. $count .'</span></li>
						</ul>
					</div>';
					
					$children = get_term_children($category->term_id, 'resource-category');
					if( !empty( $children ) )
					{
						$content .= '<div class="child-category">'. oer_front_child_category($category->term_id) .'</div>';
					}
				$content .= '</div>';
				//if(($cnt % 4) == 0){
					$content .= '<div class="child_content_wpr" data-id="'.$lepcnt.'"></div>';
					$lepcnt++;
				//}
			$cnt++;
			$content .= '</div>';
			
		}
	$content .= '</div></div>';
	
	return $content;
}