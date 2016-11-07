<?php
require OER_PATH.'Excel/reader.php';

debug_log("OER Categories Importer: Initializing Excel Reader");
	
$excl_obj = new Spreadsheet_Excel_Reader();
$excl_obj->setOutputEncoding('CP1251');
$time = time();
$date = date($time);

//Set Maximum Excution Time
ini_set('max_execution_time', 0);
set_time_limit(0);

//Categories Bulk Import
if(isset($_POST['bulk_imprt']))
{
	// Log start of import process
	debug_log("OER Categories Importer: Starting Bulk Import ");
		
	global $wpdb;
	
	try {
		if( isset($_FILES['bulk_import']) && $_FILES['bulk_import']['size'] != 0 )
		{
			$filename = $_FILES['bulk_import']['name']."-".$date;
	
			if ($_FILES["bulk_import"]["error"] > 0)
			{
				echo "Error: " . $_FILES["bulk_import"]["error"] . "<br>";
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
	debug_log("OER Categories Importer: Finished Bulk Import ");
	_e("Categories Import Successfully.",OER_SLUG);
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

<div class="oer_imprtrwpr">
	<div class="oer_hdng">
		<?php _e("Categories Bulk Import", OER_SLUG); ?>
    </div>
    <form method="post" enctype="multipart/form-data">
        <div class="fields">
            <input type="file" name="bulk_import"/>
            <input type="hidden" value="" name="bulk_imprt" />
            <input type="submit" name="" value="<?php _e("Import", OER_SLUG); ?>" class="button button-primary"/>
        </div>
    </form>
</div>
