<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Get Sub Standard **/
function oer_get_sub_standard($id, $oer_standard)
{
	global $wpdb;
	global $chck, $class;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $id ) ,ARRAY_A);
	if(!empty($oer_standard))
	{
		$stndrd_arr = explode(",",$oer_standard);
	}

	if(!empty($results))
	{
		echo "<ul>";
			foreach($results as $result)
			{
				$value = 'sub_standards-'.$result['id'];
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

				$id = 'sub_standards-'.$result['id'];
				$subchildren = oer_get_substandard_children($id);
				$child = oer_check_child($id);

				echo "<li class='oer_sbstndard ". $class ."'>
						<div class='stndrd_ttl'>";

				if(!empty($subchildren) || !empty($child))
					{
						echo "<img src='".OER_URL."images/closed_arrow.png' data-pluginpath='".OER_URL."' />";
					}

				echo			"<input type='checkbox' ".$chck." name='oer_standard[]' value='".$value."' onclick='oer_check_all(this)' >
							".$result['standard_title']."
						</div><div class='oer_stndrd_desc'></div>";

						$id = 'sub_standards-'.$result['id'];
						oer_get_sub_standard($id, $oer_standard);

						$sid = 'sub_standards-'.$result['id'];
						oer_get_standard_notation($sid, $oer_standard);
				echo "</li>";
			}
		echo "</ul>";
	}
}

/** Get Standard Notation **/
function oer_get_standard_notation($id, $oer_standard)
{
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $id ) , ARRAY_A);

	if(!empty($oer_standard))
	{
		$stndrd_arr = explode(",",$oer_standard);
	}

	if(!empty($results))
	{
		echo "<ul>";
			foreach($results as $result)
			{
				$chck = '';
				$class = '';
			  $id = 'standard_notation-'.$result['id'];
			  $child = oer_check_child($id);
			  $value = 'standard_notation-'.$result['id'];

			  if(!empty($oer_standard))
				{
					if(in_array($value, $stndrd_arr))
					{
						$chck = 'checked="checked"';
						$class = 'selected';
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
				   <div class='oer_stndrd_desc'> ". $result['description']." </div>";

				   oer_get_standard_notation($id, $oer_standard);

				   echo "</li>";
			}
		echo "</ul>";
	}
}

/** Check Child Standard Notation **/
function oer_check_child($id)
{
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $id ) , ARRAY_A);
	return $results;
}

/** Get Substandard Children **/
function oer_get_substandard_children($id)
{
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $id ) , ARRAY_A);
	return $results;
}

/** Get Core Standard **/
function oer_get_core_standard($id) {
	global $wpdb;
	$results = null;

	if ($id!=="") {
		$stds = explode("-",$id);
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_core_standards where id = %s" , $stds[1] ) , ARRAY_A);
	}
	return $results;
}

/** Get Parent Standard **/
function oer_get_parent_standard($standard_id) {
	global $wpdb, $_oer_prefix;

	$stds = explode("-",$standard_id);
	$table = $stds[0];

	$prefix = substr($standard_id,0,strpos($standard_id,"_")+1);

	$table_name = $wpdb->prefix.$_oer_prefix.$table;

	$id = $stds[1];
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $table_name. " where id = %s" , $id ) , ARRAY_A);

	foreach($results as $result) {

		$stdrds = explode("-",$result['parent_id']);
		$tbl = $stdrds[0];

		$tbls = array('sub_standards','standard_notation');

		if (in_array($tbl,$tbls)){
			$results = oer_get_parent_standard($result['parent_id']);
		}

	}
	return $results;
}

// Get Screenshot File
function oer_getScreenshotFile($url)
{
	global $_debug;

	$upload_dir = wp_upload_dir();
	$path = $upload_dir['basedir'].'/resource-images/';

	if(!file_exists($path))
	{
		mkdir($path, 0777, true);
		debug_log("OER : create upload directory");
	}

	if(!file_exists($file = $path.'Screenshot'.preg_replace('/https?|:|#|\?|\&|\//i', '-', $url).'.jpg'))
	{
		debug_log("OER : start screenshot function");

		$oer_python_script_path 	= get_option("oer_python_path");
		$oer_python_install 		= get_option("oer_python_install");


		$use_xvfb = get_option('oer_use_xvfb');

		$param_screenshots = array();
		if($use_xvfb){
			$param_screenshots = array(
						'xvfb-run',
						'--auto-servernum',
						'--server-num=1'
						);
		}

		// create screenshot
		$params = array(
			$oer_python_install,
			$oer_python_script_path,
			escapeshellarg($url),
			$file,
		);

		$params = array_merge($param_screenshots, $params);

		$lines = array();
		$val = 0;

		try {

			$output = exec(implode(' ', $params), $lines, $val);

		} catch(Exception $e) {
			if ($_debug=="on")
				error_log($e->getMessage());

		}
		debug_log("OER : end of screenshot function");
	}
	return $file;
}

// Log Debugging
function debug_log($message) {
	global $_debug;

	// if debugging is on
	if ($_debug=="on")
		error_log($message);
}

// Taxonomy rewrite
function oer_taxonomy_slug_rewrite($wp_rewrite) {
    $rules = array();

    // get all custom taxonomies
    $taxonomies = get_taxonomies(array('_builtin' => false), 'objects');

    // get all custom post types
    $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');

    foreach ($post_types as $post_type) {
        foreach ($taxonomies as $taxonomy) {

            // go through all post types which this taxonomy is assigned to
            foreach ($taxonomy->object_type as $object_type) {

                // check if taxonomy is registered for this custom type
                if ($object_type == $post_type->rewrite['slug']) {

                    // get category objects
                    $terms = get_categories(array('type' => $object_type, 'taxonomy' => $taxonomy->name, 'hide_empty' => 0));

                    // make rules
                    foreach ($terms as $term) {
                        $rules[$object_type . '/' . $term->slug . '/([^/]*)/?'] = 'index.php?pagename=$matches[1]&' . $term->taxonomy . '=' . $term->slug;
                    }
                }
            }
        }
    }

    // merge with global rules
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'oer_taxonomy_slug_rewrite');

// Get Permalink Structure
function oer_get_permalink_structure( $post_type ) {
	if ( is_string( $post_type ) ) {
		$pt_object = get_post_type_object( $post_type );
	} else {
		$pt_object = $post_type;
	}

	if ( ! empty( $pt_object->cptp_permalink_structure ) ) {
		$structure = $pt_object->cptp_permalink_structure;
	} else {

		$structure = get_option( $pt_object->name . '_structure' );
	}

	return apply_filters( 'OER_' . $pt_object->name . '_structure', $structure );
}

//Get Date Front
function oer_get_date_front( $post_type ) {

	$structure = oer_get_permalink_structure( $post_type );

	$front = '';

	preg_match_all( '/%.+?%/', $structure, $tokens );
	$tok_index = 1;
	foreach ( (array) $tokens[0] as $token ) {
		if ( '%post_id%' == $token && ( $tok_index <= 3 ) ) {
			$front = '/date';
			break;
		}
		$tok_index ++;
	}

	return apply_filters( 'OER_date_front', $front, $post_type, $structure );
}

// Taxonomy Replace Tag
function oer_create_taxonomy_replace_tag( $post_id, $permalink ) {

	$search  = array();
	$replace = array();

	$taxonomies = get_taxonomies();

	foreach ( $taxonomies as $taxonomy => $objects ) {

		if ( false !== strpos( $permalink, '%' . $taxonomy . '%' ) ) {
			$terms = get_the_terms( $post_id, $taxonomy );

			if ( $terms and ! is_wp_error( $terms ) ) {
				$parents  = array_map( "oer_get_term_parent", $terms );

				$newTerms = array();
				foreach ( $terms as $key => $term ) {
					if ( ! in_array( $term->term_id, $parents ) ) {
						$newTerms[] = $term;
					}
				}

				$term_obj  = reset( $newTerms );
				$term_slug = $term_obj->slug;

				if ( isset( $term_obj->parent ) and 0 != $term_obj->parent ) {
					$term_slug = oer_get_taxonomy_parents_slug( $term_obj->parent, $taxonomy, '/', true ) . $term_slug;
				}
			}

			if ( isset( $term_slug ) ) {
				$search[]  = '%' . $taxonomy . '%';
				$replace[] = $term_slug;
			}
		}
	}

	return array( 'search' => $search, 'replace' => $replace );
}

/** Get Taxonomy Parents Slug **/
function oer_get_taxonomy_parents_slug( $term, $taxonomy = 'category', $separator = '/', $nicename = false, $visited = array() ) {

	$chain  = '';
	$parent = get_term( $term, $taxonomy );
	if ( is_wp_error( $parent ) ) {
		return $parent;
	}

	if ( $nicename ) {
		$name = $parent->slug;
	} else {
		$name = $parent->name;
	}

	if ( $parent->parent && ( $parent->parent != $parent->term_id ) && ! in_array( $parent->parent, $visited ) ) {
		$visited[] = $parent->parent;
		$chain .= oer_get_taxonomy_parents_slug( $parent->parent, $taxonomy, $separator, $nicename, $visited );
	}
	$chain .= $name . $separator;

	return $chain;
}

/** Get Term Parent **/
function oer_get_term_parent( $term ) {
	if ( isset( $term->parent ) and $term->parent > 0 ) {
		return $term->parent;
	}
}

/** Sort Terms **/
function oer_sort_terms( $terms, $orderby = 'term_id', $order = 'ASC' ) {

	if ( function_exists( 'wp_list_sort' ) ) {
		$terms = wp_list_sort( $terms, 'term_id', 'ASC' );
	} else {

		if ( 'name' == $orderby ) {
			usort( $terms, '_usort_terms_by_name' );
		} else {
			usort( $terms, '_usort_terms_by_ID' );
		}

		if ( 'DESC' == $order ) {
			$terms = array_reverse( $terms );
		}
	}

	return $terms;
}

//Get Category Child for Sidebar
if (!function_exists('oer_get_category_child')) {
	function oer_get_category_child($categoryid, $child_term_id = 0)
	{
		$args = array('hide_empty' => 0, 'taxonomy' => 'resource-subject-area','parent' => $categoryid);
		$catchilds = get_categories($args);
		$term = get_the_title();

		//$rsltdata = get_term_by( "name", $term, "resource-category", ARRAY_A );
		$rsltdata = get_term_by( "id", $child_term_id, "resource-subject-area", ARRAY_A );

		$parentid = array();
		if($rsltdata['parent'] != 0)
		{
			$parent = oer_get_parent_term($rsltdata['parent']);

			for($k=0; $k < count($parent); $k++)
			{
				//$idObj = get_category_by_slug($parent[$k]);
				if ($parent[$k]) {
					$idObj = get_term_by('slug', $parent[$k], 'resource-subject-area');
					$parentid[] = $idObj->term_id;
				}
			}
		}

		if(!empty($catchilds))
		{
			echo '<ul class="oer-category">';
			foreach($catchilds as $catchild)
			{
				$children = get_term_children($catchild->term_id, 'resource-subject-area');
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
					echo '<li class="oer-sub-category has-child'.$class.'" title="'. $catchild->name .'" >
							<span onclick="toggleparent(this);">
								<a href="'. site_url() .'/resource-subject-area/'. $catchild->slug .'">' . $catchild->name .'</a>
							</span>';
				}
				else
				{
					echo '<li class="oer-sub-category'.$class.'" title="'. $catchild->name .'" >
							<span onclick="toggleparent(this);">
								<a href="'. site_url() .'/resource-subject-area/'. $catchild->slug .'">' . $catchild->name .'</a>
							</span>';
				}
				oer_get_category_child( $catchild->term_id);
				echo '</li>';
			}
			echo '</ul>';
		}
	}
}

