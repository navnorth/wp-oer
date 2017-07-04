<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_shortcode('oer_resource_form', 'oer_resource_front_form');
function oer_resource_front_form()
{
	global $wpdb;
	if(isset($_POST['oer_resource_submit']))
	{
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		$oer_resourcettl = $_POST["oer_resourcettl"];
		$post_name = strtolower($oer_resourcettl);
		$post_name = str_replace(' ','_', $post_name);
		$cs_date = date("Y-m-d H:i:s");

		if(!empty($oer_resourcettl) && !empty($_POST['oer_resourceurl']))
		{
			$oer_subject = $_POST["oer_subject"];
			$post = array('post_content' => '', 'post_name' => $post_name, 'post_title' => $oer_resourcettl, 'post_status' => 'publish', 'post_type' => 'resource', 'post_author' => $user_id , 'post_date' => $cs_date, 'post_date_gmt'  => $cs_date, 'comment_status' => 'open');
			$post_id = wp_insert_post( $post, $wp_error );
			wp_set_post_terms( $post_id, $oer_subject, 'resource-subject-area', true );
		}
		else
		{
			_e("Resource Title and Resource URL is required", OER_SLUG);
			exit;
		}
		//saving meta fields
		if(isset($_POST['oer_resourceurl'])){
			$oer_resourceurl = $_POST['oer_resourceurl'];
			if( !empty($_POST['oer_resourceurl']) )
			{
				if ( preg_match('/http/',$oer_resourceurl) )
				{
					$oer_resourceurl = $_POST['oer_resourceurl'];
				}
				else
				{
					$oer_resourceurl = 'http://'.$_POST['oer_resourceurl'];
				}
			}
			update_post_meta( $post_id , 'oer_resourceurl' , $oer_resourceurl);
		}
		if(isset($_POST['oer_highlight'])){
			update_post_meta( $post_id , 'oer_highlight' , $_POST['oer_highlight']);
		}
		if(isset($_POST['oer_grade'])){
			$oer_grade = implode("," ,$_POST['oer_grade']);
			update_post_meta( $post_id , 'oer_grade' , $oer_grade);
		}
		if(isset($_POST['oer_datecreated'])){
			update_post_meta( $post_id , 'oer_datecreated' , $_POST['oer_datecreated']);
		}
		if(isset($_POST['oer_datemodified'])){
			update_post_meta( $post_id , 'oer_datemodified' , $_POST['oer_datemodified']);
		}
		if(isset($_POST['oer_mediatype'])){
			update_post_meta( $post_id , 'oer_mediatype' , $_POST['oer_mediatype']);
		}
		if(isset($_POST['oer_lrtype'])){
			update_post_meta( $post_id , 'oer_lrtype' , $_POST['oer_lrtype']);
		}
		if(isset($_POST['oer_interactivity'])){
			update_post_meta( $post_id , 'oer_interactivity' , $_POST['oer_interactivity']);
		}
		if(isset($_POST['oer_userightsurl'])){
			$oer_userightsurl = $_POST['oer_userightsurl'];
			if( !empty($_POST['oer_userightsurl']) )
			{
				if ( preg_match('/http/',$oer_userightsurl) )
				{
					$oer_userightsurl = $_POST['oer_userightsurl'];
				}
				else
				{
					$oer_userightsurl = 'http://'.$_POST['oer_userightsurl'];
				}
			}
			update_post_meta( $post_id , 'oer_userightsurl' , $oer_userightsurl);
		}
		if(isset($_POST['oer_isbasedonurl'])){
			$oer_isbasedonurl = $_POST['oer_isbasedonurl'];
			if( !empty($_POST['oer_isbasedonurl']) )
			{
				if ( preg_match('/http/',$oer_isbasedonurl) )
				{
					$oer_isbasedonurl = $_POST['oer_isbasedonurl'];
				}
				else
				{
					$oer_isbasedonurl = 'http://'.$_POST['oer_isbasedonurl'];
				}
			}
			update_post_meta( $post_id , 'oer_isbasedonurl' , $oer_isbasedonurl);
		}
		if(isset($_POST['oer_standard_alignment'])){
			update_post_meta( $post_id , 'oer_standard_alignment' , $_POST['oer_standard_alignment']);
		}
		if(isset($_POST['oer_standard'])){
			$oer_standard = implode(",", $_POST['oer_standard']);
			update_post_meta( $post_id , 'oer_standard' , $oer_standard);
		}
		if(isset($_POST['oer_authortype'])){
			update_post_meta( $post_id , 'oer_authortype' , $_POST['oer_authortype']);
		}
		if(isset($_POST['oer_authorname'])){
			update_post_meta( $post_id , 'oer_authorname' , $_POST['oer_authorname']);
		}
		if(isset($_POST['oer_authorurl'])){
			$oer_authorurl = $_POST['oer_authorurl'];
			if( !empty($_POST['oer_authorurl']) )
			{
				if ( preg_match('/http/',$oer_authorurl) )
				{
					$oer_authorurl = $_POST['oer_authorurl'];
				}
				else
				{
					$oer_authorurl = 'http://'.$_POST['oer_authorurl'];
				}
			}
			update_post_meta( $post_id , 'oer_authorurl' , $oer_authorurl);
		}
		if(isset($_POST['oer_authoremail'])){
			update_post_meta( $post_id , 'oer_authoremail' , $_POST['oer_authoremail']);
		}
		if(isset($_POST['oer_authortype2'])){
			update_post_meta( $post_id , 'oer_authortype2' , $_POST['oer_authortype2']);
		}
		if(isset($_POST['oer_authorname2'])){
			update_post_meta( $post_id , 'oer_authorname2' , $_POST['oer_authorname2']);
		}
		if(isset($_POST['oer_authorurl2'])){
			$oer_authorurl2 = $_POST['oer_authorurl2'];
			if( !empty($_POST['oer_authorurl2']) )
			{
				if ( preg_match('/http/',$oer_authorurl2) )
				{
					$oer_authorurl2 = $_POST['oer_authorurl2'];
				}
				else
				{
					$oer_authorurl2 = 'http://'.$_POST['oer_authorurl2'];
				}
			}
			update_post_meta( $post_id , 'oer_authorurl2' , $oer_authorurl2);
		}
		if(isset($_POST['oer_authoremail2'])){
			update_post_meta( $post_id , 'oer_authoremail2' , $_POST['oer_authoremail2']);
		}

		if(isset($_POST['oer_publishername'])){
			update_post_meta( $post_id , 'oer_publishername' , $_POST['oer_publishername']);
		}
		if(isset($_POST['oer_publisherurl'])){
			$oer_publisherurl = $_POST['oer_publisherurl'];
			if( !empty($_POST['oer_publisherurl']) )
			{
				if ( preg_match('/http/',$oer_publisherurl) )
				{
					$oer_publisherurl = $_POST['oer_publisherurl'];
				}
				else
				{
					$oer_publisherurl = 'http://'.$_POST['oer_publisherurl'];
				}
			}
			update_post_meta( $post_id , 'oer_publisherurl' , $oer_publisherurl);
		}
		if(isset($_POST['oer_publisheremail'])){
			update_post_meta( $post_id , 'oer_publisheremail' , $_POST['oer_publisheremail']);
		}
		//saving meta fields
		
		_e("Resource Created successfully!", OER_SLUG);

	}

	$return = '';
	$return .= '<div class="oer_rsfrmwpr">';

		$return .= '<div class="oer_rsfrmhdng">';
			$return .= 'Resource Form';
		$return .= '</div>';
		$return .= '<div class="oer_rsfrminrwpr">';
			$return .= '<form action="" method="post">';

				$return .= '<div class="oer_snglfld">';
        			$return .= '<div class="oer_txt">';
            			$return .= 'Resource Title:';
            		$return .= '</div>';
            		$return .= '<div class="oer_fld">';
            			$return .= '<input type="text" name="oer_resourcettl" value="" />';
            		$return .= '</div>';
        		$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
        			$return .= '<div class="oer_txt">';
            			$return .= 'Resource URL:';
            		$return .= '</div>';
            		$return .= '<div class="oer_fld">';
            			$return .= '<input type="text" name="oer_resourceurl" value="" />';
            		$return .= '</div>';
        		$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Highlight:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<label for="oer_rsurltrue">True</label><input id="oer_rsurltrue" type="radio" value="1" name="oer_highlight" />';
						$return .= '<label for="oer_rsurlfalse">False</label><input id="oer_rsurlfalse" type="radio" value="0" name="oer_highlight" />';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
        			$return .='<div class="oer_txt">';
            			$return .='Subject:';
            		$return .='</div>';
            		$return .='<div class="oer_fld">';

						 $select_cats = wp_dropdown_categories( array( 'echo' => 0,'show_count'=>0,'hierarchical'=>1, 'taxonomy' => 'resource-subject-area', 'hide_empty' => 0 ) );
						 $select_cats = str_replace( 'id=', 'multiple="multiple" id=', $select_cats );
						 $select_cats = str_replace( "name='cat'", "name='oer_subject[]'", $select_cats );
						 $return .= $select_cats;

            		$return .='</div>';
        		$return .='</div>';

				$return .='<div class="oer_snglfld">';
        			$return .= '<div class="oer_txt">';
            			$return .= 'Grade:';
            		$return .= '</div>';
            		$return .= '<div class="oer_fld">';

						$return .= '<ul>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="pre-k">  Pre-K </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="kindergarten">  K (Kindergarten) </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="1">  1 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="2">  2 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="3">  3 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="4">  4 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="5">  5 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="6">  6 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="7">  7 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="8">  8 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="9">  9 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="10">  10 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="11">  11 </li>';
							$return .= '<li><input type="checkbox" name="oer_grade[]" value="12">  12 </li>';
						$return .= '</ul>';

            		$return .= '</div>';
        		$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Date Created:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_datecreated" value="" class="oer_datepicker"/>';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Date Modified:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_datemodified" value="" class="oer_datepicker"/>';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Media Type:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<select name="oer_mediatype">';
							$return .= '<option value="website">Website</option>';
							$return .= '<option value="audio">Audio</option>';
							$return .= '<option value="document">Document</option>';
							$return .= '<option value="image">Image</option>';
							$return .= '<option value="video">Video</option>';
							$return .= '<option value="other">Other</option>';
						$return .= '</select>';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Learning Resource Type:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<select name="oer_lrtype">';
							$return .= '<option value="website">Assessment</option>';
							$return .= '<option value="audio">Audio</option>';
							$return .= '<option value="calculator">Calculator</option>';
							$return .= '<option value="demonstration">Demonstration</option>';
							$return .= '<option value="game">Game</option>';
							$return .= '<option value="interview">Interview</option>';
							$return .= '<option value="lecture">Lecture</option>';
							$return .= '<option value="lesson_plan">Lesson Plan</option>';
							$return .= '<option value="simulation">Simulation</option>';
							$return .= '<option value="presentation">Presentation</option>';
							$return .= '<option value="other">Other</option>';
						$return .= '</select>';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Interactivity:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<select name="oer_interactivity">';
							$return .= '<option value="interactive">Interactive</option>';
							$return .= '<option value="passive">Passive</option>';
							$return .= '<option value="social">Social</option>';
							$return .= '<option value="prgorammatic">Prgorammatic</option>';
							$return .= '<option value="one_one_one">One-on-One</option>';
							$return .= '<option value="async">Async</option>';
							$return .= '<option value="sync">Sync</option>';
							$return .= '<option value="group">Group</option>';
						$return .= '</select>';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Use Rights URL:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_userightsurl" value="" />';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Is based on URL:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_isbasedonurl" value="" />';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
        			$return .= '<div class="oer_txt">';
            			$return .= 'Standards Alignment:';
            		$return .= '</div>';
            		$return .= '<div class="oer_fld">';
						$oer_url = plugin_dir_url(__FILE__);
            			$return .= '<select name="oer_standard_alignment" onchange="get_standardlist(this);" data-path="'.OER_URL.'ajax/ajax.php" img-path="'.OER_URL.'images/load.gif">';

								$return .= '<option value=""> --  Select Standards  -- </option>';
								$results = $wpdb->get_results("SELECT * from core_standards",ARRAY_A);
								foreach($results as $result)
								{
									$return .= '<option value="'.$result['id'].'">'. $result['standard_name'] .'</option>';
								}

						$return .= '</select>';
						$return .='<div class="oer_lstofstandrd"></div>';
            		$return .= '</div>';
        		$return .= '</div>';

				$return .= '<div class="oer_snglfld oer_hdngsngl">';
					$return .= 'Author Information:';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Type:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<select name="oer_authortype">';
							$return .= '<option value="person">Person</option>';
							$return .= '<option value="organization">Organization</option>';
						$return .= '</select>';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Name:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_authorname" value="" />';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'URL:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_authorurl" value="" />';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Email Address:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_authoremail" value="" />';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld oer_hdngsngl">';
					$return .= '<input type="button" class="button button-primary" value="Add Author" onClick="oer_addauthor(this);" data-url="'.OER_URL.'/images/close.png" />';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld oer_hdngsngl">';
					$return .= 'Publisher Information:';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Name:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_publishername" value="" />';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'URL:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_publisherurl" value="" />';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<div class="oer_txt">';
						$return .= 'Email Address:';
					$return .= '</div>';
					$return .= '<div class="oer_fld">';
						$return .= '<input type="text" name="oer_publisheremail" value="" />';
					$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="oer_snglfld">';
					$return .= '<input type="submit" name="oer_resource_submit" value="Submit" />';
				$return .= '</div>';

			$return .= '</form>';
		$return .= '</div>';

	$return .= '</div>';

	return $return;
}
?>
