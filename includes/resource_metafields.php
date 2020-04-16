<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;
global $wpdb;
global $chck;
?>
<div class="oer_metawpr">
	<div class="oer_metainrwpr">

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Resource URL:", OER_SLUG); ?>
            </div>
		<?php echo wp_nonce_field( 'oer_metabox_action' , 'oer_metabox_nonce_field' ); ?>
            <div class="oer_fld">
            	<?php $oer_resourceurl = get_post_meta($post->ID, 'oer_resourceurl', true);?>
                <input type="text" name="oer_resourceurl" id="oer_resourceurl" value="<?php echo esc_attr($oer_resourceurl);?>" /> <button name="oer_local_resource_button" id="oer_local_resource_button" class="ui-button" alt="Set Local Resource">...</button>
            </div>
        </div>

        <?php
	// Highlight
	$option_set = false;
	if (get_option('oer_highlight_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_highlight_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Highlight:", OER_SLUG);
			else
				echo get_option('oer_highlight_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_highlight = get_post_meta($post->ID, 'oer_highlight', true);?>
                <label for="oer_rsurltrue"><?php _e("True", OER_SLUG); ?></label><input id="oer_rsurltrue" type="radio" value="1" name="oer_highlight" <?php if($oer_highlight == '1'){echo 'checked="checked"';}?> />
                <label for="oer_rsurlfalse"><?php _e("False", OER_SLUG); ?></label><input id="oer_rsurlfalse" type="radio" value="0" name="oer_highlight" <?php if($oer_highlight == '0' || $oer_highlight == ''){echo 'checked="checked"';}?> />
            </div>
        </div>
	<?php } ?>

	<?php
	// Grade Level
	$option_set = false;
	if (get_option('oer_grade_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_grade_enabled')) || !$option_set) {
	?>
	<div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Grade:", OER_SLUG);
			else
				echo get_option('oer_grade_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php
					$oer_grade = get_post_meta($post->ID, 'oer_grade', true);
					$oer_grade = explode("," ,$oer_grade);

					function chck_val($atr , $oer_grade)
					{
						if(in_array($atr,$oer_grade))
						{
							return $chck = 'checked="checked"';
						}
						else
						{
							return $chck = '';
						}
					}
				?>
				<ul class="oer_grade">
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('pre-k',$oer_grade); ?> value="pre-k"> <?php _e("Pre-K", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('k',$oer_grade); ?> value="k">  <?php _e("K (Kindergarten)", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('1',$oer_grade); ?> value="1">  <?php _e("1", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('2',$oer_grade); ?> value="2">  <?php _e("2", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('3',$oer_grade); ?> value="3">  <?php _e("3", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('4',$oer_grade); ?> value="4">  <?php _e("4", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('5',$oer_grade); ?> value="5">  <?php _e("5", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('6',$oer_grade); ?> value="6">  <?php _e("6", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('7',$oer_grade); ?> value="7">  <?php _e("7", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('8',$oer_grade); ?> value="8">  <?php _e("8", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('9',$oer_grade); ?> value="9">  <?php _e("9", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('10',$oer_grade); ?> value="10">  <?php _e("10", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('11',$oer_grade); ?> value="11">  <?php _e("11", OER_SLUG); ?> </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('12',$oer_grade); ?> value="12">  <?php _e("12", OER_SLUG); ?> </li>
			    </ul>

            </div>
        </div>
       <?php } ?>
    
	<?php
	// Age Levels
	$option_set = false;
	if (get_option('oer_age_levels_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_age_levels_enabled')) || !$option_set) {
	?>
	<div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Age Levels:", OER_SLUG);
			else
				echo get_option('oer_age_levels_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_age_levels = get_post_meta($post->ID, 'oer_age_levels', true);?>
                <input type="text" name="oer_age_levels" value="<?php echo $oer_age_levels;?>"/>
            </div>
        </div>
	<?php } ?>
	
	<?php
	// Instructional Time
	$option_set = false;
	if (get_option('oer_instructional_time_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_instructional_time_enabled')) || !$option_set) {
	?>
	<div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Instructional Time:", OER_SLUG);
			else
				echo get_option('oer_instructional_time_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_instructional_time = get_post_meta($post->ID, 'oer_instructional_time', true);?>
                <input type="text" name="oer_instructional_time" value="<?php echo $oer_instructional_time;?>"/>
            </div>
        </div>
	<?php } ?>
	
	<?php
	// Creative Commons License
	$option_set = false;
	if (get_option('oer_creativecommons_license_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_creativecommons_license_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Creative Commons License:", OER_SLUG);
			else
				echo get_option('oer_creativecommons_license_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_creativecommons_license = get_post_meta($post->ID, 'oer_creativecommons_license', true); ?>
                <select name="oer_creativecommons_license">
					<option value=""></option>
					<option value="CC-BY" <?php if($oer_creativecommons_license == 'CC-BY'){echo 'selected="selected"';}?>><?php _e("Attribution CC BY", OER_SLUG); ?></option>
					<option value="CC-BY-SA" <?php if($oer_creativecommons_license == 'CC-BY-SA'){echo 'selected="selected"';}?>><?php _e("Attribution-ShareAlike CC BY-SA", OER_SLUG); ?></option>
					<option value="CC-BY-ND" <?php if($oer_creativecommons_license == 'CC-BY-ND'){echo 'selected="selected"';}?>><?php _e("Attribution-NoDerivs CC BY-ND", OER_SLUG); ?></option>
					<option value="CC-BY-NC" <?php if($oer_creativecommons_license == 'CC-BY-NC'){echo 'selected="selected"';}?>><?php _e("Attribution-NonCommercial CC BY-NC", OER_SLUG); ?></option>
					<option value="CC-BY-NC-SA" <?php if($oer_creativecommons_license == 'CC-BY-NC-SA'){echo 'selected="selected"';}?>><?php _e("Attribution-NonCommercial-ShareAlike CC BY-NC-SA", OER_SLUG); ?></option>
					<option value="CC-BY-NC-ND" <?php if($oer_creativecommons_license == 'CC-BY-NC-ND'){echo 'selected="selected"';}?>><?php _e("Attribution-NonCommercial-NoDerivs CC BY-NC-ND", OER_SLUG); ?></option>
                </select>
            </div>
        </div>
	<?php } ?>
	
	<?php
	// Format
	$option_set = false;
	if (get_option('oer_format_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_format_enabled')) || !$option_set) {
	?>
	<div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Format:", OER_SLUG);
			else
				echo get_option('oer_format_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_format = get_post_meta($post->ID, 'oer_format', true);?>
                <input type="text" name="oer_format" value="<?php echo $oer_format;?>"/>
            </div>
        </div>
	<?php } ?>

	<?php
	// Date Created
	$option_set = false;
	if (get_option('oer_datecreated_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_datecreated_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Date Created:", OER_SLUG);
			else
				echo get_option('oer_datecreated_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_datecreated= get_post_meta($post->ID, 'oer_datecreated', true);?>
                <input type="text" name="oer_datecreated" value="<?php echo $oer_datecreated;?>" class="oer_datepicker"/>
            </div>
        </div>
	<?php } ?>
	
	<?php
	// Date Created Estimate
	$option_set = false;
	if (get_option('oer_datecreated_estimate_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_datecreated_estimate_enabled')) || !$option_set) {
	?>
	<div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Date Created Estimate:", OER_SLUG);
			else
				echo get_option('oer_datecreated_estimate_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_datecreated_estimate = get_post_meta($post->ID, 'oer_datecreated_estimate', true);?>
                <input type="text" name="oer_datecreated_estimate" value="<?php echo $oer_datecreated_estimate;?>" />
            </div>
        </div>
	<?php } ?>

	<?php
	// Date Modified
	$option_set = false;
	if (get_option('oer_datemodified_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_datemodified_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Date Modified:", OER_SLUG);
			else
				echo get_option('oer_datemodified_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_datemodified= get_post_meta($post->ID, 'oer_datemodified', true);?>
                <input type="text" name="oer_datemodified" value="<?php echo $oer_datemodified;?>" class="oer_datepicker"/>
            </div>
        </div>
	<?php } ?>

	<?php
	// Media Type
	$option_set = false;
	if (get_option('oer_mediatype_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_mediatype_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Media Type:", OER_SLUG);
			else
				echo get_option('oer_mediatype_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_mediatype = strtolower(get_post_meta($post->ID, 'oer_mediatype', true)); ?>
                <select name="oer_mediatype">
			<option value="website" <?php if($oer_mediatype == 'website'){echo 'selected="selected"';}?>><?php _e("Website", OER_SLUG); ?></option>
                       <option value="audio" <?php if($oer_mediatype == 'audio'){echo 'selected="selected"';}?>><?php _e("Audio", OER_SLUG); ?></option>
                       <option value="document" <?php if($oer_mediatype == 'document'){echo 'selected="selected"';}?>><?php _e("Document", OER_SLUG); ?></option>
                       <option value="image" <?php if($oer_mediatype == 'image'){echo 'selected="selected"';}?>><?php _e("Image", OER_SLUG); ?></option>
                       <option value="video" <?php if($oer_mediatype == 'video'){echo 'selected="selected"';}?>><?php _e("Video", OER_SLUG); ?></option>
                       <option value="other" <?php if($oer_mediatype == 'other'){echo 'selected="selected"';}?>><?php _e("Other", OER_SLUG); ?></option>
                </select>
            </div>
        </div>
	<?php } ?>

	<?php
	// Learning Resource Type
	$option_set = false;
	if (get_option('oer_lrtype_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_lrtype_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Learning Resource Type:", OER_SLUG);
			else
				echo get_option('oer_lrtype_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_lrtype = strtolower(get_post_meta($post->ID, 'oer_lrtype', true)); ?>
                <select name="oer_lrtype">
					<option value=""></option>
					<option value="article" <?php if($oer_lrtype == 'article'){echo 'selected="selected"';}?>><?php _e("Article/Information", OER_SLUG); ?></option>
					<option value="website" <?php if($oer_lrtype == 'website'){echo 'selected="selected"';}?>><?php _e("Assessment", OER_SLUG); ?></option>
					<option value="audio" <?php if($oer_lrtype == 'audio'){echo 'selected="selected"';}?>><?php _e("Audio", OER_SLUG); ?></option>
					<option value="calculator" <?php if($oer_lrtype == 'calculator'){echo 'selected="selected"';}?>><?php _e("Calculator", OER_SLUG); ?></option>
					<option value="demonstration" <?php if($oer_lrtype == 'demonstration'){echo 'selected="selected"';}?>><?php _e("Demonstration", OER_SLUG); ?></option>
					<option value="game" <?php if($oer_lrtype == 'game'){echo 'selected="selected"';}?>><?php _e("Game", OER_SLUG); ?></option>
					<option value="interview" <?php if($oer_lrtype == 'interview'){echo 'selected="selected"';}?>><?php _e("Interview", OER_SLUG); ?></option>
					<option value="lecture" <?php if($oer_lrtype == 'lecture'){echo 'selected="selected"';}?>><?php _e("Lecture", OER_SLUG); ?></option>
					<option value="lesson plan" <?php if($oer_lrtype == 'lesson plan'){echo 'selected="selected"';}?>><?php _e("Lesson Plan", OER_SLUG); ?></option>
					<option value="simulation" <?php if($oer_lrtype == 'simulation'){echo 'selected="selected"';}?>><?php _e("Simulation", OER_SLUG); ?></option>
					<option value="presentation" <?php if($oer_lrtype == 'presentation'){echo 'selected="selected"';}?>><?php _e("Presentation", OER_SLUG); ?></option>
					<option value="other" <?php if($oer_lrtype == 'other'){echo 'selected="selected"';}?>><?php _e("Learning Resource Type:", OER_SLUG); ?>Other</option>
                </select>
            </div>
        </div>
	<?php } ?>

	<?php
	// Interactivity
	$option_set = false;
	if (get_option('oer_interactivity_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_interactivity_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Interactivity:", OER_SLUG);
			else
				echo get_option('oer_interactivity_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_interactivity = strtolower(get_post_meta($post->ID, 'oer_interactivity', true)); ?>
                <select name="oer_interactivity">
			<option value=""></option>
			<option value="interactive" <?php if($oer_interactivity == 'interactive'){echo 'selected="selected"';}?>><?php _e("Interactive", OER_SLUG); ?></option>
                       <option value="passive" <?php if($oer_interactivity == 'passive'){echo 'selected="selected"';}?>><?php _e("Passive", OER_SLUG); ?></option>
                       <option value="social" <?php if($oer_interactivity == 'social'){echo 'selected="selected"';}?>><?php _e("Social", OER_SLUG); ?></option>
                       <option value="prgorammatic" <?php if($oer_interactivity == 'prgorammatic'){echo 'selected="selected"';}?>><?php _e("Programmatic", OER_SLUG); ?></option>
                       <option value="one-on-one" <?php if($oer_interactivity == 'one-on-one'){echo 'selected="selected"';}?>><?php _e("One-on-One", OER_SLUG); ?></option>
                       <option value="async" <?php if($oer_interactivity == 'async'){echo 'selected="selected"';}?>><?php _e("Async", OER_SLUG); ?></option>
                       <option value="sync" <?php if($oer_interactivity == 'sync'){echo 'selected="selected"';}?>><?php _e("Sync", OER_SLUG); ?></option>
                       <option value="group" <?php if($oer_interactivity == 'group'){echo 'selected="selected"';}?>><?php _e("Group", OER_SLUG); ?></option>
                </select>
            </div>
        </div>
	<?php } ?>

	<?php
	// User Rights URL
	$option_set = false;
	if (get_option('oer_userightsurl_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_userightsurl_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Use Rights URL:", OER_SLUG);
			else
				echo get_option('oer_userightsurl_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_userightsurl = get_post_meta($post->ID, 'oer_userightsurl', true);?>
                <input type="text" name="oer_userightsurl" value="<?php echo esc_attr($oer_userightsurl);?>" />
            </div>
        </div>
	<?php } ?>

	<?php
	// Is Based on URL
	$option_set = false;
	if (get_option('oer_isbasedonurl_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_isbasedonurl_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Is based on URL:", OER_SLUG);
			else
				echo get_option('oer_isbasedonurl_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_isbasedonurl = get_post_meta($post->ID, 'oer_isbasedonurl', true);?>
                <input type="text" name="oer_isbasedonurl" value="<?php echo esc_attr($oer_isbasedonurl);?>" />
            </div>
        </div>
	<?php } ?>
	
	<?php
	// Standard
	if (oer_installed_standards_plugin()){
		$option_set = false;
		if (get_option('oer_standard_label')){
			$option_set = true;
		}
		if (($option_set && get_option('oer_standard_enabled')) || !$option_set) {
		?>
		<div class="oer_snglfld">
			<div class="oer_txt">
				<?php
				if (!$option_set)
					_e("Standards:", OER_SLUG);
				else
					echo get_option('oer_standard_label').":";
				?>
			</div>
			<div class="oer_fld auto-width">
				<?php
				$oStandard = get_post_meta($post->ID, 'oer_standard', true);
				$standards = explode(",", $oStandard);
				foreach($standards as $standard){
					if ($standard!=="") {
						$std_name = oer_get_standard_label($standard);
						echo "<span class='standard-label'>".$std_name."<a href='javascript:void(0)' class='remove-standard' data-id='".$standard."'><span class='dashicons dashicons-no-alt'></span></a></span>";
					}
				}
				?>
				<input type="hidden" name="oer_standard" value="<?php echo $oStandard; ?>" />
				<button id="add-new-standard" data-toggle="modal" class="ui-button components-button is-button is-default">Add Standards</button>
			</div>
	
		</div>
	<?php }
	} ?>
	
        <div class="oer_snglfld oer_hdngsngl">
		<?php _e("Author Information:", OER_SLUG); ?>
        </div>

        <?php
	// Author Type
	$option_set = false;
	if (get_option('oer_authortype_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_authortype_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Type:", OER_SLUG);
			else
				echo get_option('oer_authortype_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_authortype = strtolower(get_post_meta($post->ID, 'oer_authortype', true));?>
                <select name="oer_authortype">
                	<option value="person" <?php if($oer_authortype == 'person'){echo 'selected="selected"';}?>><?php _e("Person", OER_SLUG); ?></option>
                    <option value="organization" <?php if($oer_authortype == 'organization'){echo 'selected="selected"';}?>><?php _e("Organization", OER_SLUG); ?></option>
                </select>
            </div>
        </div>
	<?php } ?>

	<?php
	// Author Name
	$option_set = false;
	if (get_option('oer_authorname_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_authorname_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Name:", OER_SLUG);
			else
				echo get_option('oer_authorname_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_authorname = get_post_meta($post->ID, 'oer_authorname', true);?>
                <input type="text" name="oer_authorname" value="<?php echo esc_attr($oer_authorname);?>" />
            </div>
        </div>
	<?php } ?>

	<?php
	// Author URL
	$option_set = false;
	if (get_option('oer_authorurl_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_authorurl_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("URL:", OER_SLUG);
			else
				echo get_option('oer_authorurl_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_authorurl = get_post_meta($post->ID, 'oer_authorurl', true);?>
                <input type="text" name="oer_authorurl" value="<?php echo esc_attr($oer_authorurl);?>" />
            </div>
        </div>
	<?php } ?>

	<?php
	// Author Email Address
	$option_set = false;
	if (get_option('oer_authoremail_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_authoremail_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Email Address:", OER_SLUG);
			else
				echo get_option('oer_authoremail_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_authoremail = get_post_meta($post->ID, 'oer_authoremail', true);?>
                <input type="text" name="oer_authoremail" value="<?php echo esc_attr($oer_authoremail);?>" />
            </div>
        </div>
	<?php } ?>

        <?php
		$oer_authortype2 = get_post_meta($post->ID, 'oer_authortype2', true);
		$oer_authorname2 = get_post_meta($post->ID, 'oer_authorname2', true);
		$oer_authorurl2 = get_post_meta($post->ID, 'oer_authorurl2', true);
		$oer_authoremail2 = get_post_meta($post->ID, 'oer_authoremail2', true);
		if(!empty($oer_authorname2) || !empty($oer_authorurl2) || !empty($oer_authoremail2))
		{
		?>
        	<div class="oer_authrcntr">
            	<div class="oer_cls" onClick="oer_removeauthor(this);">
                	<img src="<?php echo esc_url(OER_URL.'/images/close.png') ?>" />
                </div>

                <div class="oer_snglfld oer_hdngsngl">
			<?php _e("Author Information:", OER_SLUG); ?>
                </div>

                <?php
		// Second Author Type
		$option_set = false;
		if (get_option('oer_authortype_label')){
			$option_set = true;
		}
		if (($option_set && get_option('oer_authortype_enabled')) || !$option_set) {
		?>
                <div class="oer_snglfld">
                    <div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Type:", OER_SLUG);
			else
				echo get_option('oer_authortype_label').":";
			?>
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authortype2 = get_post_meta($post->ID, 'oer_authortype2', true);?>
                        <select name="oer_authortype2">
                            <option value="person" <?php if($oer_authortype2 == 'person'){echo 'selected="selected"';}?>><?php _e("Person", OER_SLUG); ?></option>
                            <option value="organization" <?php if($oer_authortype2 == 'organization'){echo 'selected="selected"';}?>><?php _e("Organization", OER_SLUG); ?></option>
                        </select>
                    </div>
                </div>
		<?php } ?>

		<?php
		// Second Author Name
		$option_set = false;
		if (get_option('oer_authorname_label')){
			$option_set = true;
		}
		if (($option_set && get_option('oer_authorname_enabled')) || !$option_set) {
		?>
                <div class="oer_snglfld">
                    <div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Name:", OER_SLUG);
			else
				echo get_option('oer_authorname_label').":";
			?>
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authorname2 = get_post_meta($post->ID, 'oer_authorname2', true);?>
                        <input type="text" name="oer_authorname2" value="<?php echo esc_attr($oer_authorname2);?>" />
                    </div>
                </div>
		<?php } ?>

		<?php
		// Second Author URL
		$option_set = false;
		if (get_option('oer_authorurl_label')){
			$option_set = true;
		}
		if (($option_set && get_option('oer_authorurl_enabled')) || !$option_set) {
		?>
                <div class="oer_snglfld">
                    <div class="oer_txt">
			<?php
			if (!$option_set)
				_e("URL:", OER_SLUG);
			else
				echo get_option('oer_authorurl_label').":";
			?>
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authorurl2 = get_post_meta($post->ID, 'oer_authorurl2', true);?>
                        <input type="text" name="oer_authorurl2" value="<?php echo esc_attr($oer_authorurl2);?>" />
                    </div>
                </div>
		<?php } ?>

		<?php
		// Second Author Email Address
		$option_set = false;
		if (get_option('oer_authoremail_label')){
			$option_set = true;
		}
		if (($option_set && get_option('oer_authoremail_enabled')) || !$option_set) {
		?>
                <div class="oer_snglfld">
                    <div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Email Address:", OER_SLUG);
			else
				echo get_option('oer_authoremail_label').":";
			?>
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authoremail2 = get_post_meta($post->ID, 'oer_authoremail2', true);?>
                        <input type="text" name="oer_authoremail2" value="<?php echo esc_attr($oer_authoremail2);?>" />
                    </div>
                </div>
		<?php } ?>
            </div>
        <?php
		}
		else
		{
			$authorurl_label = "";
			$authorname_label = "";
			$authortype_label = "";
			$authoremail_label = "";
			if (get_option('oer_authortype_label'))
				$authortype_label = 'data-authortype-label="'.get_option('oer_authortype_label').'"';;
			if (get_option('oer_authorurl_label'))
				$authorurl_label = 'data-authorurl-label="'.get_option('oer_authorurl_label').'"';
			if (get_option('oer_authorname_label'))
				$authorname_label = 'data-authorname-label="'.get_option('oer_authorname_label').'"';;
			if (get_option('oer_authoremail_label'))
				$authoremail_label = 'data-authoremail-label="'.get_option('oer_authoremail_label').'"';;
		?>
        	<div class="oer_snglfld oer_hdngsngl">
                <input type="button" class="button button-primary" value="<?php _e("Add Author", OER_SLUG); ?>" onClick="oer_addauthor(this);" <?php echo $authorurl_label; ?> <?php echo $authorname_label; ?> <?php echo $authortype_label; ?> <?php echo $authoremail_label; ?> data-url="<?php echo OER_URL.'/images/close.png'?>" />
            </div>
        <?php
		}
		?>

        <div class="oer_snglfld oer_hdngsngl">
		<?php _e("Publisher Information:", OER_SLUG); ?>
        </div>

        <?php
	// Publisher Name
	$option_set = false;
	if (get_option('oer_publishername_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_publishername_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Name:", OER_SLUG);
			else
				echo get_option('oer_publishername_label').":";
			?>
		</div>
		<div class="oer_fld">
		    <?php $oer_publishername = get_post_meta($post->ID, 'oer_publishername', true);?>
		    <input type="text" name="oer_publishername" value="<?php echo esc_attr($oer_publishername);?>" />
		</div>
        </div>
	<?php } ?>

	<?php
	// Publisher URL
	$option_set = false;
	if (get_option('oer_publisherurl_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_publisherurl_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("URL:", OER_SLUG);
			else
				echo get_option('oer_publisherurl_label').":";
			?>
		</div>
		<div class="oer_fld">
		    <?php $oer_publisherurl = get_post_meta($post->ID, 'oer_publisherurl', true);?>
		    <input type="text" name="oer_publisherurl" value="<?php echo esc_attr($oer_publisherurl);?>" />
		</div>
        </div>
	<?php } ?>

	<?php
	// Publisher Email Address
	$option_set = false;
	if (get_option('oer_publisheremail_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_publisheremail_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Email Address:", OER_SLUG);
			else
				echo get_option('oer_publisheremail_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php $oer_publisheremail = get_post_meta($post->ID, 'oer_publisheremail', true);?>
                <input type="text" name="oer_publisheremail" value="<?php echo esc_attr($oer_publisheremail);?>" />
            </div>
        </div>
	<?php } ?>
	
	<div class="oer_snglfld oer_hdngsngl">
		<?php _e("Repository Information:", OER_SLUG); ?>
        </div>

        <?php
	// External Repository
	$option_set = false;
	if (get_option('oer_external_repository_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_external_repository_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("External Repository:", OER_SLUG);
			else
				echo get_option('oer_external_repository_label').":";
			?>
		</div>
		<div class="oer_fld">
		    <?php $oer_external_repository = get_post_meta($post->ID, 'oer_external_repository', true);?>
		    <input type="text" name="oer_external_repository" value="<?php echo esc_attr($oer_external_repository);?>" />
		</div>
        </div>
	<?php } ?>

	<?php
	// Repository Record URL
	$option_set = false;
	if (get_option('oer_repository_recordurl_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_repository_recordurl_enabled')) || !$option_set) {
	?>
        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Repository Record URL:", OER_SLUG);
			else
				echo get_option('oer_repository_recordurl_label').":";
			?>
		</div>
		<div class="oer_fld">
		    <?php $oer_repository_recordurl = get_post_meta($post->ID, 'oer_repository_recordurl', true);?>
		    <input type="text" name="oer_repository_recordurl" value="<?php echo esc_attr($oer_repository_recordurl);?>" />
		</div>
        </div>
	<?php } ?>

	<?php
	// Citation
	$option_set = false;
	if (get_option('oer_citation_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_citation_enabled')) || !$option_set) {
	?>
	<div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Citation:", OER_SLUG);
			else
				echo get_option('oer_citation_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php 	$oer_citation = get_post_meta($post->ID, 'oer_citation', true);
			wp_editor( $oer_citation, 'oer_citation', array( "wpautop" => true, "media_buttons"  => true, 'textarea_name' => 'oer_citation', 'textarea_rows' => 10,  "tinymce" => true, 'teeny' => true ) ); ?>
            </div>
        </div><!-- Citation Section -->
	<?php } ?>
	
	<?php
	// Sensitive Material Warning
	$option_set = false;
	if (get_option('oer_sensitive_material_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_sensitive_material_enabled')) || !$option_set) {
	?>
	<div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Sensitive Material Warning:", OER_SLUG);
			else
				echo get_option('oer_sensitive_material_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php 	$oer_sensitive_material = get_post_meta($post->ID, 'oer_sensitive_material', true);
			wp_editor( $oer_sensitive_material, 'oer_sensitive_material', array( "wpautop" => false, "media_buttons"  => true, "tinymce" => true, 'teeny' => true ) ); ?>
            </div>
        </div><!-- Sensitive Material Warning field -->
	<?php } ?>
	
	<?php
	// Transcription
	$option_set = false;
	if (get_option('oer_transcription_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_transcription_enabled')) || !$option_set) {
	?>
	<div class="oer_snglfld">
        	<div class="oer_txt">
			<?php
			if (!$option_set)
				_e("Transcription:", OER_SLUG);
			else
				echo get_option('oer_transcription_label').":";
			?>
            </div>
            <div class="oer_fld">
            	<?php 	$oer_transcription = get_post_meta($post->ID, 'oer_transcription', true);
			wp_editor( $oer_transcription, 'oer_transcription', array( "wpautop" => false, "media_buttons"  => true, "tinymce" => true, 'teeny' => true ) ); ?>
            </div>
        </div><!-- Transcription field -->
	<?php } ?>

	<?php
	$option_set = false;
	if (get_option('oer_related_resource_label')){
		$option_set = true;
	}
	if (($option_set && get_option('oer_related_resource_enabled')) || !$option_set) {
	?>
			<div class="oer_snglfld oer_hdngsngl">Related Resources:</div>
			<div class="oer_snglfld">
				<div class="oer_txt">
					<?php
					if (!$option_set)
						_e("Related Resources:", OER_SLUG);
					else
						echo get_option('oer_related_resource_label').":";
					?>
				</div>
				<div class="oer_fld">
						<div class="form-group">
								<div class="oer_fld auto-width oer_related_resource_display">
									<?php
										$oer_related_resource = get_post_meta($post->ID, 'oer_related_resource', true);
										if(!empty($oer_related_resource)){
											$_tmps = explode(",",$oer_related_resource);
											foreach ($_tmps as $_tmp){
												$_res = get_post($_tmp);
												?>
												<span class="standard-label"><?php echo $_res->post_title; ?><a href="javascript:void(0)" class="remove-related_resource" data-id="<?php echo $_res->ID; ?>"><span class="dashicons dashicons-no-alt"></span></a></span>
												<?php
											}
										}
									?>
								</div>
								<input type="hidden" name="oer_related_resource" value="<?php echo $oer_related_resource ?>"/>
								<button id="add-new-related-resource" data-toggle="modal" class="ui-button components-button is-button is-default">Add Related Resources</button>
						</div>
				</div>
			</div>
	  <?php } ?>
		
    </div>
</div>

<div class="clear"></div>
