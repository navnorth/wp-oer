<?php
/**
 * Blog-layout template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      1.0.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) { exit( 'Direct script access denied.' ); }

global $wp_query;

// Set the correct post container layout classes.
$blog_layout = avada_get_blog_layout();
$pagination_type = Avada()->settings->get( 'blog_pagination_type' );
$post_class  = 'fusion-post-' . $blog_layout;

// Masonry needs additional grid class.
if ( 'masonry' === $blog_layout ) {
	$post_class .= ' fusion-post-grid';
}

$container_class = 'fusion-posts-container ';
$wrapper_class = 'fusion-blog-layout-' . $blog_layout . '-wrapper ';

if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) {
	$container_class .= 'fusion-blog-layout-grid fusion-blog-layout-grid-' . Avada()->settings->get( 'blog_grid_columns' ) . ' isotope ';

	if ( 'masonry' === $blog_layout ) {
		$container_class .= 'fusion-blog-layout-' . $blog_layout . ' ';
	}
} else if ( 'timeline' !== $blog_layout ) {
	$container_class .= 'fusion-blog-layout-' . $blog_layout . ' ';
}

if ( ! Avada()->settings->get( 'post_meta' ) ) {
	$container_class .= 'fusion-no-meta-info ';
}

// Set class for scrolling type.
if ( 'Infinite Scroll' === $pagination_type ) {
	$container_class .= 'fusion-posts-container-infinite ';
	$wrapper_class .= 'fusion-blog-infinite ';
} else if ( 'load_more_button' === $pagination_type ) {
	$container_class .= 'fusion-posts-container-infinite fusion-posts-container-load-more ';
} else {
	$container_class .= 'fusion-blog-pagination ';
}

if ( ! Avada()->settings->get( 'featured_images' ) ) {
	$container_class .= 'fusion-blog-no-images ';
}

// Add class if rollover is enabled.
if ( Avada()->settings->get( 'image_rollover' ) && Avada()->settings->get( 'featured_images' ) ) {
	$container_class .= ' fusion-blog-rollover';
}

$number_of_pages = $wp_query->max_num_pages;
if ( is_search() && Avada()->settings->get( 'search_results_per_page' ) ) {
	$number_of_pages = ceil( $wp_query->found_posts / Avada()->settings->get( 'search_results_per_page' ) );
}
?>
<div id="posts-container" class="fusion-blog-archive <?php echo esc_attr( $wrapper_class ); ?>fusion-clearfix">
	<div class="<?php echo esc_attr( $container_class ); ?>" data-pages="<?php echo esc_attr($number_of_pages); ?>">
		<?php if ( 'timeline' === $blog_layout ) : ?>
			<?php // Add the timeline icon. ?>
			<div class="fusion-timeline-icon"><i class="fusion-icon-bubbles"></i></div>
			<div class="fusion-blog-layout-timeline fusion-clearfix">

			<?php
			// Initialize the time stamps for timeline month/year check.
			$post_count = 1;
			$prev_post_timestamp = null;
			$prev_post_month = null;
			$prev_post_year = null;
			$first_timeline_loop = false;
			?>

			<?php // Add the container that holds the actual timeline line. ?>
			<div class="fusion-timeline-line"></div>
		<?php endif; ?>

		<?php if ( 'masonry' === $blog_layout ) : ?>
			<article class="fusion-post-grid fusion-post-masonry post fusion-grid-sizer"></article>
		<?php endif; ?>

		<?php // Start the main loop. ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			// Set the time stamps for timeline month/year check.
			$alignment_class = '';
			if ( 'timeline' === $blog_layout ) {
				$post_timestamp = get_the_time( 'U' );
				$post_month     = date( 'n', $post_timestamp );
				$post_year      = get_the_date( 'Y' );
				$current_date   = get_the_date( 'Y-n' );

				// Set the correct column class for every post.
				if ( $post_count % 2 ) {
					$alignment_class = 'fusion-left-column';
				} else {
					$alignment_class = 'fusion-right-column';
				}

				// Set the timeline month label.
				if ( $prev_post_month != $post_month || $prev_post_year != $post_year ) {

					if ( $post_count > 1 ) {
						echo '</div>';
					}
					echo '<h3 class="fusion-timeline-date">' . get_the_date( Avada()->settings->get( 'timeline_date_format' ) ) . '</h3>';
					echo '<div class="fusion-collapse-month">';
				}
			}

			// Set the has-post-thumbnail if a video is used. This is needed if no featured image is present.
			$thumb_class = '';
			if ( get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
				$thumb_class = ' has-post-thumbnail';
			}

			// Masonry layout, get the element orientation class.
			$element_orientation_class = '';
			if ( 'masonry' === $blog_layout ) {
				$masonry_cloumns = Avada()->settings->get( 'blog_grid_columns' );
				$masonry_columns_spacing = Avada()->settings->get( 'blog_grid_column_spacing' );
				$responsive_images_columns = $masonry_cloumns;
				$masonry_attributes = array();
				$element_base_padding = 0.8;

				// Set image or placeholder and correct corresponding styling.
				if ( has_post_thumbnail() ) {
					$post_thumbnail_attachment = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					$masonry_attribute_style = 'background-image:url(' . $post_thumbnail_attachment[0] . ');';
				} else {
					$post_thumbnail_attachment = array();
					$masonry_attribute_style = 'background-color:#f6f6f6;';
				}

				// Get the correct image orientation class.
				$element_orientation_class = Avada()->images->get_element_orientation_class( $post_thumbnail_attachment );
				$element_base_padding  = Avada()->images->get_element_base_padding( $element_orientation_class );

				$masonry_column_offset = ' - ' . ( (int) $masonry_columns_spacing / 2 ) . 'px';
				if ( 'fusion-element-portrait' === $element_orientation_class ) {
					$masonry_column_offset = '';
				}

				$masonry_column_spacing = ( (int) $masonry_columns_spacing ) . 'px';

				if ( class_exists( 'Fusion_Sanitize' ) && class_exists( 'Fusion_Color' ) &&
					'transparent' !== Fusion_Sanitize::color( Avada()->settings->get( 'timeline_color' ) ) &&
					'0' != Fusion_Color::new_color( Avada()->settings->get( 'timeline_color' ) )->alpha ) {

					$masonry_column_offset = ' - ' . ( (int) $masonry_columns_spacing / 2 ) . 'px';
					if ( 'fusion-element-portrait' === $element_orientation_class ) {
						$masonry_column_offset = ' + 4px';
					}

					$masonry_column_spacing = ( (int) $masonry_columns_spacing - 2 ) . 'px';
					if ( 'fusion-element-landscape' === $element_orientation_class ) {
						$masonry_column_spacing = ( (int) $masonry_columns_spacing - 6 ) . 'px';
					}
				}

				// Calculate the correct size of the image wrapper container, based on orientation and column spacing.
				$masonry_attribute_style .= 'padding-top:calc((100% + ' . $masonry_column_spacing . ') * ' . $element_base_padding . $masonry_column_offset . ');';

				// Check if we have a landscape image, then it has to stretch over 2 cols.
				if ( 'fusion-element-landscape' === $element_orientation_class ) {
					$responsive_images_columns = $masonry_cloumns / 2;
				}

				// Set the masonry attributes to use them in the first featured image function.
				$masonry_attributes = array(
					'class' => 'fusion-masonry-element-container',
					'style' => $masonry_attribute_style,
				);

				// Get the post image.
				Avada()->images->set_grid_image_meta( array(
					'layout' => 'portfolio_full',
					'columns' => $responsive_images_columns,
					'gutter_width' => $masonry_columns_spacing,
				));

				$permalink = get_permalink( $post->ID );

				$image = fusion_render_first_featured_image_markup( $post->ID, 'full', $permalink, false, false, false, 'default', 'default', '', '', 'yes', false, $masonry_attributes );

				Avada()->images->set_grid_image_meta( array() );
			} // End if().

			$post_classes = array(
				$post_class,
				$alignment_class,
				$thumb_class,
				$element_orientation_class,
				'post',
				'fusion-clearfix',
			);
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
				<?php if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) : ?>
					<?php // Add an additional wrapper for grid layout border. ?>
					<div class="fusion-post-wrapper">
				<?php endif; ?>

				<?php if ( ( ( is_search() && Avada()->settings->get( 'search_featured_images' ) ) || ( ! is_search() && Avada()->settings->get( 'featured_images' ) ) ) && 'large-alternate' === $blog_layout ) : ?>
					<?php
					// Get featured images for large-alternate layout.
					get_template_part( 'new-slideshow' );

					?>
				<?php endif; ?>

				<?php if ( 'large-alternate' === $blog_layout || 'medium-alternate' === $blog_layout ) : ?>
					<?php // Get the post date and format box for alternate layouts. ?>
					<div class="fusion-date-and-formats">
						<?php
						/**
						 * The avada_blog_post_date_adn_format hook.
						 *
						 * @hooked avada_render_blog_post_date - 10 (outputs the HTML for the date box).
						 * @hooked avada_render_blog_post_format - 15 (outputs the HTML for the post format box).
						 */
						do_action( 'avada_blog_post_date_and_format' );
						?>
					</div>
				<?php endif; ?>

				<?php if ( ( ( is_search() && Avada()->settings->get( 'search_featured_images' ) ) || ( ! is_search() && Avada()->settings->get( 'featured_images' ) ) ) && 'large-alternate' !== $blog_layout ) : ?>
					<?php
					if ( 'masonry' === $blog_layout ) {
						echo wp_kses_post($image); // WPCS: XSS ok.
					} else {
						// Get featured images for all but large-alternate layout.
						get_template_part( 'new-slideshow' );
					}
					?>
				<?php endif; ?>

				<?php if ( 'grid' === $blog_layout || 'masonry' === $blog_layout || 'timeline' === $blog_layout ) : ?>
					<?php // The post-content-wrapper is only needed for grid and timeline. ?>
					<div class="fusion-post-content-wrapper">
				<?php endif; ?>

				<?php if ( 'timeline' === $blog_layout ) : ?>
					<?php // Add the circles for timeline layout. ?>
					<div class="fusion-timeline-circle"></div>
					<div class="fusion-timeline-arrow"></div>
				<?php endif; ?>

				<div class="fusion-post-content post-content">
					<?php echo avada_render_post_title( $post->ID ); // WPCS: XSS ok. ?>
					<?php
					$subjects = array();
					$grades = array();
					//Display Meta of Resource
					if ($post->post_type=="resource"){
						$ID = $post->ID;
						$url = get_post_meta($ID, "oer_resourceurl", true);
						$url_domain = oer_getDomainFromUrl($url);
						$grades =  trim(get_post_meta($ID, "oer_grade", true),",");
						?>
						<h5 class="fusion-post-meta">
						<?php
						if ($grades) {
							$grades = explode(",",$grades);
							if(is_array($grades) && !empty($grades) && array_filter($grades)){
								$grade_label = "Grade: ";
								if (count($grades)>1)
									$grade_label = "Grades: ";
								
								echo "<span class='fusion-post-meta-box fusion-post-meta-grades'><strong>".esc_html($grade_label)."</strong>";
								echo oer_grade_levels($grades);
								echo "</span>";
							}
						}
						if (oer_isExternalUrl($url)) {
							?>
							<span class="fusion-post-meta-box fusion-post-meta-domain"><strong>Domain: </strong><a href="<?php echo esc_url(get_post_meta($ID, "oer_resourceurl", true)); ?>" target="_blank" >
							<?php echo esc_html($url_domain); ?>
							</a></span>
							<?php
						}
						?>
						</h5>
						<?php
						$subjects = oer_get_subject_areas($ID);
					}
					?>
					<?php // Render post meta for grid and timeline layouts. ?>
					<?php if ( 'grid' === $blog_layout || 'masonry' === $blog_layout || 'timeline' === $blog_layout ) : ?>
						<?php echo avada_render_post_metadata( 'grid_timeline' ); // WPCS: XSS ok. ?>

						<?php if ( 'masonry' !== $blog_layout && ( Avada()->settings->get( 'post_meta' ) && ( Avada()->settings->get( 'post_meta_author' ) || Avada()->settings->get( 'post_meta_date' ) || Avada()->settings->get( 'post_meta_cats' ) || Avada()->settings->get( 'post_meta_tags' ) || Avada()->settings->get( 'post_meta_comments' ) || Avada()->settings->get( 'post_meta_read' ) ) ) && 0 < Avada()->settings->get( 'excerpt_length_blog' ) ) : ?>
							<?php
							$separator_styles_array = explode( '|', Avada()->settings->get( 'separator_style_type' ) );
							$separator_styles = '';

							foreach ( $separator_styles_array as $separator_style ) {
								$separator_styles .= ' sep-' . $separator_style;
							}
							?>
							<div class="fusion-content-sep<?php echo esc_attr( $separator_styles ); ?>"></div>
						<?php endif; ?>

					<?php elseif ( 'large-alternate' === $blog_layout || 'medium-alternate' === $blog_layout ) : ?>
						<?php // Render post meta for alternate layouts. ?>
						<?php echo avada_render_post_metadata( 'alternate' ); // WPCS: XSS ok. ?>
					<?php endif; ?>
					<?php if (strlen(trim($post->post_content))>0): ?>
					<div class="fusion-post-content-container">
						<?php
						/**
						 * The avada_blog_post_content hook.
						 *
						 * @hooked avada_render_blog_post_content - 10 (outputs the post content wrapped with a container).
						 */
						do_action( 'avada_blog_post_content' );
						?>
					</div>
					<?php endif; ?>
					<?php
					// Display subject areas
					if ($subjects) {
						$oer_subjects = array_unique($subjects, SORT_REGULAR);
			
						if(!empty($oer_subjects))
						{
							?>
							<div class="fusion-subject-areas tagcloud">
							<?php
							foreach($oer_subjects as $subject)
							{
								echo '<span><a href="'.esc_url(site_url().'/'.$subject->taxonomy.'/'.$subject->slug).'" class="button">'.ucwords(esc_html($subject->name)).'</a></span>';
							}
							?>
							</div>
							<?php
						}
					}
					?>
				</div>

				<?php if ( 'medium' === $blog_layout || 'medium-alternate' === $blog_layout ) : ?>
					<div class="fusion-clearfix"></div>
				<?php endif; ?>

				<?php if ( ( Avada()->settings->get( 'post_meta' ) && ( Avada()->settings->get( 'post_meta_author' ) || Avada()->settings->get( 'post_meta_date' ) || Avada()->settings->get( 'post_meta_cats' ) || Avada()->settings->get( 'post_meta_tags' ) || Avada()->settings->get( 'post_meta_comments' ) || Avada()->settings->get( 'post_meta_read' ) ) ) ) : ?>
					<?php // Render post meta data according to layout. ?>
					<div class="fusion-meta-info">
						<?php if ( 'grid' === $blog_layout || 'masonry' === $blog_layout || 'timeline' === $blog_layout ) : ?>
							<?php // Render read more for grid/timeline layouts. ?>
							<div class="fusion-alignleft">
								<?php if ( Avada()->settings->get( 'post_meta_read' ) ) : ?>
									<?php $link_target = ( 'yes' === fusion_get_page_option( 'link_icon_target', $post->ID ) || 'yes' === fusion_get_page_option( 'post_links_target', $post->ID ) ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
									<a href="<?php echo esc_url_raw( get_permalink() ); ?>" class="fusion-read-more"<?php echo esc_attr($link_target); // WPCS: XSS ok. ?>>
										<?php echo esc_textarea( apply_filters( 'avada_blog_read_more_link', esc_attr__( 'Read More', 'Avada' ) ) ); ?>
									</a>
								<?php endif; ?>
							</div>

							<?php // Render comments for grid/timeline layouts. ?>
							<div class="fusion-alignright">
								<?php if ( Avada()->settings->get( 'post_meta_comments' ) ) : ?>
									<?php if ( ! post_password_required( $post->ID ) ) : ?>
										<?php comments_popup_link( '<i class="fusion-icon-bubbles"></i>&nbsp;0', '<i class="fusion-icon-bubbles"></i>&nbsp;1', '<i class="fusion-icon-bubbles"></i>&nbsp;%' ); ?>
									<?php else : ?>
										<i class="fusion-icon-bubbles"></i>&nbsp;<?php esc_attr_e( 'Protected', 'Avada' ); ?>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						<?php else : ?>
							<?php // Render all meta data for medium and large layouts. ?>
							<?php if ( 'large' === $blog_layout || 'medium' === $blog_layout ) : ?>
								<?php echo avada_render_post_metadata( 'standard' ); // WPCS: XSS ok. ?>
							<?php endif; ?>

							<?php // Render read more for medium/large and medium/large alternate layouts. ?>
							<div class="fusion-alignright">
								<?php if ( Avada()->settings->get( 'post_meta_read' ) ) : ?>
									<?php $link_target = ( 'yes' === fusion_get_page_option( 'link_icon_target', $post->ID ) || 'yes' === fusion_get_page_option( 'post_links_target', $post->ID ) ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
									<a href="<?php echo esc_url_raw( get_permalink() ); ?>" class="fusion-read-more"<?php echo esc_attr($link_target); // WPCS: XSS ok. ?>>
										<?php echo esc_textarea( apply_filters( 'avada_read_more_name', esc_attr__( 'Read More', 'Avada' ) ) ); ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php elseif ( ! Avada()->settings->get( 'post_meta' ) ) : ?>
					<?php echo fusion_render_rich_snippets_for_pages(); // WPCS: XSS ok. ?>
				<?php endif; ?>

				<?php if ( 'grid' === $blog_layout || 'masonry' === $blog_layout || 'timeline' === $blog_layout ) : ?>
					</div>
				<?php endif; ?>

				<?php if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) : ?>
					</div>
				<?php endif; ?>
			</article>

			<?php
			// Adjust the timestamp settings for next loop.
			if ( 'timeline' === $blog_layout ) {
				$prev_post_timestamp = $post_timestamp;
				$prev_post_month     = $post_month;
				$prev_post_year      = $post_year;
				$post_count++;
			}
			?>

		<?php endwhile; ?>

		<?php if ( 'timeline' === $blog_layout && 1 < $post_count ) : ?>
			</div>
		<?php endif; ?>

	</div>

	<?php // If infinite scroll with "load more" button is used. ?>
	<?php if ( 'load_more_button' === $pagination_type && 1 < $number_of_pages ) : ?>
		<div class="fusion-load-more-button fusion-blog-button fusion-clearfix">
			<?php echo esc_textarea( apply_filters( 'avada_load_more_posts_name', esc_attr__( 'Load More Posts', 'Avada' ) ) ); ?>
		</div>
	<?php endif; ?>
	<?php if ( 'timeline' === $blog_layout ) : ?>
	</div>
	<?php endif; ?>
<?php // Get the pagination. ?>
<?php fusion_pagination( '', 2 ); ?>
</div>
<?php

wp_reset_postdata();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
