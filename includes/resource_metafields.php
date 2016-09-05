<?php
global $post;
global $wpdb;
?>
<div class="oer_metawpr">
	<div class="oer_metainrwpr">

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Resource URL:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_resourceurl = get_post_meta($post->ID, 'oer_resourceurl', true);?>
                <input type="text" name="oer_resourceurl" value="<?php echo $oer_resourceurl;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Highlight:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_highlight = get_post_meta($post->ID, 'oer_highlight', true);?>
                <label for="oer_rsurltrue"><?php _e("True", OER_SLUG); ?></label><input id="oer_rsurltrue" type="radio" value="1" name="oer_highlight" <?php if($oer_highlight == '1'){echo 'checked="checked"';}?> />
                <label for="oer_rsurlfalse"><?php _e("False", OER_SLUG); ?></label><input id="oer_rsurlfalse" type="radio" value="0" name="oer_highlight" <?php if($oer_highlight == '0' || $oer_highlight == ''){echo 'checked="checked"';}?> />
            </div>
        </div>

       <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Grade:", OER_SLUG); ?>
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

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Date Created:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_datecreated= get_post_meta($post->ID, 'oer_datecreated', true);?>
                <input type="text" name="oer_datecreated" value="<?php echo $oer_datecreated;?>" class="oer_datepicker"/>
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Date Modified:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_datemodified= get_post_meta($post->ID, 'oer_datemodified', true);?>
                <input type="text" name="oer_datemodified" value="<?php echo $oer_datemodified;?>" class="oer_datepicker"/>
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Media Type:", OER_SLUG); ?>
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

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Learning Resource Type:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_lrtype = strtolower(get_post_meta($post->ID, 'oer_lrtype', true)); ?>
                <select name="oer_lrtype">
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

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Interactivity:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_interactivity = strtolower(get_post_meta($post->ID, 'oer_interactivity', true)); ?>
                <select name="oer_interactivity">
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

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Use Rights URL:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_userightsurl = get_post_meta($post->ID, 'oer_userightsurl', true);?>
                <input type="text" name="oer_userightsurl" value="<?php echo $oer_userightsurl;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Is based on URL:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_isbasedonurl = get_post_meta($post->ID, 'oer_isbasedonurl', true);?>
                <input type="text" name="oer_isbasedonurl" value="<?php echo $oer_isbasedonurl;?>" />
            </div>
        </div>

		<div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Standards Alignment:", OER_SLUG); ?>
            </div>
           	<?php
				$oer_standard_alignment = get_post_meta($post->ID, 'oer_standard_alignment', true);
			 	$oer_standard = get_post_meta($post->ID, 'oer_standard', true);
			 ?>
			 <div class="oer_fld">
				<div class="oer_lstofstandrd ">
				 	  <?php
							$results = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "core_standards",ARRAY_A);
							foreach($results as $result)
							{
								$value = 'core_standards-'.$result['id'];
								echo "<li class='oer_sbstndard main'>
										<div class='stndrd_ttl'>
											<img src='".OER_URL."images/closed_arrow.png' data-pluginpath='".OER_URL."' class='tglimg' />
											<input type='checkbox' ".$chck." name='oer_standard[]' value='".$value."' onclick='oer_check_all(this)' >
											".$result['standard_name']."
										</div><div class='stndrd_desc'></div>";

										get_sub_standard($value, $oer_standard);
								echo "</li>";
							}
						?>
                   <script src="<?php echo OER_URL;?>/js/extrnl_script.js" type="text/javascript"></script>
				 </div>
            </div>

        </div>

        <div class="oer_snglfld oer_hdngsngl">
		<?php _e("Author Information:", OER_SLUG); ?>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Type:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_authortype = strtolower(get_post_meta($post->ID, 'oer_authortype', true));?>
                <select name="oer_authortype">
                	<option value="person" <?php if($oer_authortype == 'person'){echo 'selected="selected"';}?>><?php _e("Person", OER_SLUG); ?></option>
                    <option value="organization" <?php if($oer_authortype == 'organization'){echo 'selected="selected"';}?>><?php _e("Organization", OER_SLUG); ?></option>
                </select>
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Name:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_authorname = get_post_meta($post->ID, 'oer_authorname', true);?>
                <input type="text" name="oer_authorname" value="<?php echo $oer_authorname;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("URL:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_authorurl = get_post_meta($post->ID, 'oer_authorurl', true);?>
                <input type="text" name="oer_authorurl" value="<?php echo $oer_authorurl;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Email Address:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_authoremail = get_post_meta($post->ID, 'oer_authoremail', true);?>
                <input type="text" name="oer_authoremail" value="<?php echo $oer_authoremail;?>" />
            </div>
        </div>

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
                	<img src="<?php echo OER_URL.'/images/close.png'?>" />
                </div>

                <div class="oer_snglfld oer_hdngsngl">
			<?php _e("Author Information:", OER_SLUG); ?>
                </div>

                <div class="oer_snglfld">
                    <div class="oer_txt">
			<?php _e("Type:", OER_SLUG); ?>
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authortype2 = get_post_meta($post->ID, 'oer_authortype2', true);?>
                        <select name="oer_authortype2">
                            <option value="person" <?php if($oer_authortype2 == 'person'){echo 'selected="selected"';}?>><?php _e("Person", OER_SLUG); ?></option>
                            <option value="organization" <?php if($oer_authortype2 == 'organization'){echo 'selected="selected"';}?>><?php _e("Organization", OER_SLUG); ?></option>
                        </select>
                    </div>
                </div>

                <div class="oer_snglfld">
                    <div class="oer_txt">
			<?php _e("Name:", OER_SLUG); ?>
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authorname2 = get_post_meta($post->ID, 'oer_authorname2', true);?>
                        <input type="text" name="oer_authorname2" value="<?php echo $oer_authorname2;?>" />
                    </div>
                </div>

                <div class="oer_snglfld">
                    <div class="oer_txt">
			<?php _e("URL:", OER_SLUG); ?>
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authorurl2 = get_post_meta($post->ID, 'oer_authorurl2', true);?>
                        <input type="text" name="oer_authorurl2" value="<?php echo $oer_authorurl2;?>" />
                    </div>
                </div>

                <div class="oer_snglfld">
                    <div class="oer_txt">
			<?php _e("Email Address:", OER_SLUG); ?>
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authoremail2 = get_post_meta($post->ID, 'oer_authoremail2', true);?>
                        <input type="text" name="oer_authoremail2" value="<?php echo $oer_authoremail2;?>" />
                    </div>
                </div>
            </div>
        <?php
		}
		else
		{
		?>
        	<div class="oer_snglfld oer_hdngsngl">
                <input type="button" class="button button-primary" value="<?php _e("Add Author", OER_SLUG); ?>" onClick="oer_addauthor(this);" data-url="<?php echo OER_URL.'/images/close.png'?>" />
            </div>
        <?php
		}
		?>

        <div class="oer_snglfld oer_hdngsngl">
		<?php _e("Publisher Information:", OER_SLUG); ?>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Name:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_publishername = get_post_meta($post->ID, 'oer_publishername', true);?>
                <input type="text" name="oer_publishername" value="<?php echo $oer_publishername;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("URL:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_publisherurl = get_post_meta($post->ID, 'oer_publisherurl', true);?>
                <input type="text" name="oer_publisherurl" value="<?php echo $oer_publisherurl;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
			<?php _e("Email Address:", OER_SLUG); ?>
            </div>
            <div class="oer_fld">
            	<?php $oer_publisheremail = get_post_meta($post->ID, 'oer_publisheremail', true);?>
                <input type="text" name="oer_publisheremail" value="<?php echo $oer_publisheremail;?>" />
            </div>
        </div>

    </div>
</div>
<div class="clear"></div>
