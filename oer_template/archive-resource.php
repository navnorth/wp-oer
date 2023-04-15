<?php
/*
 * Template Name: Default Archive Resource Template
 */
global $_products;

add_filter( 'body_class','oer_archive_body_classes' );
function oer_archive_body_classes( $classes ) {
 
    $classes[] = 'resource-archive';
    return $classes;
     
}

$allowed_tags = oer_allowed_html();

wp_register_script(	"resources-script",	OER_URL."js/resources.js" );
wp_enqueue_script( "resources-script" );
wp_localize_script( "resources-script" , "resources", array("ajaxurl" => admin_url( 'admin-ajax.php' ), "plugin_url" => OER_URL));
wp_enqueue_style( "resources-style", OER_URL."css/resources.css" );

get_header();

$filter_enabled = empty(get_option('oer_enable_search_filters'))?false:true;
?>
<div class="oer-cntnr">
    <section id="primary" class="site-content">
	<div id="content" role="main">

	    <?php if ( have_posts() ) : ?>
				<header class="archive-header">
				    <h1 class="archive-title oer-resources-title"><?php 
				    	if (get_option('oer_resources_page_title')): 
				    		echo esc_html(get_option('oer_resources_page_title'));
				    	else : 
				    		_e( 'Resource Collection', OER_SLUG ); 
				    	endif; ?></h1>
				    <div class="nalrc-resources-description"><?php 
				    	if (get_option('oer_resources_content')):
				    		echo do_shortcode(wpautop(wp_kses(get_option('oer_resources_content'), $allowed_tags)));
				    	endif;
				    ?></div>
				</header><!-- .archive-header -->
			<?php /** Search Filter **/ 
			if ($filter_enabled) : ?>
				<div class="resource-search-filters">
					<div class="row filter-title">
						<div class="col-md-12">
							<h2><?php _e('Search Resource Collection: ', OER_SLUG); ?></h2>
						</div>
					</div>
					<div class="row">
						<div class="resource-search-keyword col-md-5">
							 <i class="fa fa-search icon"></i>
							<input type="text" id="keyword" placeholder="<?php _e('Search by keyword or phrase',OER_SLUG); ?>" aria-label="<?php _e('Search resources by keyword or phrase',OER_SLUG); ?>" />
						</div>
						<div class="resource-search-product col-md-3">
							<div class="resource-select-wrapper">
								<select id="product" class="resource-product-filter resource-select-filter selectpicker" multiple title="Resource Type" aria-label="Resource Type">
									<?php foreach ($_products as $product): ?>
										<option value="<?php echo esc_html($product['value']); ?>"><?php echo esc_html($product['label']); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="resource-search-grade-level col-md-2">
							<?php
							$grades = get_terms(['taxonomy'=>'resource-grade-level','hide_empty'=>false]);
							?>
							<div class="resource-select-wrapper">
								<select id="gradeLevel" class="resource-grade-level-filter resource-select-filter selectpicker" multiple title="Grade Level" aria-label="Grade Level">
									<?php foreach($grades as $grade): ?>
										<option value="<?php echo esc_html($grade->term_id); ?>"><?php echo esc_html($grade->name); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="resource-search-button-wrapper col-md-2">
							<button class="resource-search-button"><?php _e('Search >', OER_SLUG); ?></button>
						</div>
					</div>
				</div>
		<?php endif;
		/* Start the Loop */ ?>

		<article class="oer_resource_posts">

		<?php while ( have_posts() ) :  the_post(); ?>

		    <div class="oer_blgpst resource-blogpost">
				    
			<?php if ( has_post_thumbnail() ) {?>
			    <div class="oer-feature-image col-md-2">
				<?php if ( ! post_password_required() && ! is_attachment() ) : ?>
					<a href="<?php echo esc_url(get_permalink($post->ID)); ?>" tabindex="-1" aria-hidden="true">
						<?php 
						$image_id = get_post_thumbnail_id();
						$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
						if (empty($image_alt))
							$image_alt = esc_html(get_the_title($post->ID));
						the_post_thumbnail("thumbnail", array('alt'=>$image_alt)); 
						?>
					</a>
				<?php endif; ?>
			    </div>
			<?php } else {
			    $new_image_url = OER_URL . 'images/default-icon-220x180.png';
			    $col = 'col-md-2';
			    echo '<div class="oer-feature-image '.$col.'"><a href="'.esc_url(get_permalink($post->ID)).'" tabindex="-1"><img src="'.esc_url($new_image_url).'" alt="'.esc_html(get_the_title($post->ID)).' image"></a></div>';
			}
			$content_col = 'col-md-10';

			$resource_atts = "";
			?>
				    
			<div class="rght-sd-cntnr-blg <?php echo $content_col; ?>">
			    <h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
			    <div class="small">
			    	<?php if (!empty(get_post_meta($post->ID, 'oer_datecreated')[0])): 
			    		$resource_atts .= '<span>'.esc_html(get_post_meta($post->ID, 'oer_datecreated')[0]).'</span>';
			    	endif; ?>
			    	<?php if (!empty(get_post_meta($post->ID,'oer_lrtype')[0])): 
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
		<div class="nalrc-pagination-nav">
			<div class="alignleft"><?php previous_posts_link( '&laquo; Previous' ); ?></div>
			<div class="alignright"><?php next_posts_link( 'Next &raquo;', '' ); ?></div>
		</div>
		</article>
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