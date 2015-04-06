<?php

function user_pages($ids)
{
	global $wpdb;
	if(is_array($ids))
	{
		foreach($ids as $id)
		{
			$pages = $wpdb->get_results("select page_id from " . $wpdb->prefix. "category_page where resource_category_id = '$id'",ARRAY_A);
			$page_id[] = $pages[0]['page_id'];
		}
	}
	else
	{
		$pages = $wpdb->get_results("select page_id from " . $wpdb->prefix. "category_page where resource_category_id = '$id'",ARRAY_A);
		$page_id = $pages[0]['page_id'];
	}
	return $page_id;
	exit;
}
function get_post_ids($cats , $post_type)
{
	$cat = implode(",",$cats);

	if(!empty($post_type))
	{
		$args=array('post_type' => $post_type, 'tax_query' => array(	array('taxonomy' => 'resource-category','terms' => array($cat))), 'post_status' => 'publish', 'posts_per_page' => -1,'fields' => 'ids');
	}
	else
	{
		$args = array('numberposts' => -1,'category' => $cat,'fields' => 'ids');
	}

    $post_array[] = get_posts($args);
	return $post_array[0];

}

if(isset($_POST['oer_userasgnctgries']))
{
	extract($_POST);
	$posts_array = get_post_ids($oer_userasgnctgries, 'resource');
	$oer_userasgnrsrc_post = serialize($posts_array);
	update_user_meta($user_id, 'oer_userasgnrsrc_post', $oer_userasgnrsrc_post);

	$posts_array = get_post_ids($oer_userasgnctgries, '');
	$oer_userasgnblog_post = serialize($posts_array);
	update_user_meta($user_id, 'oer_userasgnblog_post', $oer_userasgnblog_post);

	$page_id = user_pages($oer_userasgnctgries);
	$oer_userasgnpages = serialize($page_id);
	update_user_meta($user_id, 'oer_userasgnpages', $oer_userasgnpages);

	$oer_userasgnctgries = serialize($oer_userasgnctgries);
	update_user_meta($user_id, 'oer_userasgnctgries', $oer_userasgnctgries);

}
?>
<style>
.selctedCats{ display: none;}
</style>
<div class="oer_metawpr">
	<div class="oer_metainrwpr">
		<form method="post" action="">
			<?php if(isset($_POST['oer_user']))
			{
				$oer_user = $_POST['oer_user'];
				$asgn_catgrs = get_user_meta($oer_user, 'oer_userasgnctgries',true);
				if(empty($asgn_catgrs))
				{
					$asgn_catgrs = array();
				}
				else
				{
					$asgn_catgrs = unserialize($asgn_catgrs);
				}
			?>
				<input type="hidden" name="user_id" value="<?php echo $_POST['oer_user']; ?>" />
				<div class="oer_snglfld">
					<div class="oer_txt">
						<button type="button" name="Select_All" id="oer_selectall" class="button button-primary" onclick="oer_select_all()">Select All</button>
						<button type="button" name="UnSelect_All" id="oer_unselectall" class="button button-primary" onclick="oer_unselect_all()">UnSelect All</button>
					</div>
				</div>
				<div class="oer_snglfld">
					<div class="oer_txt">
						Assign Category
					</div>
					<?php
						echo '<ul class="oer_cats">';
							$args = array('hide_empty' => 0, 'taxonomy' => 'resource-category', 'parent' => 0);
							$categories= get_categories($args);
							foreach($categories as $category)
							{
								$children = get_term_children($category->term_id, 'resource-category');
								if( !empty( $children ) )
								{
									echo "<li class='oer_sbstndard has-child'>
										<div class='stndrd_ttl'>
											<img src='".OER_URL."images/open_arrow.png' data-pluginpath='".OER_URL."' />
											<input type='checkbox' ". ischck_cats($asgn_catgrs, $category->term_id) ." name='oer_userasgnctgries[]' value='".$category->term_id ."' onclick='oer_check_all(this)' >".$category->name."</div><div class='stndrd_desc'></div>";
								}
								else
								{
									echo "<li class='oer_sbstndard'>
										<div class='stndrd_ttl'>
											<input type='checkbox' ". ischck_cats($asgn_catgrs, $category->term_id) ." name='oer_userasgnctgries[]' value='".$category->term_id ."' onclick='oer_check_all(this)' >".$category->name."</div><div class='stndrd_desc'></div>";
								}
								echo process_cat_tree( $category->term_id, $asgn_catgrs );
								echo '</li>';
							}
						echo '</ul>';
						echo "<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery('li').children('.stndrd_ttl').children('img').click(function(e)
							{
								var plgnpth = jQuery(this).attr('data-pluginpath');
								if( jQuery(this).parent('.stndrd_ttl').next('.stndrd_desc').next('ul').hasClass('active') )
							   {
									jQuery(this).attr('src', plgnpth+'images/open_arrow.png');
									jQuery(this).parent('.stndrd_ttl').next('.stndrd_desc').next('ul').removeClass('active').children('li').slideToggle();
									e.stopPropagation();
							   }
							   else
								  {
									jQuery(this).attr('src', plgnpth+'images/closed_arrow.png')
									jQuery(this).parent('.stndrd_ttl').next('.stndrd_desc').next('ul').addClass('active').children('li').slideToggle();
									e.stopPropagation();
								  }
							});
						});
						</script>";
						wp_reset_query();
					?>
				</div>
			<?php
			}
			elseif(isset($_POST["oer_usrtyp"]))
			{?>

				<div class="oer_snglfld oer_hdngsngl">
					Assign Categories
				</div>
				<?php
					 if($_POST["oer_usrtyp"] == 'editor')
					 {
					 	$users = get_users( array( 'role' => 'editor' ) );
					 }
					 else
					 {
					 	$users = get_users( array( 'role' => 'author' ) );
					 }
				?>
				<div class="oer_snglfld">
					<div class="oer_txt">
						Select User
					</div>
					<div class="oer_fld">
						<select name="oer_user">
							<?php
							foreach($users as $user)
							{
							?>
								<option value="<?php echo $user->data->ID;?>"><?php echo $user->data->user_login;?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>

			<?php
			}
			else
			{?>
				<div class="oer_snglfld oer_hdngsngl">
					Assign Categories
				</div>
				<div class="oer_snglfld">
					<div class="oer_txt">
						Select User Type
					</div>
					<div class="oer_fld">
						<div class="radio_btn"><input type="radio" name="oer_usrtyp" value="editor" />Editor</div>
						<div class="radio_btn"><input type="radio" name="oer_usrtyp" value="author" />Author</div>
					</div>
				</div>

			<?php
			}
			?>
			<div class="oer_snglfld">
				<input type="hidden" value="" name="hdnuser" />
            	<input type="submit" name="" value="Submit" class="button button-primary"/>
			</div>
		</form>

	</div>
</div>
<?php
/*function process_cat_tree( $cat, $asgn_catgrs )
{
 	$args = array('hide_empty' => 0, 'taxonomy' => 'resource-category','parent' => $cat);
	$next = get_categories($args);
	if( $next )
	{
		echo '<ul class="child">';
		foreach( $next as $cat )
		{
			 echo '<li><input '. ischck_cats($asgn_catgrs, $cat->term_id) .' type="checkbox" onclick="oer_check_myChild(this)" name="oer_userasgnctgries[]" value="'.$cat->term_id .'"><span class="">' . $cat->name.'</span>';
			 	process_cat_tree( $cat->term_id, $asgn_catgrs);
			 echo '</li>';
		}
		echo '</ul>';
	}
}*/
function process_cat_tree($categoryid, $asgn_catgrs )
{
 	$args = array('hide_empty' => 0, 'taxonomy' => 'resource-category','parent' => $categoryid);
	$catchilds = get_categories($args);

	if(!empty($catchilds))
	{
		echo '<ul class="oer_cats">';
		foreach($catchilds as $catchild)
		{
			$children = get_term_children($catchild->term_id, 'resource-category');
			if( !empty( $children ) )
			{
				echo "<li class='oer_sbstndard has-child'>
						<div class='stndrd_ttl'>
							<img src='".OER_URL."images/open_arrow.png' data-pluginpath='".OER_URL."' />
							<input type='checkbox' ". ischck_cats($asgn_catgrs, $catchild->term_id) ." name='oer_userasgnctgries[]' value='".$catchild->term_id ."' onclick='oer_check_all(this)' >".$catchild->name."</div><div class='stndrd_desc'></div>";
			}
			else
			{
				echo "<li class='oer_sbstndard'>
						<div class='stndrd_ttl'>
							<input type='checkbox' ". ischck_cats($asgn_catgrs, $catchild->term_id) ." name='oer_userasgnctgries[]' value='".$catchild->term_id ."' onclick='oer_check_all(this)' >".$catchild->name."</div><div class='stndrd_desc'></div>";
			}

			process_cat_tree( $catchild->term_id, $asgn_catgrs );
			echo '</li>';
		}
		echo '</ul>';
	}
}
function ischck_cats($data, $ctagrs_id)
{
	$rtn = '';
	if(in_array ( $ctagrs_id , $data , true ))
	{
		$rtn = 'checked="checked"';
		return $rtn;
		exit;
	}
	else
	{
		$rtn = '';
		return $rtn;
	}

}
?>
