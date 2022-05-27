<?php
$post_meta_data = get_post_meta($post->ID );
$author_set = (get_option('oer_authorname_label'))?true:false;
$author_enabled = (get_option('oer_authorname_enabled'))?true:false;
$standards_set = (get_option('oer_standard_label'))?true:false;
$standards_enabled = (get_option('oer_standard_enabled'))?true:false;
$oer_standard = get_post_meta($post->ID, 'oer_standard', true);
$age_levels_set = (get_option('oer_age_levels_label'))?true:false;
$age_levels_enabled = (get_option('oer_age_levels_enabled'))?true:false;
$suggested_time_set = (get_option('oer_instructional_time_label'))?true:false;
$suggested_time_enabled = (get_option('oer_instructional_time_enabled'))?true:false;
$cc_license_set = (get_option('oer_creativecommons_license_label'))?true:false;
$cc_license_enabled = (get_option('oer_creativecommons_license_enabled'))?true:false;
$external_repository_set = (get_option('oer_creativecommons_license_label'))?true:false;
$external_repository_enabled = (get_option('oer_creativecommons_license_enabled'))?true:false;
$repository_record_set = (get_option('oer_repository_recordurl_label'))?true:false;
$repository_record_enabled = (get_option('oer_repository_recordurl_enabled'))?true:false;
$citation_set = (get_option('oer_citation_label'))?true:false;
$citation_enabled = (get_option('oer_citation_enabled'))?true:false;
$transcription_set = (get_option('oer_transcription_label'))?true:false;
$transcription_enabled = (get_option('oer_transcription_enabled'))?true:false;
$sensitive_material_set = (get_option('oer_sensitive_material_label'))?true:false;
$sensitive_material_enabled = (get_option('oer_sensitive_material_enabled'))?true:false;
?>
<div class="oer-rsrclftcntr-img col-md-5 col-sm-12 col-xs-12">
    <!--Resource Image-->
    <div class="oer-sngl-rsrc-img oer-sngl-standard-type">
        <?php if ($youtube) {
            $embed = oer_generate_youtube_embed_code($url);
            echo esc_html($embed);
        } elseif($isPDF) {
            if ($isExternal) {
                $external_option = get_option("oer_external_pdf_viewer");
                if ($external_option==1) {
                    $pdf_url = "https://docs.google.com/gview?url=".$url."&embedded=true";
                    echo oer_get_embed_code_frame($pdf_url);
                } elseif($external_option==0) {
                    $embed_disabled = true;
                }
            } else {
                $local_option = get_option("oer_local_pdf_viewer");
                switch ($local_option){
                    case 0:
                        $embed_disabled = true;
                        break;
                    case 1:
                        $pdf_url = "https://docs.google.com/gview?url=".$url."&embedded=true";
                        echo oer_get_embed_code_frame($pdf_url);
                        break;
                    case 2:
                        $pdf_url = OER_URL."pdfjs/web/viewer.html?file=".urlencode($url);
                        $embed_code = '<iframe class="oer-pdf-viewer" width="100%" src="'.esc_url_raw($pdf_url).'"></iframe>';
                        echo esc_html($embed_code);
                        break;
                    case 3:
                        if(shortcode_exists('wonderplugin_pdf')) {
                            $embed_code = "[wonderplugin_pdf src='".esc_url_raw($url)."' width='100%']";
                            echo do_shortcode($embed_code);
                        } else {
                            $embed_disabled = true;
                        }
                        break;
                    case 4:
                        if(shortcode_exists('pdf-embedder')){
                            $embed_code = "[pdf-embedder url='".esc_url_raw($url)."' width='100%']";
                            echo do_shortcode($embed_code);
                        } else {
                            $embed_disabled = true;
                        }
                        break;
                    case 5:
                        if(shortcode_exists('pdfviewer')){
                            $embed_code = "[pdfviewer width='100%']".$url."[/pdfviewer]";
                            echo do_shortcode($embed_code);
                        } else {
                            $embed_disabled = true;
                        }
                        break;
                }
            }
        } else {
            $type=oer_get_resource_file_type($url);
            if ($type['name']=="Video"){
                echo oer_embed_video_file($url, $type['type']);
            } else {
                echo oer_display_default_thumbnail($post);
            }
        }
        if ($embed_disabled){
            echo oer_display_default_thumbnail($post);
        }
        ?>
    </div>
    <div id="" class="oer-authorName oer-cbxl">
        <?php
        $oer_authorname = get_post_meta($post->ID, "oer_authorname", true);
        $oer_authorurl = get_post_meta($post->ID, "oer_authorurl", true);
        $oer_authorname2 = get_post_meta($post->ID, "oer_authorname2", true);
        $oer_authorurl2 = get_post_meta($post->ID, "oer_authorurl2", true);

        if(!empty($oer_authorname) || !empty($oer_authorname2))
        {
            $option_set = false;
            if (get_option('oer_authorname_label'))
                $option_set = true;
        ?>
            <h4><strong><?php
            if (!$option_set)
                _e("Creator:", OER_SLUG);
	    else
		echo get_option('oer_authorname_label').":"; ?></strong>
            <span><?php if (!empty($oer_authorurl)): ?><a href="<?php echo esc_url($oer_authorurl); ?>" target="_blank"><?php endif; ?><?php echo trim($oer_authorname); ?><?php if (!empty($oer_authorurl)): ?></a><?php endif; ?></span><?php if ($oer_authorname2): echo ", "; ?><span><?php if (!empty($oer_authorurl2)): ?><a href="<?php echo esc_url($oer_authorurl2); ?>" target="_blank"><?php endif; ?><?php echo $oer_authorname2; ?><?php if (!empty($oer_authorurl2)): ?></a><?php endif; ?></span>
            <?php endif; ?>
            </h4>
        <?php } ?>
    </div>
    <?php
    $oer_publishername = get_post_meta($post->ID, "oer_publishername", true);
    $oer_publisherurl = get_post_meta($post->ID, "oer_publisherurl", true);

    if(!empty($oer_publishername) && !empty($oer_publisherurl))
    {
        $option_set = false;
	if (get_option('oer_publishername_label'))
	    $option_set = true;
    ?><div id="" class="oer-publisherName oer-cbxl">
        <h4><strong><?php
        if (!$option_set)
            _e("Publisher:", OER_SLUG);
        else
            echo get_option('oer_publishername_label').":";
        ?></strong>
        <span><a href="<?php echo esc_url($oer_publisherurl); ?>" target="_blank"><?php echo esc_html($oer_publishername); ?></a></span></h4>
    </div>
    <?php } ?>
