<?php /** Website/Image/Document(except PDF)/Other Resource Template **/ ?>
<div class="oer-rsrclftcntr-img col-md-5 col-sm-12 col-xs-12">
    <!--Resource Image-->
    <div class="oer-sngl-rsrc-img oer-sngl-audio-type">
        <?php
        $type=oer_get_resource_file_type($url);
        if ($type['name']=="Audio")
            echo oer_generate_audio_resource_embed($url);
        else
            echo display_default_thumbnail($post);
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
            <span><?php if (!empty($oer_authorurl)): ?><a href="<?php echo esc_url($oer_authorurl); ?>" target="_blank"><?php endif; ?><?php echo $oer_authorname; ?><?php if (!empty($oer_authorurl)): ?></a><?php endif; ?></span>
            <?php if ($oer_authorname2): echo ", "; ?>
            <span><?php if (!empty($oer_authorurl2)): ?><a href="<?php echo esc_url($oer_authorurl2); ?>" target="_blank"><?php endif; ?><?php echo $oer_authorname2; ?><?php if (!empty($oer_authorurl2)): ?></a><?php endif; ?></span>
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
        <span><a href="<?php echo esc_url($oer_publisherurl); ?>" target="_blank"><?php echo $oer_publishername; ?></a></span></h4>
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
            <?php _e("Subjects",OER_LESSON_PLAN_SLUG); ?>
        </h4>
       <div class="tc-oer-subject-details clearfix">
            <ul class="tc-oer-subject-areas-list">
                <?php
                $i = 1;
                $cnt = count($post_terms);
                $moreCnt = $cnt - 2;
                foreach($post_terms as $term){
                    $subject_parent = get_term_parents_list($term->term_id,'resource-subject-area', array('separator' => ' <i class="fas fa-angle-double-right"></i> ', 'inclusive' => false));
                    $subject = $subject_parent . '<a href="'.get_term_link($term->term_id).'">'.$term->name.'</a>';
                    if ($i>2)
                        echo '<li class="collapse lp-subject-hidden">'.$subject.'</li>';
                    else
                        echo '<li>'.$subject.'</li>';
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
            <?php _e("Connected Compilations",OER_LESSON_PLAN_SLUG); ?>
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
                        echo '<li class="collapse lp-subject-hidden"><a href="'.$curriculum_url.'">'.$curriculum['post_title'].'</a></li>';
                    else
                        echo "<li><a href='".$curriculum_url."'>".$curriculum['post_title']."</a></li>";
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
<div class="oer-see-more-row">
    <p class="center"><span><a id="oer-see-more-link" class="oer-see-more-link" role="button" data-toggle="collapse" href="#tcHiddenFields" aria-expanded="false" aria-controls="tcHiddenFields"><?php _e("SEE MORE +",OER_LESSON_PLAN_SLUG); ?></a></span></p>
</div>
<?php endif; ?>
<div id="tcHiddenFields" class="tc-hidden-fields collapse row">
    <div class="col-md-5">
        <!-- Age Levels -->
        <?php
        if (($age_levels_set && $age_levels_enabled) || !$age_levels_set) {
            $age_label = oer_field_label('oer_age_levels');
            if (!empty($age_levels)){
            ?>
            <div class="form-field">
                <div class="oer-lp-label"><?php echo $age_label; ?>:</div> <div class="oer-lp-value"><?php echo $age_levels; ?></div>
            </div>
            <?php
            }
        }
        ?>
        
        <!-- Grade Level -->
        <?php
        $grades = explode(",",$grades);
        
        if(is_array($grades) && !empty($grades) && array_filter($grades))
        {
            $option_set = false;
            if (get_option('oer_grade_label'))
                $option_set = true;
        ?>
            <div class="form-field">
                <div class="oer-lp-label"><?php
                if (!$option_set){
                    if (count($grades)>1)
                        _e("Grade Levels:", OER_SLUG);
                    else
                        _e("Grade Level:", OER_SLUG);
                } else
                        echo get_option('oer_grade_label').":";
                ?></div>
                <div class="oer-lp-value">
                <?php
                echo oer_grade_levels($grades);
                ?>
                </div>
            </div>
        <?php }?>
        
        <!-- Instruction Time -->
        <?php
        if (($suggested_time_set && $suggested_time_enabled) || !$suggested_time_set) {
             $suggested_label = oer_field_label('oer_instructional_time');
             if (!empty($suggested_time)){
             ?>
             <div class="form-field">
                 <div class="oer-lp-label"><?php echo $suggested_label; ?>:</div> <div class="oer-lp-value"><?php echo $suggested_time; ?></div>
             </div>
             <?php
             }
         }
        ?>
        
        <!-- Creative Commons License -->
        <?php
        if (($cc_license_set && $cc_license_enabled) || !$cc_license_set) {
            $cc_label = oer_field_label('oer_creativecommons_license');
            if (!empty($cc_license)){
            ?>
            <div class="form-field license-field">
                <img src="<?php echo oer_cc_license_image($cc_license); ?>">
            </div>
            <?php
            }
        }
        
        ?>
    </div>
    <div class="col-md-7">
        <!-- External Repository -->
        <?php
        if (($external_repository_set && $external_repository_enabled) || !$external_repository_set) {
             $external_repository_label = oer_field_label('oer_external_repository');
             if (!empty($external_repository)){
             ?>
             <div class="form-field">
                 <div class="oer-lp-label"><?php echo $external_repository_label; ?>:</div> <div class="oer-lp-value"><?php echo $external_repository; ?></div>
             </div>
             <?php
             }
         }
        ?>
        
        <!-- Repository URL -->
        <?php
        if (($repository_record_set && $repository_record_enabled) || !$repository_record_set) {
             $repository_record_label = oer_field_label('oer_repository_recordurl');
             if (!empty($repository_record)){
             ?>
             <div class="form-field">
                 <div class="oer-lp-label"><?php echo $repository_record_label; ?>:</div> <div class="oer-lp-value"><a href="<?php echo $repository_record; ?>"><?php echo $repository_record; ?></a></a></div>
             </div>
             <?php
             }
         }
        ?>
        
        <!-- Citation -->
        <?php
        if (($citation_set && $citation_enabled) || !$citation_set) {
            $citation_label = oer_field_label('oer_citation');
            if (!empty($citation)){
            ?>
            <div class="form-field">
                <div class="oer-lp-label"><?php echo $citation_label; ?>:</div><div class="oer-lp-value"><?php if (strlen($citation)>230): ?>
                <div class="oer-lp-value-excerpt"><?php echo oer_get_content( $citation, 230); ?></div>
                <div class="oer-lp-value-full"><?php echo $citation; ?>  <a href="javascript:void(0);" class="lp-read-less">(read less)</a></div>
                <?php
                else: 
                    echo $citation;
                endif; ?></div>
            </div>
            <?php
            }
        }
        ?>
        
        <!-- Transcription -->
        <?php
        if (($transcription_set && $transcription_enabled) || !$transcription_set) {
            $transcription_label = oer_field_label('oer_transcription');
            if (!empty($transcription)){
            ?>
            <div class="form-field">
                <div class="oer-lp-label"><?php echo $transcription_label; ?>:</div><div class="oer-lp-value"><?php if (strlen($transcription)>230): ?>
                <div class="oer-lp-value-excerpt"><?php echo oer_get_content( $transcription, 230); ?></div>
                <div class="oer-lp-value-full"><?php echo $transcription; ?> <a href="javascript:void(0);" class="lp-read-less">(read less)</a></div>
                <?php
                else: 
                    echo $transcription;
                endif; ?></div>
            </div>
            <?php
            }
        }
        ?>
        
        <!-- Sensitive Material Warning -->
        <?php
        if (($sensitive_material_set && $sensitive_material_enabled) || !$sensitive_material_set) {
            $sensitive_material_label = oer_field_label('oer_sensitive_material');
            if (!empty($sensitive_material)){
            ?>
            <div class="form-field">
                <div class="oer-lp-label oer-lp-red"><?php echo $sensitive_material_label; ?>:</div> <div class="oer-lp-value"><?php if (strlen($sensitive_material)>230): ?>
                <div class="oer-lp-value-excerpt"><?php echo oer_get_content( $sensitive_material, 230); ?></div>
                <div class="oer-lp-value-full"><?php echo $sensitive_material; ?>  <a href="javascript:void(0);" class="lp-read-less">(read less)</a></div>
                <?php
                else: 
                    echo $sensitive_material;
                endif; ?></div>
            </div>
            <?php
            }
        }
        ?>
    </div>
</div>