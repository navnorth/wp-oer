<?php
/*
 * Template Name: Default Archive Resource Template
 */
global $_nalrc, $_nalrc_products, $_resource_results; ;
$printable = false;
$params = [];
$allowed_tags = oer_allowed_html();
add_filter( 'body_class','oer_archive_body_classes' );
function oer_archive_body_classes( $classes ) {
 
    $classes[] = 'resource-archive';
    return $classes;
     
}
// Load Custom NALRC Javascript
if ($_nalrc){
	wp_register_script("nalrc-script",OER_URL."js/nalrc.js");
	wp_enqueue_script("nalrc-script");
	wp_localize_script("nalrc-script", "nalrc_object", array("ajaxurl" => admin_url( 'admin-ajax.php' ), "plugin_url" => OER_URL));
}

if (isset($_GET['action']) && $_GET['action']=='print'){
	$printable = true;
} else {
	$printable = false;
}

get_header();
if (!$printable){
?>
<div class="oer-cntnr">
    <section id="primary" class="site-content<?php if ($_nalrc) _e(' nalrc-resources-content',OER_SLUG); ?>">
	<div id="content" role="main">

	    <?php if ( have_posts() ) : ?>
	    	<?php if ($_nalrc): ?>
	    		<header class="archive-header nalrc-resources-header">
				    <h1 class="archive-title nalrc-resources-title"><?php 
				    	if (get_option('oer_nalrc_resources_page_title')): 
				    		echo esc_html(get_option('oer_nalrc_resources_page_title'));
				    	else : 
				    		_e( 'Native American Language Resource Collection', OER_SLUG ); 
				    	endif; ?></h1>
				    <p class="nalrc-resources-description"><?php 
				    	if (get_option('oer_nalrc_resources_content')):
				    		echo do_shortcode(wpautop(wp_kses(get_option('oer_nalrc_resources_content'), $allowed_tags)));
				    	else :
				    		_e('Explore a collection of high-quality multimedia instructional resources, informed by research, for use by Native American language stakeholders. These resources have been reviewed by subject matter experts and recommended and approved by the U.S. Department of Education, Office of Indian Education.', OER_SLUG); 
				    	endif;
				    ?></p>
				</header><!-- .archive-header -->
	    	<?php else: ?>
				<header class="archive-header">
				    <h1 class="archive-title"><?php printf( __( 'Archives: %s', OER_SLUG ), '<span>' .post_type_archive_title('', false).'</span>' );?></h1>
				</header><!-- .archive-header -->
			<?php endif; ?>
			<?php /** NALRC Search Filter **/
			if ($_nalrc): ?>
				<div class="nalrc-search-filters">
					<div class="action-btns">
						<a href="#" id="oer_print_resource_list"><i class="fa fa-print" aria-hidden="true"></i> <?php _e('Print list' , OER_SLUG) ?></a>
						<a href="#" id="oer_print_resource_table"><i class="fa fa-print" aria-hidden="true"></i> <?php _e('Print table' , OER_SLUG) ?></a>
					</div>
					<div class="row filter-title">
						<div class="col-md-12">
							<h2><?php _e('Search Resource Collection: ', OER_SLUG); ?></h2>
						</div>
					</div>
					<div class="row">
						<div class="nalrc-search-keyword col-md-5">
							 <i class="fa fa-search icon"></i>
							<input type="text" id="keyword" placeholder="<?php _e('Search by keyword or phrase',OER_SLUG); ?>" aria-label="<?php _e('Search resources by keyword or phrase',OER_SLUG); ?>" />
						</div>
						<div class="nalrc-search-product col-md-3">
							<div class="nalrc-select-wrapper">
								<select id="product" class="nalrc-product-filter nalrc-select-filter selectpicker" multiple title="Resource Type" aria-label="Resource Type">
									<?php foreach ($_nalrc_products as $product): ?>
										<option value="<?php echo esc_html($product['value']); ?>"><?php echo esc_html($product['label']); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="nalrc-search-grade-level col-md-2">
							<?php
							$grades = get_terms(['taxonomy'=>'resource-grade-level','hide_empty'=>false]);
							?>
							<div class="nalrc-select-wrapper">
								<select id="gradeLevel" class="nalrc-grade-level-filter nalrc-select-filter selectpicker" multiple title="Grade Level" aria-label="Grade Level">
									<?php foreach($grades as $grade): ?>
										<option value="<?php echo esc_html($grade->term_id); ?>"><?php echo esc_html($grade->name); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="nalrc-search-button-wrapper col-md-2">
							<button class="nalrc-search-button"><?php _e('Search >', OER_SLUG); ?></button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php /* Start the Loop */ ?>

		<?php if ($_nalrc): ?>
			<article class="oer_resource_posts">
		<?php endif; ?>

		<?php while ( have_posts() ) :  the_post(); ?>

		    <div class="oer_blgpst<?php if ($_nalrc) _e(' nalrc-blogpost',OER_SLUG); ?>">
				    
			<?php if ( has_post_thumbnail() ) {?>
			    <div class="oer-feature-image <?php if ($_nalrc): ?>col-md-2<?php else: ?>col-md-3<?php endif; ?>">
				<?php if ( ! post_password_required() && ! is_attachment() ) : ?>
					<a href="<?php echo esc_url(get_permalink($post->ID)); ?>" tabindex="-1" aria-hidden="true">
						<?php 
						$image_id = get_post_thumbnail_id();
						$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
						if (empty($image_alt))
							$image_alt = esc_html(get_the_title($post->ID));
						the_post_thumbnail("thumbnail", array('alt'=>__('Resource Screenshot: ').$image_alt)); 
						?>
					</a>
				<?php endif; ?>
			    </div>
			<?php } else {
			    $new_image_url = OER_URL . 'images/default-icon-220x180.png';
			    $col = 'col-md-3';
			     if ($_nalrc)
			     	$col = 'col-md-2';
			    echo '<div class="oer-feature-image '.$col.'"><a href="'.esc_url(get_permalink($post->ID)).'" tabindex="-1"><img src="'.esc_url($new_image_url).'" alt="'.__('Resource Screenshot: ').esc_html(get_the_title($post->ID)).' image"></a></div>';
			}
			$content_col = 'col-md-9';
			if ($_nalrc)
				$content_col = 'col-md-10';

			$resource_atts = "";
			?>
				    
			<div class="rght-sd-cntnr-blg <?php echo $content_col; ?>">
			    <h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
			    <div class="small">
			    	<?php if ($_nalrc && !empty(get_post_meta($post->ID, 'oer_datecreated')[0])): 
			    		$resource_atts .= '<span>'.esc_html(get_post_meta($post->ID, 'oer_datecreated')[0]).'</span>';
			    	endif; ?>
			    	<?php if ($_nalrc && !empty(get_post_meta($post->ID,'oer_lrtype')[0])): 
			    		if (!empty($resource_atts))
			    			$resource_atts .= ' | <span>'.ucfirst(get_post_meta($post->ID, 'oer_lrtype')[0]).'</span>';
			    		else
			    			$resource_atts .= '<span>'.ucfirst(get_post_meta($post->ID, 'oer_lrtype')[0]).'</span>';
			    	endif; 
			    	echo $resource_atts;
			    	?>
			    </div>
					    
			    <div class="oer-post-content">
					<?php 
					$excerpt = get_the_excerpt($post->ID);
					$excerpt = oer_get_limited_excerpt($excerpt,150);
					echo esc_html(ucfirst($excerpt));
					 ?>
			    </div>
			    <?php
			    $grades = array();
			    $grade_terms = get_the_terms( $post->ID, 'resource-grade-level' );
			    
			    if (is_array($grade_terms)){
			        foreach($grade_terms as $grade){
			            $grades[] = $grade->name;
			        }
			    }
			    if (!empty($grades) && oer_grade_levels($grades)!="N/A"):
			    ?>
			    <div class="oer-intended-audience">
			    	<span class="label"><?php _e("For: ", OER_SLUG); ?></span><span class="value"><?php echo oer_grade_levels($grades); ?></span>
			    </div>
				<?php endif; ?>
			</div>
		    </div>
		<?php endwhile; ?>

		<?php if ($_nalrc): ?>
			<div class="nalrc-pagination-nav">
				<div class="alignleft"><?php previous_posts_link( '&laquo; Previous' ); ?></div>
				<div class="alignright"><?php next_posts_link( 'Next &raquo;', '' ); ?></div>
			</div>
			</article>
		<?php endif; ?>

	    <?php else : ?>
		<article id="post-0" class="post no-results not-found">
		    <header class="entry-header">
			<h1 class="entry-title"><?php _e( 'Nothing Found', OER_SLUG ); ?></h1>
		    </header>

		    <div class="entry-content">
			<p><?php _e( 'Sorry, but there are no resources to display.', OER_SLUG ); ?></p>
		    </div><!-- .entry-content -->
		</article><!-- #post-0 -->
	    <?php endif; ?>

	</div><!-- #content -->
    </section><!-- #primary -->
</div>
<?php 
} else { 
	if (isset($_GET['keyword']) && $_GET['keyword'])
		$params['keyword'] = $_GET['keyword'];
	if (isset($_GET['gradelevel']) && $_GET['gradelevel'])
		$params['gradelevel'] = $_GET['gradelevel'];
	if (isset($_GET['product']) && $_GET['product'])
		$params['product'] = $_GET['product'];
	$resources = oer_filter_resources($params);
	
	// List Style Printing
	if (isset($_GET['view']) && $_GET['view']=='list'){
?>
<div class="oer-cntnr">
    <section id="primary" class="site-content<?php if ($_nalrc) _e(' nalrc-resources-content',OER_SLUG); ?>">
	<div id="content" role="main">

	    <?php if ( count($resources)>0 ) : ?>
	    	<?php if ($_nalrc): ?>
	    		<header class="archive-header nalrc-resources-header">
				    <h1 class="archive-title nalrc-resources-title"><?php 
				    	if (get_option('oer_nalrc_resources_page_title')): 
				    		echo esc_html(get_option('oer_nalrc_resources_page_title'));
				    	else : 
				    		_e( 'Native American Language Resource Collection', OER_SLUG ); 
				    	endif; ?></h1>
				</header><!-- .archive-header -->
	    	<?php else: ?>
				<header class="archive-header">
				    <h1 class="archive-title"><?php printf( __( 'Archives: %s', OER_SLUG ), '<span>' .post_type_archive_title('', false).'</span>' );?></h1>
				</header><!-- .archive-header -->
			<?php endif; ?>

		<?php if ($_nalrc): ?>
			<article class="oer_resource_posts">
		<?php endif; ?>

			<?php foreach($resources as $resource) { 
				?>
		    <div class="oer_blgpst<?php if ($_nalrc) _e(' nalrc-blogpost',OER_SLUG); ?>">
				    
			<?php if ( has_post_thumbnail($resource->ID) ) {?>
			    <div class="oer-feature-image <?php if ($_nalrc): ?>col-md-2<?php else: ?>col-md-3<?php endif; ?>">
				<?php if ( ! post_password_required() && ! is_attachment() ) : ?>
					<a href="<?php echo esc_url(get_permalink($resource->ID)); ?>" tabindex="-1" aria-hidden="true">
						<?php 
						$image_id = get_post_thumbnail_id($resource->ID);
						$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
						if (empty($image_alt))
							$image_alt = esc_html(get_the_title($resource->ID));
						echo get_the_post_thumbnail($resource->ID, "thumbnail", array('alt'=>__('Resource Screenshot: ').$image_alt)); 
						?>
					</a>
				<?php endif; ?>
			    </div>
			<?php } else {
			    $new_image_url = OER_URL . 'images/default-icon-220x180.png';
			    $col = 'col-md-3';
			     if ($_nalrc)
			     	$col = 'col-md-2';
			    echo '<div class="oer-feature-image '.$col.'"><a href="'.esc_url(get_permalink($resource->ID)).'" tabindex="-1"><img src="'.esc_url($new_image_url).'" alt="'.__('Resource Screenshot: ').esc_html(get_the_title($post->ID)).' image"></a></div>';
			}
			$content_col = 'col-md-9';
			if ($_nalrc)
				$content_col = 'col-md-10';

			$resource_atts = "";
			?>
				    
			<div class="rght-sd-cntnr-blg <?php echo $content_col; ?>">
			    <h3><a href="<?php the_permalink($resource->ID); ?>" rel="bookmark" title="<?php echo get_the_title($resource->ID); ?>"><?php echo get_the_title($resource->ID); ?></a></h3>
			    <div class="small">
			    	<?php if ($_nalrc && !empty(get_post_meta($resource->ID, 'oer_datecreated')[0])): 
			    		$resource_atts .= '<span>'.esc_html(get_post_meta($resource->ID, 'oer_datecreated')[0]).'</span>';
			    	endif; ?>
			    	<?php if ($_nalrc && !empty(get_post_meta($resource->ID,'oer_lrtype')[0])): 
			    		if (!empty($resource_atts))
			    			$resource_atts .= ' | <span>'.ucfirst(get_post_meta($resource->ID, 'oer_lrtype')[0]).'</span>';
			    		else
			    			$resource_atts .= '<span>'.ucfirst(get_post_meta($resource->ID, 'oer_lrtype')[0]).'</span>';
			    	endif; 
			    	echo $resource_atts;
			    	?>
			    </div>
					    
			    <div class="oer-post-content">
					<?php 
					$excerpt = get_the_excerpt($resource->ID);
					$excerpt = oer_get_limited_excerpt($excerpt,150);
					echo esc_html(ucfirst($excerpt));
					 ?>
			    </div>
			    <?php
			    $grades = array();
			    $grade_terms = get_the_terms( $resource->ID, 'resource-grade-level' );
			    
			    if (is_array($grade_terms)){
			        foreach($grade_terms as $grade){
			            $grades[] = $grade->name;
			        }
			    }
			    if (!empty($grades) && oer_grade_levels($grades)!="N/A"):
			    ?>
			    <div class="oer-intended-audience">
			    	<span class="label"><?php _e("For: ", OER_SLUG); ?></span><span class="value"><?php echo oer_grade_levels($grades); ?></span>
			    </div>
				<?php endif; ?>
			</div>
		    </div>
		<?php } ?>
		<?php if ($_nalrc): ?>
			</article>
		<?php endif; ?>
	    <?php else : ?>
		<article id="post-0" class="post no-results not-found">
		    <header class="entry-header">
			<h1 class="entry-title"><?php _e( 'Nothing Found', OER_SLUG ); ?></h1>
		    </header>

		    <div class="entry-content">
			<p><?php _e( 'Sorry, but there are no resources to display.', OER_SLUG ); ?></p>
		    </div><!-- .entry-content -->
		</article><!-- #post-0 -->
	    <?php endif; ?>

	</div><!-- #content -->
    </section><!-- #primary -->
</div>
<?php
	} elseif (isset($_GET['view']) && $_GET['view']=='table'){
	// Table View Printing	
	?>
	<div class="oer-cntnr">
	    <section id="primary" class="site-content<?php if ($_nalrc) _e(' nalrc-resources-content',OER_SLUG); ?>">
		<div id="content" role="main">

		    <?php if ( count($resources)>0 ) : ?>
		    	<?php if ($_nalrc): ?>
		    		<header class="archive-header nalrc-resources-header">
					    <h1 class="archive-title nalrc-resources-title"><?php 
					    	if (get_option('oer_nalrc_resources_page_title')): 
					    		echo esc_html(get_option('oer_nalrc_resources_page_title'));
					    	else : 
					    		_e( 'Native American Language Resource Collection', OER_SLUG ); 
					    	endif; ?></h1>
					</header><!-- .archive-header -->
		    	<?php else: ?>
					<header class="archive-header">
					    <h1 class="archive-title"><?php printf( __( 'Archives: %s', OER_SLUG ), '<span>' .post_type_archive_title('', false).'</span>' );?></h1>
					</header><!-- .archive-header -->
				<?php endif; ?>

			<?php if ($_nalrc): ?>
				<table class="oer_resource_posts responsive-table">
					<thead>
						<tr>
							<th class="resource-thumbnail">Thumbnail</th>
							<th class="resource-title">Title</th>
							<th class="resource-date-created">Date Created</th>
							<th class="resource-type">Resource Type</th>
							<th class="resource-description">Description</th>
							<th class="resource-grade-level">Grade Level</th>
							<th class="resource-link">Link</th>
						</tr>
					</thead>
					<tbody>
			<?php endif; ?>

			<?php foreach($resources as $resource) {  ?>
				<tr>
					<td class="resource-thumbnail">    
				<?php if ( has_post_thumbnail($resource->ID) ) {?>
				    <div class="oer-feature-image <?php if ($_nalrc): ?>col-md-2<?php else: ?>col-md-3<?php endif; ?>">
					<?php if ( ! post_password_required() && ! is_attachment() ) : ?>
						<a href="<?php echo esc_url(get_permalink($resource->ID)); ?>" tabindex="-1" aria-hidden="true">
							<?php 
							$image_id = get_post_thumbnail_id($resource->ID);
							$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
							if (empty($image_alt))
								$image_alt = esc_html(get_the_title($resource->ID));
							echo get_the_post_thumbnail($resource->ID, "thumbnail", array('alt'=>__('Resource Screenshot: ').$image_alt)); 
							?>
						</a>
					<?php endif; ?>
				    </div>
				<?php } else {
				    $new_image_url = OER_URL . 'images/default-icon-220x180.png';
				    $col = 'col-md-3';
				     if ($_nalrc)
				     	$col = 'col-md-2';
				    echo '<div class="oer-feature-image '.$col.'"><a href="'.esc_url(get_permalink($resource->ID)).'" tabindex="-1"><img src="'.esc_url($new_image_url).'" alt="'.__('Resource Screenshot: ').esc_html(get_the_title($resource->ID)).' image"></a></div>';
				}
				$content_col = 'col-md-9';
				if ($_nalrc)
					$content_col = 'col-md-10';

				$resource_atts = "";
				?>
				</td>
				<td class="resource-title"><?php echo get_the_title($resource->ID); ?></td>   
				<td class="resource-date-created"><?php 
					if (!empty(get_post_meta($resource->ID, 'oer_datecreated')[0]))
						echo esc_html(get_post_meta($resource->ID, 'oer_datecreated')[0]); 
				?></td>   
				<td class="resource-type"><?php echo ucfirst(get_post_meta($resource->ID, 'oer_lrtype')[0]); ?></td>
				<td class="resource-description"><?php 
					$excerpt = get_the_excerpt($resource->ID);
					$excerpt = oer_get_limited_excerpt($excerpt,150);
					echo esc_html(ucfirst($excerpt));
				 ?>
				 </td>   
				 <td class="resource-grade-level">
				 	<?php
				 	$grades = array();
				    $grade_terms = get_the_terms( $resource->ID, 'resource-grade-level' );
				    
				    if (is_array($grade_terms)){
				        foreach($grade_terms as $grade){
				            $grades[] = $grade->name;
				        }
				    }

				    echo oer_grade_levels($grades);
				    ?>
				 </td>
				 <td class="resource-link"><?php echo get_the_permalink($resource->ID); ?></td>
			<?php } ?>
			<?php if ($_nalrc): ?>
				</tbody>
				</table>
			<?php endif; ?>
		    <?php else : ?>
			<article id="post-0" class="post no-results not-found">
			    <header class="entry-header">
				<h1 class="entry-title"><?php _e( 'Nothing Found', OER_SLUG ); ?></h1>
			    </header>

			    <div class="entry-content">
				<p><?php _e( 'Sorry, but there are no resources to display.', OER_SLUG ); ?></p>
			    </div><!-- .entry-content -->
			</article><!-- #post-0 -->
		    <?php endif; ?>

		</div><!-- #content -->
	    </section><!-- #primary -->
	</div>
	<?php
	}
}
get_footer(); ?>