</div>
<div class="oer-rsrcrghtcntr col-md-7 col-sm-12 col-xs-12">
    <!--Resource Description-->
    <?php if(!empty($post->post_content)) {?>
        <div class="oer-sngl-rsrc-dscrptn">
            <?php if (strlen($post->post_content)>230) : ?>
                <div class="oer-lp-excerpt"><?php echo oer_get_content($post->post_content, 230); ?></div>
                <div class="oer-lp-full-content"><?php echo get_the_content(null, false, $post->ID); ?> <a href="javascript:void(0);" class="lp-read-less">(read less)</a>
                </div>
            <?php else : ?>
                <div class="oer-lp-content"><?php echo $post->post_content; ?></div>
            <?php endif; ?>
            <?php //echo $content = apply_filters ("the_content", $post->post_content); ?>
        </div>
    <?php } ?>

    <?php
    $keywords = wp_get_post_tags($post->ID);
    if(!empty($keywords))
    {
    ?>
        <div class="oer-rsrckeyword">
                <div class="oer_meta_container tagcloud">
           <?php
                        foreach($keywords as $keyword)
                        {
                                echo "<span><a href='".esc_url(get_tag_link($keyword->term_id))."' class='button'>".ucwords($keyword->name)."</a></span>";
                        }
                ?>
                </div>
        </div>
    <?php } ?>

    <!-- Standards List -->
    <?php if ($oer_standard) {
        if (($standards_set && $standards_enabled) || !$standards_set) {
    ?>
    <div class="tc-oer-standards">
       <h4 class="tc-field-heading clearfix">
           <?php echo oer_field_label('oer_standard'); ?>
       </h4>
       <div class="tc-oer-standards-details clearfix">
           <?php oer_standards_list_display($post->ID); ?>
        </div>
    </div>
    <?php
         }
    } ?>

    <!-- Subject Areas -->
    <?php
    $post_terms = get_the_terms( $post->ID, 'resource-subject-area' );
    if (!empty($post_terms)) {
    ?>
    <div class="tc-oer-subject-areas">
       <h4 class="tc-field-heading clearfix">
            <?php _e("Subjects",OER_SLUG); ?>
        </h4>
       <div class="tc-oer-subject-details clearfix">
            <ul class="tc-oer-subject-areas-list">
                <?php
                $i = 1;
                $cnt = count($post_terms);
                $moreCnt = $cnt - 2;
                foreach($post_terms as $term){
                    $subject_parent = get_term_parents_list($term->term_id,'resource-subject-area', array('separator' => ' <i class="fas fa-angle-double-right"></i> ', 'inclusive' => false));
                    $subject = $subject_parent . '<a href="'.esc_url(get_term_link($term->term_id)).'">'.$term->name.'</a>';
                    if ($i>2)
                        echo '<li class="collapse lp-subject-hidden">'.esc_html($subject).'</li>';
                    else
                        echo '<li>'.esc_html($subject).'</li>';
                    if (($i==2) && ($cnt>2))
                        echo '<li><a class="see-more-subjects" data-toggle="collapse" data-count="'.$moreCnt.'" href=".lp-subject-hidden">SEE '.$moreCnt.' MORE +</a></li>';
                    $i++;
                }
                ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <!-- Curriculum -->
    <?php
    $connected_curriculums = oer_get_connected_curriculums($post->post_title);
    if (!empty($connected_curriculums)) {
    ?>
    <div class="tc-oer-connected-curriculum">
       <h4 class="tc-field-heading clearfix">
            <?php _e("Appears In",OER_SLUG); ?>
        </h4>
       <div class="tc-oer-curriculum-details clearfix">
            <ul class="tc-oer-subject-areas-list">
                <?php
                $i = 1;
                $cnt = count($connected_curriculums);
                $moreCnt = $cnt - 2;
                foreach($connected_curriculums as $curriculum){
                    $curriculum_url = get_the_permalink($curriculum['post_id']);
                    if ($i>2)
                        echo '<li class="collapse lp-subject-hidden"><a href="'.esc_url($curriculum_url).'">'.esc_html($curriculum['post_title']).'</a></li>';
                    else
                        echo "<li><a href='".esc_url($curriculum_url)."'>".esc_html($curriculum['post_title'])."</a></li>";
                    if (($i==2) && ($cnt>2))
                        echo '<li><a class="see-more-subjects" data-toggle="collapse" data-count="'.$moreCnt.'" href=".lp-subject-hidden">SEE '.$moreCnt.' MORE +</a></li>';
                    $i++;
                }
                ?>
            </ul>
       </div>
    </div>
    <?php } ?>
    </div>
</div> <!--Description & Resource Info at Right-->
<?php  if ($display_see_more): ?>
<div class="oer-see-more-row col-md-12 col-sm-12 col-xs-12">
    <p class="center"><span><a id="oer-see-more-link" class="oer-see-more-link" role="button" data-toggle="collapse" href="#tcHiddenFields" aria-expanded="false" aria-controls="tcHiddenFields"><?php esc_html_e("SEE MORE +",OER_SLUG); ?></a></span></p>
</div>
<?php endif; ?>
<div id="tcHiddenFields" class="tc-hidden-fields collapse row col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-5">
        <?php  
            // Load content-details partial template
            oer_get_template_part('partial/content','details', $meta_args);  
        ?>
    </div>
    <div class="col-md-7">
        <?php  
            // Load content-meta partial template
            oer_get_template_part('partial/content','meta', $template_args);  
        ?>
    </div>
</div>

<!-- RELATED RESOURCES -->
<?php include_once OER_PATH.'includes/related-resources.php';?>