//GET Custom Texonomy Parent
if (!function_exists('oer_get_custom_category_parents')) {
	function oer_get_custom_category_parents( $id, $taxonomy = false, $link = false, $separator = '/', $nicename = false, $visited = array() ) {

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
			$chain .= oer_get_custom_category_parents( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
		}

		if ( $link ) {
			// $chain .= '<a href="' . esc_url( get_category_link( $parent->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $parent->name ) ) . '">'.$name.'</a>' . $separator;
			$chain .= '<a href="' . esc_url( get_term_link( (int) $parent->term_id, $taxonomy ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'wp-oer' ), $parent->name ) ) . '">'.$name.'</a>' . $separator;
		} else {
			$chain .= $name.$separator;
		}
		return $chain;
	}
}

//Get Category Parent List
if (!function_exists('oer_get_parent_term')) {
	function oer_get_parent_term($id)
	{
		$curr_cat = get_category_parents($id, false, '/' ,true);
		$curr_cat = explode('/',$curr_cat);

		return $curr_cat;
	}
}

if (!function_exists('oer_get_parent_term_list')) {
	function oer_get_parent_term_list($id) {
		$args = array(
			'format' => 'name',
			'separator' => '/',
			'link' => false,
			'inclusive' => false
			      );
		$curr_cat = get_term_parents_list($id, 'resource-subject-area', $args);
		$curr_cat = explode('/',$curr_cat);

		return $curr_cat;
	}
}

if (!function_exists('oer_get_term_top_most_parent')) {
	function oer_get_term_top_most_parent($term_id, $taxonomy="resource-subject-area"){
	    // start from the current term
	    $parent  = get_term_by( 'id', $term_id, $taxonomy);
	    // climb up the hierarchy until we reach a term with parent = '0'
	    while ($parent->parent != '0'){
		$term_id = $parent->parent;

		$parent  = get_term_by( 'id', $term_id, $taxonomy);
	    }
	    return $parent;
	}
}

//Get Total Post Count
if (!function_exists('oer_get_post_count')) {
	function oer_get_post_count($category, $taxonomy)
	{
		$count = 0;
		$args = array(
		  'child_of' => $category,
		);

		$tax_terms = get_terms($taxonomy,$args);
		foreach ($tax_terms as $tax_term)
		{
			$count +=$tax_term->count;
		}
		return $count;
	}
}

//Get Category Child for Homepage
if (!function_exists('oer_front_child_category')) {
	function oer_front_child_category($categoryid)
	{
		$args = array('hide_empty' => 0, 'taxonomy' => 'resource-subject-area','parent' => $categoryid);
		$catchilds = get_categories($args);
		$rtrn = "";

		if(!empty($catchilds))
		{
			$rtrn .= '<ul class="oer-category">';
			foreach($catchilds as $catchild)
			{
				$children = get_term_children($catchild->term_id, 'resource-subject-area');
				$count = oer_get_post_count($catchild->term_id, "resource-subject-area");
				$count = $count + $catchild->count;
				$child_url = site_url() .'/resource-subject-area/'. $catchild->slug;
				if( !empty( $children ) )
				{
					$rtrn .=  '<li class="oer-sub-category has-child"><span onclick="toggleparent(this); gethght(this);"><a href="'. esc_url($child_url) .'">' . $catchild->name .'</a><label>'. $count .'</label></span>';
				}
				else
				{
					$rtrn .=  '<li class="oer-sub-category"><span onclick="toggleparent(this);"><a href="'. esc_url($child_url) .'">' . $catchild->name .'</a><label>'. $count .'</label></span>';
				}
				$rtrn .=  oer_front_child_category( $catchild->term_id);
				$rtrn .= '</li>';
			}
			$rtrn .=  '</ul>';
		}

		return $rtrn;
	}
}

//Generate slug for category urls
function oer_slugify($text)
{
	// replace non letter or digits by -
	$text = preg_replace('~[^\pL\d]+~u', '-', $text);

	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);

	// trim
	$text = trim($text, '-');

	// remove duplicate -
	$text = preg_replace('~-+~', '-', $text);

	// lowercase
	$text = strtolower($text);

	if (empty($text)) {
	  return 'n-a';
	}

	return $text;
}

/** Import Standards **/
function oer_importStandards($file){
	global $wpdb;

	$time = time();
	$date = date($time);

	//Set Maximum Excution Time
	ini_set('max_execution_time', 0);
	set_time_limit(0);

	// Log start of import process
	debug_log("OER Standards Importer: Start Bulk Import of Standards");

	if( isset($file) )
	{
		try {

			$filedetails = pathinfo($file);

			$filename = $filedetails['filename']."-".$date;

			$doc = new DOMDocument();
			$doc->preserveWhiteSpace = FALSE;
			$doc->load( $file );

			$StandardDocuments = $doc->getElementsByTagName('StandardDocument');

			$xml_arr = array();
			$m = 0;
			foreach( $StandardDocuments as $StandardDocument)
			{
				$url = $StandardDocuments->item($m)->getAttribute('rdf:about');
				$titles = $StandardDocuments->item($m)->getElementsByTagName('title');
				$core_standard[$url]['title'] = $titles->item($m)->nodeValue;
			}

			$Statements = $doc->getElementsByTagName('Statement');
			$i = 0;
			foreach( $Statements as $Statement)
			{
				$statementNotations = $Statements->item($i)->getElementsByTagName('statementNotation');
				if($statementNotations->length == 1)
				{
					$url = $Statements->item($i)->getAttribute('rdf:about');
					$isChildOfs = $Statements->item($i)->getElementsByTagName('isChildOf');
					$descriptions = $Statements->item($i)->getElementsByTagName('description');
					for($j = 0; $j < sizeof($statementNotations); $j++)
					{
						$standard_notation[$url]['ischild'] = $isChildOfs->item($j)->getAttribute('rdf:resource');
						$standard_notation[$url]['title'] = $statementNotations->item($j)->nodeValue;
						$standard_notation[$url]['description'] = $descriptions->item($j)->nodeValue;
					}
				}
				else
				{
					$descriptions = $Statements->item($i)->getElementsByTagName('description');
					$url = $Statements->item($i)->getAttribute('rdf:about');
					$isChildOfs = $Statements->item($i)->getElementsByTagName('isChildOf');
					$k = 0;
					foreach( $descriptions as $description)
					{
						$xml_arr[$url]['ischild'] = $isChildOfs->item($k)->getAttribute('rdf:resource');
						$xml_arr[$url]['title'] = $descriptions->item($k)->nodeValue;
						$k++;
					}
				}
				$i++;
			}

			// Get Core Standard
			foreach($core_standard as $cskey => $csdata)
			{
				$url = $cskey;
				$title = $csdata['title'];
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "oer_core_standards where standard_name = %s" , $title ));
				if(empty($results))
				{
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'oer_core_standards values("", %s , %s)' , $title , $url ));
				}
			}
			// Get Core Standard

			// Get Sub Standard
			foreach($xml_arr as $key => $data)
			{
				$url = esc_url_raw($key);
				$ischild = $data['ischild'];
				$title = sanitize_text_field($data['title']);
				$parent = '';

				$rsltset = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "oer_core_standards where standard_url=%s" , $ischild ));
				if(!empty($rsltset))
				{
					$parent = "core_standards-".$rsltset[0]->id;
				}
				else
				{
					$rsltset_sec = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "oer_sub_standards where url=%s" , $ischild ));
					if(!empty($rsltset_sec))
					{
						$parent = 'sub_standards-'.$rsltset_sec[0]->id;
					}
				}

				$res = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s && url = %s" , $parent , $url ));
				if(empty($res))
				{
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'oer_sub_standards values("", %s, %s, %s)' , $parent , $title , $url ));
				}
			}
			// Get Sub Standard

			// Get Standard Notation
			foreach($standard_notation as $st_key => $st_data)
			{
				$url = esc_url_raw($st_key);
				$ischild = $st_data['ischild'];
				$notation = sanitize_text_field($st_data['title']);
				$description = sanitize_text_field($st_data['description']);
				$parent = '';

				$rsltset = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "oer_sub_standards where url=%s" , $ischild ));
				if(!empty($rsltset))
				{
					$parent = 'sub_standards-'.$rsltset[0]->id;
				}
				else
				{
					$rsltset_sec = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "oer_standard_notation where url=%s" , $ischild ));
					if(!empty($rsltset_sec))
					{
						$parent = 'standard_notation-'.$rsltset_sec[0]->id;
					}
				}

				$res = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "oer_standard_notation where standard_notation = %s && parent_id = %s && url = %s" , $notation , $parent , $url ));
				if(empty($res))
				{
					//$description = preg_replace("/[^a-zA-Z0-9]+/", " ", html_entity_decode($description))
					$description = esc_sql($description);
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'oer_standard_notation values("", %s, %s, %s, "", %s)' , $parent , $notation , $description , $url ));
				}
			}

		} catch(Exception $e) {
			$response = array(
					  'message' => $e->getMessage(),
					  'type' => 'error'
					  );
			// Log any error during import process
			debug_log($e->getMessage());
			return $response;
		}
		// Log Finished Import
		debug_log("OER Standards Importer: Finished Bulk Import of Standards");
		// Get Standard Notation
		$response = array(
			'message' => 'successful',
			'type' => 'success'
		);
		return $response;
	}
}

//Check if Standard Exists
function oer_isStandardExisting($standard) {
	global $wpdb;

	$response = false;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "oer_core_standards where standard_name like %s" , '%'.$standard.'%'));
	if(!empty($results))
		$response = true;

	return $response;
}

//Get Domain from Url
function oer_getDomainFromUrl($url) {
	$url_details = parse_url($url);
	return $url_details['host'];
}

//Get Image from External URL
function oer_getImageFromExternalURL($url) {
	global $_debug;

	$external_service_url = get_option('oer_service_url');
	$img_url = str_replace('$url',$url,$external_service_url);

	$ch = curl_init ($img_url);

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	$raw=curl_exec($ch);
	curl_close ($ch);

	$upload_dir = wp_upload_dir();
	$path = $upload_dir['basedir'].'/resource-images/';

	if(!file_exists($path))
	{
		mkdir($path, 0777, true);
		debug_log("OER : create upload directory");
	}

	if(!file_exists($file = $path.'Screenshot'.preg_replace('/https?|:|#|\?|\&|\//i', '-', $url).'.jpg'))
	{
		debug_log("OER : start screenshot function");

		$fp = fopen($file,'wb');
		fwrite($fp, $raw);
		fclose($fp);

		debug_log("OER : end of screenshot function");
	}
	return $file;
}

