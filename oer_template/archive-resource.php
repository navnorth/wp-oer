<?php
/*
 * Template Name: Default Archive Resource Template
 */
global $_nalrc;
add_filter( 'body_class','oer_archive_body_classes' );
function oer_archive_body_classes( $classes ) {
 
    $classes[] = 'resource-archive';
    return $classes;
     
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
								<input type="text" placeholder="<?php _e('Search by keyword or phrase',OER_SLUG); ?>" />
						</div>
						<div class="nalrc-search-topic col-md-3">
							<?php
							$topics = get_terms(['taxonomy'=>'resource-subject-area','hide_empty'=>false]);
							?>
							<select id="nalrc-topic-filter nalrc-select-filter">
								<option value="">Topic Area</option>
								<?php foreach($topics as $topic): ?>
									<option value="<?php echo esc_html($topic->term_id); ?>"><?php echo esc_html($topic->name); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="nalrc-search-product col-md-3">
							<select id="nalrc-product-filter nalrc-select-filter">
								<option value="">Product Type</option>
							</select>
						</div>
						<div class="nalrc-search-year col-md-1">
							<select id="nalrc-year-filter nalrc-select-filter">
								<option value="">Year</option>
							</select>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php /* Start the Loop */ ?>
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
				<?php ucfirst(the_excerpt()); ?>
			    </div>
			</div>
		    </div>
		<?php endwhile; ?>

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