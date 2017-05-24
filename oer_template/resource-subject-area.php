<?php
/*
 * Template Name: Default Category Page Template
 */
/** Add default stylesheet for Resource Category page **/
wp_enqueue_style('bxslider-style', OER_URL.'/css/jquery.bxslider.css');
wp_register_style( "resource-category-styles", OER_URL . "css/resource-category-style.css" );
wp_enqueue_style( "resource-category-styles" );

/** Add default javascript **/
wp_enqueue_script('bxslider-script', OER_URL.'/js/jquery.bxslider.js');
wp_register_script( "resource-script" , OER_URL ."js/resource-category.js" );
wp_enqueue_script( "resource-script" );
wp_enqueue_script( "ajax-script", OER_URL."js/front_ajax.js", array("jquery"));

/** Load WordPress Theme Header **/
get_header();

//Add this hack to display top nav and head section on Eleganto theme
$cur_theme = wp_get_theme();
$theme = $cur_theme->get('Name');
if ($theme == "Eleganto"){
	get_template_part( 'template-part', 'topnav' );
	get_template_part( 'template-part', 'head' );
}

global $_subjectarea;

//Get ID of Resource Category
$term_id = get_queried_object_id();

//Get Term based on Category ID
//$term = get_the_title();
$terms = get_term_by( "id" , $term_id , 'resource-subject-area' , object );
$term = $terms->name;

$rsltdata = get_term_by( "name", $term, "resource-subject-area", ARRAY_A );

$parentid = array();
if($rsltdata['parent'] != 0)
{
	$parent = get_oer_parent_term($rsltdata['parent']);
	
	for($k=0; $k < count($parent); $k++)
	{
		if ($parent[$k]) {
			//$idObj = get_category_by_slug($parent[$k]);
			$idObj = get_term_by('slug', $parent[$k], 'resource-subject-area');
			$parentid[] = $idObj->term_id;
		}
	}
}

// Checks if subject area title is set to hide 
$hide_title = get_option('oer_hide_subject_area_title');
?>
<div id="oer-content">
<div class="oer-cntnr row">
<?php if ($_subjectarea) { ?>
	<div class="col-md-3">
	<!--<div class="resource_category_sidebar template_resource_category_sidebar col-md-3">-->
	<?php
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('subject_area_sidebar') ) : 

	endif;
	/*echo '<ul class="resource_category">';
			$args = array('hide_empty' => 0, 'taxonomy' => 'resource-subject-area', 'parent' => 0);
			$categories= get_categories($args);
                        
			foreach($categories as $category)
			{
				$children = get_term_children($category->term_id, 'resource-subject-area');
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
					echo '<li class="oer-sub-category has-child'.$class.'"><span onclick="toggleparent(this);"><a href="'. site_url() .'/'.$category->taxonomy.'/'. $category->slug .'" title="'. $category->name .'" >'. $category->name .'</a></span>';
				}
				else
				{
					echo '<li class="oer-sub-category'.$class.'"><span onclick="toggleparent(this);"><a href="'. site_url() .'/'.$category->taxonomy.'/'. $category->slug .'"  title="'. $category->name .'" >'. $category->name .'</a></span>';
				}
				
				echo get_oer_category_child( $category->term_id, $rsltdata['term_id']);
				echo '</li>';
			}
	echo '</ul>';*/
	?>
	</div>