function oer_save_image_to_file($image_url) {
	$ch = curl_init ($image_url);

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	$raw=curl_exec($ch);
	curl_close ($ch);

	$upload_dir = wp_upload_dir();
	$path = $upload_dir['basedir'].'/resource-images/';

	if(!file_exists($path))
	{
		mkdir($path, 0777, true);
		debug_log("OER : create upload directory");
	}

	if(!file_exists($file = $path.'Screenshot'.preg_replace('/https?|:|#|\?|\&|\//i', '-', $image_url).'.jpg'))
	{
		debug_log("OER : start screenshot function");

		$fp = fopen($file,'wb');
		fwrite($fp, $raw);
		fclose($fp);

		debug_log("OER : end of screenshot function");
	}
	return $file;
}

//Get External Thumbnail Image
function oer_getExternalThumbnailImage($url, $local=false) {
	global $_debug;

	$local_filename = $url;

	if ($local) {
		$url = OER_URL.$url;
	} else {
		// Curl to download image
		$ch = curl_init ($url);

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		$raw=curl_exec($ch);
		curl_close ($ch);
	}

	$upload_dir = wp_upload_dir();
	$path = $upload_dir['basedir'].'/resource-images/';

	if ($local){
		$source_thumbnail_url = OER_PATH.$local_filename;
		$ext = ".".pathinfo($local_filename, PATHINFO_EXTENSION);
		$local_filename = str_replace($ext,"",$local_filename);
		$url = "-".$local_filename;
	}

	if(!file_exists($path))
	{
		mkdir($path, 0777, true);
		debug_log("OER : create upload directory");
	}

	if(!file_exists($file = $path.'Screenshot'.preg_replace('/https?|:|#|\?|\&|\//i', '-', $url).'.jpg'))
	{
		debug_log("OER : start download image function");

		if ($local){
			$file = $path.'Screenshot'.preg_replace('/https?|:|#|\?|\&|\//i', '-', $url).'.jpg';
			copy($source_thumbnail_url,$file);
		} else {
			$fp = fopen($file,'wb');
			fwrite($fp, $raw);
			fclose($fp);
		}

		debug_log("OER : end of download image function");
	}
	return $file;
}

//Checks if bootstrap is loaded
function oer_is_bootstrap_loaded(){
	$bootstrap = false;
	$js = "";
	$url = get_site_url();

	if (ini_get('allow_url_fopen'))
		$content = file_get_contents($url);
	else
		$content = oer_curlRequest($url);

	$content = htmlentities($content);

	preg_match_all("#(<head[^>]*>.*?<\/head>)#ims", $content, $head);
	$content = implode('',$head[0]);

	preg_match_all("#<script(.*?)<\/script>#is", $content, $matches);
	foreach ($matches[0] as $value) {
		$js .= $value;
	}

	$locate_bootstrap = strpos($js,"bootstrap.");

	if ($locate_bootstrap>0)
		$bootstrap = true;

	return $bootstrap;
}

/** Get resource via curl **/
function oer_curlRequest($url) {
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $data = curl_exec($ch);
   curl_close($ch);
   return $data;
}

/** Resize Image **/
function oer_resize_image($orig_img_url, $width, $height, $crop = false) {
	$new_image_url = $orig_img_url;

	$suffix = "{$width}x{$height}";

	$img_path = $new_img_path = parse_url($orig_img_url);
	$img_path = $_SERVER['DOCUMENT_ROOT'] . $img_path['path'];

	if (!empty($img_path)) {
		//Resize Image using WP_Image_Editor class
		$image_editor = wp_get_image_editor($img_path);

		if ( !is_wp_error($image_editor) ) {
			$new_image = $image_editor->resize( $width, $height, $crop );

			//Get Additional information of file
			$info = pathinfo( $img_path );
			$dir = $info['dirname'];
			$ext = $info['extension'];
			$name = wp_basename( $img_path , ".{$ext}" );

			$dest_filename = "{$dir}/{$name}-{$suffix}.{$ext}";

			//Set port if port is not 80
			$new_port = ($new_img_path['port'])?':'.$new_img_path['port']:'';

			//new image url
			$new_image_url = str_replace($_SERVER['DOCUMENT_ROOT'], "{$new_img_path['scheme']}://{$new_img_path['host']}{$new_port}", $dest_filename);

			if (!file_exists($dest_filename)) {
				//save new resize image to file
				$image_file = $image_editor->save($dest_filename);

				if ($image_file)
					return $new_image_url;
			}
		}
	}
	return $new_image_url;
}

