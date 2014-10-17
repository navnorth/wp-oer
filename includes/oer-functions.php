<?php
function get_sub_standard($id, $oer_standard)
{
	global $wpdb;
	$results = $wpdb->get_results("SELECT * from oer_sub_standards where parent_id ='$id'",ARRAY_A);
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
	$results = $wpdb->get_results("SELECT * from oer_standard_notation where parent_id ='$id'",ARRAY_A);

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
	$results = $wpdb->get_results("SELECT * from oer_standard_notation where parent_id ='$id'",ARRAY_A);
	return $results;
}
?>
