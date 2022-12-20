<?php
/*
 * Template Name: Default Archive Resource Template
 */
global $_nalrc, $_nalrc_products;
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

get_header();
?>
<div class="oer-cntnr">
    <section id="primary" class="site-content<?php if ($_nalrc) _e(' nalrc-resources-content',OER_SLUG); ?>">
	<div id="content" role="main">

	    <?php if ( have_posts() ) : ?>
	    	<?php if ($_nalrc): ?>
	    		<header class="archive-header nalrc-resources-header">
				    <h1 class="archive-title nalrc-resources-title"><?php _e( 'Native American Language Resource Collection', OER_SLUG ); ?></h1>
				    <p class="nalrc-resources-description"><?php _e('Explore a collection of high-quality multimedia instructional resources, informed by research, for use by Native American language stakeholders. These resources have been reviewed by subject matter experts and recommended and approved by the U.S. Department of Education, Office of Indian Education.', OER_SLUG); ?></p>
				</header><!-- .archive-header -->
	    	<?php else: ?>
				<header class="archive-header">
				    <h1 class="archive-title"><?php printf( __( 'Archives: %s', OER_SLUG ), '<span>' .post_type_archive_title('', false).'</span>' );?></h1>
				</header><!-- .archive-header -->
			<?php endif; ?>
			<?php /** NALRC Search Filter **/
			if ($_nalrc): ?>
				<div class="nalrc-search-filters">
					<div class="row filter-title">
						<div class="col-md-12">
							<h4><?php _e('Search the collection by category, keyword, date, and more', OER_SLUG); ?></h4>
						</div>
					</div>
					<div class="row">
						<div class="nalrc-search-keyword col-md-5">
							 <i class="fa fa-search icon"></i>
							<input type="text" id="keyword" placeholder="<?php _e('Search by keyword or phrase',OER_SLUG); ?>" />
						</div>
						<div class="nalrc-search-topic col-md-3">
							<?php
							$topics = get_terms(['taxonomy'=>'resource-subject-area','hide_empty'=>false]);
							?>
							<select id="topic" class="nalrc-topic-filter nalrc-select-filter">
								<option value=""><?php _e('Topic Area', OER_SLUG); ?></option>
								<?php foreach($topics as $topic): ?>
									<option value="<?php echo esc_html($topic->term_id); ?>"><?php echo esc_html($topic->name); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="nalrc-search-product col-md-3">
							<select id="product" class="nalrc-product-filter nalrc-select-filter">
								<option value=""><?php _e('Product Type',OER_SLUG); ?></option>
								<?php foreach ($_nalrc_products as $product): ?>
									<option value="<?php echo esc_html($product['value']); ?>"><?php echo esc_html($product['label']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="nalrc-search-year col-md-1">
							<select id="year" class="nalrc-year-filter nalrc-select-filter">
								<option value="">Year</option>
								<?php $years = oer_get_created_year(); 
								foreach($years as $year):
								?>
								<option value="<?php echo esc_html($year); ?>"><?php echo esc_html($year); ?></option>
								<?php endforeach; ?>
							</select>
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
				<?php if ( ! post_password_required() && ! is_attachment() ) :
				    the_post_thumbnail("thumbnail");
				endif; ?>
			    </div>
			<?php } else {
			    $new_image_url = OER_URL . 'images/default-icon-220x180.png';
			    $col = 'col-md-3';
			     if ($_nalrc)
			     	$col = 'col-md-2';
			    echo '<div class="oer-feature-image '.$col.'"><a href="'.esc_url(get_permalink($post->ID)).'"><img src="'.esc_url($new_image_url).'"></a></div>';
			}
			$content_col = 'col-md-9';
			if ($_nalrc)
				$content_col = 'col-md-10';
			?>
				    
			<div class="rght-sd-cntnr-blg <?php echo $content_col; ?>">
			    <h4><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
			    <div class="small">
			    	<span><?php the_time('F jS, Y'); ?> </span>
			    	<?php if ($_nalrc && !empty(get_post_meta($post->ID,'oer_lrtype')[0])): ?>
			    	| <span><?php echo ucfirst(get_post_meta($post->ID, 'oer_lrtype')[0]); ?></span>
			    	<?php endif; ?>
			    </div>
					    
			    <div class="oer-post-content">
					<?php 
					$excerpt = get_the_excerpt($post->ID);
					$excerpt = oer_get_limited_excerpt($excerpt,100);
					echo esc_html(ucfirst($excerpt));
					 ?>
			    </div>
			    <?php
			    $grades = array();
			    $grade_terms = get_the_terms( $post->ID, 'resource-grade-level' );
			    
			    if (is_array($grade_terms)){
			        foreach($grade_terms as $grade){
			            $grades[] = $grade->slug;
			        }
			    }
			    if (!empty($grades)):
			    ?>
			    <div class="oer-intended-audience">
			    	<span class="label"><?php _e("For: ", OER_SLUG); ?></span><span class="value"><?php echo oer_grade_levels($grades); ?></span>
			    </div>
				<?php endif; ?>
			</div>
		    </div>
		<?php endwhile; ?>

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
<?php get_footer(); ?>