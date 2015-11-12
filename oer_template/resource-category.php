<?php
/*
 * Template Name: Default Category Page Template
 */
/** Add default stylesheet for Resource Category page **/
wp_register_style( "resource-category-styles", OER_URL . "css/resource-category-style.css" );
wp_enqueue_style( "resource-category-styles" );

/** Add default javascript **/
wp_register_script( "resource-script" , OER_URL ."js/resource-category.js" );
wp_enqueue_script( "resource-script" );

/** Load WordPress Theme Header **/
get_header();
//Get ID of Resource Category
$term_id = get_queried_object_id();

//Get Term based on Category ID
//$term = get_the_title();
$terms = get_term_by( "id" , $term_id , 'resource-category' , object );
$term = $terms->name;

$rsltdata = get_term_by( "name", $term, "resource-category", ARRAY_A );

$parentid = array();
if($rsltdata['parent'] != 0)
{
	$parent = get_parent_term($rsltdata['parent']);
	for($k=0; $k < count($parent); $k++)
	{
		$idObj = get_category_by_slug($parent[$k]);
		$parentid[] = $idObj->term_id;
	}
}
?>
<div class="cntnr">
	<div class="resource_category_sidebar template_resource_category_sidebar">
	<?php
	echo '<ul class="resource_category">';
			$args = array('hide_empty' => 0, 'taxonomy' => 'resource-category', 'parent' => 0);
			$categories= get_categories($args);
                        
			foreach($categories as $category)
			{
				$children = get_term_children($category->term_id, 'resource-category');
				$getimage = $wpdb->get_results( $wpdb->prepare ( "SELECT * FROM ".$wpdb->prefix.'postmeta'." WHERE meta_key='category_image' AND meta_value=%s" , $category->term_id));
				if(!empty($getimage)){
                                    $attach_icn = get_post($getimage[0]->post_id);
                                } else {
                                    $attach_icn = array();
                                }
				
				if($rsltdata['term_id'] == $category->term_id)
				{
					$class = ' activelist current_class';	
				}
				elseif(in_array($category->term_id, $parentid))
				{
					$class = ' activelist current_class';
				}
				else
				{
					$class = '';
				}
				
				if( !empty( $children ) )
				{
					echo '<li class="sub-category has-child'.$class.'"><span onclick="toggleparent(this);"><a href="'. site_url() .'/'.$category->taxonomy.'/'. $category->slug .'" title="'. $category->name .'" >'. $category->name .'</a></span>';
				}
				else
				{
					echo '<li class="sub-category'.$class.'"><span onclick="toggleparent(this);"><a href="'. site_url() .'/'.$category->taxonomy.'/'. $category->slug .'"  title="'. $category->name .'" >'. $category->name .'</a></span>';
				}
				echo get_category_child( $category->term_id);
				echo '</li>';
			}
	echo '</ul>';
	?>
