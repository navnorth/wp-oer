<?php

function oer_template_display_standards($core_standard, $standards, &$end_html){
    $html = "";
    if ($standards){
        $cnt = count($standards);
	$index = 1;
        foreach($standards as $standard){
            $slug = "resource/standards/".sanitize_title($core_standard->standard_name)."/".sanitize_title($parent_substandard['standard_title']);
            if ($cnt>1) {
                $html .= '<ul class="oer-substandards">';
                $html .= '  <li>';
                $html .= '      <a href="'.home_url($slug).'">'.$standard['standard_title'].'</a>';
                $html .= '  </li>';
                $end_html .= '</ul>';
            } else {
                $html .= '<li>';
                $html .= '<a href="'.home_url($slug).'">'.$substandard['standard_title'].'</a>';
                $html .= '</li>';
            }
            $index++;
        } 
        $end_html .= '</li>';
    }
    echo $html;
}
function display_custom_styles(){
    ?>
    <style type="text/css">
        .substandards-template #content ul.oer-substandards > li:not(:active),
        .standards-template #content ul.oer-standards > li,
        .substandards-template #content ul.oer-notations > li,
        .notation-template #content ul.oer-subnotations > li { background:url(<?php echo OER_URL."/images/arrow-right.png"; ?>) no-repeat top left; padding-left:28px; }
    </style>
    <?php
}
?>