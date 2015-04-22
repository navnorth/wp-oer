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
$timthumb = get_template_directory_uri().'/lib/timthumb.php';

$parentid = array();
if($rsltdata['parent'] != 0)
{
	$parent = get_top_parents($rsltdata['parent']);
	for($k=0; $k < count($parent); $k++)
	{
		$idObj = get_category_by_slug($parent[$k]);
		$parentid[] = $idObj->term_id;
	}
}
?>
<div class="cntnr">
	<div class="resource_category_sidebar">
	<?php
	echo '<ul class="resource_category">';
			$args = array('hide_empty' => 0, 'taxonomy' => 'resource-category', 'parent' => 0);
			$categories= get_categories($args);
                        
			foreach($categories as $category)
			{
				$children = get_term_children($category->term_id, 'resource-category');
				$getimage = $wpdb->get_results( $wpdb->prepare ( "SELECT * FROM ".$wpdb->prefix.'postmeta'." WHERE meta_key='category_image' AND meta_value=%d" , $category->term_id));
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
					$strcat = get_custom_category_parents($term_id, "resource-category" , FALSE, ':', TRUE);
                                        
					if(strpos($strcat,':'))
					{
						$top_cat = explode(':',$strcat);
					}
					$parent = $top_cat[0];
					
					$catobj = get_term_by( 'slug' , $parent , 'resource-category' );
                                        
					$getimage = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix.'postmeta'."  WHERE meta_key='category_image' AND meta_value=%d" , $catobj->term_id ));
					if(!empty($getimage))
					{
						$attach_icn = get_post($getimage[0]->post_id);
						echo '<li><img src="'. $timthumb.'?src='.$attach_icn->guid.'&w=32&h=32&zc=0" /></li>';
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
					$timthumb = get_template_directory_uri().'/lib/timthumb.php';
					foreach($posts as $post)
					{
						$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
						$title =  $post->post_title;
						$content =  $post->post_content;
						$content = substr($content, 0, 180);
					?>
						<div class="snglrsrc">
							 <?php if(!empty($image)){?>
								<a href="<?php echo get_permalink($post->ID);?>"><div class="snglimglft"><img src="<?php echo $timthumb.'?src='.$image.'&w=80&h=60&zc=0';?>"></div></a>
							<?php }
							else
							{
								$dfltimg = site_url().'/wp-content/plugins/wp-oer/images/default-icon.png';
								echo '<a href="'.get_permalink($post->ID).'"><div class="snglimglft"><img src="'.$timthumb.'?src='.$dfltimg.'&amp;w=80&amp;h=60&amp;zc=0" alt="'.$title.'"></div></a>';
							}
							?>
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
        <div class="clear"></div>
</div>
<?php get_footer(); ?><!-- Load WordPress Theme Footer -->