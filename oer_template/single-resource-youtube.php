<div class="oer-rsrclftcntr-video col-md-12 col-sm-12 col-xs-12">    
        <?php
        if ($youtube){
                echo '<div class="videoWrapper">';
                $embed = oer_generate_youtube_embed_code($url);
                echo $embed;
                echo '</div>';
        } elseif($isSSLResource){
                echo '<div class="SLLWrapper">';
                $embed = oer_generate_sll_resource_embed_code($url);
                echo $embed;
                echo '</div>';
        } elseif($isSLLCollection){
                echo '<div class="SLLWrapper">';
                $embed = oer_generate_sll_collection_embed_code($url);
                echo $embed;
                echo '</div>';
        }
            
        ?>
</div>
<div class="oer-rsrccntr-details col-md-12 col-sm-12 col-xs-12">
    <div class="oer-rsrclftcntr col-md-6 col-sm-12 col-xs-12">
        <!--Resource Description-->
        <?php if(!empty($post->post_content)) {?>
            <div class="oer-sngl-rsrc-dscrptn">
                    <h2><?php _e("Description", OER_SLUG) ?></h2>
                    <?php echo $content = apply_filters ("the_content", $post->post_content); ?>
            </div>
        <?php } ?>
        
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
        <div class="oer-rsrcurl oer-cbxl">
                <h4><strong>Original Resource:</strong> <a href="<?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?>" target="_blank" ><?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?></a></h4>
        </div>
        <?php } ?>
    </div>
    <div class="oer-rsrcrghtcntr col-md-6 col-sm-12 col-xs-12">
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
        <div id="" class="oer-authorName oer-cbxl">
        <?php
        $oer_authorname = get_post_meta($post->ID, "oer_authorname", true);
        $oer_authorurl = get_post_meta($post->ID, "oer_authorurl", true);
        $oer_authorname2 = get_post_meta($post->ID, "oer_authorname2", true);
        $oer_authorurl2 = get_post_meta($post->ID, "oer_authorurl2", true);

        if(!empty($oer_authorname) && !empty($oer_authorname2))
        {
                $option_set = false;
                if (get_option('oer_authorname_label'))
                        $option_set = true;
        ?>
            <h4><strong><?php
                if (!$option_set)
                        _e("Creator:", OER_SLUG);
		else
			echo get_option('oer_authorname_label').":";
                 ?>
                </strong>
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
    ?>
    <div id="" class="oer-publisherName oer-cbxl">
        <h4><strong><?php
                if (!$option_set)
                        _e("Publisher:", OER_SLUG);
                else
                        echo get_option('oer_publishername_label').":";
        ?></strong>
        <span><a href="<?php echo esc_url($oer_publisherurl); ?>" target="_blank"><?php echo $oer_publishername; ?></a></span></h4>
    </div>
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
            ?>
            </span></h4>
        </div>
    <?php }?>

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
</div>