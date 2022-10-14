<?php /** Video Resource Template **/ 

$allowed_tags = oer_allowed_html();

// Checks if display more button should be displayed
if (!empty($external_repository) || !empty($repository_record)
    || !empty($citation) || !empty($transcription) || !empty($sensitive_material))
    $display_see_more = true;
?>
<div class="oer-rsrclftcntr-video col-md-12 col-sm-12 col-xs-12">    
        <?php
        $type=oer_get_resource_file_type($url);
        if (is_array($type)){
            if ($type['name']=="Video")
                echo oer_embed_video_file($url, $type['type']);
        }
        ?>
</div>
<div class="oer-rsrccntr-details col-md-12 col-sm-12 col-xs-12">
    <div class="oer-rsrclftcntr col-md-5 col-sm-12 col-xs-12">
        <!-- Author -->
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
                    esc_html_e("Creator:", OER_SLUG);
            else
            echo get_option('oer_authorname_label').":"; ?></strong>
                <span><?php if (!empty($oer_authorurl)): ?><a href="<?php echo esc_url($oer_authorurl); ?>" target="_blank"><?php endif; ?><?php echo esc_html($oer_authorname); ?><?php if (!empty($oer_authorurl)): ?></a><?php endif; ?></span><?php if ($oer_authorname2): echo ", "; ?><span><?php if (!empty($oer_authorurl2)): ?><a href="<?php echo esc_url($oer_authorurl2); ?>" target="_blank"><?php endif; ?><?php echo esc_html($oer_authorname2); ?><?php if (!empty($oer_authorurl2)): ?></a><?php endif; ?></span>
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
                esc_html_e("Publisher:", OER_SLUG);
            else
                echo get_option('oer_publishername_label').":";
            ?></strong>
            <span><a href="<?php echo esc_url($oer_publisherurl); ?>" target="_blank"><?php echo esc_html($oer_publishername); ?></a></span></h4>
        </div>
        <?php } ?>
        
        <?php  
            // Load content-details partial template
            oer_get_template_part('partial/content','details', $meta_args);  
        ?>
        
        <!-- Keywords -->
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
                                    echo "<span><a href='".esc_url(get_tag_link($keyword->term_id))."' class='button'>".esc_html(ucwords($keyword->name))."</a></span>";
                            }
                    ?>
                    </div>
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
                    <div class="oer-lp-content"><?php echo wp_kses_post($post->post_content); ?></div>
                <?php endif; ?>
                <?php //echo $content = apply_filters ("the_content", $post->post_content); ?>
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
                <?php esc_html_e("Subjects",OER_SLUG); ?>
            </h4>
           <div class="tc-oer-subject-details clearfix">
                <ul class="tc-oer-subject-areas-list">
                    <?php
                    $i = 1;
                    $cnt = count($post_terms);
                    $moreCnt = $cnt - 2;
                    foreach($post_terms as $term){
                        $subject_parent = get_term_parents_list($term->term_id,'resource-subject-area', array('separator' => ' <i class="fas fa-angle-double-right"></i> ', 'inclusive' => false));
                        $subject = $subject_parent . '<a href="'.esc_url(get_term_link($term->term_id)).'">'.esc_html($term->name).'</a>';
                        if ($i>2)
                            echo '<li class="collapse lp-subject-hidden">'.wp_kses($subject,$allowed_tags).'</li>';
                        else
                            echo '<li>'.wp_kses($subject,$allowed_tags).'</li>';
                        if (($i==2) && ($cnt>2))
                            echo '<li><a class="see-more-subjects" data-bs-toggle="collapse" data-count="'.esc_attr($moreCnt).'" href=".lp-subject-hidden">SEE '.esc_html($moreCnt).' MORE +</a></li>';
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
                <?php esc_html_e("Connected Compilations",OER_SLUG); ?>
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
                            echo '<li><a class="see-more-subjects" data-bs-toggle="collapse" data-count="'.esc_attr($moreCnt).'" href=".lp-subject-hidden">SEE '.esc_html($moreCnt).' MORE +</a></li>';
                        $i++;
                    }
                    ?>
                </ul>
           </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php  if ($display_see_more): ?>
<div class="oer-see-more-row col-md-12 col-sm-12 col-xs-12">
    <p class="center"><span><a id="oer-see-more-link" class="oer-see-more-link" role="button" data-bs-toggle="collapse" href="#tcHiddenFields" aria-expanded="false" aria-controls="tcHiddenFields"><?php esc_html_e("SEE MORE +",OER_SLUG); ?></a></span></p>
</div>
<?php endif; ?>
<div id="tcHiddenFields" class="tc-hidden-fields collapse row col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-12">
        <?php  
            // Load content-meta partial template
            oer_get_template_part('partial/content','meta', $template_args);  
        ?>
    </div>
</div>