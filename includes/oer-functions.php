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
						</div><div class='oer_stndrd_desc'></div>";

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
				   <div class='oer_stndrd_desc'> ". $result['description']." </div>";

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
			echo '<ul class="oer-category">';
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
			$rtrn .= '<ul class="oer-category">';
			foreach($catchilds as $catchild)
			{
				$children = get_term_children($catchild->term_id, 'resource-subject-area');
				$count = get_oer_post_count($catchild->term_id, "resource-subject-area");
				$count = $count + $catchild->count;
				if( !empty( $children ) )
				{
					$rtrn .=  '<li class="oer-sub-category has-child"><span onclick="toggleparent(this); gethght(this);"><a href="'. site_url() .'/resource-subject-area/'. $catchild->slug .'">' . $catchild->name .'</a><label>'. $count .'</label></span>';
				}
				else
				{
					$rtrn .=  '<li class="oer-sub-category"><span onclick="toggleparent(this);"><a href="'. site_url() .'/resource-subject-area/'. $catchild->slug .'">' . $catchild->name .'</a><label>'. $count .'</label></span>';
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
					$description = esc_sql($description);
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

//Check if Standard Exists
function isStandardExisting($standard) {
	global $wpdb;
	
	$response = false;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "core_standards where standard_name like %s" , '%'.$standard.'%'));
	if(!empty($results))
		$response = true;
		
	return $response;
}

//Get Domain from Url
function getDomainFromUrl($url) {
	$url_details = parse_url($url);
	return $url_details['host'];
}

//Get Image from External URL
function getImageFromExternalURL($url) {
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

//Get External Thumbnail Image
function getExternalThumbnailImage($url) {
	global $_debug;
	
	$ch = curl_init ($url);
	
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
		debug_log("OER : start download image function");
			
		$fp = fopen($file,'wb');
		fwrite($fp, $raw);
		fclose($fp);
		
		debug_log("OER : end of download image function");
	}
	return $file;
}


