<?php
if (!empty($args)){
	extract($args);
?> 
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
<?php 
}
?>
