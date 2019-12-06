<?php
$_delimited = get_post_meta($post->ID,"oer_related_resource");
if(!empty($_delimited[0])):
?>
  <div class="row lp-primary-sources-row">
  <div class="lp-related-resources-header">Related Resources</div>
  <?php
  $_related_resources = explode(",",$_delimited[0]);
  if (is_array($_related_resources)):
     foreach($_related_resources as $_res_id){
       $oer_resourceurl = get_post_meta($_res_id,"oer_resourceurl")[0];
       $oer_media_type = get_post_meta($_res_id,"oer_mediatype")[0];
       $_res_post = get_post($_res_id);
       $oer_resourcetitle = $_res_post->post_title;
       $resource_img = wp_get_attachment_image_url( get_post_thumbnail_id($_res_post), 'resource-thumbnail' );
       $oer_authorname = get_post_meta($_res_id, "oer_authorname", true);
       $oer_authorurl = get_post_meta($_res_id, "oer_authorurl", true);
       $oer_authorname2 = get_post_meta($_res_id, "oer_authorname2", true);
       $oer_authorurl2 = get_post_meta($_res_id, "oer_authorurl2", true);
       ?>
       <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 padding-0">
           <div class="media-image">
               <div class="image-thumbnail">
                   <a href="<?php echo get_post_permalink($_res_id); ?>" target="_new">
                      <?php if(!$resource_img): $_avtr = getResourceIcon($oer_media_type, $oer_resourceurl); ?>
                          <div class="resource-avatar"><span class="dashicons <?php echo $_avtr; ?>"></span></div>
                       <?php endif; ?>
                       <span class="resource-overlay"></span>
                       <span class="lp-source-type"><?php echo ucfirst($oer_media_type); ?></span>
                       <div class="resource-thumbnail" style="background: url('<?php echo $resource_img; ?>') no-repeat center rgba(204,97,12,.1); background-size:cover;"></div>
                   </a>
               </div>
               <div class="lp-resource-title"><b><?php echo $oer_resourcetitle; ?></b></div>
               <div class="lp-resource-author">
                 <?php if( $oer_authorname != ''):?>
                   <div class="lp-resource-author_block"><a href="<?php echo $oer_authorurl; ?>" target="_new"><?php echo $oer_authorname; ?></a></div>
                 <?php endif; ?>
                 <?php /* if( $oer_authorname2 != ''):?>
                   <div class="lp-resource-author_block"><a href=""><?php echo $oer_authorname2; ?></a></div>
                 <?php endif;*/ ?>
               </div>
               <div class="lp-resource-excerpt"><?php echo oer_get_related_resource_content($_res_post->post_content, 60); ?></div>
           </div>
       </div>
       <?php
     }
  endif;
  ?>
  </div>
<?php endif; ?>