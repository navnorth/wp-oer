<?php
/*
 * Template Name: Category Template Two
 */
get_header();
$term = get_the_title();
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
	<div class="category_sidebar">
	<?php
	echo '<ul class="category">';
			$args = array('hide_empty' => 0, 'taxonomy' => 'resource-category', 'parent' => 0);
			$categories= get_categories($args);
			foreach($categories as $category)
			{
				$children = get_term_children($category->term_id, 'resource-category');
				$getimage = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.'postmeta'." WHERE meta_key='category_image' AND meta_value='$category->term_id'");
				if(!empty($getimage)){ $attach_icn = get_post($getimage[0]->post_id); }
				else { $attach_icn = array(); }
				
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
					echo '<li class="sub-category has-child'.$class.'"><span onclick="toggleparent(this);"><a href="'. site_url() .'/'. $category->slug .'" title="'. $category->name .'" >'. $category->name .'</a></span>';
				}
				else
				{
					echo '<li class="sub-category'.$class.'"><span onclick="toggleparent(this);"><a href="'. site_url() .'/'. $category->slug .'"  title="'. $category->name .'" >'. $category->name .'</a></span>';
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
					$termid = get_term_by('name', $term, "resource-category" );
					$strcat = get_custom_category_parents($termid, "resource-category" , FALSE, ':', TRUE);
					if(strpos($strcat,':'))
					{
						$top_cat = split(':',$strcat);
					}
					$parent = $top_cat[0];
					
					$catobj = get_category_by_slug($parent);
					$getimage = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.'postmeta'."  WHERE meta_key='category_image' AND meta_value='$catobj->term_id'");
					if(!empty($getimage))
					{
						$attach_icn = get_post($getimage[0]->post_id);
						$new_image_url = wft_resize_image( $attach_icn->guid , 32 , 32 , true );
						echo '<li><img src="'. $new_image_url .'" /></li>';
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
			<div class="snglrsrchdng">Browse <?php echo get_the_title();?> Resources</div>
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
						$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
						$title =  $post->post_title;
						$content =  $post->post_content;
						$content = substr($content, 0, 180);
					?>
						<div class="snglrsrc">
							<?php if(empty($image)){
								$image = site_url().'/wp-content/plugins/wp-oer/images/default-icon.png';
							}
							$new_image_url = wft_resize_image( $image , 80 , 60 , true );
							?>
							<a href="<?php echo get_permalink($post->ID);?>"><div class="snglimglft"><img src="<?php echo $new_image_url;?>" alt="<?php echo $title; ?>"></div></a>
							<div class="snglttldscrght <?php if(empty($image)){ echo 'snglttldscrghtfull';}?>">
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
</div>

<?php //get_sidebar( 'front' ); ?>
<?php get_footer(); ?>