//Import Default Resources
function oer_importResources($default=false) {
	global $wpdb, $_oer_prefix;
	require_once OER_PATH.'Excel/reader.php';

	debug_log("OER Resources Importer: Initializing Excel Reader");

	$excl_obj = new Oer_Spreadsheet_Excel_Reader();
	$excl_obj->setOutputEncoding('CP1251');
	$time = time();
	$date = date($time);

	//Set Maximum Excution Time
	ini_set('max_execution_time', 0);
	ini_set('max_input_time ', -1);
	ini_set('memory_limit ', -1);
	set_time_limit(0);

	// Log start of import process
	debug_log("OER Resources Importer: Starting Bulk Import of Resources");

	$cnt = 0;
		try{
			if ($default==true) {
				//default resource filename
				$filename = "resource_import_sample_data.xls";
				$excl_obj->read(OER_PATH."samples/".$filename);
			} else {
				if( isset($_FILES['resource_import']) && $_FILES['resource_import']['size'] != 0 )
				{
					$filename = $_FILES['resource_import']['name']."-".$date;

					if ($_FILES["resource_import"]["error"] > 0)
					{
						$message = "Error: " . $_FILES["resource_import"]["error"] . "<br>";
						$type = "error";
					}
					else
					{
						/** Check if OER Plugin upload exists if not create to avoid error moving uploaded file **/
						if (!(is_dir(OER_PATH."upload"))){
							mkdir(OER_PATH."upload",0777);
						}
						"Upload: " . $_FILES["resource_import"]["name"] . "<br>";
						"Type: " . $_FILES["resource_import"]["type"] . "<br>";
						"Size: " . ($_FILES["resource_import"]["size"] / 1024) . " kB<br>";
						"stored in:" .move_uploaded_file($_FILES["resource_import"]["tmp_name"],OER_PATH."upload/".$filename) ;
					}
					$excl_obj->read(OER_PATH."upload/".$filename);
				}
			}

			$fnldata = $excl_obj->sheets[0];
			for ($k =2; $k <= $fnldata['numRows']; $k++)
			{
				/** Clear variable values after a loop **/
				$oer_title 		= "";
				$oer_resourceurl 	= "";
				$oer_description 	= "";
				$oer_highlight 		= "";
				$oer_categories 	= "";
				$oer_grade 		= "";
				$oer_kywrd 		= "";
				$oer_datecreated 	= "";
				$oer_datemodified 	= "";
				$oer_mediatype 		= "";
				$oer_lrtype 		= "";
				$oer_interactivity 	= "";
				$oer_userightsurl 	= "";
				$oer_isbasedonurl   	= "";
				$oer_standard       	= "";
				$oer_authortype     	= "";
				$oer_authorname     	= "";
				$oer_authorurl      	= "";
				$oer_authoremail    	= "";
				$oer_publishername  	= "";
				$oer_publisherurl   	= "";
				$oer_publisheremail 	= "";
				$oer_authortype2    	= "";
				$oer_authorname2    	= "";
				$oer_authorurl2     	= "";
				$oer_authoremail2   	= "";
				$oer_thumbnailurl	= "";

				/** Check first if column is set **/
				if (isset($fnldata['cells'][$k][1]))
					$oer_title          = $fnldata['cells'][$k][1];
				if (isset($fnldata['cells'][$k][2]))
					$oer_resourceurl    = $fnldata['cells'][$k][2];
				if (isset($fnldata['cells'][$k][3]))
					$oer_description    = $fnldata['cells'][$k][3];
				if (isset($fnldata['cells'][$k][4]))
					$oer_highlight      = $fnldata['cells'][$k][4];
				if (isset($fnldata['cells'][$k][5]))
					$oer_categories     = $fnldata['cells'][$k][5];
				if (isset($fnldata['cells'][$k][6]))
					$oer_grade          = $fnldata['cells'][$k][6];
				if (isset($fnldata['cells'][$k][7]))
					$oer_kywrd          = $fnldata['cells'][$k][7];
				if (isset($fnldata['cells'][$k][8]))
					$oer_datecreated    = $fnldata['cells'][$k][8];
				if (isset($fnldata['cells'][$k][9]))
					$oer_datemodified   = $fnldata['cells'][$k][9];
				if (isset($fnldata['cells'][$k][10]))
					$oer_mediatype      = $fnldata['cells'][$k][10];
				if (isset($fnldata['cells'][$k][11]))
					$oer_lrtype         = $fnldata['cells'][$k][11];
				if (isset($fnldata['cells'][$k][12]))
					$oer_interactivity  = $fnldata['cells'][$k][12];
				if (isset($fnldata['cells'][$k][13]))
					$oer_userightsurl   = $fnldata['cells'][$k][13];
				if (isset($fnldata['cells'][$k][14]))
					$oer_isbasedonurl   = $fnldata['cells'][$k][14];
				if (isset($fnldata['cells'][$k][15]))
					$oer_standard       = $fnldata['cells'][$k][15];
				if (isset($fnldata['cells'][$k][16]))
					$oer_authortype     = $fnldata['cells'][$k][16];
				if (isset($fnldata['cells'][$k][17]))
					$oer_authorname     = $fnldata['cells'][$k][17];
				if (isset($fnldata['cells'][$k][18]))
					$oer_authorurl      = $fnldata['cells'][$k][18];
				if (isset($fnldata['cells'][$k][19]))
					$oer_authoremail    = $fnldata['cells'][$k][19];
				if (isset($fnldata['cells'][$k][20]))
					$oer_publishername  = $fnldata['cells'][$k][20];
				if (isset($fnldata['cells'][$k][21]))
					$oer_publisherurl   = $fnldata['cells'][$k][21];
				if (isset($fnldata['cells'][$k][22]))
					$oer_publisheremail = $fnldata['cells'][$k][22];
				if (isset($fnldata['cells'][$k][23]))
					$oer_authortype2    = $fnldata['cells'][$k][23];
				if (isset($fnldata['cells'][$k][24]))
					$oer_authorname2    = $fnldata['cells'][$k][24];
				if (isset($fnldata['cells'][$k][25]))
					$oer_authorurl2     = $fnldata['cells'][$k][25];
				if (isset($fnldata['cells'][$k][26]))
					$oer_authoremail2   = $fnldata['cells'][$k][26];
				if (isset($fnldata['cells'][$k][27]))
					$oer_thumbnailurl   = $fnldata['cells'][$k][27];

				if(!empty($oer_standard) && (!is_array($oer_standard)))
				{
					$oer_standard = explode(",", $oer_standard);
				}

				if(!empty($oer_categories))
				{
					$oer_categories = explode(",",$oer_categories);
					$category_id = array();
					for($i = 0; $i <= sizeof($oer_categories); $i++)
					{
						if(!empty($oer_categories [$i]))
						{
						    $cat = get_term_by( 'name', trim($oer_categories[$i]), 'resource-subject-area' );
						    if($cat)
						    {
							    $category_id[$i] = $cat->term_id;
						    }
						    else
						    {
							    // Categories are not found then assign as keyword
							    $oer_kywrd .= ",".$oer_categories [$i];
						    }
						}
					}
				}
				else
				{
					$category_id = array();
				}

				//Check if $oer_title is set
				if ( isset( $oer_title ) ){
					$post_name = strtolower($oer_title);
					$post_name = str_replace(' ','_', $post_name);
				}

				if(!empty($oer_title) && !empty($oer_resourceurl))
				{
					/** Get Current WP User **/
					$user_id = get_current_user_id();
					/** Get Current Timestamp for post_date **/
					$cs_date = current_time('mysql');

					$post = array('post_content' => $oer_description, 'post_name' => $post_name, 'post_title' => $oer_title, 'post_status' => 'publish', 'post_type' => 'resource', 'post_author' => $user_id , 'post_date' => $cs_date, 'post_date_gmt'  => $cs_date, 'comment_status' => 'open');
					/** Set $wp_error to false to return 0 when error occurs **/
					$post_id = wp_insert_post( $post, false );

					//Set Category of Resources
					$tax_ids = wp_set_object_terms( $post_id, $category_id, 'resource-subject-area', true );

					// Set Tages
					$oer_kywrd = strtolower(trim($oer_kywrd,","));
					wp_set_post_tags(  $post_id, $oer_kywrd , true );



				if($oer_resourceurl)
				{
					if( !empty($oer_resourceurl) )
					{
						if ( preg_match('/http/',$oer_resourceurl) )
						{
							$oer_resourceurl = $oer_resourceurl;
						}
						else
						{
							$oer_resourceurl = 'http://'.$oer_resourceurl;
						}
					}
					update_post_meta( $post_id , 'oer_resourceurl' , esc_url_raw($oer_resourceurl));
				}

				if(!empty($oer_highlight))
				{
					update_post_meta( $post_id , 'oer_highlight' , $oer_highlight);
				}

				if(!empty($oer_grade))
				{
					$oer_grades = "";
					$oer_grade = trim($oer_grade, '"');
					if(strpos($oer_grade , "-"))
					{
						$oer_grade = explode("-",$oer_grade);
						if(is_array($oer_grade))
						{
							if (strtolower($oer_grade[0])=="k"){
								$oer_grades .= "K,";
								$oer_grade[0] = 1;
							}
							for($j = $oer_grade[0]; $j <= $oer_grade[1]; $j++)
							{
								$oer_grades .= $j.",";
							}
						}
					}
					else
					{
						$oer_grades = $oer_grade;
					}
					update_post_meta( $post_id , 'oer_grade' , $oer_grades);
				}

				if(!empty($oer_datecreated) && !($oer_datecreated==""))
				{
					update_post_meta( $post_id , 'oer_datecreated' , $oer_datecreated);
				}

				if(!empty($oer_datemodified))
				{
					update_post_meta( $post_id , 'oer_datemodified' , $oer_datemodified);
				}

				if(!empty($oer_mediatype))
				{
					update_post_meta( $post_id , 'oer_mediatype' , sanitize_text_field($oer_mediatype));
				}
				if(!empty($oer_lrtype))
				{
					update_post_meta( $post_id , 'oer_lrtype' , sanitize_text_field($oer_lrtype));
				}
				if(!empty($oer_interactivity))
				{
					update_post_meta( $post_id , 'oer_interactivity' , sanitize_text_field($oer_interactivity));
				}
				if(!empty($oer_userightsurl))
				{
						if ( preg_match('/http/',$oer_userightsurl) )
						{
							$oer_userightsurl = $oer_userightsurl;
						}
						else
						{
							$oer_userightsurl = 'http://'.$oer_userightsurl;
						}
					update_post_meta( $post_id , 'oer_userightsurl' , esc_url_raw($oer_userightsurl));
				}
				if(!empty($oer_isbasedonurl))
				{
						if ( preg_match('/http/',$oer_isbasedonurl) )
						{
							$oer_isbasedonurl = $oer_isbasedonurl;
						}
						else
						{
							$oer_isbasedonurl = 'http://'.$oer_isbasedonurl;
						}
					update_post_meta( $post_id , 'oer_isbasedonurl' , esc_url_raw($oer_isbasedonurl));
				}
				if(!empty($oer_standard))
				{
					$gt_oer_standard = '';
					for($l = 0; $l < count($oer_standard); $l++)
					{
						$results = $wpdb->get_row( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where standard_notation =%s" , $oer_standard[$l] ),ARRAY_A);

						if(!empty($results))
						{
							$gt_oer_standard .= "standard_notation-".$results['id'].",";

							$table = explode("-", $results['parent_id']);

							if(!empty($table))
							{
								$stndrd_algn = $wpdb->get_row( $wpdb->prepare( "SELECT * from  " . $wpdb->prefix. $_oer_prefix . $table[0] . " where id =%s" , $table[1] ),ARRAY_A);

								if($stndrd_algn['parent_id'])
								{
									oer_fetch_stndrd($stndrd_algn['parent_id'], $post_id);
								}
							}
						}
						else
						{
							$gt_oer_standard .= ",";
						}
					}
					$gt_oer_standard = trim($gt_oer_standard,",");
					update_post_meta( $post_id , 'oer_standard' , $gt_oer_standard);
				}
				if(!empty($oer_authortype))
				{
					update_post_meta( $post_id , 'oer_authortype' , sanitize_text_field($oer_authortype));
				}
				if(!empty($oer_authorname))
				{
					update_post_meta( $post_id , 'oer_authorname' , sanitize_text_field($oer_authorname));
				}
				if(!empty($oer_authorurl))
				{
						if ( preg_match('/http/',$oer_authorurl) )
						{
							$oer_authorurl = $oer_authorurl;
						}
						else
						{
							$oer_authorurl = 'http://'.$oer_authorurl;
						}
					update_post_meta( $post_id , 'oer_authorurl' , esc_url_raw($oer_authorurl));
				}
				if(!empty($oer_authoremail))
				{
					update_post_meta( $post_id , 'oer_authoremail' , sanitize_email($oer_authoremail));
				}
				if(!empty($oer_authortype2))
				{
					update_post_meta( $post_id , 'oer_authortype2' , sanitize_text_field($oer_authortype2));
				}
				if(!empty($oer_authorname2))
				{
					update_post_meta( $post_id , 'oer_authorname2' , sanitize_text_field($oer_authorname2));
				}
				if(!empty($oer_authorurl2))
				{
						if ( preg_match('/http/',$oer_authorurl2) )
						{
							$oer_authorurl2 = $oer_authorurl2;
						}
						else
						{
							$oer_authorurl2 = 'http://'.$oer_authorurl2;
						}
					update_post_meta( $post_id , 'oer_authorurl2' , esc_url_raw($oer_authorurl2));
				}
				if(!empty($oer_authoremail2))
				{
					update_post_meta( $post_id , 'oer_authoremail2' , sanitize_email($oer_authoremail2));
				}

				if(!empty($oer_publishername))
				{
					update_post_meta( $post_id , 'oer_publishername' , sanitize_text_field($oer_publishername));
				}
				if(!empty($oer_publisherurl))
				{
					if ( preg_match('/http/',$oer_publisherurl) )
					{
						$oer_publisherurl = $oer_publisherurl;
					}
					else
					{
						$oer_publisherurl = 'http://'.$oer_publisherurl;
					}
						update_post_meta( $post_id , 'oer_publisherurl' , esc_url_raw($oer_publisherurl));
				}
				if(!empty($oer_publisheremail))
				{
					update_post_meta( $post_id , 'oer_publisheremail' , sanitize_email($oer_publisheremail));
				}
				//saving meta fields

				if(!empty($oer_resourceurl))
				{
					$url = esc_url_raw($oer_resourceurl);
					$upload_dir = wp_upload_dir();
					$file = '';

					//Check first if screenshot is enabled
					$screenshot_enabled = get_option( 'oer_enable_screenshot' );
					//Check if external service screenshot is enabled
					$external_screenshot = get_option( 'oer_external_screenshots' );

					if(!has_post_thumbnail( $post_id ))
					{
						if (!empty($oer_thumbnailurl)) {
							if (substr(trim($oer_thumbnailurl),0,2)=="./") {
								$oer_thumbnailurl = substr(trim($oer_thumbnailurl),2);
								$file = oer_getExternalThumbnailImage($oer_thumbnailurl, true);
							} else {
								$file = oer_getExternalThumbnailImage($oer_thumbnailurl);
							}
						} elseif ($screenshot_enabled) {
							$file = oer_getScreenshotFile($url);
						} elseif ( $external_screenshot ) {
							// if external screenshot utility enabled
							$file = oer_getImageFromExternalURL($url);
						}
					}

					if(file_exists($file))
					{
						$filetype = wp_check_filetype( basename( $file ), null );
						$wp_upload_dir = wp_upload_dir();

						$guid = $wp_upload_dir['url'] . '/' . basename( $file );

						$attachment = array(
							'guid'           => $guid,
							'post_mime_type' => $filetype['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
						update_post_meta($post_id, "_thumbnail_id", $attach_id);

						// Generate the metadata for the attachment, and update the database record.
						$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
						wp_update_attachment_metadata( $attach_id, $attach_data );
					}

				}//Create Screeenshot
				$cnt++;
			}

		}
	  } catch(Exception $e) {
		// Log any error encountered during the import process
		if ($_debug=="on")
			error_log($e->getMessage());
	}
	// Log finish of import process
	debug_log("OER Resources Importer: Finished Bulk Import of Resources");
	$message = sprintf(__("Successfully imported %s resources.", 'wp-oer'), $cnt);
	$type = "success";
	$response = array('message' => $message, 'type' => $type);
	return $response;
}

// Import LR Resources
function oer_importLRResources(){
	$lr_url = $_POST['lr_import'];

	$resources = null;
	$lr_resources = array();
	$schema = array(
			"LRMI",
			"JSON-LD",
			"json",
			"schema.org"
		);

	if ($lr_url){
		if( ini_get('allow_url_fopen') ) {
			$resources = file_get_contents($lr_url);
		} else {
			$resources = curlResources($lr_url);
		}
		$resources = json_decode($resources);
	}

	$index = 0;

	if(strpos($lr_url,"slice") === false) {
		// Getting LR Imports through Obtain
		foreach($resources->documents as $document){
			$lr_resource = array();

			foreach ($document as $doc) {
				if ($doc[0]->doc_type=="resource_data"){
					$exists = custom_array_intersect($schema, $doc[0]->payload_schema);

					if (!empty($exists)){
						if ($doc[0]->resource_data->items){
							foreach($doc[0]->resource_data as $resource){
								$lr_resource['resource_url'] = trim($resource[0]->properties->url[0]);
								if (strtolower($resource[0]->properties->educationalAlignment[0]->properties->alignmentType[0])=="educationlevel"){
									if (strpos($resource[0]->properties->educationalAlignment[0]->properties->targetName[0],"Fourth")>=0){
										$lr_resource['grade'] = "4";
									}
								}
								if (strpos($resource[0]->properties->author[0]->type[0],"Person")){
									$lr_resource['author_type'] = "person";
								} else {
									$lr_resource['author_type'] = "organization";
								}
								$lr_resource['author_url'] = $resource[0]->properties->author[0]->properties->url[0][0];
								if (!is_array($resource[0]->properties->author[0]->properties->name[0]))
									$lr_resource['author_name'] = $resource[0]->properties->author[0]->properties->name[0];
								else
									$lr_resource['author_name'] = $resource[0]->properties->author[0]->properties->name[0][0];
								$lr_resource['author_email'] = $resource[0]->properties->author[0]->properties->email[0][0];
								if (strpos($resource[0]->properties->publisher[0]->type[0],"Organization")){
									$lr_resource['publisher_name'] = $resource[0]->properties->publisher[0]->properties->name[0];
									$lr_resource['publisher_url'] = $resource[0]->properties->publisher[0]->properties->url[0];
								}
								$lr_resource['interactivity'] = $resource[0]->properties->interactivityType[0];
								$lr_resource['title'] = $resource[0]->properties->name[0];
								$lr_resource['media_type'] = strtolower($resource[0]->properties->mediaType[0]);
								$lr_resource['date_created'] = $resource[0]->properties->dateCreated[0];
								$lr_resource['lr_type'] = strtolower($resource[0]->properties->learningResourceType[0]);
								$lr_resource['description'] = $resource[0]->properties->description[0];
								$lr_resource['tags'] = $resource[0]->properties->keywords[0];
								$lr_resources[] = $lr_resource;
							}
						} else {
							$resource = $doc[0]->resource_data;
							if (!is_object($resource)){
								$resource = json_decode($resource);
								$lr_resource['resource_url'] = trim($resource->url);
								$lr_resource['description'] = $resource->description;
								$lr_resource['title'] = $resource->name;
								$lr_resource['publisher_name'] = $resource->publisher->name;
								$lr_resource['author_name'] = $resource->author->name;
								$lr_resource['date_created'] = $resource->dateCreated;
								$lr_resource['tags'] = $resource->keywords;
							} else {
								$lr_resource['resource_url'] = trim($resource->url);
								$lr_resource['description'] = $resource->description[0];
								$lr_resource['title'] = $resource->name[0];
								$lr_resource['publisher_name'] = $resource->publisher[0]->name;
								$lr_resource['author_name'] = $resource->author[0]->name;
								$lr_resource['based_on_url'] = $resource->isBasedOnURL[0];
								$lr_resource['date_created'] = $resource->dateCreated[0];
								$lr_resource['subject_areas'] = $resource->about;
							}
							$lr_resources[] = $lr_resource;
						}
					}
				}

			}
			$index++;
		}
	} else {
		$lr_resources = get_sliceLRResources($lr_url);
	}
	return $lr_resources;
}

// Import LR Resources with slice and resumption token
function get_sliceLRResources($lr_url){
	$lr_resources = array();

	$lrUrl = $lr_url;

	do {
		// Get LR Resources based on initial slice URL
		if( ini_get('allow_url_fopen') ) {
			$resources = file_get_contents($lrUrl);
		} else {
			$resources = curlResources($lrUrl);
		}
		$resources = json_decode($resources);

		// Exit loop if no resources returned
		if (empty($resources->documents))
		    break;

		// Getting LR Imports through Slice
		foreach($resources->documents as $document){
			$lr_resource = array();

			if($document->resource_data_description->doc_type=="resource_data"){
				$resource = $document->resource_data_description;
				$resource_data = json_decode($resource->resource_data);

				$lr_resource['resource_url'] = $resource->resource_locator;
				$lr_resource['title'] = $resource_data->name;
				$lr_resource['description'] = $resource_data->description;
				$lr_resource['date_created'] = $resource_data->dateCreated;
				$lr_resource['lr_type'] = $resource_data->learningResourceType;
				$lr_resource['thumbnail_url'] = $resource_data->thumbnailUrl;

				if($resource_data->author->{'@type'}=="Organization")
					$lr_resource['author_type'] = "organization";
				else
					$lr_resource['author_type'] = "person";

				$lr_resource['author_name'] = $resource_data->author->name;
				$lr_resource['publisher_name'] = $resource_data->publisher->name;
				$lr_resource['publisher_url'] = $resource_data->publisher->url;
				$lr_resource['subject_areas'] = $resource_data->keywords;

				$lr_resources[] = $lr_resource;
			}
		}

		// get resumption token for next batch of resources
		$resume_token = $resources->resumption_token;
		$lrUrl = $lr_url."&resumption_token=".$resume_token;

	} while(!empty($resources->resumption_token));

	return $lr_resources;
}

function curlResources($url){
	if (!function_exists('curl_init')){
		die('CURL is not installed!');
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

function custom_array_intersect($firstArray, $secondArray){
  $intersection = [];
  foreach ($firstArray as $a){
      $A = strtolower($a);
      foreach ($secondArray as $b) {
	  $B = strtolower($b);
	  if ($A === $B) {
	      $intersection[] = array($a,$b);
	      break;
	  }
      }
  }
  return $intersection;
}

//Import Subject Areas
function oer_importSubjectAreas($default=false) {
	global $wpdb;
	require_once OER_PATH.'Excel/reader.php';

	debug_log("OER Subject Areas Importer: Initializing Excel Reader");

	$excl_obj = new Oer_Spreadsheet_Excel_Reader();
	$excl_obj->setOutputEncoding('CP1251');
	$time = time();
	$date = date($time);

	//Set Maximum Excution Time
	ini_set('max_execution_time', 0);
	set_time_limit(0);

	// Log start of import process
	debug_log("OER Subject Areas Importer: Starting Bulk Import ");

	global $wpdb;

	try {
		if ($default==true) {
			//default subject area filename
			$filename = "subject_area_import.xls";
			$excl_obj->read(OER_PATH."samples/".$filename);
		} else {
			if( isset($_FILES['bulk_import']) && $_FILES['bulk_import']['size'] != 0 )
			{
				$filename = $_FILES['bulk_import']['name']."-".$date;

				if ($_FILES["bulk_import"]["error"] > 0)
				{
					$message = "Error: " . $_FILES["bulk_import"]["error"] . "<br>";
					$type = "error";
				}
				else
				{
					//Upload File
					"Upload: " . $_FILES["bulk_import"]["name"] . "<br>";
					"Type: " . $_FILES["bulk_import"]["type"] . "<br>";
					"Size: " . ($_FILES["bulk_import"]["size"] / 1024) . " kB<br>";
					"stored in:" .move_uploaded_file($_FILES["bulk_import"]["tmp_name"],OER_PATH."upload/".$filename) ;
				}

				//Read Excel Data
				$excl_obj->read(OER_PATH."upload/".$filename);
			}
		}

			$fnldata = $excl_obj->sheets;
			$length = count($fnldata);

			$ids_arr = array(0);
			$cat_ids = array(0);
			$page_ids = array(0);
			$cnt = 0;
			for($i = 0; $i < $length; $i++)
			{
				for($j = 1; $j <= $fnldata[$i]['numRows']; $j++)
				{
					for($k = 1; $k <= $fnldata[$i]['numCols']; $k++)
					{
						if(!empty($fnldata[$i]['cells'][$j][$k]))
						{
							$title = $fnldata[$i]['cells'][$j][$k];
							$description = '';
							if(strpos($title, "|"))
							{
								//$title = strip_tags($title);
								$cattl = explode("|",$title);
								$title = $cattl[0];
								$description = $cattl[1];
							}

							if(!term_exists( $title, "resource-subject-area", $ids_arr[$k-1] ))
							{
								$catslug = oer_slugify($title);
								$catarr = array(  'cat_name' => $title, 'category_parent' => $ids_arr[$k-1],'taxonomy' => 'resource-subject-area','category_description' => $description,'category_nicename'=>$catslug );

								$rsc_parentid = wp_insert_category( $catarr ); //Insert Resource Category
								$cat_parentid = wp_create_category( $title, $cat_ids[$k-1] ); //Insert Post Category

								$ids_arr[$k] = $rsc_parentid;
								$cat_ids[$k] = $cat_parentid;

								//Create Pages
								/*$term = get_term( $rsc_parentid , "resource-category", ARRAY_A );
								$slug = $term['slug'];

								$post =array('comment_status' => 'closed', 'ping_status' =>  'closed', 'post_author' => 1, 'post_date' => date('Y-m-d H:i:s'), 	'post_name' => $slug, 'post_status'=> 'publish', 'post_title' => $title, 'post_type' => 'page', 'post_content' =>$content, 'post_parent' => $page_ids[$k-1]);
								$newvalue = wp_insert_post( $post, false );
								$page_ids[$k] = $newvalue;
								update_post_meta( $newvalue, '_wp_page_template', get_option("oer_category_template") );*/
								$cnt++;
								$wpdb->get_results( $wpdb->prepare( "insert into " . $wpdb->prefix. "category_page values('', %s, %s, %s, %s)" , $cat_parentid , $rsc_parentid , $newvalue, $title));
								break;
							}
							else
							{
								$rsc_parentid = term_exists( $title, "resource-subject-area", $ids_arr[$k-1]);
								$ids_arr[$k] = $rsc_parentid['term_id'];

								$cat_parentid = term_exists( $title, "category", $cat_ids[$k-1]);
								$cat_ids[$k] = $cat_parentid['term_id'];

								$term = get_term( $ids_arr[$k] , "resource-subject-area" );
								$slug = $term->slug;

								$page = oer_get_page_by_slug($slug, ARRAY_A, "page", $page_ids[$k-1] );
								$page_ids[$k] = $page['ID'];
							}

						}
					}//For All Data Columns
				}//For All Data Rows
			}// For Multiple Sheeet
	} catch (Exception $e) {
		// Log any error encountered during the import process
		debug_log($e->getMessage());
	}
	// Log finish of import process
	debug_log("OER Subject Areas Importer: Finished Bulk Import ");

	$message = sprintf(__("Successfully imported %s subject areas.", 'wp-oer'), $cnt);
	$type = "success";
	$response = array('message' => $message, 'type' => $type);
	return $response;
}

//Import Default CCSS
function oer_importDefaultStandards() {
	$files = array(
		OER_PATH."samples/CCSS_Math.xml",
		OER_PATH."samples/CCSS_ELA.xml",
		OER_PATH."samples/NGSS.xml"
		);
	foreach ($files as $file) {
		$import = oer_importStandards($file);
		if ($import['type']=="success") {
		    if (strpos($file,'Math')) {
				$message .= "Successfully imported Common Core Mathematics Standards. \n";
		    } else {
				$message .= "Successfully imported Common Core English Language Arts Standards. \n";
		    }
		}
		$type = $import['type'];
	}
	$response = array( 'message' => $message, 'type' => $type );
	return $response;
}

/** Fetch Standard **/
function oer_fetch_stndrd($pId, $postid)
{
	global $wpdb, $_oer_prefix;
	$table = explode("-", $pId);

	$stndrd_algn = $wpdb->get_row( $wpdb->prepare( "SELECT * from  " . $wpdb->prefix.$_oer_prefix. $table[0] . " where id =%s" , $table[1] ),ARRAY_A);

	if(preg_match("/core_standards/", $table[0]))
	{
		$return = $stndrd_algn['id'];
		update_post_meta( $postid, 'oer_standard_alignment' , $return);
	}
	elseif($stndrd_algn['parent_id'])
	{
		oer_fetch_stndrd($stndrd_algn['parent_id'], $postid);
	}
}

/** Get Page By Slug **/
function oer_get_page_by_slug($page_slug, $output = OBJECT, $post_type = 'page', $parent = 0 )
{
	global $wpdb;
	$page = $wpdb->get_var($wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_parent = %d AND post_status = 'publish'", $page_slug, $post_type, $parent ));

	if ($page)
		return get_post($page, $output);
		return null;
}

//Limited Content
function oer_content($the_content, $limit) {
  $content = explode(' ', $the_content, $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}

//Get Child Subject Areas
function oer_get_child_subjects($subject_area_id) {
	$args = array('hide_empty' => 0, 'taxonomy' => 'resource-subject-area','parent' => $subject_area_id);
	$subchildren = get_categories($args);
	return $subchildren;
}

//Get Resource Count from Subject Area
function oer_get_subject_resource_count($subject_id) {
	$args = array(
		'post_type' => 'resource',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($subject_id)))
	);
	$resources = get_posts($args);
	return count($resources);
}

/** Display Sort Box **/
function oer_get_sort_box($subjects=array()){
	global $oer_session;

	if (!isset($oer_session))
		$oer_session = OER_WP_Session::get_instance();

	$sort = 0;
	if (isset($oer_session['resource_sort']))
		$sort = (int)$oer_session['resource_sort'];
	?>
	<div class="sort-box">
		<span class="sortoption"></span>
		<span class="sort-resources" title="Sort stories" tabindex="0" role="button"><i class="fa fa-sort" aria-hidden="true"></i></span>
		<div class="sort-options">
			<ul>
				<li data-value="0"<?php if ($sort==0): ?> class="cs-selected"<?php endif; ?>><a href="javascript:void(0);"><span>Newest</span></a></li>
				<li data-value="1"<?php if ($sort==1): ?> class="cs-selected"<?php endif; ?>><a href="javascript:void(0);"><span>Oldest</span></a></li>
				<li data-value="2"<?php if ($sort==2): ?> class="cs-selected"<?php endif; ?>><a href="javascript:void(0);"><span>A-Z</span></a></li>
				<li data-value="3"<?php if ($sort==3): ?> class="cs-selected"<?php endif; ?>><a href="javascript:void(0);"><span>Z-A</span></a></li>
			</ul>
		</div>
		<select class="sort-selectbox" data-subject-ids="<?php echo json_encode($subjects); ?>">
			<option value="0"<?php if ($sort==0): ?>  selected<?php endif; ?>>Newest</option>
			<option value="1"<?php if ($sort==1): ?>  selected<?php endif; ?>>Oldest</option>
			<option value="2"<?php if ($sort==2): ?>  selected<?php endif; ?>>A-Z</option>
			<option value="3"<?php if ($sort==3): ?>  selected<?php endif; ?>>Z-A</option>
		</select>
	</div>
	 <?php
}

/** Apply Sort Arguments **/
function oer_apply_sort_args($args){
	global $oer_session;

	if (!isset($oer_session))
		$oer_session = OER_WP_Session::get_instance();

	$sort = 0;
	if (isset($oer_session['resource_sort']))
		$sort = (int)$oer_session['resource_sort'];

	switch($sort){
		case 0:
			$args['orderby'] = 'post_date';
			$args['order'] = 'DESC';
			break;
		case 1:
			$args['orderby'] = 'post_date';
			$args['order'] = 'ASC';
			break;
		case 2:
			$args['orderby'] = 'post_title';
			$args['order'] = 'ASC';
			break;
		case 3:
			$args['orderby'] = 'post_title';
			$args['order'] = 'DESC';
			break;
	}
	return $args;
}

/** Get Image Sizes for all registered image sizes **/
function oer_get_image_sizes() {
	global $_wp_additional_image_sizes;

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	return $sizes;
}

/** Get Image size for specific size **/
function oer_get_image_size( $size ) {
	$sizes = oer_get_image_sizes();

	if ( isset( $sizes[ $size ] ) ) {
		return $sizes[ $size ];
	}

	return false;
}

/** Get Image Width based on size **/
function oer_get_image_width( $size ) {
	if ( ! $size = oer_get_image_size( $size ) ) {
		return false;
	}

	if ( isset( $size['width'] ) ) {
		return $size['width'];
	}

	return false;
}

/** Get Image Height based on size **/
function oer_get_image_height( $size ) {
	if ( ! $size = oer_get_image_size( $size ) ) {
		return false;
	}

	if ( isset( $size['height'] ) ) {
		return $size['height'];
	}

	return false;
}

/** Display loader image **/
function oer_display_loader(){
?>
<div class="loader"><div class="loader-img"><div><img src="<?php echo OER_URL; ?>images/loading.gif" align="center" valign="middle" /></div></div></div>
<?php
}

/** Delete Standards Data **/
function oer_delete_standards() {
	global $wpdb;

	//Check if standard notations exist
	$standard_notations = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."oer_standard_notation", ARRAY_A);

	//Delete Standard Notation
	if (count($standard_notations)>0){
		$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."oer_standard_notation");
	}

	//Check if substandards exist
	$substandards = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."oer_sub_standards");

	//Delete Substandards
	if (count($substandards)>0){
		$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."oer_sub_standards");
	}

	//Check if core standards exist
	$core_standards = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."oer_core_standards");

	//Delete Core Standards
	if (count($core_standards)>0){
		$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."oer_core_standards");
	}

	$message = __("Successfully deleted standards", 'wp-oer');
	$type = "success";
	$response = array( 'message' => $message, 'type' => $type );
	return $response;
}