<!--</div>--> <!--Left Sidebar-->
<?php } ?>
	<div class="oer-rightcatcntr<?php if ($_subjectarea) { ?> col-md-9<?php } ?>">
		<?php if (!$hide_title) { ?>
		<div class="oer-pgbrdcrums">
			<ul>
				<?php
					$strcat = get_custom_oer_category_parents($term_id, "resource-subject-area" , FALSE, ':', TRUE);
                                        
					if(strpos($strcat,':'))
					{
						$top_cat = explode(':',$strcat);
					}
					
					$parent = $top_cat[0];
					
					$catobj = get_term_by( 'slug' , $parent , 'resource-subject-area' );
                                        
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
					$opt = array(
						'nofollowhome' => true,
						'home' => 'Home',
						'blog' => 'Blog',
						'sep' => '-',
						'prefix' => ''
					);
					/*if(function_exists('yoast_breadcrumb'))
					{
						//Custom breadcrumbs using Yoast
						$opt = get_option("yoast_breadcrumbs");*/
					
					$nofollow = ' ';
					if ($opt['nofollowhome']) {
						$nofollow = ' rel="nofollow" ';
					}
					
					$on_front = get_option('show_on_front');
					if ($on_front == "page") {
						//$homelink = '<a'.$nofollow.'href="'.get_permalink(get_option('page_on_front')).'">'.$opt['home'].'</a>';
						$bloglink = $homelink.' '.$opt['sep'].' <a href="'.get_permalink(get_option('page_for_posts')).'">'.$opt['blog'].'</a>';
					} else {
						//$homelink = '<a'.$nofollow.'href="'.get_bloginfo('url').'">'.$opt['home'].'</a>';
						$bloglink = $homelink;
					}
					$taxonomy 	= get_taxonomy ( get_query_var('taxonomy') );
					$term 		= get_query_var('term');
					$cur_term 	= get_term_by( 'slug' , $term , 'resource-subject-area' );
					$output 	.= ' '.$opt['sep'].' ';
					$post 		= $wp_query->get_queried_object();
					
					// If this is a top level Page, it's simple to output the breadcrumb
					if ( 0 == $post->parent ) {
						//$output = "<h1>".$homelink." ".$opt['sep']." ".$taxonomy->label .': '.$cur_term->name . "</h1>";
						$output = "<h1>".$cur_term->name . "</h1>";
					} else {
						if (isset($post->ancestors)) {
							if (is_array($post->ancestors))
								$ancestors = array_values($post->ancestors);
							else 
								$ancestors = array($post->ancestors);				
						} else {
							$ancestors = array($post->parent);
						}
			
						// Reverse the order so it's oldest to newest
						$ancestors = array_reverse($ancestors);
						
						// Add the current Page to the ancestors list (as we need it's title too)
						$ancestors[] = $post->term_id;
						
						$links = array();			
						foreach ( $ancestors as $ancestor ) {
							$termObj = get_term_by( 'term' , $ancestor , 'resource-subject-area' );
							//var_dump($termObj);
							$tmp  = array();
							$tmp['title'] 	= $termObj->name;
							$tmp['url'] 	= site_url() .'/resource-subject-area/'. $termObj->slug;
							$tmp['cur'] = false;
							if ($ancestor == $post->term_id) {
								$tmp['cur'] = true;
							}
							$links[] = $tmp;
						}
						$output = "<h1>";
						//$output .= $homelink;
						
						foreach ( $links as $link ) {
							//$output .= ' '.$opt['sep'].' ';
							if (!$link['cur']) {
								$output .= '<a href="'.$link['url'].'">'.$link['title'].'</a>';
							} else {
								//$output .= $taxonomy->label .': '. $link['title'];
								$output .= ': '. $link['title'];
							}
						}
					}
					if ($opt['prefix'] != "") {
						$output = $opt['prefix']." ".$output;
					}
					
					$output .= "</h1>";
					//$output .= $taxonomy->label .': '. $cur_term->name ;
					echo ucwords($output);
					//$breadcrumbs = yoast_breadcrumb("","",false);
					//echo ucwords ($breadcrumbs);
					/*} */
					?>
				</li>
			</ul>	
		</div> <!--Breadcrumbs-->
		<?php } ?>
		
		
		<?php
		// Display Child Subject Areas
		if (!$_subjectarea) {
		?>
		
		<div class="oer-rsrcctgries tagcloud">
		
		<?php
		$child_subjects = get_child_subjects($term_id);
			
		if ( ! is_wp_error( $child_subjects ) ) {
			foreach ( $child_subjects as $subject ) {
				echo '<span><a href="'.get_term_link($subject).'" class="button">'.ucwords ($subject->name).'</a></span>';
			}
		}
		?>
		
		</div><!-- Child Subject Areas -->
		
		<?php
		}
		
		//Get Highlighted Resources count
		$args = array(
			'meta_key' => 'oer_highlight',
			'meta_value' => 1,
			'post_type'  => 'resource',
			'orderby'	 => 'rand',
			'posts_per_page' => -1,
			'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
		);
		$highlighted_resources = get_posts($args);
		$highlighted_resources_count = count($highlighted_resources);
		
		$items_per_load = 4;
		
		$args = array(
			'meta_key' => 'oer_highlight',
			'meta_value' => 1,
			'post_type'  => 'resource',
			'orderby'	 => 'rand',
			'posts_per_page' => $items_per_load,
			'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
		);
		$max_resources = new WP_Query($args);
		$max_limit = $max_resources->max_num_pages;
		
		$paged = 1;
		
		$args = array(
			'meta_key' => 'oer_highlight',
			'meta_value' => 1,
			'post_type'  => 'resource',
			'orderby'	 => 'rand',
			'posts_per_page' => $items_per_load*$paged,
			'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
		);
		
		$args = array(
			'meta_key' => 'oer_highlight',
			'meta_value' => 1,
			'post_type'  => 'resource',
			'orderby'	 => 'rand',
			'posts_per_page' => -1,
			'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
		);
		
		$posts = get_posts($args);
		
		if(!empty($posts))
		{ ?>
		<div class="oer_right_featuredwpr">
			<div class="oer-ftrdttl">Highlighted Resources</div>
			<ul class="featuredwpr_bxslider" data-term-id="<?php echo $rsltdata['term_id']; ?>" data-max-page="<?php echo $max_limit; ?>" data-count="<?php echo $highlighted_resources_count; ?>">
				<?php
				$i=1;
				foreach($posts as $post)
				{
					setup_postdata( $post );
					$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					$title =  $post->post_title;
					//$content =  $post->post_content;
					$offset = 0;
					$ellipsis = "...";
					if (strlen($post->post_content)>150) {
						$offset = strpos($post->post_content, ' ', 150);
					} else
						$ellipsis = "";
					
					$length = 150;
					
					$content =  trim(substr($post->post_content,0,$length)).$ellipsis;
				?>
					<li data-id="<?php echo $post->ID; ?>">
					<?php if ($i<=$items_per_load) { ?>
						<div class="frtdsnglwpr">
							<?php
							if(empty($image)){
								$image = site_url().'/wp-content/plugins/wp-oer/images/default-icon.png';
							}
							$new_image_url = oer_resize_image( $image, 220, 180, true );
							?>
							<a href="<?php echo get_permalink($post->ID);?>"><div class="img"><img class="lazy" data-original="<?php echo $new_image_url;?>" alt="<?php echo $title;?>"></div></a>
							<div class="ttl"><a href="<?php echo get_permalink($post->ID);?>"><?php echo $title;?></a></div>
							<div class="desc"><?php echo apply_filters('the_content',$content); ?></div>
						</div>
					<?php } ?>
					</li>
				<?php
				$i++;
				}
				wp_reset_postdata();
				?>
			</ul>
		</div> <!--Highlighted Resources-->
		<?php 
			}
		?>
		
		<?php 
			 $postid = get_the_ID();
			 $rslt = get_post_meta($postid, "enhance_page_content", true);
			
			if(!empty($rslt))
			{ 
				echo '<div class="oer-allftrdpst">'.$rslt .'</div> ';
			}
			$termObj = get_term_by( 'slug' , $term , 'resource-subject-area' );
		?> <!--Text and HTML Widget-->
		
		<div class="oer-allftrdrsrc">
			<?php
				//Get Resource count
				$args = array(
					'post_type' => 'resource',
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
				);
				$resources = get_posts($args);
				$resource_count = count($resources);
				?>
			<div class="oer-snglrsrchdng"><?php printf(__("Browse All %d Resources", OER_SLUG), $resource_count); ?><?php get_sort_box(array($rsltdata['term_id'])); ?></div>
			<div class="oer-allftrdrsrccntr" id="content-resources" file-path="<?php echo get_template_directory_uri();?>/lib/ajax-scroll.php" data-id="<?php echo $rsltdata['term_id'];?>">
				<?php
				//Get number of pages
				$items_per_page = 20;
				$args = array(
					'post_type' => 'resource',
					'posts_per_page' => $items_per_page,
					'post_status' => 'publish',
					'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
				);
				$max = new WP_Query($args);
				$max_pages = $max->max_num_pages;
				
				$paged = 1;
				
				$args = array(
					      'post_type' => 'resource',
					      'posts_per_page' => 20 * $paged,
					      'post_status' => 'publish',
					      'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
					      );
				
				//Apply sort args
				$args = apply_sort_args($args);
				
				$posts = get_posts($args);
				
				if(!empty($posts))
				{
					foreach($posts as $post)
					{
						$w_image = true;
						//set new_image_url to empty to reset on every loop
						$new_image_url = "";
						
						$img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , "full" );
						
						if (empty($img_url)) {
							$w_image = false;
							$new_image_url = OER_URL . 'images/default-icon-220x180.png';
						}
						
						$title =  $post->post_title;
						$content =  $post->post_content;
						$ellipsis = "...";
						if (strlen($post->post_content)<180)
							$ellipsis = "";
							
						$content = substr($content, 0, 180).$ellipsis;
						
						$img_path = $new_img_path = parse_url($img_url[0]);
						$img_path = $_SERVER['DOCUMENT_ROOT'] . $img_path['path'];
						if(!empty($img_url))
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
						<div class="oer-snglrsrc">
							<?php
							echo '<a href="'.get_permalink($post->ID).'" class="oer-resource-link"><div class="oer-snglimglft"><img src="'.$new_image_url.'"></div></a>';
							?>
							<div class="oer-snglttldscrght <?php if(empty($img_url)){ echo 'snglttldscrghtfull';}?>">
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
					?>
					<div class='oer-snglrsrc'><?php sprintf(__("There are no resources available for %s", OER_SLUG), $term); ?></div>
					<?php
				}
				//Show load more button
				if ($resource_count>$items_per_page & $paged<(int)$max_pages) {
					$base_url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
					
					if (isset($_SESSION['resource_sort']))
						$_rsort = " data-sort='".(int)$_SESSION['resource_sort']."'";
					
					if (strpos($base_url,"page"))
							$base_url = substr($base_url,0,strpos($base_url, "page")-1);
					echo '<div class="col-md-12 tagcloud resourcecloud"><a href="?page='.($paged+1).'" '.$_rsort.' data-subject-ids="'.json_encode(array($rsltdata['term_id'])).'" data-page-number="'.($paged+1).'" data-base-url="'.$base_url.'" class="button resource-load-more-button" data-max-page="'.$max_pages.'" class="btn-load-more">Load More</a></div>';
				}
				?>
		   </div>
		</div> <!--Browse By Categories-->
		
		<?php
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => -1,
			'category_name' => $termObj->slug
		);
		
		$posts = get_posts($args);
		
		if(!empty($posts))
		{ ?>
		<div class="oer-allftrdpst">
			<div class="oer-alltrdpsthdng"><?php printf( __( 'Recommended %s Content', OER_SLUG ) , $termObj->name ); ?></div>
			<div class="oer-inrftrdpstwpr">
				<ul class="allftrdpst_slider">
				<?php
				foreach($posts as $post)
				{
					$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					$title =  $post->post_title;
					$content = strip_tags($post->post_content);
					$ellipsis = "...";
					
					if (strlen($post->post_content)<180)
						$ellipsis = "";
							
					$content = substr($content, 0, 250).$ellipsis;
				?>
					<li>
						<div class="allftrdsngl">
							<?php
							if(!empty($image)){
								$new_image = oer_resize_image( $image , 220 , 180 , true );
								?>
							<div class="pstimg"><img src="<?php echo $new_image;?>" alt="<?php echo $title;?>"></div>
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
			</div>
		</div> <!--Feature Resource -->
		<?php 
			}
		?>
    
	</div>
        <div class="clear"></div>
</div>
</div>
<script type="text/javascript">
	jQuery(window).load(function(){
		//Sets height of browse section to 4 items by default
		var bHeight = jQuery('.oer-snglrsrc').outerHeight(true);
		/*if (jQuery('.oer-snglrsrc:nth-child(2)').length) {
		    bHeight = jQuery('.oer-snglrsrc:nth-child(2)').outerHeight();
		}*/
		bHeight = Math.floor(bHeight*3.5);
		jQuery('.oer-allftrdrsrccntr').css('height',bHeight+'px')
	});
</script>
<?php
if ($theme == "Eleganto"){
	get_template_part( 'template-part', 'footernav' );
}

get_footer();
?><!-- Load WordPress Theme Footer -->