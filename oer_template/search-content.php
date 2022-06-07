<?php
/**
 * Template part for displaying posts with excerpts
 *
 * Used in Search Results and for Recent Posts in Front Page panels.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */
// Start the loop.
while ( have_posts() ) : the_post();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php if ( is_front_page() && ! is_home() ) {

			// The excerpt is being displayed within a front page section, so it's a lower hierarchy than h2.
			the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
		} else {
			the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		} ?>
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
				<h5 class="post-meta">
				<?php
				if ($grades) {
					$grades = explode(",",$grades);
					if(is_array($grades) && !empty($grades) && array_filter($grades)){
						$grade_label = "Grade: ";
						if (count($grades)>1)
							$grade_label = "Grades: ";
						
						echo "<span class='post-meta-box post-meta-grades'><strong>".esc_html($grade_label)."</strong>";
						echo oer_grade_levels($grades);
						echo "</span>";
					}
				}
				if (oer_isExternalUrl($url)) {
					?>
					<span class="post-meta-box post-meta-domain"><strong>Domain: </strong><a href="<?php echo esc_url(get_post_meta($ID, "oer_resourceurl", true)); ?>" target="_blank" >
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
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
		<?php
		// Display subject areas
		if ($subjects) {
			$oer_subjects = array_unique($subjects, SORT_REGULAR);

			if(!empty($oer_subjects))
			{
				?>
				<div class="tagcloud">
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
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
<?php
// End the loop.
endwhile;
?>
