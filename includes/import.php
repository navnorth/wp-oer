<?php
/** Import Page **/
global $wpdb;
require_once OER_PATH.'includes/oer-functions.php';
require OER_PATH.'Excel/reader.php';

$message = null;
$type = null;

//Resource Import
if(isset($_POST['resrc_imprt']))
{
	
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
		
	if( isset($_FILES['resource_import']) && $_FILES['resource_import']['size'] != 0 )
	{
		try{
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
			$fnldata = $excl_obj->sheets[0];
	
			for ($k =2; $k <= $fnldata['numRows']; $k++)
			{
				/** Clear variable values after a loop **/
				$oer_title 		= "";
				$oer_resourceurl 	= "";
				$oer_description 	= "";
				$oer_highlight 		= "";
				$oer_cetagories 	= "";
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
					$oer_cetagories     = $fnldata['cells'][$k][5];
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
	
				if(!empty($oer_standard) && (!is_array($oer_standard)))
				{
					$oer_standard = explode(",", $oer_standard);
				}
	
				if(!empty($oer_cetagories))
				{
					$oer_cetagories = explode(",",$oer_cetagories);
					$category_id = array();
					for($i = 0; $i <= sizeof($oer_cetagories); $i++)
					{
						if(!empty($oer_cetagories [$i]))
						{
							if(get_cat_ID( $oer_cetagories [$i]))
							{
								$category_id[$i] = get_cat_ID( $oer_cetagories [$i]);
							}
							else
							{
								// Categories are not found then assign as keyword
								$oer_kywrd .= ",".$oer_cetagories [$i];
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
					wp_set_object_terms( $post_id, $category_id, 'resource-subject-area', true );
					
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
					
					if(!has_post_thumbnail( $post_id ))
					{
						if ($screenshot_enabled)
							$file = getScreenshotFile($url);
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
	
			}
		
		}
	  } catch(Exception $e) {
		// Log any error encountered during the import process
		if ($_debug=="on")
			error_log($e->getMessage());
	}
	// Log finish of import process
	debug_log("OER Resources Importer: Finished Bulk Import of Resources");
	$message = sprintf(__("Successfully imported %s resources.", OER_SLUG), $fnldata['numRows']);
	$type = "success";
	//_e("Resource Created successfully!", OER_SLUG);
}}

//Subject Areas Bulk Import
if(isset($_POST['bulk_imprt']))
{
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
			$fnldata = $excl_obj->sheets;
			$length = count($fnldata);
	
			$ids_arr = array(0);
			$cat_ids = array(0);
			$page_ids = array(0);
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
	
		}
	} catch (Exception $e) {
		// Log any error encountered during the import process
		debug_log($e->getMessage());
	}
	// Log finish of import process
	debug_log("OER Subject Areas Importer: Finished Bulk Import ");
	
	$message = sprintf(__("Successfully imported %s subject areas.", OER_SLUG), $length);
	$type = "success";
	//_e("Categories Import Successfully.",OER_SLUG);
}

//Categories Bulk Import
//Standards Bulk Import
if(isset($_POST['standards_import']))
{   
    $files = array();
    
    if (isset($_POST['oer_common_core_mathematics'])){
	$files[] = OER_PATH."samples/CCSS_Math.xml";
    }
    
    if (isset($_POST['oer_common_core_english'])){
	$files[] = OER_PATH."samples/CCSS_ELA.xml";
    }
	
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
?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <p class="oer_heading">Import - OER</p>
    <?php settings_errors(); ?>
    <div class="oer-import-body">
	<div class="oer-import-row">
		<div class="row-left">
			<?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis nunc tempor, maximus nulla nec, consectetur dolor. Cras tempor fermentum dolor ut maximus. Suspendisse pellentesque lacus semper justo blandit, non interdum velit tempor. Aenean euismod viverra erat eu pretium. Proin ut molestie velit, sit amet vehicula tellus. Praesent et pretium lectus.", OER_SLUG); ?>
			<div class="oer-import-row">
			<h2 class="hidden"></h2>
			<?php if ($message) { ?>
			<div class="notice notice-<?php echo $type; ?> is-dismissible">
			    <p><?php echo $message; ?></p>
			</div>
			<?php } ?>
			</div>
			<div class="oer-import-row">
			    <?php
				$table_name = $wpdb->prefix . "resource_csv";
				include_once(OER_PATH.'includes/resources-importer.php');
			    ?>
			</div>
			<div class="oer-import-row">
			    <?php
				include_once(OER_PATH.'includes/categories-importer.php');
			    ?>
			</div>
			<div class="oer-import-row">
			    <?php
				include_once(OER_PATH.'includes/standards-importer.php');
			    ?>
			</div>
		</div>
		<div class="row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="#" target="_blank"><?php _e("WordPress Plugin Support Forums", OER_SLUG); ?></a></li>
				<li><?php _e("Navigation North <a href='#' target='_blank'>direct supprt</a>", OER_SLUG); ?></li>
			</ul>
		</div>
	</div>
    </div>
</div><!-- /.wrap -->
<div class="plugin-footer">
	<div class="plugin-info"><?php echo OER_PLUGIN_NAME . " " . OER_VERSION .""; ?></div>
	<div class="plugin-link"><a href='http://www.navigationnorth.com/portfolio/oer-management/' target='_blank'><?php _e("More info", OER_SLUG); ?></a></div>
	<div class="clear"></div>
</div>