</div> <!--Left Sidebar-->

	<div class="rightcatcntr">
	
		<div class="pgbrdcrums">
			<ul>
				<?php
					$strcat = get_custom_category_parents($term_id, "resource-category" , FALSE, ':', TRUE);
                                        
					if(strpos($strcat,':'))
					{
						$top_cat = explode(':',$strcat);
					}
					$parent = $top_cat[0];
					
					$catobj = get_term_by( 'slug' , $parent , 'resource-category' );
                                        
					$getimage = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix.'postmeta'."  WHERE meta_key='category_image' AND meta_value=%s" , $catobj->term_id ));
					if(!empty($getimage))
					{
						$attach_icn = get_post($getimage[0]->post_id);
						$img_path = $new_img_path = parse_url($attach_icn->guid);
						$img_path = $_SERVER['DOCUMENT_ROOT'] . $img_path['path'];
						//Resize Image using WP_Image_Editor
						$image_editor = wp_get_image_editor($img_path);
						if ( !is_wp_error($image_editor) ) {
							$new_image = $image_editor->resize( 32, 32, true );
							$suffix = "32x32";
							
							//Additional info of file
							$info = pathinfo( $img_path );
							$dir = $info['dirname'];
							$ext = $info['extension'];
							$name = wp_basename( $img_path, ".$ext" );
							$dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";
							$new_port = ($new_img_path['port']==80)?'':':'.$new_img_path['port'];
							$new_image_url = str_replace($_SERVER['DOCUMENT_ROOT'], "{$new_img_path['scheme']}://{$new_img_path['host']}{$new_port}", $dest_file_name);
							
							if ( !file_exists($dest_file_name) ){
								$image_file = $image_editor->save($dest_file_name);
							}
						}
						echo '<li><img src="'.$new_image_url.'" /></li>';
					}
					else
					{
						echo '<li></li>';
					}
					
				?>
				<li>
					<?php
					if(function_exists('yoast_breadcrumb'))
					{
						$breadcrumbs = yoast_breadcrumb("","",false);
						echo ucwords ($breadcrumbs);
					} 
					?>
				</li>
			</ul>	
		</div> <!--Breadcrumbs-->
	
		<?php 
			 $postid = get_the_ID();
			 $rslt = get_post_meta($postid, "enhance_page_content", true);
			
			if(!empty($rslt))
			{ 
				echo '<div class="allftrdpst">'.$rslt .'</div> ';
			}
		?> <!--Text and HTML Widget-->
		
		<div class="allftrdrsrc">
			<div class="snglrsrchdng">Browse <?php echo $term;?> Resources</div>
			<div class="allftrdrsrccntr" onScroll="load_onScroll(this)" file-path="<?php echo get_template_directory_uri();?>/lib/ajax-scroll.php" data-id="<?php echo $rsltdata['term_id'];?>">
				<?php
				$args = array(
					'post_type' => 'resource',
					'posts_per_page' => 15,
					'tax_query' => array(array('taxonomy' => 'resource-category','terms' => array($rsltdata['term_id'])))
				);
				$posts = get_posts($args);
				
				if(!empty($posts))
				{
					foreach($posts as $post)
					{
						$img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , "full" );
						if (empty($img_url))
							$img_url = site_url().'/wp-content/plugins/wp-oer/images/default-icon.png';
						$title =  $post->post_title;
						$content =  $post->post_content;
						$content = substr($content, 0, 180);
						
						$img_path = $new_img_path = parse_url($img_url[0]);
						$img_path = $_SERVER['DOCUMENT_ROOT'] . $img_path['path'];
						if(!empty($img_path))
						{
							//Resize Image using WP_Image_Editor
							$image_editor = wp_get_image_editor($img_path);
							if ( !is_wp_error($image_editor) ) {
								$new_image = $image_editor->resize( 220, 180, true );
								$suffix = "220x180";
								
								//Additional info of file
								$info = pathinfo( $img_path );
								$dir = $info['dirname'];
								$ext = $info['extension'];
								$name = wp_basename( $img_path, ".$ext" );
								$dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";
								$new_port = ($new_img_path['port']==80)?'':':'.$new_img_path['port'];
								$new_image_url = str_replace($_SERVER['DOCUMENT_ROOT'], "{$new_img_path['scheme']}://{$new_img_path['host']}{$new_port}", $dest_file_name);
								
								if ( !file_exists($dest_file_name) ){
									$image_file = $image_editor->save($dest_file_name);
								}
							}
						}
					?>
						<div class="snglrsrc">
							<?php
							echo '<a href="'.get_permalink($post->ID).'"><div class="snglimglft"><img src="'.$new_image_url.'"></div></a>';
							?>
							<div class="snglttldscrght <?php if(empty($img_url)){ echo 'snglttldscrghtfull';}?>">
								<div class="ttl"><a href="<?php echo get_permalink($post->ID);?>"><?php echo $title;?></a></div>
								<div class="desc"><?php echo $content; ?></div>
							</div>
						</div>
					<?php
					}
					wp_reset_postdata();
				}
				else
				{
					echo "<div class='snglrsrc'>There are no resources available for $term</div>";
				}	
				?>
		   </div>
		</div> <!--Browse By Categories-->
    
	</div>
        <div class="clear"></div>
</div>
<?php get_footer(); ?><!-- Load WordPress Theme Footer -->