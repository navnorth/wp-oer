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
		<div class="oer-pgbrdcrums" id="breadcrumbs">
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
						$homelink = '<a'.$nofollow.'href="'.get_permalink(get_option('page_on_front')).'">'.$opt['home'].'</a>';
						$bloglink = $homelink.' '.$opt['sep'].' <a href="'.get_permalink(get_option('page_for_posts')).'">'.$opt['blog'].'</a>';
					} else {
						$homelink = '<a'.$nofollow.'href="'.get_bloginfo('url').'">'.$opt['home'].'</a>';
						$bloglink = $homelink;
					}
					$taxonomy 	= get_taxonomy ( get_query_var('taxonomy') );
					$term 		= get_query_var('term');
					$cur_term 	= get_term_by( 'slug' , $term , 'resource-subject-area' );
					$output 	.= ' '.$opt['sep'].' ';
					$post 		= $wp_query->get_queried_object();
					
					// If this is a top level Page, it's simple to output the breadcrumb
					if ( 0 == $post->parent ) {
						$output = $homelink." ".$opt['sep']." ".$taxonomy->label .': '.$cur_term->name;
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
			
						$output = $homelink;
						
						foreach ( $links as $link ) {
							$output .= ' '.$opt['sep'].' ';
							if (!$link['cur']) {
								$output .= '<a href="'.$link['url'].'">'.$link['title'].'</a>';
							} else {
								$output .= $taxonomy->label .': '. $link['title'];
							}
						}
					}
					if ($opt['prefix'] != "") {
						$output = $opt['prefix']." ".$output;
					}
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
			 $postid = get_the_ID();
			 $rslt = get_post_meta($postid, "enhance_page_content", true);
			
			if(!empty($rslt))
			{ 
				echo '<div class="oer-allftrdpst">'.$rslt .'</div> ';
			}
		?> <!--Text and HTML Widget-->
		
		<div class="oer-allftrdrsrc">
			<div class="oer-snglrsrchdng"><?php sprintf(__("Browse %s Resources", OER_SLUG), $term); ?></div>
			<div class="oer-allftrdrsrccntr" onScroll="load_onScroll(this)" file-path="<?php echo get_template_directory_uri();?>/lib/ajax-scroll.php" data-id="<?php echo $rsltdata['term_id'];?>">
				<?php
				$args = array(
					'post_type' => 'resource',
					'posts_per_page' => 15,
					'tax_query' => array(array('taxonomy' => 'resource-subject-area','terms' => array($rsltdata['term_id'])))
				);
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
						$content = substr($content, 0, 180);
						
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
				?>
		   </div>
		</div> <!--Browse By Categories-->
    
	</div>
        <div class="clear"></div>
</div>
<?php
if ($theme == "Eleganto"){
	get_template_part( 'template-part', 'footernav' );
}

get_footer();
?><!-- Load WordPress Theme Footer -->