/* Delete Resource Subject Area Taxonomies */
function oer_delete_subject_areas(){
	$terms = get_terms(array('taxonomy' => 'resource-subject-area', 'hide_empty' => false));
	foreach($terms as $term) {
		wp_delete_term( $term->term_id, 'resource-subject-area' );
	}
	$message = __("Successfully deleted all subject areas", 'wp-oer');
	$type = "success";
	$response = array( 'message' => $message, 'type' => $type );
	return $response;
}

/** Delete All Resources **/
function oer_delete_resources(){
	global $wpdb;

	//Check if term relationships data exist
	$term_relationships = $wpdb->get_results($wpdb->prepare("SELECT * FROM ". $wpdb->prefix."term_relationships WHERE object_id IN (
						SELECT ID
						FROM  ".$wpdb->prefix."posts
						WHERE post_type =  %s
						)", "resource"), ARRAY_A);

	/** Delete Term related to resources **/
	if (count($term_relationships)>0) {
		$wpdb->query($wpdb->prepare("DELETE FROM  ". $wpdb->prefix ."term_relationships WHERE object_id IN (
						SELECT ID
						FROM  ".$wpdb->prefix."posts
						WHERE post_type =  %s
						)","resource"));
	}

	//Check if postmeta data exist
	$post_meta = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id IN (
						SELECT ID
						FROM  ".$wpdb->prefix."posts
						WHERE post_type =  %s
						)","resource"), ARRAY_A);

	/** Delete Post meta related to resources **/
	if (count($post_meta)>0) {
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."postmeta WHERE post_id IN (
						SELECT ID
						FROM  ".$wpdb->prefix."posts
						WHERE post_type =  %s
						)","resource"));
	}

	//Check if resources exist
	$resources = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = %s","resource"), ARRAY_A);

	/** Delete all resources **/
	if (count($resources)>0) {
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."posts WHERE post_type = %s","resource"));
	}

	$message = __("Successfully deleted all resources", 'wp-oer');
	$type = "success";
	$response = array( 'message' => $message, 'type' => $type );
	return $response;
}

