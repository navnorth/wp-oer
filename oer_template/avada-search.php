<?php
/**
 * Template for search results.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php get_header(); ?>
<section id="content" <?php Avada()->layout->add_class( 'content_class' ); ?> <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php if ( have_posts() && 0 != strlen( trim( get_search_query() ) ) ) : ?>
		<?php if ( 'bottom' == Avada()->settings->get( 'search_new_search_position' ) ) : ?>
			<?php get_template_part( 'templates/blog', 'layout' ); ?>
			<div class="fusion-clearfix"></div>
		<?php endif; ?>

		<?php if ( 'hidden' != Avada()->settings->get( 'search_new_search_position' ) ) : ?>
			<div class="search-page-search-form search-page-search-form-<?php echo esc_attr( Avada()->settings->get( 'search_new_search_position' ) ); ?>">
				<?php
				/**
				 * Render the post title
				 */
				echo avada_render_post_title( 0, false, esc_html__( 'Need a new search?', 'Avada' ) ); // WPCS: XSS ok.
				?>
				<p><?php esc_html_e( 'If you didn\'t find what you were looking for, try a new search!', 'Avada' ); ?></p>
				<form class="searchform seach-form" role="search" method="get" action="<?php echo esc_url_raw( home_url( '/' ) ); ?>">
					<div class="search-table">
						<div class="search-field">
							<label class="screen-reader-text" for="searchform"><?php esc_attr_e( 'Search for:', 'Avada' ); ?></label>
							<input id="searchform" type="text" value="" name="s" class="s" placeholder="<?php esc_html_e( 'Search ...', 'Avada' ); ?>" required aria-required="true" aria-label="<?php esc_html_e( 'Search ...', 'Avada' ); ?>"/>
						</div>
						<div class="search-button">
							<input type="submit" class="searchsubmit" value="&#xf002;" alt="<?php esc_attr_e( 'Search', 'Avada' ); ?>" />
						</div>
					</div>
				</form>
			</div>
		<?php endif; ?>

		<?php if ( 'top' == Avada()->settings->get( 'search_new_search_position' ) || 'hidden' == Avada()->settings->get( 'search_new_search_position' ) ) : ?>
			<?php
                            load_template(OER_PATH.'oer_template/search-layout.php');
                        ?>
		<?php endif; ?>

	<?php else : ?>

		<div class="post-content">

			<?php Avada()->template->title_template( esc_html__( 'Couldn\'t find what you\'re looking for!', 'Avada' ) ); ?>
			<div class="error-page">
				<div class="fusion-columns fusion-columns-3">
					<div class="fusion-column col-lg-4 col-md-4 col-sm-4">
						<h1 class="oops"><?php esc_html_e( 'Oops!', 'Avada' ); ?></h1>
					</div>
					<div class="fusion-column col-lg-4 col-md-4 col-sm-4 useful-links">
						<h3><?php esc_html_e( 'Helpful Links:', 'Avada' ); ?></h3>
						<?php $circle_class = ( Avada()->settings->get( 'checklist_circle' ) ) ? 'circle-yes' : 'circle-no'; ?>
						<?php wp_nav_menu( array(
							'theme_location' => '404_pages',
							'depth'          => 1,
							'container'      => false,
							'menu_class'     => 'error-menu list-icon list-icon-arrow ' . $circle_class,
							'echo'           => 1,
							'item_spacing'   => 'discard',
						) ); ?>
					</div>
					<div class="fusion-column col-lg-4 col-md-4 col-sm-4">
						<h3><?php esc_html_e( 'Try again', 'Avada' ); ?></h3>
						<p><?php esc_html_e( 'If you want to rephrase your query, here is your chance:', 'Avada' ); ?></p>
						<?php echo get_search_form( false ); ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
