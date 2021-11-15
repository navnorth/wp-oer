<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Contains all shortcodes used by OER Plugin
 **/
add_shortcode( 'oer_subjects_index', 'oer_show_subjects' );
function oer_show_subjects($atts) {
    global $wpdb;
    //Default
    $column = " col-md-3";
    $show_children = false;
    $display_size = "oer-cat-div";
    $expand_element = "";
    $children = null;
    
    if ($atts)
	extract($atts);
    
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
	    'taxonomy'                 => 'resource-subject-area',
	    'pad_counts'               => false );
		    
    $categories = get_categories( $args );
    
    $content =  '<div class="oer-cntnr"><div class="oer-ctgry-cntnr row">';
		    $cnt = 1;
		    $lepcnt = 1;
		    
		    foreach($categories as $category)	
		    {
			    //$getimage = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.'postmeta'." WHERE meta_key='category_image' AND meta_value='$category->term_id'");
			    //$getimage_hover = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.'postmeta'." WHERE meta_key='category_image_hover' AND meta_value='$category->term_id'");
			    
			    // Updated code to get term meta
			    $getimage = get_term_meta($category->term_id,'mainIcon');
			    $getimage_hover = get_term_meta($category->term_id,'hoverIcon');
			    $icn_guid = "";
			    $icn_hover_guid = "";
			    
			    if(empty($getimage) && empty($getimage_hover)){
				    
				    $attach_icn = array();
				    $attach_icn_hover = array();
				    $icn_guid = oer_get_default_category_icon($category->name);
				    $icn_hover_guid = oer_get_default_category_icon($category->name, true);
				    
			    } else {
				    //Checks if icon is empty
				    if (!empty($getimage)) {
					    //$attach_icn = get_post($getimage[0]->post_id);
					    //$icn_guid = $attach_icn->guid;
					    $icn_guid = $getimage[0];
				    } else {
					    $icn_guid = oer_get_default_category_icon($category->name);
				    }
				    
				    if (!empty($getimage_hover)) {
					    //$attach_icn_hover = get_post($getimage_hover[0]->post_id);
					    //$icn_hover_guid = $attach_icn_hover->guid;
					    $icn_hover_guid = $getimage_hover[0];
				    } else {
					    $icn_hover_guid = oer_get_default_category_icon($category->name, true);
				    }
			    }
			    
			    $count = oer_get_post_count($category->term_id, "resource-subject-area");
			    $count = $count + $category->count;
			    
			    // Size attribute
			    if (isset($size)){
				$display_size = "oer-cat-div-".$size;
			    }
			    
			    // Columns attribute
			    if (isset($columns)) {
				$colnum = 3;
				switch ($columns){
				    case 1:
					$colnum = 12;
					break;
				    case 2:
					$colnum = 6;
					break;
				    case 3:
					$colnum = 4;
					break;
				    case 4:
				    default:
					break;
				}
				$column = " col-md-".$colnum;    
			    }
			    
			    $count_span = '<span>'. $count .'</span>';
			    
			    // Show Counts Attribute
			    if (isset($show_counts)){
				if ($show_counts=="no" || $show_counts=="false")
				    $count_span = "";
			    }
			    
			    $toggle_navigation = "";
			    //Sublevel attributes
			    if (isset($sublevels)){
				if ($sublevels=="yes" || $sublevels=="true"){
				    $show_children=true;
				    $toggle_navigation = ' onclick="togglenavigation(this);"';
				    $expand_element = '<span title="expand sublevels" class="oer-expand-subject"><i class="fas fa-expand"></i></span>';
				}
			    }

			    if ($show_children) {
				    $children = get_term_children($category->term_id, 'resource-subject-area');
				    if (empty($children))
				    	$toggle_navigation = '';
				}
			    
			    $content .= '<div class="oer_snglctwpr'.esc_attr($column).'"><div class="'.esc_attr($display_size).'" data-ownback="'.get_template_directory_uri().'/img/top-arrow.png" onMouseOver="changeonhover(this)" onMouseOut="changeonout(this);" '.$toggle_navigation.' data-id="'.esc_attr($cnt).'" data-class="'.esc_attr($lepcnt).'" data-normalimg="'.esc_url($icn_guid).'" data-hoverimg="'.esc_attr($icn_hover_guid).'" tabindex="0">
				    <div class="oer-cat-icn" style="background: url('.esc_url($icn_guid).') no-repeat scroll center center; "></div>
				    <div class="oer-cat-txt-btm-cntnr">
					    <ul>
						    <li>'.$expand_element.'<label class="oer-mne-sbjct-ttl" ><a href="'. esc_url(site_url() .'/resource-subject-area/'. $category->slug) .'">'. $category->name .'</a></label>'.$count_span.'</li>
					    </ul>
				    </div>';
			    
			    if( !empty( $children ) )
			    {
				    $content .= '<div class="oer-child-category">'. oer_front_child_category($category->term_id) .'</div>';
			    }
				
				
			    $content .= '</div>';
			    //if(($cnt % 4) == 0){
				    $content .= '<div class="oer_child_content_wpr" data-id="'.$lepcnt.'"></div>';
				    $lepcnt++;
			    //}
		    $cnt++;
		    $content .= '</div>';
		    
	    }
    $content .= '</div></div>';
    
    return $content;
}