<?php
$author_set = (get_option('oer_authorname_label'))?true:false;
$author_enabled = (get_option('oer_authorname_enabled'))?true:false;
$standards_set = (get_option('oer_standard_label'))?true:false;
$standards_enabled = (get_option('oer_standard_enabled'))?true:false;
$oer_standard = get_post_meta($post->ID, 'oer_standard', true);
?>
<div class="oer-rsrclftcntr-img col-md-5 col-sm-12 col-xs-12">
    <!--Resource Image-->
    <div class="oer-sngl-rsrc-img">
        <?php if ($youtube) { 
            $embed = oer_generate_youtube_embed_code($url);
            echo $embed;
        } elseif($isPDF) {
            if ($isExternal) {
                $external_option = get_option("oer_external_pdf_viewer");
                if ($external_option==1) {
                    $pdf_url = "https://docs.google.com/gview?url=".$url."&embedded=true";
                    echo get_embed_code($pdf_url);
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
                        echo get_embed_code($pdf_url);
                        break;
                    case 2:
                        $pdf_url = OER_URL."pdfjs/web/viewer.html?file=".urlencode($url);
                        $embed_code = '<iframe class="oer-pdf-viewer" width="100%" src="'.$pdf_url.'"></iframe>';
                        echo $embed_code;
                        break;
                    case 3:
                        if(shortcode_exists('wonderplugin_pdf')) {
                            $embed_code = "[wonderplugin_pdf src='".$url."' width='100%']";
                            echo do_shortcode($embed_code);
                        } else {
                            $embed_disabled = true;
                        }
                        break;
                    case 4:
                        if(shortcode_exists('pdf-embedder')){
                            $embed_code = "[pdf-embedder url='".$url."' width='100%']";
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
            echo display_default_thumbnail($post);
        }
        if ($embed_disabled){
            echo display_default_thumbnail($post);
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
                <?php echo $content = apply_filters ("the_content", $post->post_content); ?>
        </div>
    <?php } ?>
    
    <div class="oer-rsrcctgries tagcloud">
    <?php
    /** Resource Subject Areas **/
    $subjects = array_unique($subject_areas, SORT_REGULAR);
    
    if(!empty($subjects))
    {
        foreach($subjects as $subject)
        {
            echo '<span><a href="'.esc_url(site_url().'/'.$subject->taxonomy.'/'.$subject->slug).'" class="button">'.ucwords ($subject->name).'</a></span>';
        }
    }
    ?>
    </div>

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
                                            echo "<span><a href='".esc_url(get_tag_link($keyword->term_id))."' class='button'>".ucwords($keyword->name)."</a></span>";
                                    }
                            ?>
                            </div>
                    </div>
    <?php } ?>
    </div>
</div> <!--Description & Resource Info at Right-->
<div id="tcHiddenFields" class="tc-hidden-fields collapse">
    <!-- Grade Level -->
    <?php
    $grades =  trim(get_post_meta($post->ID, "oer_grade", true),",");
    $grades = explode(",",$grades);
    
    if(is_array($grades) && !empty($grades) && array_filter($grades))
    {
        $option_set = false;
        if (get_option('oer_grade_label'))
            $option_set = true;
    ?>
        <div class="oer-rsrcgrd oer-cbxl">
            <h4><strong><?php
            if (!$option_set){
                if (count($grades)>1)
                    _e("Grades:", OER_SLUG);
                else
                    _e("Grade:", OER_SLUG);
            } else
                    echo get_option('oer_grade_label').":";
            ?></strong>
            <span>
            <?php
            echo oer_grade_levels($grades);
            ?>
            </span></h4>
        </div>
    <?php }?>
    
    <!-- Transcription -->
    <?php
    $oer_transcription = get_post_meta($post->ID, 'oer_transcription', true);
    if (!empty($oer_transcription)){
        $option_set = false;
        if (get_option('oer_transcription_label'))
            $option_set = true;
        ?>
        <div class="oer-sngl-rsrc-dscrptn">
                <h2><?php
                if (!$option_set)
                    _e("Transcription", OER_SLUG);
                else
                    echo get_option('oer_transcription_label');
                ?></h2>
                <?php echo $oer_transcription; ?>
        </div>
        <?php
    }
    ?>
    
    <!-- Sensitive Material Warning -->
    <?php
    $oer_sensitive_material = get_post_meta($post->ID, 'oer_sensitive_material', true);
    if (!empty($oer_sensitive_material)){
        $option_set = false;
	if (get_option('oer_sensitive_material_label'))
	    $option_set = true;
        ?>
        <div class="oer-sngl-rsrc-dscrptn">
                <h2><?php
                if (!$option_set)
                    _e("Sensitive Material Warning", OER_SLUG);
                else
                    echo get_option('oer_sensitive_material_label');
                ?></h2>
                <?php echo $oer_sensitive_material; ?>
        </div>
        <?php
    }
    ?>
    
    <?php
    $oer_resourceurl = get_post_meta($post->ID, "oer_resourceurl", true);
    if (!empty($oer_resourceurl)) {
    ?>
        <?php if ($youtube) { ?>
            <div class="oer-rsrcurl oer-cbxl"><h4><strong>Original Resource:</strong> <a href="<?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?>" target="_blank" ><?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?></a></h4></div>
        <?php } else { ?>
            <div class="oer-rsrcurl oer-cbxl"><h4><strong>Original Resource:</strong> <a href="<?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?>" target="_blank" ><?php echo $url_domain; ?></a></h4></div>
        <?php } ?>
    <?php } ?>
    
    <!-- Date Created Estimate -->
    <?php
    $oer_datecreated_estimate = get_post_meta($post->ID, 'oer_datecreated_estimate', true);
    if (!empty($oer_datecreated_estimate)){ ?>
    <div id="oerDateCreatedEstimate" class="oer-dateCreatedEstimate oer-cbxl">
        <?php
        $option_set = false;
        if (get_option('oer_datecreated_estimate_label'))
            $option_set = true;
        ?>
        <h4><strong><?php
        if (!$option_set)
            _e("Date Created Estimate:", OER_SLUG);
        else
            echo get_option('oer_datecreated_estimate_label').":";
        ?></strong>
        <span><?php echo $oer_datecreated_estimate; ?></span></h4>
    </div>
    <?php } ?>
    
    <div id="" class="oer-mediaType oer-cbxl">
        <?php
            $oer_mediatype = get_post_meta($post->ID, "oer_mediatype", true);
            if(!empty($oer_mediatype))
            {
                $option_set = false;
                if (get_option('oer_mediatype_label'))
                        $option_set = true;
                ?>
                <h4><strong><?php
                if (!$option_set)
				_e("Type:", OER_SLUG);
			else
				echo get_option('oer_mediatype_label').":";
                ?></strong>
                <span><?php echo ucwords($oer_mediatype); ?></span></h4>
        <?php } ?>
    </div>
    
    <!-- Format -->
    <?php
    $oer_format = get_post_meta($post->ID, 'oer_format', true);
    if (!empty($oer_format)){ ?>
    <div id="oerFormat" class="oer-Format oer-cbxl">
        <?php
        $option_set = false;
        if (get_option('oer_format_label'))
            $option_set = true;
        ?>
        <h4><strong><?php
        if (!$option_set)
            _e("Format:", OER_SLUG);
        else
            echo get_option('oer_format_label').":";
        ?></strong>
        <span><?php echo $oer_format; ?></span></h4>
    </div>
    <?php } ?>
    
    <!-- Citation -->
    <?php
    $oer_citation = get_post_meta($post->ID, 'oer_citation', true);
    if (!empty($oer_citation)){ ?>
    <div id="oerCitation" class="oer-Citation oer-cbxl">
        <?php
        $option_set = false;
        if (get_option('oer_citation_label'))
            $option_set = true;
        ?>
        <h4><strong><?php
        if (!$option_set)
            _e("Citation:", OER_SLUG);
        else
            echo get_option('oer_citation_label').":";
        ?></strong>
        <span><?php echo $oer_citation; ?></span></h4>
    </div>
    <?php } ?>

    <?php
            $oer_datecreated = get_post_meta($post->ID, "oer_datecreated", true);
            if(!empty($oer_datecreated))
            {
                $option_set = false;
                if (get_option('oer_datecreated_label'))
                        $option_set = true;
            ?>
        <div class="oer-created oer-cbxl">
        <h4><strong><?php
        if (!$option_set)
                _e("Created:", OER_SLUG);
        else
                echo get_option('oer_datecreated_label').":";
        ?></strong>
        <span><?php echo $oer_datecreated; ?></span></h4>
    </div>
    <?php } ?>
</div>
<div class="oer-see-more-row">
    <p class="center"><span><a id="oer-see-more-link" class="oer-see-more-link" role="button" data-toggle="collapse" href="#tcHiddenFields" aria-expanded="false" aria-controls="tcHiddenFields"><?php _e("SEE MORE +",OER_LESSON_PLAN_SLUG); ?></a></span></p>
</div>