function oer_resize_image($orig_img_url, $width, $height, $crop = false) {
	$new_image_url = "";
	
	$suffix = "{$width}x{$height}";
	
	if ( !function_exists( 'get_home_path' ) )
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	
	$home_path = get_home_path();
	
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
function importResources($default=false) {
	global $wpdb;
	require_once OER_PATH.'Excel/reader.php';
	
	debug_log("OER Resources Importer: Initializing Excel Reader");

	$excl_obj = new Spreadsheet_Excel_Reader();
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
						    $cat = get_term_by( 'name', $oer_categories [$i], 'resource-subject-area' );
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
					update_post_meta( $post_id , 'oer_resourceurl' , $oer_resourceurl);
				}
	
				if(!empty($oer_highlight))
				{
					update_post_meta( $post_id , 'oer_highlight' , $oer_highlight);
				}
	
				if(!empty($oer_grade))
				{
					$oer_grade = trim($oer_grade, '"');
					if(strpos($oer_grade , "-"))
					{
						$oer_grade = explode("-",$oer_grade);
						if(is_array($oer_grade))
						{
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
					update_post_meta( $post_id , 'oer_mediatype' , $oer_mediatype);
				}
				if(!empty($oer_lrtype))
				{
					update_post_meta( $post_id , 'oer_lrtype' , $oer_lrtype);
				}
				if(!empty($oer_interactivity))
				{
					update_post_meta( $post_id , 'oer_interactivity' , $oer_interactivity);
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
					update_post_meta( $post_id , 'oer_userightsurl' , $oer_userightsurl);
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
					update_post_meta( $post_id , 'oer_isbasedonurl' , $oer_isbasedonurl);
				}
				if(!empty($oer_standard))
				{
					$gt_oer_standard = '';
					for($l = 0; $l < count($oer_standard); $l++)
					{
						
						$results = $wpdb->get_row( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "standard_notation where standard_notation =%s" , $oer_standard[$l] ),ARRAY_A);
						if(!empty($results))
						{
							$gt_oer_standard .= "oer_standard_notation-".$results['id'].",";
							$table = explode("-", $results['parent_id']);
							if(!empty($table))
							{
								$stndrd_algn = $wpdb->get_row( $wpdb->prepare( "SELECT * from  " . $wpdb->prefix. $table[0] . " where id =%s" , $table[1] ),ARRAY_A);
								if($stndrd_algn['parent_id'])
								{
									fetch_stndrd($stndrd_algn['parent_id'], $post_id);
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
					update_post_meta( $post_id , 'oer_authortype' , $oer_authortype);
				}
				if(!empty($oer_authorname))
				{
					update_post_meta( $post_id , 'oer_authorname' , $oer_authorname);
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
					update_post_meta( $post_id , 'oer_authorurl' , $oer_authorurl);
				}
				if(!empty($oer_authoremail))
				{
					update_post_meta( $post_id , 'oer_authoremail' , $oer_authoremail);
				}
				if(!empty($oer_authortype2))
				{
					update_post_meta( $post_id , 'oer_authortype2' , $oer_authortype2);
				}
				if(!empty($oer_authorname2))
				{
					update_post_meta( $post_id , 'oer_authorname2' , $oer_authorname2);
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
					update_post_meta( $post_id , 'oer_authorurl2' , $oer_authorurl2);
				}
				if(!empty($oer_authoremail2))
				{
					update_post_meta( $post_id , 'oer_authoremail2' , $oer_authoremail2);
				}
	
				if(!empty($oer_publishername))
				{
					update_post_meta( $post_id , 'oer_publishername' , $oer_publishername);
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
						update_post_meta( $post_id , 'oer_publisherurl' , $oer_publisherurl);
				}
				if(!empty($oer_publisheremail))
				{
					update_post_meta( $post_id , 'oer_publisheremail' , $oer_publisheremail);
				}
				//saving meta fields
	
				if(!empty($oer_resourceurl))
				{
					$url = $oer_resourceurl;
					$upload_dir = wp_upload_dir();
					$file = '';
					
					//Check first if screenshot is enabled
					$screenshot_enabled = get_option( 'oer_enable_screenshot' );
					//Check if external service screenshot is enabled
					$external_screenshot = get_option( 'oer_external_screenshots' );
					
					if(!has_post_thumbnail( $post_id ))
					{
						if (!empty($oer_thumbnailurl)) {
							$file = getExternalThumbnailImage($oer_thumbnailurl);
						} elseif ($screenshot_enabled) {
							$file = getScreenshotFile($url);
						} elseif ( $external_screenshot ) {
							// if external screenshot utility enabled
							$file = getImageFromExternalURL($url);
						}
					}
					
					if(file_exists($file))
					{
						$filetype = wp_check_filetype( basename( $file ), null );
						$wp_upload_dir = wp_upload_dir();
	
						$attachment = array(
							'guid'           => $wp_upload_dir['url'] . '/' . basename( $file ),
							'post_mime_type' => $filetype['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);
	
						$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
						update_post_meta($post_id, "_thumbnail_id", $attach_id);
	
						// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
	
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
	$message = sprintf(__("Successfully imported %s resources.", OER_SLUG), $cnt);
	$type = "success";
	$response = array('message' => $message, 'type' => $type);
	return $response;
}

//Import Subject Areas
function importSubjectAreas($default=false) {
	global $wpdb;
	require_once OER_PATH.'Excel/reader.php';
	
	debug_log("OER Subject Areas Importer: Initializing Excel Reader");
	
	$excl_obj = new Spreadsheet_Excel_Reader();
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
								$catslug = slugify($title);
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
	
								$page = get_page_by_slug($slug, ARRAY_A, "page", $page_ids[$k-1] );
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
	
	$message = sprintf(__("Successfully imported %s subject areas.", OER_SLUG), $cnt);
	$type = "success";
	$response = array('message' => $message, 'type' => $type);
	return $response;
}

//Import Default CCSS
function importDefaultStandards() {
	$files = array(
		OER_PATH."samples/CCSS_Math.xml",
		OER_PATH."samples/CCSS_ELA.xml"
		);
	foreach ($files as $file) {
		$import = importStandards($file);
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

function fetch_stndrd($pId, $postid)
{
	global $wpdb;
	$table = explode("-", $pId);
	$stndrd_algn = $wpdb->get_row( $wpdb->prepare( "SELECT * from  " . $wpdb->prefix. $table[0] . " where id =%s" , $table[1] ),ARRAY_A);

	if(preg_match("/core_standards/", $table[0]))
	{
		$return = $stndrd_algn['id'];
		update_post_meta( $postid, 'oer_standard_alignment' , $return);
	}
	elseif($stndrd_algn['parent_id'])
	{
		fetch_stndrd($stndrd_algn['parent_id'], $postid);
	}
}

function get_page_by_slug($page_slug, $output = OBJECT, $post_type = 'page', $parent = 0 )
{
	global $wpdb;
	$page = $wpdb->get_var($wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_parent = %d AND post_status = 'publish'", $page_slug, $post_type, $parent ));

	if ($page)
		return get_post($page, $output);
		return null;
}

//Limited Content
function content($the_content, $limit) {
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
function get_child_subjects($subject_area_id) {
	$args = array('hide_empty' => 0, 'taxonomy' => 'resource-subject-area','parent' => $subject_area_id);
	$subchildren = get_categories($args);
	return $subchildren;
}

function get_sort_box($subjects=array()){
	$sort = 0;
	if (isset($_SESSION['resource_sort']))
		$sort = (int)$_SESSION['resource_sort'];
	?>
	<div class="sort-box">
		<span class="sortoption"></span>
		<span class="sort-resources" title="Sort stories"><i class="fa fa-sort" aria-hidden="true"></i></span>
		<div class="sort-options">
			<ul>
				<li data-value="0"<?php if ($sort==0): ?> class="cs-selected"<?php endif; ?>><a href="javascript:void(0);"><span>Newest</span></a></li>
				<li data-value="1"<?php if ($sort==1): ?> class="cs-selected"<?php endif; ?>><a href="javascript:void(0);"><span>Oldest</span></a></li>
				<li data-value="2"<?php if ($sort==2): ?> class="cs-selected"<?php endif; ?>><a href="javascript:void(0);"><span>A-Z</span></a></li>
				<li data-value="3"<?php if ($sort==3): ?> class="cs-selected"<?php endif; ?>><a href="javascript:void(0);"><span>Z-A</span></a></li>
			</ul>
		</div>
		<select class="sort-selectbox" data-subject-ids="<?php echo json_encode($subjects); ?>">
			<option value="0"<?php if ($sort==0): ?>  disabled selected<?php endif; ?>>Newest</option>
			<option value="1"<?php if ($sort==1): ?>  disabled selected<?php endif; ?>>Oldest</option>
			<option value="2"<?php if ($sort==2): ?>  disabled selected<?php endif; ?>>A-Z</option>
			<option value="3"<?php if ($sort==3): ?>  disabled selected<?php endif; ?>>Z-A</option>
		</select>
	</div>
	 <?php
}

function apply_sort_args($args){
	$sort = 0;
	if (isset($_SESSION['resource_sort']))
		$sort = (int)$_SESSION['resource_sort'];

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
?>