/** Delete Resource Media **/
function oer_delete_resource_media() {

	$args = array(
		'post_type' => 'resource',
		'posts_per_page' => -1
		      );

	$posts = get_posts($args);
	foreach($posts as $post) {
		if (has_post_thumbnail($post->ID)){
			delete_post_thumbnail($post->ID);
			$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
			wp_delete_attachment($post->ID);
		}
	}
	$message = __("Successfully deleted resource media", 'wp-oer');
	$type = "success";
	$response = array( 'message' => $message, 'type' => $type );
	return $response;
}

/** Remove plugin settings **/
function oer_remove_plugin_settings(){
	//General Settings
	if (get_option('oer_disable_screenshots'))
		delete_option('oer_disable_screenshots');

	if (get_option('oer_enable_screenshot'))
		delete_option('oer_enable_screenshot');

	//if (get_option('oer_use_xvfb'))
		delete_option('oer_use_xvfb');

	//if (get_option('oer_python_install'))
		delete_option('oer_python_install');

	//if (get_option('oer_python_path'))
		delete_option('oer_python_path');

	//if (get_option('oer_external_screenshots'))
		delete_option('oer_external_screenshots');

	if (get_option('oer_service_url'))
		delete_option('oer_service_url');

	//Styles Settings
	//if (get_option('oer_use_bootstrap'))
		delete_option('oer_use_bootstrap');

	//if (get_option('oer_display_subject_area'))
		delete_option('oer_display_subject_area');

	//if (get_option('oer_hide_subject_area_title'))
		delete_option('oer_hide_subject_area_title');

	//if (get_option('oer_hide_resource_title'))
		delete_option('oer_hide_resource_title');

	//if (get_option('oer_additional_css'))
		delete_option('oer_additional_css');


	//Setup Settings
	if (get_option('oer_import_sample_resources'))
		delete_option('oer_import_sample_resources');

	if (get_option('oer_import_default_subject_areas'))
		delete_option('oer_import_default_subject_areas');

	if (get_option('oer_import_ccss'))
		delete_option('oer_import_ccss');

	//if (get_option('oer_use_bootstrap'))
		delete_option('oer_use_bootstrap');

	$message = __("Successfully removed all plugin settings", 'wp-oer');
	$type = "success";
	$response = array( 'message' => $message, 'type' => $type );
	return $response;
}

function oer_sanitize_subject($subject) {
	return intval($subject);
}

function oer_is_youtube_url($url) {
	$match = false;

	$pattern = '/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/';
	$pattern_match = preg_match($pattern, $url, $matches);

	if ($pattern_match == 1)
		$match = true;

	return $match;
}

