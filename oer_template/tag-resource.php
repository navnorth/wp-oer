<?php
/*
 * Template Name: Default Tag Page Template
 */

get_header();
?>
<div class="oer-cntnr">
	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php printf( __( 'Tag Archives: %s', OER_SLUG ), '<span>' .single_tag_title( '', false ).'</span>' );?></h1>

			<?php if ( tag_description() ) : // Show an optional tag description ?>
				<div class="archive-meta"><?php echo tag_description(); ?></div>
			<?php endif; ?>
			</header><!-- .archive-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<div class="oer_blgpst">
					
                    <?php if ( has_post_thumbnail() ) {?>
						<div class="oer-feature-image">
						<?php if ( ! post_password_required() && ! is_attachment() ) :
							the_post_thumbnail("thumbnail");
						endif; ?>
						</div>
					<?php }?>
					
                    <div class="rght-sd-cntnr-blg">
                        <h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
            
                        <div class="small"><span><?php the_time('F jS, Y'); ?> </span></div>
                                                
                        <div class="oer-post-content">
                            <?php the_excerpt(); ?>
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
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', OER_SLUG ); ?></p>
				</div><!-- .entry-content -->
			</article><!-- #post-0 -->
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->
</div>
<?php get_footer(); ?>