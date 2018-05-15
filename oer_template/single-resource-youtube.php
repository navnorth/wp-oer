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
        
        
        <div class="oer-rsrcurl oer-cbxl"><h4><strong>Original Resource:</strong> <a href="<?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?>" target="_blank" ><?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?></a></h4></div>
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

        if(!empty($oer_authorname) && !empty($oer_authorurl))
        {
        ?>
            <h4><strong><?php _e("Creator:", OER_SLUG) ?></strong>
            <span><a href="<?php echo esc_url($oer_authorurl); ?>" target="_blank"><?php echo $oer_authorname; ?></a></span></h4>
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
        <span><a href="<?php echo esc_url($oer_publisherurl); ?>" target="_blank"><?php echo $oer_publishername; ?></a></span></h4>
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
                                            echo "<span><a href='".esc_url(get_tag_link($keyword->term_id))."' class='button'>".ucwords($keyword->name)."</a></span>";
                                    }
                            ?>
                            </div>
                    </div>
    <?php } ?>
    </div>
</div>