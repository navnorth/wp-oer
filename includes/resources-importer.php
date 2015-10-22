<?php
require OER_PATH.'Excel/reader.php';

$excl_obj = new Spreadsheet_Excel_Reader();
$excl_obj->setOutputEncoding('CP1251');
$time = time();
$date = date($time);

//Set Maximum Excution Time
ini_set('max_execution_time', 0);
ini_set('max_input_time ', -1);
ini_set('memory_limit ', -1);
set_time_limit(0);

//Resource Import
if(isset($_POST['resrc_imprt']))
{
	if( isset($_FILES['resource_import']) && $_FILES['resource_import']['size'] != 0 )
	{
		$filename = $_FILES['resource_import']['name']."-".$date;

		if ($_FILES["resource_import"]["error"] > 0)
		{
		 	echo "Error: " . $_FILES["resource_import"]["error"] . "<br>";
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

				//Set Category
				wp_set_post_terms( $post_id, $category_id, 'resource-category', true );
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
						$file = getScreenshotFile_mlt($url);
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
	echo "Resource Created successfully !";
}}

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

function getScreenshotFile_mlt($url)
{
	$upload_dir = wp_upload_dir();
	$path = $upload_dir['basedir'].'/resource-images/';

	if(!file_exists($path))
	{
		mkdir($path, 0777, true);
	}
	$file = $path.'Screenshot'.preg_replace('/https?|:|#|\//i', '-', $url).'.jpg';
	if(!file_exists($file))
	{
		$oer_python_script_path = get_option("oer_python_path");
		$oer_python_install = get_option("oer_python_install");

		// create screenshot
		$params = array(
			'xvfb-run',
			'--auto-servernum',
			'--server-num=1',
			$oer_python_install,
			$oer_python_script_path,
			escapeshellarg($url),
			$file,
		);

		$lines = array();
		$val = 0;

		$output = exec(implode(' ', $params), $lines, $val);
	}
	return $file;
}

?>

<div class="oer_imprtrwpr">
	<div class="oer_hdng">
    	Import Resources
    </div>
    <form method="post" enctype="multipart/form-data">
        <div class="fields">
            <input type="file" name="resource_import"/>
            <input type="hidden" value="" name="resrc_imprt" />
            <input type="submit" name="" value="Import" class="button button-primary"/>
        </div>
    </form>
</div>
