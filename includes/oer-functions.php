<?php
function get_sub_standard($id, $oer_standard)
{
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "sub_standards where parent_id = %s" , $id ) ,ARRAY_A);
	if(!empty($oer_standard))
	{
		$stndrd_arr = explode(",",$oer_standard);
	}

	if(!empty($results))
	{
		echo "<ul>";
			foreach($results as $result)
			{
				$value = 'oer_sub_standards-'.$result['id'];
				if(!empty($stndrd_arr))
				{
					if(in_array($value, $stndrd_arr))
					{
						$chck = 'checked="checked"';
						$class = 'selected';
					}
					else
					{
						$chck = '';
						$class = '';
					}
				}

				echo "<li class='oer_sbstndard ". $class ."'>
						<div class='stndrd_ttl'>
							<img src='".OER_URL."images/closed_arrow.png' data-pluginpath='".OER_URL."' />
							<input type='checkbox' ".$chck." name='oer_standard[]' value='".$value."' onclick='oer_check_all(this)' >
							".$result['standard_title']."
						</div><div class='stndrd_desc'></div>";

						$id = 'sub_standards-'.$result['id'];
						get_sub_standard($id, $oer_standard);

						$sid = 'sub_standards-'.$result['id'];
						get_standard_notation($sid, $oer_standard);
				echo "</li>";
			}
		echo "</ul>";
	}
}

function get_standard_notation($id, $oer_standard)
{
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "standard_notation where parent_id = %s" , $id ) , ARRAY_A);

	if(!empty($oer_standard))
	{
		$stndrd_arr = explode(",",$oer_standard);
	}

	if(!empty($results))
	{
		echo "<ul>";
			foreach($results as $result)
			{
			  $id = 'standard_notation-'.$result['id'];
			  $child = check_child($id);
			  $value = 'oer_standard_notation-'.$result['id'];

			  if(!empty($oer_standard))
				{
					if(in_array($value, $stndrd_arr))
					{
						$chck = 'checked="checked"';
						$class = 'selected';
					}
					else
					{
						$chck = '';
						$class = '';
					}
				}

			  echo "<li class='".$class."'>
				   <div class='stndrd_ttl'>";

					if(!empty($child))
					{
						echo "<img src='".OER_URL."images/closed_arrow.png' data-pluginpath='".OER_URL."' />";
					}

			  echo "<input type='checkbox' ".$chck." name='oer_standard[]' value='".$value."' onclick='oer_check_myChild(this)'>
			 	   ". $result['standard_notation']."
				   </div>
				   <div class='stndrd_desc'> ". $result['description']." </div>";

				   get_standard_notation($id, $oer_standard);

				   echo "</li>";
			}
		echo "</ul>";
	}
}

function check_child($id)
{
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "standard_notation where parent_id = %s" , $id ) , ARRAY_A);
	return $results;
}

//Get Category Child for Sidebar
/*if (!function_exists('get_category_child')) {
	function get_category_child($categoryid)
	{
		$args = array('hide_empty' => 0, 'taxonomy' => 'resource-category','parent' => $categoryid);
		$catchilds = get_categories($args);
		$term = get_the_title();
		$rsltdata = get_term_by( "name", $term, "resource-category", ARRAY_A );
		$parentid = array();
		if($rsltdata['parent'] != 0)
		{
			$parent = get_parent_term($rsltdata['parent']);
			for($k=0; $k < count($parent); $k++)
			{
				$idObj = get_category_by_slug($parent[$k]);
				$parentid[] = $idObj->term_id;
			}
		}
	
		if(!empty($catchilds))
		{
			echo '<ul class="category">';
			foreach($catchilds as $catchild)
			{
				$children = get_term_children($catchild->term_id, 'resource-category');
				//current class
				if($rsltdata['term_id'] == $catchild->term_id)
				{
					$class = ' activelist current_class';
				}
				elseif(in_array($catchild->term_id, $parentid))
				{
					$class = ' activelist current_class';
				}
				else
				{
					$class = '';
				}
	
				if( !empty( $children ) )
				{
					echo '<li class="sub-category has-child'.$class.'" title="'. $catchild->name .'" >
							<span onclick="toggleparent(this);">
								<a href="'. site_url() .'/'. $catchild->slug .'">' . $catchild->name .'</a>
							</span>';
				}
				else
				{
					echo '<li class="sub-category'.$class.'" title="'. $catchild->name .'" >
							<span onclick="toggleparent(this);">
								<a href="'. site_url() .'/'. $catchild->slug .'">' . $catchild->name .'</a>
							</span>';
				}
				get_category_child( $catchild->term_id);
				echo '</li>';
			}
			echo '</ul>';
		}
	}
}

//GET Custom Texonomy Parent
if (!function_exists('get_custom_category_parents')) {
	function get_custom_category_parents( $id, $taxonomy = false, $link = false, $separator = '/', $nicename = false, $visited = array() ) {
	
		if(!($taxonomy && is_taxonomy_hierarchical( $taxonomy )))
			return '';
	
		$chain = '';
		// $parent = get_category( $id );
		$parent = get_term( $id, $taxonomy);
		if ( is_wp_error( $parent ) )
			return $parent;
	
		if ( $nicename )
			$name = $parent->slug;
		else
			$name = $parent->name;
	
		if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
			$visited[] = $parent->parent;
			// $chain .= get_category_parents( $parent->parent, $link, $separator, $nicename, $visited );
			$chain .= get_custom_category_parents( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
		}
	
		if ( $link ) {
			// $chain .= '<a href="' . esc_url( get_category_link( $parent->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $parent->name ) ) . '">'.$name.'</a>' . $separator;
			$chain .= '<a href="' . esc_url( get_term_link( (int) $parent->term_id, $taxonomy ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $parent->name ) ) . '">'.$name.'</a>' . $separator;
		} else {
			$chain .= $name.$separator;
		}
		return $chain;
	}
}

//Get Category Parent List
if (!function_exists('get_parent_term')) {
	function get_parent_term($id)
	{
		$curr_cat = get_category_parents($id, false, '/' ,true);
		$curr_cat = explode('/',$curr_cat);
	
		return $curr_cat;
	}
}

if (!function_exists('get_term_top_most_parent')) {
	function get_term_top_most_parent($term_id, $taxonomy="resource-category"){
	    // start from the current term
	    $parent  = get_term_by( 'id', $term_id, $taxonomy);
	    // climb up the hierarchy until we reach a term with parent = '0'
	    while ($parent->parent != '0'){
		$term_id = $parent->parent;
	
		$parent  = get_term_by( 'id', $term_id, $taxonomy);
	    }
	    return $parent;
	}
}*/

?>
