<?php
function get_sub_standard($id, $oer_standard)
{
	global $wpdb;
	global $chck, $class;
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

// Get Screenshot File
function getScreenshotFile($url)
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

//Get Category Child for Sidebar
if (!function_exists('get_oer_category_child')) {
	function get_oer_category_child($categoryid, $child_term_id = 0)
	{
		$args = array('hide_empty' => 0, 'taxonomy' => 'resource-subject-area','parent' => $categoryid);
		$catchilds = get_categories($args);
		$term = get_the_title();
		
		//$rsltdata = get_term_by( "name", $term, "resource-category", ARRAY_A );
		$rsltdata = get_term_by( "id", $child_term_id, "resource-subject-area", ARRAY_A );
		
		$parentid = array();
		if($rsltdata['parent'] != 0)
		{
			$parent = get_oer_parent_term($rsltdata['parent']);
			
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
			echo '<ul class="category">';
			foreach($catchilds as $catchild)
			{
				//var_dump($catchild->term_id);
				//var_dump($rsltdata['term_id']);
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
					echo '<li class="sub-category has-child'.$class.'" title="'. $catchild->name .'" >
							<span onclick="toggleparent(this);">
								<a href="'. site_url() .'/resource-subject-area/'. $catchild->slug .'">' . $catchild->name .'</a>
							</span>';
				}
				else
				{
					echo '<li class="sub-category'.$class.'" title="'. $catchild->name .'" >
							<span onclick="toggleparent(this);">
								<a href="'. site_url() .'/resource-subject-area/'. $catchild->slug .'">' . $catchild->name .'</a>
							</span>';
				}
				get_oer_category_child( $catchild->term_id);
				echo '</li>';
			}
			echo '</ul>';
		}
	}
}

//GET Custom Texonomy Parent
if (!function_exists('get_custom_oer_category_parents')) {
	function get_custom_oer_category_parents( $id, $taxonomy = false, $link = false, $separator = '/', $nicename = false, $visited = array() ) {
	
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
			$chain .= get_custom_oer_category_parents( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
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
if (!function_exists('get_oer_parent_term')) {
	function get_oer_parent_term($id)
	{
		$curr_cat = get_category_parents($id, false, '/' ,true);
		$curr_cat = explode('/',$curr_cat);
	
		return $curr_cat;
	}
}

if (!function_exists('get_term_top_most_parent')) {
	function get_term_top_most_parent($term_id, $taxonomy="resource-subject-area"){
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
if (!function_exists('get_oer_post_count')) {
	function get_oer_post_count($category, $taxonomy)
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
			$rtrn .= '<ul class="category">';
			foreach($catchilds as $catchild)
			{
				$children = get_term_children($catchild->term_id, 'resource-subject-area');
				$count = get_oer_post_count($catchild->term_id, "resource-subject-area");
				$count = $count + $catchild->count;
				if( !empty( $children ) )
				{
					$rtrn .=  '<li class="sub-category has-child"><span onclick="toggleparent(this); gethght(this);"><a href="'. site_url() .'/resource-subject-area/'. $catchild->slug .'">' . $catchild->name .'</a><label>'. $count .'</label></span>';
				}
				else
				{
					$rtrn .=  '<li class="sub-category"><span onclick="toggleparent(this);"><a href="'. site_url() .'/resource-subject-area/'. $catchild->slug .'">' . $catchild->name .'</a><label>'. $count .'</label></span>';
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
function slugify($text)
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

function importStandards($file){
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
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "core_standards where standard_name = %s" , $title ));
				if(empty($results))
				{
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'core_standards values("", %s , %s)' , $title , $url ));
				}
			}
			// Get Core Standard
	
			// Get Sub Standard
			foreach($xml_arr as $key => $data)
			{
				$url = $key;
				$ischild = $data['ischild'];
				$title = $data['title'];
				$parent = '';
	
				$rsltset = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "core_standards where standard_url=%s" , $ischild ));
				if(!empty($rsltset))
				{
					$parent = "core_standards-".$rsltset[0]->id;
				}
				else
				{
					$rsltset_sec = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "sub_standards where url=%s" , $ischild ));
					if(!empty($rsltset_sec))
					{
						$parent = 'sub_standards-'.$rsltset_sec[0]->id;
					}
				}
	
				$res = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "sub_standards where parent_id = %s && url = %s" , $parent , $url ));
				if(empty($res))
				{
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'sub_standards values("", %s, %s, %s)' , $parent , $title , $url ));
				}
			}
			// Get Sub Standard
	
			// Get Standard Notation
			foreach($standard_notation as $st_key => $st_data)
			{
				$url = $st_key;
				$ischild = $st_data['ischild'];
				$notation = $st_data['title'];
				$description = $st_data['description'];
				$parent = '';
	
				$rsltset = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "sub_standards where url=%s" , $ischild ));
				if(!empty($rsltset))
				{
					$parent = 'sub_standards-'.$rsltset[0]->id;
				}
				else
				{
					$rsltset_sec = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "standard_notation where url=%s" , $ischild ));
					if(!empty($rsltset_sec))
					{
						$parent = 'standard_notation-'.$rsltset_sec[0]->id;
					}
				}
	
				$res = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "standard_notation where standard_notation = %s && parent_id = %s && url = %s" , $notation , $parent , $url ));
				if(empty($res))
				{
					//$description = preg_replace("/[^a-zA-Z0-9]+/", " ", html_entity_decode($description))
					$description = mysql_real_escape_string($description);
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'standard_notation values("", %s, %s, %s, "", %s)' , $parent , $notation , $description , $url ));
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

?>