function oer_is_sll_resource($url) {
	$match = false;

	//$pattern = '/^(http(s)?:\/\/)?((w){3}.)?learninglab.si?(\.edu)?\/.+/';
	$pattern = '/^(http(s)?:\/\/)?((w){3}.)?learninglab.si?(\.edu)?\/resource(s)\/view\/.+/';
	$pattern_match = preg_match($pattern, $url, $matches);

	if ($pattern_match == 1)
		$match = true;

	return $match;
}

function oer_is_sll_collection($url) {
	$match = false;

	//$pattern = '/^(http(s)?:\/\/)?((w){3}.)?learninglab.si?(\.edu)?\/.+/';
	$pattern = '/^(http(s)?:\/\/)?((w){3}.)?learninglab.si?(\.edu)?\/(collection(s)|q)\/.+/';
	$pattern_match = preg_match($pattern, $url, $matches);

	if ($pattern_match == 1)
		$match = true;

	return $match;
}

//Generate youtube embed code
function oer_generate_youtube_embed_code($url) {
	$embed_code = "";

	$youtube_id = oer_get_youtube_id($url);

	//Generate embed code
	if ($youtube_id) {
		$embed_code = '<div class="videoWrapper"><iframe width="640" height="360" src="https://www.youtube.com/embed/'.$youtube_id.'?rel=0" frameborder="0" allowfullscreen></iframe></div>';
	}
	return $embed_code;
}

function oer_get_youtube_id($url) {
	$youtube_id = null;

	if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
		$youtube_id = $id[1];
	} else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
		$youtube_id = $id[1];
	} else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $id)) {
		$youtube_id = $id[1];
	} else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
		$youtube_id = $id[1];
	} else if (preg_match('/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^\&\?\/]+)/', $url, $id)) {
		$youtube_id = $id[1];
	}

	return $youtube_id;
}

// Get Youtube Thumbnail
function oer_get_youtube_thumbnail($youtube_url){
	$youtube_id = oer_get_youtube_id($youtube_url);

	$thumbnail_url = "https://i.ytimg.com/vi/".$youtube_id."/hqdefault.jpg";

	$thumbnail_file = oer_save_image_to_file($thumbnail_url);

	return $thumbnail_file;
}

function oer_generate_sll_resource_embed_code($url){
	$embed_code = "";

	$sll_resource_id = oer_get_ssl_resource_id($url);

	//Generate embed code
	if ($sll_resource_id) {
		$embed_code = '<script type="text/javascript" src="https://learninglab.si.edu/embed/widget/q/r/'.$sll_resource_id.'/embed.js"></script><div class="sll-embed" data-widget-type="r" data-widget-key="'.$sll_resource_id.'"></div>';
	}
	return $embed_code;
}

function oer_get_ssl_resource_id($url){
	$resource_id = null;

	if (preg_match('/learninglab\.si\.edu\/resources\/view\/([^\&\?\/]+)/', $url, $id)) {
		$resource_id = $id[1];
	}

	return $resource_id;
}

function oer_generate_sll_collection_embed_code($url){
	$embed_code = "";

	$sll_collection_id = oer_get_ssl_collection_id($url);

	//Generate embed code
	if ($sll_collection_id) {
		$embed_code = '<script type="text/javascript" src="https://learninglab.si.edu/embed/widget/q/c/'.$sll_collection_id.'/embed.js"></script><div class="sll-embed" data-widget-type="c" data-widget-key="'.$sll_collection_id.'"></div>';
	}
	return $embed_code;
}

function oer_get_ssl_collection_id($url){
	$collection_id = null;

	if (preg_match('/learninglab\.si\.edu\/collections\/[A-Za-z0-9\_\@\.\/\#\&\+\-]*\/([^\&\?\/]+)/', $url, $id)) {
		$collection_id = $id[1];
	}elseif (preg_match('/learninglab\.si\.edu\/q\/[A-Za-z0-9\_\@\.\/\#\&\+\-]*\/([^\&\?\/]+)/', $url, $id)) {
		$collection_id = $id[1];
	}

	if (substr($collection_id,-2)=="#r"){
		$collection_id = substr($collection_id,0,strrpos($collection_id,"#r"));
	}

	return $collection_id;
}

// Get Subject Areas
function oer_get_subject_areas($resource_id){
	// Resource Subject Areas
	$subject_areas = array();
	$post_terms = get_the_terms( $resource_id, 'resource-subject-area' );

	if(!empty($post_terms))
	{
		foreach($post_terms as $term)
		{
			if($term->parent != 0)
			{
				$parent[] = oer_get_parent_term($term->term_id);
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
	}
	return $subject_areas;
}

//Replace PDF Url to embedded PDF
//add_filter( 'the_content' , 'replace_pdf_to_embed' );
function replace_pdf_to_embed($content){
    $pattern = '/(http|https):\/\/.*?\.pdf\b/i';

    $matches = array();

    preg_match_all($pattern, $content, $matches);

    foreach ($matches[0] as $match) {
	$match_url = strip_tags($match);
	if(shortcode_exists('wonderplugin_pdf')) {
		$embed_code = "[wonderplugin_pdf src='".$match_url."' width='100%']";
	} elseif(shortcode_exists('pdf-embedder')){
		$embed_code = "[pdf-embedder url='".$match_url."' width='100%']";
	} elseif(shortcode_exists('pdfviewer')){
		$embed_code = "[pdfviewer width='100%']".$match_url."[/pdfviewer]";
	} else {
		$pdf_url = OER_URL."pdfjs/web/viewer.html?file=".urlencode($match_url);
		$embed_code = '<iframe class="oer-pdf-viewer" width="100%" src="'.$pdf_url.'"></iframe>';
	}
	if ($embed_code)
	    $content = str_replace($match, $embed_code, $content);
    }
    return $content;
}

function is_pdf_resource($url) {
	$is_pdf = false;

	if (preg_match('/(http|https):\/\/.*?\.pdf\b/i', $url, $id))
		$is_pdf = true;

	return $is_pdf;
}

function is_external_url($url) {
	$is_external = false;

	$base_host = parse_url(home_url(), PHP_URL_HOST);
	$url_host = parse_url($url, PHP_URL_HOST);

	if ($base_host !== $url_host)
		$is_external = true;

	return $is_external;
}

/**
 * Get Standards Count
 **/
function get_standards_count(){
	global $wpdb;
	$cnt = 0;

	$query = "SELECT count(*) FROM {$wpdb->prefix}oer_core_standards";

	$cnt = $wpdb->get_var($query);

	return $cnt;
}

/**
 * Get Standards
 **/
function get_standards(){
	global $wpdb;

	$query = "SELECT * FROM {$wpdb->prefix}oer_core_standards";

	$standards = $wpdb->get_results($query);

	return $standards;
}

/**
 * Get Standard By Slug
 **/
function get_standard_by_slug($slug){
	global $wpdb;

	$std = null;

	$query = "SELECT * FROM {$wpdb->prefix}oer_core_standards";

	$standards = $wpdb->get_results($query);

	foreach($standards as $standard){
		if (sanitize_title($standard->standard_name)===$slug)
			$std = $standard;
	}

	return $std;
}

/**
 * Get Standard By Id
 **/
function get_standard_by_id($id){
	global $wpdb;

	$std = null;

	$query = "SELECT * FROM {$wpdb->prefix}oer_core_standards WHERE id = %d";

	$standards = $wpdb->get_results($wpdb->prepare($query,$id));

	foreach($standards as $standard){
			$std = $standard;
	}

	return $std;
}

/**
 * Get SubStandard By Slug
 **/
function get_substandard_by_slug($slug){
	global $wpdb;

	$std = null;

	$query = "SELECT * FROM {$wpdb->prefix}oer_sub_standards";

	$substandards = $wpdb->get_results($query);

	foreach($substandards as $substandard){
		if (sanitize_title($substandard->standard_title)===$slug)
			$std = $substandard;
	}

	return $std;
}

/**
 * Get child standards of a core standard
 **/
function get_substandards($standard_id, $core=true){
	global $wpdb;

	if ($core)
		$std_id = "core_standards-".$standard_id;
	else
		$std_id = "sub_standards-".$standard_id;

	$substandards = array();

	$query = "SELECT * FROM {$wpdb->prefix}oer_sub_standards where parent_id='%s'";

	$substandards = $wpdb->get_results($wpdb->prepare($query, $std_id));

	return $substandards;
}

/**
 * Get Standard Notations under a Sub Standard
 **/
function get_standard_notations($standard_id){
	global $wpdb;

	$std_id = "sub_standards-".$standard_id;

	$notations = array();

	$query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation where parent_id='%s'";

	$result = $wpdb->get_results($wpdb->prepare($query, $std_id));

	foreach ($result as $row){
		$notations[] = $row;
	}

	return $notations;
}

/**
 * Get Parent Sub Standard by Notation
 **/
function get_substandard_by_notation($notation) {
	global $wpdb;

	$std = null;

	$query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation WHERE standard_notation = '%s'";

	$substandards = $wpdb->get_results($wpdb->prepare($query, $notation));

	foreach($substandards as $substandard){
		$std = $substandard;
	}

	return $std;
}

/**
 * Get Core Standard by Notation
 **/
function get_standard_by_notation($notation){
	global $wpdb;

	$std = null;

	$query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation WHERE standard_notation = '%s'";

	$standard_notation = $wpdb->get_results($wpdb->prepare($query, $notation));

	if ($standard_notation){
		$substandard_id = $standard_notation[0]->parent_id;
		$substandard = oer_get_parent_standard($substandard_id);

		if (strpos($substandard[0]['parent_id'],"core_standards")!==false){
			$pIds = explode("-",$substandard[0]['parent_id']);

			if (count($pIds)>1){
			    $parent_id=(int)$pIds[1];
			    $std = get_standard_by_id($parent_id);
			}
		}
	}

	return $std;
}

/**
 * Get Substandard(s) by Notation
 **/
function get_substandards_by_notation($notation){
	global $wpdb;

	$std = null;

	$query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation WHERE standard_notation = '%s'";

	$standard_notation = $wpdb->get_results($wpdb->prepare($query, $notation));

	if ($standard_notation){
		$substandard_id = $standard_notation[0]->parent_id;
		$std = get_hierarchical_substandards($substandard_id);
	}

	return $std;
}

/**
 * Get hierarchical substandards by substandard id
 **/
function get_hierarchical_substandards($substandard_id) {

	$stds = null;

	$substandard = oer_get_parent_standard($substandard_id);

	foreach($substandard as $std){
		$stds[] = $std;
	}

	if (strpos($substandard[0]['parent_id'],"sub_standards")!==false){
		$stds[] = get_hierarchical_substandards($substandard[0]['parent_id']);
	}

	return $stds;
}

/**
 * Get Resources by notation
 **/
function get_resources_by_notation($notation_id) {

	$notation = "standard_notation-".$notation_id;

	//later in the request
	$args = array(
		'post_type'  => 'resource', //or a post type of your choosing
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
			'key' => 'oer_standard',
			'value' => $notation,
			'compare' => 'like'
			)
		)
	);

	$query = new WP_Query($args);

	return $query->posts;
}

