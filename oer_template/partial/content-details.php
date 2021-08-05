<?php
if (!empty($args)){
    extract($args);
?> 
    <!-- Age Levels -->
    <?php
    if (($age_levels_set && $age_levels_enabled) || !$age_levels_set) {
        $age_label = oer_field_label('oer_age_levels');
        if (!empty($age_levels) && trim($age_levels)!==""){
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
         if (!empty($suggested_time) && trim($suggested_time)!==""){
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
            <img src="<?php echo esc_url(oer_cc_license_image($cc_license)); ?>">
        </div>
        <?php
        }
    }
}
?>