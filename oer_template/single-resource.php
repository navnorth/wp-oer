<?php
/**
 * The Template for displaying all single resource
 */
/** Add default stylesheet for Resource page **/
wp_register_style( "resource-styles", OER_URL . "css/resource-style.css" );
wp_enqueue_style( "resource-styles" );

get_header();

//Add this hack to display top nav and head section on Eleganto theme
$cur_theme = wp_get_theme();
$theme = $cur_theme->get('Name');
if ($theme == "Eleganto"){
	get_template_part( 'template-part', 'topnav' );
	get_template_part( 'template-part', 'head' );
}

global $post;
global $wpdb;

$url = get_post_meta($post->ID, "oer_resourceurl", true);
$url_domain = getDomainFromUrl($url);

$hide_title = get_option('oer_hide_resource_title');
?>
<!--<div id="primary" class="content-area">-->
    <main id="main" class="site-main" role="main">

    <article id="oer-resource-<?php the_ID(); ?>" class="oer_sngl_resource_wrapper post-content">
        <div id="sngl-resource" class="entry-content oer-cntnr post-content oer_sngl_resource_wrapper row">
		<?php if (!$hide_title): ?>
        <header class="entry-header">
            <h1 class="entry-title"><?php echo $post->post_title;?></h1>
        </header>
	<?php endif; ?>
    	    <div class="oer-rsrclftcntr-img col-md-5">
                <!--Resource Image-->
                <div class="oer-sngl-rsrc-img">
                    <a class="oer-featureimg" href="<?php echo get_post_meta($post->ID, "oer_resourceurl", true)?>" target="_blank" >
    			<?php
    				$img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , "full" );
    				$img_path = $new_img_path = parse_url($img_url[0]);
    				$img_path = $_SERVER['DOCUMENT_ROOT'] . $img_path['path'];
    				$new_image_url = OER_URL.'images/default-icon-528x455.png';
				$img_width = get_image_width('large');
				$img_height = get_image_height('large');
				
			if(!empty($img_path))
    				{
    					if ( is_wp_error($image_editor) ) {
						debug_log("Can't get Image editor to resize Resource screenshot.");
					} else {
						$new_image_url = oer_resize_image($img_url[0], $img_width, $img_height, true);
    					}
                    }

    				echo '<img src="'.$new_image_url.'" alt="'.get_the_title().'"/>';

    				?>
                	</a>
                    <a class="oer-rsrcurl" href="<?php echo get_post_meta($post->ID, "oer_resourceurl", true); ?>" target="_blank" >
                        <?php echo $url_domain; ?>
                    </a>
                </div>
            </div>

    		<div class="oer-rsrcrghtcntr col-md-7">
            	<div class="oer-rsrcctgries tagcloud">
                	<?php

                            $post_terms = get_the_terms( $post->ID, 'resource-subject-area' );
    			if(!empty($post_terms))
    			{
    				foreach($post_terms as $term)
    				{
    					if($term->parent != 0)
    					{
    						$parent[] = get_oer_parent_term($term->term_id);
    					}
    					else
    					{
    						echo '<span><a href="'.site_url().'/'.$term->taxonomy.'/'.$term->slug.'" class="button">'.ucwords ($term->name).'</a></span>';
    					}
    				}

    				if(!empty($parent) && array_filter($parent))
    				{
    					$recur_multi_dimen_arr_obj =  new RecursiveArrayIterator($parent);
    					$recur_flat_arr_obj =  new RecursiveIteratorIterator($recur_multi_dimen_arr_obj);
    					$flat_arr = iterator_to_array($recur_flat_arr_obj, false);

    					$flat_arr = array_values(array_unique($flat_arr));
    					for($k=0; $k < count($flat_arr); $k++)
    					{
    						//$idObj = get_category_by_slug($flat_arr[$k]);
    						$idObj = get_term_by( 'slug' , $flat_arr[$k] , 'resource-subject-area' );

    						if(!empty($idObj->name))
    						echo '<span><a href="'.site_url().'/'.$idObj->taxonomy.'/'.$idObj->slug.'" class="button">'.ucwords ($idObj->name).'</a></span>';
    					}
    				}
    			}
    			?>
                        </div>

                        <!--Resource Description-->
    					<?php if(!empty($post->post_content)) {?>
    						<div class="oer-sngl-rsrc-dscrptn">
    							<h2><?php _e("Description", OER_SLUG) ?></h2>
    							<?php echo $content = apply_filters ("the_content", $post->post_content); ?>
    						</div>
    					<?php } ?>

                        <div id="" class="oer-authorName oer-cbxl">
    						<?php
    							$oer_authorname = get_post_meta($post->ID, "oer_authorname", true);
    							$oer_authorurl = get_post_meta($post->ID, "oer_authorurl", true);

    							if(!empty($oer_authorname) && !empty($oer_authorurl))
    							{
    							?>
    								<h4><strong><?php _e("Creator:", OER_SLUG) ?></strong>
    								<span><a href="<?php echo $oer_authorurl; ?>" target="_blank"><?php echo $oer_authorname; ?></a></span></h4>
    							<?php } ?>
                        </div>
                            <?php
    			$oer_publishername = get_post_meta($post->ID, "oer_publishername", true);
    			$oer_publisherurl = get_post_meta($post->ID, "oer_publisherurl", true);

    			if(!empty($oer_publishername) && !empty($oer_publisherurl))
    			{
    			?>
    			<div id="" class="oer-publisherName oer-cbxl">
    				<h4><strong><?php _e("Publisher:", OER_SLUG) ?></strong>
    				<span><a href="<?php echo $oer_publisherurl; ?>" target="_blank"><?php echo $oer_publishername; ?></a></span></h4>
    			</div>
    			<?php } ?>
                        <div id="" class="oer-mediaType oer-cbxl">
    						<?php
    							$oer_mediatype = get_post_meta($post->ID, "oer_mediatype", true);
    							if(!empty($oer_mediatype))
    							{ ?>
    								<h4><strong><?php _e("Type:", OER_SLUG) ?></strong>
    								<span><?php echo ucwords($oer_mediatype); ?></span></h4>
    						<?php } ?>
                        </div>
    					<?php
    						$grades =  trim(get_post_meta($post->ID, "oer_grade", true),",");
    						$grades = explode(",",$grades);

    						if(is_array($grades) && !empty($grades) && array_filter($grades))
    						{
    					?>
    						<div class="oer-rsrcgrd oer-cbxl">
    							<h4><strong><?php
							if (count($grades)>1)
								_e("Grades:", OER_SLUG);
							else
								_e("Grade:", OER_SLUG)
							?></strong>
    							<span>
						<?php
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
    										if($elmnt == $fltrarr[$i])
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
										if (count($fltrarr)>1)
											echo $fltrarr[0]."-".$fltrarr[$end_filter];
										else
											echo $fltrarr[0];
    									}
    							?>
							</span></h4>
						</div>
    					<?php }?>

    					<?php
    						$oer_datecreated = get_post_meta($post->ID, "oer_datecreated", true);
    						if(!empty($oer_datecreated))
    						{
    						?>
					<div class="oer-created oer-cbxl">
					    <h4><strong>Created:</strong>
					    <span><?php echo $oer_datecreated; ?></span></h4>
					</div>
    					<?php } ?>

    					<?php
    						$keywords = wp_get_post_tags($post->ID);
    						if(!empty($keywords))
    						{
    					?>
    							<div class="oer-rsrckeyword">
    								<h4><strong><?php _e("Keywords:", OER_SLUG) ?></strong></h4>
    								<div class="oer_meta_container tagcloud">
    							   <?php
    									foreach($keywords as $keyword)
    									{
    										echo "<span><a href='".get_tag_link($keyword->term_id)."' class='button'>".ucwords($keyword->name)."</a></span>";
    									}
    								?>
    								</div>
    							</div>
    					<?php } ?>


                        <!--Resource Meta Data-->
                        <div class="oer-sngl-rsrc-meta">
                            <!-- Meta Data Navigation Tab-->
                            <!--<div class="tabNavigator">
                             <a href="javascript:" data-id="tags" title="Metadata Tags" onclick="rsrc_tabs(this);">1</a>
                             <a href="javascript:" data-id="alignedStandards" title="Aligned Standards" onclick="rsrc_tabs(this);"><?php //echo count($stnd_arr);?></a>
                             <a href="javascript:" data-id="keyword" title="Keywords" onclick="rsrc_tabs(this);"><?php //echo count($keywords); ?></a>
                             <a href="javascript:" data-id="moreLikeThis" title="More Like This" onclick="rsrc_tabs(this);"><?php //echo $count; ?></a>
                           </div>-->

                           <!-- Meta Data Navigation Tab Related Post-->
                           <!--<div class="moreLikeThis" style="display: none;" >
                                <h3>More Like This</h3>
                                <div class="oer_meta_container">
                                <?php

                                    /*$tags = wp_get_post_tags($post->ID);
                                    if ($tags)
                                    {
                                          $tag_ids = array();
                                          foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;

                                          $args=array(
                                            'tag__in' 		=> $tag_ids,
                                            'post__not_in' 	=> array($post->ID),
                                            'showposts'		=> -1,
                                            'post_type'		=> 'resource',
                                            'ignore_sticky_posts'	=> 1
                                           );

                                          $my_query = new WP_Query($args);

                                          if( $my_query->have_posts() )
                                          {
                                            while ($my_query->have_posts()) : $my_query->the_post(); */?>
                                                <div class="sngl-rltd-rsrc">
                                                    <div class="sngl-rltd-rsrc-title">
                                                        <a href="<?php //the_permalink() ?>" rel="bookmark" title="<?php //the_title_attribute(); ?>"><?php //the_title(); ?></a>
                                                    </div>
                                                    <div class="sngl-rltd-rsrc-description">
                                                        <?php //echo the_content(); ?>
                                                    </div>
                                                    <div class="sngl-rltd-rsrc-img">
                                                        <?php //$img_url = wp_get_attachment_url(get_post_meta( //$post->ID, "_thumbnail_id" , true)); ?>
                                                        <img src="<?php //echo $img_url;?>" alt="<?php //the_title();?>"/>
                                                    </div>
                                                </div>
                                              <?php
                                            /*endwhile;
                                          }
                                          else
                                          {
                                            echo "No Resource Found Like This!!";
                                          }
                                    }
                                    else
                                    {
                                        echo "No Resource Found Like This!!";
                                    }*/
                                ?>
                                </div>
                           </div>-->
                        </div>
                    </div> <!--Description & Resource Info at Right-->

                    <!-- Standards Alignment -->
                    <div class="oer-rsrclftcntr">

    					<?php

    						$stdrd_id = get_post_meta($post->ID, 'oer_standard_alignment', true);
    						$oer_standard = get_post_meta($post->ID, 'oer_standard', true);
    						if(!empty($stdrd_id) || !empty($oer_standard))
    						{
    					?>
                        	<div class="alignedStandards">
                                <h2><?php _e("Standards", OER_SLUG) ?></h2>
                                <div class="oer_meta_container">
                                    <div class="oer_stndrd_align">
                                    <?php
                                        if(!empty($stdrd_id))
                                        {
    					            ?>
    					                   <h3><?php _e("Standard Alignment", OER_SLUG) ?></h3>
    					                   <?php
                                             $res = $wpdb->get_row( $wpdb->prepare( "select standard_name from ".$wpdb->prefix."core_standards where id=%d" , $stdrd_id ), ARRAY_A);
                                             echo "<div class='stndrd_ttl'>".$res['standard_name']."</div>";
                                        }
                                    ?>

                                    </div>

                                    <div class="oer_stndrds_notn">
                                    <?php
                                        if(!empty($oer_standard))
                                        {
    					?>
						<h3><?php _e("Standard Notations", OER_SLUG) ?></h3>
						<?php

    						$stnd_arr = explode(",", $oer_standard);
						
                                                for($i=0; $i< count($stnd_arr); $i++)
                                                {
                                                    $table = explode("-",$stnd_arr[$i]);
						    $prefix = substr($stnd_arr[$i],0,strpos($stnd_arr[$i],"_"));
                                                    $table_name = $table[0];
                                                    $id = $table[1];
                                                    if(strcmp($prefix, $wpdb->prefix) !== 0)
                                                    {
                                                        $table_name = str_replace($prefix,$wpdb->prefix,$table[0]);
                                                    }
						    $res = $wpdb->get_row( $wpdb->prepare("select * from $table_name where id=%d" , $id ), ARRAY_A);
						    var_dump($res);
						    echo "<div class='oer_sngl_stndrd'>";
							echo "<div class='oer_sngl_notation'>".$res['standard_notation']."</div>";
							echo "<div class='oer_sngl_description'>".$res['description']."</div>";
						    echo "</div>";
                                                }
                                        }
                                    ?>
                                    </div>

                                </div>
                           </div>
    					<?php } ?>
                    </div> <!--Thumbnail & Standards Info at Left-->


            </div><!-- .single resource wrapper -->

    </article>
</main>
<!--</div>-->

<?php
if ($theme == "Eleganto"){
	get_template_part( 'template-part', 'footernav' );
}

get_footer();
?>
