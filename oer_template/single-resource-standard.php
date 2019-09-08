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
</div>
<div class="oer-rsrcrghtcntr col-md-7 col-sm-12 col-xs-12">
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

    <!--Resource Description-->
    <?php if(!empty($post->post_content)) {?>
        <div class="oer-sngl-rsrc-dscrptn">
                <h2><?php _e("Description", OER_SLUG) ?></h2>
                <?php echo $content = apply_filters ("the_content", $post->post_content); ?>
        </div>
    <?php } ?>
    
    <?php if ($youtube) { ?>
        <div class="oer-rsrcurl oer-cbxl"><h4><strong>Original Resource:</strong> <a href="<?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?>" target="_blank" ><?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?></a></h4></div>
    <?php } else { ?>
        <div class="oer-rsrcurl oer-cbxl"><h4><strong>Original Resource:</strong> <a href="<?php echo esc_url(get_post_meta($post->ID, "oer_resourceurl", true)); ?>" target="_blank" ><?php echo $url_domain; ?></a></h4></div>
    <?php } ?>

    <div id="" class="oer-authorName oer-cbxl">
        <?php
        $oer_authorname = get_post_meta($post->ID, "oer_authorname", true);
        $oer_authorurl = get_post_meta($post->ID, "oer_authorurl", true);
        $oer_authorname2 = get_post_meta($post->ID, "oer_authorname2", true);
        $oer_authorurl2 = get_post_meta($post->ID, "oer_authorurl2", true);

        if(!empty($oer_authorname) && !empty($oer_authorurl))
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
            <span><a href="<?php echo esc_url($oer_authorurl); ?>" target="_blank"><?php echo $oer_authorname; ?></a></span>
            <?php if ($oer_authorname2): echo ", "; ?>
            <span><a href="<?php echo esc_url($oer_authorurl2); ?>" target="_blank"><?php echo $oer_authorname2; ?></a></span>
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
<div class="oer-rsrclftcntr col-md-12">

                    <?php

                            $stdrd_id = get_post_meta($post->ID, 'oer_standard_alignment', true);
                            $oer_standard = get_post_meta($post->ID, 'oer_standard', true);
                            
                            $standards = explode(",", $oer_standard);
                            $oer_standards = array();
                            
                            foreach ($standards as $standard) {
                                    if ($standard!=""){
                                            $stds = oer_get_parent_standard($standard);
                                            foreach($stds as $std){
                                                    $core_std = oer_get_core_standard($std['parent_id']);
                                                    $oer_standards[] = array(
                                                                            'id' => $standard,
                                                                            'core_id' => $core_std[0]['id'],
                                                                            'core_title' => $core_std[0]['standard_name']
                                                                             );
                                            }
                                    }
                            }
                            
                            foreach ($oer_standards as $key => $row) {
                                    $core[$key]  = $row['core_id'];
                            }
                            
                            if (!empty($oer_standards))
                                    array_multisort($core, SORT_ASC, $oer_standards);
                            
                            if(!empty($stdrd_id) || !empty($oer_standards))
                            {
                                $option_set = false;
                                if (get_option('oer_standard_label'))
                                        $option_set = true;
                    ?>
            <div class="alignedStandards">
            <h2><?php
            if (!$option_set)
		_e("Standards Alignment", OER_SLUG);
	    else
                echo get_option('oer_standard_label').":";
            ?></h2>
            <div class="oer_meta_container">
                <!--<div class="oer_stndrd_align">-->
                <?php
                    if(!empty($stdrd_id))
                    {
                                ?>
                                       <!--<h3><?php _e("Standard Alignment", OER_SLUG) ?></h3>-->
                                       <?php
                         //$res = $wpdb->get_row( $wpdb->prepare( "select standard_name from ".$wpdb->prefix."oer_core_standards where id=%d" , $stdrd_id ), ARRAY_A);
                         //echo "<div class='stndrd_ttl'>".$res['standard_name']."</div>";
                    }
                ?>

                <!--</div>-->

                <div class="oer_stndrds_notn">
                <?php
                    if(!empty($oer_standards))
                    {
                    ?>
                            <?php
                            $displayed_core_standards = array();
                            foreach($oer_standards as $o_standard) {
                                    
                                    if (!in_array($o_standard['core_id'],$displayed_core_standards)){
                                            echo "<div class='oer-core-title'><h4><strong>".$o_standard['core_title']."</strong></h4></div>";
                                            $displayed_core_standards[] = $o_standard['core_id'];
                                    }
                                    
                                    $oer_standard =$o_standard['id'];
                                    $stnd_arr = explode(",", $oer_standard);
                                    
                                    for($i=0; $i< count($stnd_arr); $i++)
                                    {
                                        $table = explode("-",$stnd_arr[$i]);
                                        
                                        $table_name = $wpdb->prefix.$_oer_prefix.$table[0];
                                        
                                        $id = $table[1];
                                        
                                        $res = $wpdb->get_row( $wpdb->prepare("select * from $table_name where id=%d" , $id ), ARRAY_A);
                                        
                                        echo "<div class='oer_sngl_stndrd'>";
                                            if (strpos($table_name,"sub_standards")>0) {
                                                    echo "<span class='oer_sngl_description'>".stripslashes($res['standard_title'])."</span>";
                                            } else {
                                                    echo "<span class='oer_sngl_notation'>".$res['standard_notation']."</span>";
                                                    echo "<span class='oer_sngl_description'>".stripslashes($res['description'])."</span>";
                                            }
                                        echo "</div>";
                                    }
                            }
                    }
                ?>
                </div>

            </div>
       </div>
                    <?php } ?>
</div> <!--Thumbnail & Standards Info at Left-->