<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once($parse_uri[0].'/wp-load.php');
global $wpdb;
if($_REQUEST["task"] == 'get_standards')
{
	require_once('../../../../wp-load.php');
	global $wpdb;
	extract($_POST);

	$standard_id = 'core_standards-'.$standard_id;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "sub_standards where parent_id = %s", $standard_id) ,ARRAY_A);
	if(!empty($results))
	{
		get_child($standard_id);
		echo "<script type='text/javascript'>
				jQuery(document).ready(function(){
					jQuery('li').children('.stndrd_ttl').children('img').click(function(e)
					{
						var plgnpth = jQuery(this).attr('data-pluginpath');
						if( jQuery(this).parent('.stndrd_ttl').next('.stndrd_desc').next('ul').hasClass('active') )
					   {
							jQuery(this).attr('src', plgnpth+'images/closed_arrow.png');
							jQuery(this).parent('.stndrd_ttl').next('.stndrd_desc').next('ul').removeClass('active').children('li').slideToggle();
							e.stopPropagation();
					   }
					   else
						  {
							jQuery(this).attr('src', plgnpth+'images/open_arrow.png')
							jQuery(this).parent('.stndrd_ttl').next('.stndrd_desc').next('ul').addClass('active').children('li').slideToggle();
							e.stopPropagation();
						  }
					});
				});
				</script>";
	}
	else
	{
		echo "empty";
	}
}

function get_child($id)
{
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "sub_standards where parent_id = %s", $id ) ,ARRAY_A);
	if(!empty($results))
	{
		echo "<ul>";
			foreach($results as $result)
			{
				echo "<li class='oer_sbstndard'>
						<div class='stndrd_ttl'>
							<img src='".OER_URL."images/closed_arrow.png' data-pluginpath='".OER_URL."' />
							<input type='checkbox' name='oer_standard[]' value='oer_sub_standards-".$result['id']."' onclick='oer_check_all(this)' >
							".$result['standard_title']."
						</div><div class='stndrd_desc'></div>";

						$id = 'sub_standards-'.$result['id'];
						get_child($id);
						$sid = 'sub_standards-'.$result['id'];
						get_thrchild($sid);
				echo "</li>";
			}
		echo "</ul>";
	}
}

function get_thrchild($id )
{
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "standard_notation where parent_id = %s" , $id  ) ,ARRAY_A);
	if(!empty($results))
	{
		echo "<ul>";
			foreach($results as $result)
			{
			  $id = 'standard_notation-'.$result['id'];
			  $child = child_exist($id);

			  echo "<li>
				   <div class='stndrd_ttl'>";

					if(!empty($child))
					{
						echo "<img src='".OER_URL."images/closed_arrow.png' data-pluginpath='".OER_URL."' />";
					}

			  echo "<input type='checkbox' name='oer_standard[]' value='oer_standard_notation-".$result['id'] ."' onclick='oer_check_myChild(this)'>
			 	   ". $result['standard_notation']."
				   </div>
				   <div class='stndrd_desc'> ". $result['description']." </div>";

				   get_thrchild($id);

				   echo "</li>";
			}
		echo "</ul>";
	}
}

function child_exist($id)
{
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "standard_notation where parent_id = %s" , $id ) ,ARRAY_A);
	return $results;
}
?>
