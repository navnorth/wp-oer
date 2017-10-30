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

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php
				echo twentyseventeen_time_link();
				twentyseventeen_edit_link();
				?>
			</div><!-- .entry-meta -->
		<?php elseif ( 'page' === get_post_type() && get_edit_post_link() ) : ?>
			<div class="entry-meta">
				<?php twentyseventeen_edit_link(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>

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
						
						echo "<span class='post-meta-box post-meta-grades'><strong>".$grade_label."</strong>";
						
						sort($grades);

						for($x=0; $x < count($grades); $x++)
						{
						  $grades[$x];
						}
						$fltrarr = array_filter($grades, 'strlen');
						$flag = array();
						$elmnt = $fltrarr[min(array_keys($fltrarr))];
						for($i =0; $i < count($fltrarr); $i++)
						{
							if($elmnt == $fltrarr[$i] || "k" == strtolower($fltrarr[$i]))
							{
								$flag[] = 1;
							}
							else
							{
								$flag[] = 0;
							}
							$elmnt++;
						}

						if(in_array('0',$flag))
						{
							echo implode(",",array_unique($fltrarr));
						}
						else
						{
							$arr_flt = array_keys($fltrarr);
							$end_filter = end($arr_flt);
							if (count($fltrarr)>1) {
								if (strtolower($fltrarr[$end_filter])=="k") {
									$last_index = count($fltrarr)-2;
									echo $fltrarr[$end_filter]."-".$fltrarr[$last_index];
								}
								else
									echo $fltrarr[0]."-".$fltrarr[$end_filter];
							}
							else
								echo $fltrarr[0];
						}
						echo "</span>";
					}
				}
				if ($url_domain) {
					?>
					<span class="post-meta-box post-meta-domain"><strong>Domain: </strong><a href="<?php echo esc_url(get_post_meta($ID, "oer_resourceurl", true)); ?>" target="_blank" >
					<?php echo $url_domain; ?>
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
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
