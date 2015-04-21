<?php
/*
 * Template Name: Category Template One
 */
get_header();
$term = get_the_title();
$rsltdata = get_term_by( "name", $term, "resource-category", ARRAY_A );
?>
<div class="cntnr">
	<div class="subject-btn" onclick="tglcategories(this);"> Subjects List </div>
    <div class="category_sidebar">
	<?php
	echo '<ul class="category">';
			$args = array('hide_empty' => 0, 'taxonomy' => 'resource-category', 'parent' => 0);
			$categories= get_categories($args);
			foreach($categories as $category)
			{
				$children = get_term_children($category->term_id, 'resource-category');
				
				if($rsltdata['term_id'] == $category->term_id)
				{
					$class = ' activelist current_class';	
				}
				elseif($rsltdata['parent']  == $category->term_id)
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
					$timthumb = get_template_directory_uri().'/lib/timthumb.php';
					$term = get_the_title();
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
						echo '<li><img src="'. $timthumb.'?src='.$attach_icn->guid.'&amp;w=32&amp;h=32&amp;zc=0" alt="Breadcrumbs Icon" /></li>';
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
	
		<div class="right_featuredwpr">
			<div class="ftrdttl">Highlighted Resources</div>
			<?php
			$args = array(
				'meta_key' => 'oer_highlight',
				'meta_value' => 1,
				'post_type'  => 'resource',
				'orderby'	 => 'rand',
				'posts_per_page' => 10,
				'tax_query' => array(array('taxonomy' => 'resource-category','terms' => array($rsltdata['term_id'])))
			);
			$posts = get_posts($args);
			
			if(!empty($posts))
			{ ?>
			<ul class="featuredwpr_bxslider">
				<?php
				$timthumb = get_template_directory_uri().'/lib/timthumb.php';
				foreach($posts as $post)
				{
					setup_postdata( $post );
					$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					$title =  $post->post_title;
					$content =  $post->post_content;
				?>
					<li>
						<div class="frtdsnglwpr">
							<?php
							if(!empty($image)){?>
							<a href="<?php echo get_permalink($post->ID);?>"><div class="img"><img src="<?php echo $timthumb.'?src='.$image.'&amp;w=220&amp;h=180&amp;zc=0';?>" alt="<?php echo $title;?>"></div></a>
							<?php }
							else
							{
								$dfltimg = site_url().'/wp-content/plugins/wp-oer/images/default-icon.png';
								echo '<a href="'.get_permalink($post->ID).'"><div class="img"><img src="'.$timthumb.'?src='.$dfltimg.'&amp;w=220&amp;h=180&amp;zc=0" alt="'.$title.'"></div></a>';
							}
							?>
							<div class="ttl"><a href="<?php echo get_permalink($post->ID);?>"><?php echo $title;?></a></div>
							<div class="desc"><?php echo apply_filters('the_content',$content); ?></div>
						</div>
					</li>
				<?php
				}
				wp_reset_postdata();
				?>
			</ul>
			<?php 
			}
			else
			{
				echo "<ul class='featuredwpr_bxslider'>There are no resources available for $term</ul>";
			}
			?>	
		</div> <!--Highlighted Resources-->
    
		<div class="allftrdrsrc">
			<div class="snglrsrchdng">Browse <?php echo get_the_title();?> Resources</div>
			<div class="allftrdrsrccntr" onScroll="load_onScroll(this)" file-path="<?php echo get_template_directory_uri();?>/lib/ajax-scroll.php" data-id="<?php echo $rsltdata['term_id'];?>">
				<?php
				$args = array(
					'post_type' => 'resource',
					'posts_per_page' => 10,
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
							<a href="<?php echo get_permalink($post->ID);?>"><div class="snglimglft"><img src="<?php echo $timthumb.'?src='.$image.'&amp;w=80&amp;h=60&amp;zc=0';?>" alt="<?php echo $title;?>"></div></a>
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
    
		<?php 
			 $postid = get_the_ID();
			 $rslt = get_post_meta($postid, "enhance_page_content", true);
			
			if(!empty($rslt))
			{ 
				echo '<div class="allftrdpst">'.$rslt .'</div> ';
			}
		?><!--Text and HTML Widget-->
			
		<div class="allftrdpst">
			<div class="alltrdpsthdng">Features</div>
			<div class="inrftrdpstwpr">
				<?php
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => -1,
					'category' => $rsltdata['term_id']
				);
				$posts = get_posts($args);
				
				if(!empty($posts))
				{ ?>
				
				<ul class="allftrdpst_slider">
				<?php
				$timthumb = get_template_directory_uri().'/lib/timthumb.php';
				foreach($posts as $post)
				{
					$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					$title =  $post->post_title;
					$content = strip_tags($post->post_content);
					$content = substr($content, 0, 250);
				?>
					<li>
						<div class="allftrdsngl">
							<?php
							if(!empty($image)){?>
							<div class="pstimg"><img src="<?php echo $timthumb.'?src='.$image.'&amp;w=220&amp;h=180&amp;zc=0';?>" alt="<?php echo $title;?>"></div>
							<?php }?>
							<div class="rght-sd-cntnr-cntnt">
                                <div class="psttl"><?php echo $title;?></div>
                                <div class="pstdesc"><?php echo $content; ?></div>
                                <div class="pstrdmr"><a href="<?php echo get_permalink($post->ID);?>">More</a></div>
                                <div class="pstmta">
                                    <span class="date-icn"><?php echo get_the_time( 'F j, Y', $post->ID );?></span>
                                    <span class="time-icn"><?php echo  date('H:i', get_post_time( 'U', true));?></span>
                                </div>
							</div>
                        </div>
					</li>
				<?php
				}
				wp_reset_postdata();
				?>
			</ul>
			<?php 
			}
			else
			{
				echo "<ul class='allftrdpst_slider'>There are no resources available for $term</ul>";
			}
			?>
			</div>
		</div> <!--Feature Resource -->
    
	</div> <!--Content-->
</div>

<?php //get_sidebar( 'front' ); ?>
<?php get_footer(); ?>