function get_child_notations($notation_id){
	global $wpdb;

	$notation = "standard_notation-".$notation_id;

	$query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation WHERE parent_id = '%s'";

	$standard_notations = $wpdb->get_results($wpdb->prepare($query, $notation));

	return $standard_notations;
}

/**
 * Get Resource Count By Notation
 **/
function get_resource_count_by_notation($notation_id){
	$cnt = 0;

	$notation = "standard_notation-".$notation_id;

	//later in the request
	$args = array(
		'post_type'  => 'resource', //or a post type of your choosing
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
			'key' => 'oer_standard',
			'value' => $notation,
			'compare' => 'like'
			)
		)
	);

	$query = new WP_Query($args);

	$cnt += count($query->posts);

	$child_notations = get_child_notations($notation_id);

	if ($child_notations){
		foreach ($child_notations as $child_notation){
			$cnt += get_resource_count_by_notation($child_notation->id);
		}
	}

	return $cnt;
}

/**
 * Get Resource Count By Sub-Standard
 **/
function get_resource_count_by_substandard($substandard_id){
	$cnt = 0;

	$child_substandards = get_substandards($substandard_id, false);

	if(count($child_substandards)>0){
		foreach($child_substandards as $child_substandard){
			$cnt += get_resource_count_by_substandard($child_substandard->id, false);
		}
	}
	$notations = get_standard_notations($substandard_id);

	if ($notations){
		foreach($notations as $notation){
			$cnt += get_resource_count_by_notation($notation->id);
		}
	}
	return $cnt;
}

/**
 * Get Resource Count By Standard
 **/
function get_resource_count_by_standard($standard_id){

	$cnt = 0;

	$substandards = get_substandards($standard_id);

	if(count($substandards)>0){
		foreach($substandards as $substandard){
			$cnt += get_resource_count_by_substandard($substandard->id);
		}
	}
	$notations = get_standard_notations($standard_id);

	if ($notations){
		foreach($notations as $notation){
			$cnt += get_resource_count_by_notation($notation->id);
		}
	}
	return $cnt;
}

/**
 * Get Core Standard by standard or substandard ID
 **/
function get_corestandard_by_standard($parent_id){
	global $wpdb;

	$standard = null;
	$parent = explode("-",$parent_id);
	if ($parent[0]=="sub_standards") {
		$query = "SELECT * FROM {$wpdb->prefix}oer_sub_standards WHERE id = '%s'";
		$substandards = $wpdb->get_results($wpdb->prepare($query, $parent[1]));

		foreach($substandards as $substandard){
			$standard = get_corestandard_by_standard($substandard->parent_id);
		}
	} else {
		$query = "SELECT * FROM {$wpdb->prefix}oer_core_standards WHERE id = '%s'";
		$standards = $wpdb->get_results($wpdb->prepare($query, $parent[1]));
		foreach($standards as $std){
			$standard = $std;
		}
	}

	return $standard;
}

/**
 * Add OER Resource
 **/
function oer_add_resource($resource) {
	$post_name = "";
	$oer_resourceurl = "";
	$file = "";
	$post_id = null;
	$category_id = array();
	$oer_kywrd = null;

	if (!empty($resource['tags'])){
		$oer_kywrd = $resource['tags'];
	}

	// Save Subject Areas
	if(!empty($resource['subject_areas'])){
		$oer_categories = $resource['subject_areas'];
		if (is_array($oer_categories)) {
			$categories = array();
			foreach($oer_categories as $cat)
			{
				if (strpos($cat,"--")){
					$cats = explode(" -- ", $cat);
					$categories = array_merge($categories, $cats);
				} else {
					$categories[] = $cat;
				}
			}
			$categories = array_unique($categories);
			if(!empty($categories)){
				foreach($categories as $category){
					$cat = get_term_by( 'name', trim($category), 'resource-subject-area' );
					if($cat){
						$category_id[$i] = $cat->term_id;
					}
					else{
						// Categories are not found then assign as keyword
						$oer_kywrd[] = $category;
					}
				}
			}
		}
	}

	//Check if resource title is set
	if ( isset( $resource['title'] ) ){
		$post_name = strtolower($resource['title']);
		$post_name = str_replace(' ','_', $post_name);
	}

	// Save resource title
	if(!empty($resource['title']) && !empty($resource['resource_url']))
	{
		/** Get Current WP User **/
		$user_id = get_current_user_id();
		/** Get Current Timestamp for post_date **/
		$cs_date = current_time('mysql');

		$post = array('post_content' => $resource['description'], 'post_name' => $post_name, 'post_title' => $resource['title'], 'post_status' => 'publish', 'post_type' => 'resource', 'post_author' => $user_id , 'post_date' => $cs_date, 'post_date_gmt'  => $cs_date, 'comment_status' => 'open');
		/** Set $wp_error to false to return 0 when error occurs **/
		$post_id = wp_insert_post( $post, false );

		//Set Category of Resources
		$tax_ids = wp_set_object_terms( $post_id, $category_id, 'resource-subject-area', true );

		// Set Tags
		if (!is_array($oer_kywrd))
			$oer_kywrd = explode(",", $oer_kywrd);

		wp_set_post_tags(  $post_id, $oer_kywrd , true );
	}

	// Save Resource URL and Create Screenshot
	if( !empty($resource['resource_url']) )
	{
		if ( preg_match('/http/',$resource['resource_url']) )
		{
			$oer_resourceurl = $resource['resource_url'];
		}
		else
		{
			$oer_resourceurl = 'http://'.$resource['resource_url'];
		}
		update_post_meta( $post_id , 'oer_resourceurl' , esc_url_raw($oer_resourceurl));

		//Create Screenshot
		//--------------------------------------
		$url = esc_url_raw($oer_resourceurl);
		$upload_dir = wp_upload_dir();

		//if screenshot is enabled
		$screenshot_enabled = get_option( 'oer_enable_screenshot' );
		//if external service screenshot is enabled
		$external_screenshot = get_option( 'oer_external_screenshots' );

		if(!has_post_thumbnail( $post_id ))
		{
			if ($screenshot_enabled) {
				$file = oer_getScreenshotFile($url);
			} elseif ( $external_screenshot ) {
				// if external screenshot utility enabled
				$file = oer_getImageFromExternalURL($url);
			}
		}

		if(file_exists($file))
		{
			$filetype = wp_check_filetype( basename( $file ), null );
			$wp_upload_dir = wp_upload_dir();

			$guid = $wp_upload_dir['url'] . '/' . basename( $file );

			$attachment = array(
				'guid'           => $guid,
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
			update_post_meta($post_id, "_thumbnail_id", $attach_id);

			// Generate the metadata for the attachment, and update the database record.
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );
		}
		//--------------------------------------
	}

	// Save Date Created
	if(!empty($resource['date_created'])){
		update_post_meta( $post_id , 'oer_datecreated' , $resource['date_created']);
	}

	// Save Author Type
	if(!empty($resource['author_type'])){
		update_post_meta( $post_id , 'oer_authortype' , sanitize_text_field($resource['author_type']));
	}

	// Save Author Name
	if(!empty($resource['author_name'])){
		update_post_meta( $post_id , 'oer_authorname' , sanitize_text_field($resource['author_name']));
	}

	// Save Author Url
	if(!empty($resource['author_url'])){
		$oer_authorurl = $resource['author_url'];
		if ( preg_match('/http/',$oer_authorurl) ){
			$oer_authorurl = $oer_authorurl;
		}
		else{
			$oer_authorurl = 'http://'.$oer_authorurl;
		}
		update_post_meta( $post_id , 'oer_authorurl' , esc_url_raw($oer_authorurl));
	}

	// Save Publisher name
	if(isset($resource['publisher_name'])){
		update_post_meta( $post_id , 'oer_publishername' , sanitize_text_field($resource['publisher_name']));
	}

	// Save publisher url
	if(isset($resource['publisher_url']))
	{
		$oer_publisherurl = $resource['publisher_url'];
		if( !empty($resource['publisher_url']) )
		{
			if ( preg_match('/http/',$oer_publisherurl) )
			{
				$oer_publisherurl = $resource['publisher_url'];
			}
			else
			{
				$oer_publisherurl = 'http://'.$resource['publisher_url'];
			}
		}
		update_post_meta( $post_id , 'oer_publisherurl' , esc_url_raw($oer_publisherurl));
	}

	// Save media type
	if(!empty($resource['media_type'])){
		update_post_meta( $post_id , 'oer_mediatype' , sanitize_text_field($resource['media_type']));
	}

	// Save Learning Resource Type
	if(!empty($resource['lr_type'])){
		update_post_meta( $post_id , 'oer_lrtype' , sanitize_text_field($resource['media_type']));
	}

	// Save Interactivity
	if(!empty($resource['interactivity'])){
		update_post_meta( $post_id , 'oer_interactivity' , sanitize_text_field($resource['interactivity']));
	}

	// Save Based On URL
	if(!empty($resource['based_on_url'])){
		update_post_meta( $post_id , 'oer_isbasedonurl' , sanitize_text_field($resource['based_on_url']));
	}

}

// Checks if resource exists
function resource_exists($resource){
	$exists = false;

	$args = array(
		'fields' => 'ids',
		'post_type'  => 'resource',
		'meta_query' => array(
			array(
				'key' => 'oer_resourceurl',
				'value' => $resource['resource_url']
			)
		)
	);

	$my_query = new WP_Query( $args );

	if( $my_query->post_count>0 ) {
		$exists = true;
	}
	return $exists;
}

// Get Hierarchical Notations
function get_hierarchical_notations($notation_id){
	$notation=null;
	$notations = array();
	$hierarchy = "";
	$ids = explode("-",$notation_id);
	if (strpos($notation_id,"standard_notation")!==false) {
		do {
			$notation = get_notation_details($ids[1]);
			$ids = explode("-", $notation[0]['parent_id']);
			$notations[] = $notation;
		} while(strpos($notation[0]['parent_id'],"standard_notation")!==false);
	}
	return $notations;
}

// Get Notation Details
function get_notation_details($notation_id){
	global $wpdb;
	$notations = null;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where id = %s" , $notation_id  ) , ARRAY_A);
	foreach ($results as $row){
		$notations = $row;
	}
	return $notations;
}

// Get Hierarchical Substandards
function oer_get_hierarchical_substandards($substandard_id){
	$substandard=null;
	$substandards = null;
	$hierarchy = "";
	$ids = explode("-",$substandard_id);
	if (strpos($substandard_id,"sub_standards")!==false) {
		do {

			$substandard = oer_get_substandard_details($ids[1]);
			$ids = explode("-", $substandard['parent_id']);
			$substandards[] = $substandard;

		} while(strpos($substandard['parent_id'],"sub_standards")!==false);
	}

	return $substandards;
}

// Get Substandard Details
function oer_get_substandard_details($substandard_id){
	global $wpdb;
	$substandards = null;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where id = %s" , $substandard_id  ) , ARRAY_A);
	foreach ($results as $row){
		$substandards = $row;
	}
	return $substandards;
}
?>
