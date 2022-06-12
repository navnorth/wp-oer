<?php
/*
 * Template Name: Category Template One
 */
get_header();
$term = get_the_title();
$rsltdata = get_term_by( "name", $term, "resource-subject-area", ARRAY_A );
?>
<div class="oer-cntnr">
	<div class="oer-subject-btn" onclick="tglcategories(this);"> Subjects List </div>
    <div class="oer_category_sidebar">
	<?php
	echo '<ul class="oer-category">';
			$args = array('hide_empty' => 0, 'taxonomy' => 'resource-subject-area', 'parent' => 0);
			$categories= get_categories($args);
			foreach($categories as $category)
			{
				$children = get_term_children($category->term_id, 'resource-subject-area');
				
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
					echo '<li class="oer-sub-category has-child'.esc_attr($class).'"><span onclick="toggleparent(this);"><a href="'. esc_url(site_url() .'/'. $category->slug) .'" title="'. esc_attr($category->name) .'" >'. esc_html($category->name) .'</a></span>';
				}
				else
				{
					echo '<li class="oer-sub-category'.esc_attr($class).'"><span onclick="toggleparent(this);"><a href="'. esc_url(site_url() .'/'. $category->slug) .'"  title="'. esc_attr($category->name) .'" >'. esc_html($category->name) .'</a></span>';
				}
				echo get_category_child( $category->term_id);
				echo '</li>';
			}
	echo '</ul>';
	?>
</div> <!--Left Sidebar-->

	<div class="oer-rightcatcntr">
	
		<div class="oer-pgbrdcrums">
			<ul>
			<?php
				$term = get_the_title();
				$termid = get_term_by('name', $term, "resource-subject-area" );
				$strcat = get_custom_category_parents($termid, "resource-subject-area" , FALSE, ':', TRUE);
				if(strpos($strcat,':'))
				{
					$top_cat = split(':',$strcat);
				}
				$parent = $top_cat[0];
				
				$catobj = get_category_by_slug($parent);
				$getimage = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix.'postmeta'."  WHERE meta_key='category_image' AND meta_value=%s",$catobj->term_id));
				if(!empty($getimage))
				{
					$attach_icn = get_post($getimage[0]->post_id);
					$new_image_url = oer_resize_image( $attach_icn->guid, 32 , 32 , true );
					echo '<li><img src="'. esc_url($new_image_url) .'" alt="Breadcrumbs Icon" /></li>';
				}
				else
				{
					echo '<li></li>';
				}
				
			?>
			</ul>
		</div> <!--Breadcrumbs-->
	
		<div class="oer_right_featuredwpr">
			<div class="oer-ftrdttl">Highlighted Resources</div>
			<?php
			$args = array(
				'meta_key' => 'oer_highlight',
				'meta_value' => 1,
				'post_type'  => 'resource',
				'orderby'	 => 'rand',
				'posts_per_page' => 10,
				'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
			);
			$posts = get_posts($args);
			
			if(!empty($posts))
			{ ?>
			<ul class="featuredwpr_bxslider">
				<?php
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
							
							if(empty($image)){
								$image = OER_URL.'images/default-icon.png';
							}
							$new_image_url = oer_resize_image( $image, 220, 180, true );
							?>
							<a href="<?php echo esc_url(get_permalink($post->ID));?>"><div class="img"><img src="<?php echo esc_url($new_image_url);?>" alt="<?php echo esc_attr($title);?>"></div></a>
							<div class="ttl"><a href="<?php echo esc_url(get_permalink($post->ID));?>"><?php echo esc_html($title);?></a></div>
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
    
		<div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng">Browse <?php echo get_the_title();?> Resources</div>
			<div class="oer-allftrdrsrccntr" onScroll="load_onScroll(this)" file-path="<?php echo get_template_directory_uri();?>/lib/ajax-scroll.php" data-id="<?php echo esc_attr($rsltdata['term_id']);?>">
				<?php
				$args = array(
					'post_type' => 'resource',
					'posts_per_page' => 10,
					'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
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
						<div class="oer-snglrsrc">
							 <?php if(empty($image)){
								$image = OER_URL.'images/default-icon.png';
							}
							$new_image_url = oer_resize_image( $image , 80 , 60 , true );
							?>
							<a href="<?php echo esc_url(get_permalink($post->ID));?>"><div class="oer-snglimglft"><img src="<?php echo esc_url($new_image_url);?>" alt="<?php echo esc_attr($title);?>"></div></a>
							<div class="oer-snglttldscrght <?php if(empty($image)){ echo 'snglttldscrghtfull';}?>">
								<div class="ttl"><a href="<?php echo esc_url(get_permalink($post->ID));?>"><?php echo esc_html($title);?></a></div>
								<div class="desc"><?php echo wp_kses_post($content); ?></div>
							</div>
						</div>
					<?php
					}
					wp_reset_postdata();
				}
				else
				{
					echo "<div class='oer-snglrsrc'>There are no resources available for $term</div>";
				}	
				?>
		   </div>
		</div> <!--Browse By Categories-->
    
		<?php 
			 $postid = get_the_ID();
			 $rslt = get_post_meta($postid, "enhance_page_content", true);
			
			if(!empty($rslt))
			{ 
				echo '<div class="oer-allftrdpst">'.wp_kses_post($rslt) .'</div> ';
			}
		?><!--Text and HTML Widget-->
			
		<div class="oer-allftrdpst">
			<div class="oer-alltrdpsthdng">Features</div>
			<div class="oer-inrftrdpstwpr">
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
							if(!empty($image)){
								$new_image = oer_resize_image( $image , 220 , 180 , true );
								?>
							<div class="pstimg"><img src="<?php echo esc_url($new_image);?>" alt="<?php echo esc_attr($title);?>"></div>
							<?php }?>
							<div class="rght-sd-cntnr-cntnt">
							<div class="psttl"><?php echo esc_html($title);?></div>
							<div class="pstdesc"><?php echo wp_kses_post($content); ?></div>
							<div class="pstrdmr"><a href="<?php echo esc_url(get_permalink($post->ID));?>">More</a></div>
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
