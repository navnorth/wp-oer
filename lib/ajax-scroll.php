<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once($parse_uri[0].'/wp-load.php');
global $wpdb;
if($_REQUEST["task"] == 'dataScroll')
{
	$args = array(
					'post_type' => 'resource',
					'posts_per_page' => -1,
					'tax_query' => array(array('taxonomy' => 'resource-category','terms' => array($_REQUEST["termid"])))
				);
	$posts = get_posts($args);
	$timthumb = get_template_directory_uri().'/lib/timthumb.php';
	foreach($posts as $post)
	{
		$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$title =  $post->post_title;
		$content =  $post->post_content;
		$content = substr($content, 0, 180);
	?>
		<div class="snglrsrc">
			 <?php if(!empty($image)){?>
			<div class="snglimglft"><img src="<?php echo $timthumb.'?src='.$image.'&amp;w=80&amp;h=60&amp;zc=0';?>" alt="<?php echo $title;?>"></div>
			<?php }
			else
			{
				$dfltimg = site_url().'/wp-content/plugins/wp-oer/images/default-icon.png';
				echo '<a href="'.get_permalink($post->ID).'"><div class="snglimglft"><img src="'.$timthumb.'?src='.$dfltimg.'&amp;w=80&amp;h=60&amp;zc=0" alt="'.$title.'"></div></a>';
			}
			?>
			<div class="snglttldscrght <?php if(empty($image)){ echo 'snglttldscrghtfull';}?>">
				<div class="ttl"><a href="<?php echo get_permalink($post->ID);?>"><?php echo $title;?></a></div>
				<div class="desc"><?php echo $content; ?></div>
			</div>
		</div>
	<?php
	}
}
?>
