<?php
global $post;
global $wpdb;
?>
<div class="oer_metawpr">
	<div class="oer_metainrwpr">

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Resource URL:
            </div>
            <div class="oer_fld">
            	<?php $oer_resourceurl = get_post_meta($post->ID, 'oer_resourceurl', true);?>
                <input type="text" name="oer_resourceurl" value="<?php echo $oer_resourceurl;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Highlight:
            </div>
            <div class="oer_fld">
            	<?php $oer_highlight = get_post_meta($post->ID, 'oer_highlight', true);?>
                <label for="oer_rsurltrue">True</label><input id="oer_rsurltrue" type="radio" value="1" name="oer_highlight" <?php if($oer_highlight == '1'){echo 'checked="checked"';}?> />
                <label for="oer_rsurlfalse">False</label><input id="oer_rsurlfalse" type="radio" value="0" name="oer_highlight" <?php if($oer_highlight == '0' || $oer_highlight == ''){echo 'checked="checked"';}?> />
            </div>
        </div>

       <div class="oer_snglfld">
        	<div class="oer_txt">
            	Grade:
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
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('pre-k',$oer_grade); ?> value="pre-k">  Pre-K </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('k',$oer_grade); ?> value="k">  K (Kindergarten) </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('1',$oer_grade); ?> value="1">  1 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('2',$oer_grade); ?> value="2">  2 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('3',$oer_grade); ?> value="3">  3 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('4',$oer_grade); ?> value="4">  4 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('5',$oer_grade); ?> value="5">  5 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('6',$oer_grade); ?> value="6">  6 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('7',$oer_grade); ?> value="7">  7 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('8',$oer_grade); ?> value="8">  8 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('9',$oer_grade); ?> value="9">  9 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('10',$oer_grade); ?> value="10">  10 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('11',$oer_grade); ?> value="11">  11 </li>
					<li><input type="checkbox" name="oer_grade[]" <?php echo chck_val('12',$oer_grade); ?> value="12">  12 </li>
			    </ul>

            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Date Created:
            </div>
            <div class="oer_fld">
            	<?php $oer_datecreated= get_post_meta($post->ID, 'oer_datecreated', true);?>
                <input type="text" name="oer_datecreated" value="<?php echo $oer_datecreated;?>" class="oer_datepicker"/>
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Date Modified:
            </div>
            <div class="oer_fld">
            	<?php $oer_datemodified= get_post_meta($post->ID, 'oer_datemodified', true);?>
                <input type="text" name="oer_datemodified" value="<?php echo $oer_datemodified;?>" class="oer_datepicker"/>
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Media Type:
            </div>
            <div class="oer_fld">
            	<?php $oer_mediatype = strtolower(get_post_meta($post->ID, 'oer_mediatype', true)); ?>
                <select name="oer_mediatype">
					   <option value="website" <?php if($oer_mediatype == 'website'){echo 'selected="selected"';}?>>Website</option>
                       <option value="audio" <?php if($oer_mediatype == 'audio'){echo 'selected="selected"';}?>>Audio</option>
                       <option value="document" <?php if($oer_mediatype == 'document'){echo 'selected="selected"';}?>>Document</option>
                       <option value="image" <?php if($oer_mediatype == 'image'){echo 'selected="selected"';}?>>Image</option>
                       <option value="video" <?php if($oer_mediatype == 'video'){echo 'selected="selected"';}?>>Video</option>
                       <option value="other" <?php if($oer_mediatype == 'other'){echo 'selected="selected"';}?>>Other</option>
                </select>
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Learning Resource Type:
            </div>
            <div class="oer_fld">
            	<?php $oer_lrtype = strtolower(get_post_meta($post->ID, 'oer_lrtype', true)); ?>
                <select name="oer_lrtype">
					   <option value="website" <?php if($oer_lrtype == 'website'){echo 'selected="selected"';}?>>Assessment</option>
                       <option value="audio" <?php if($oer_lrtype == 'audio'){echo 'selected="selected"';}?>>Audio</option>
                       <option value="calculator" <?php if($oer_lrtype == 'calculator'){echo 'selected="selected"';}?>>Calculator</option>
                       <option value="demonstration" <?php if($oer_lrtype == 'demonstration'){echo 'selected="selected"';}?>>Demonstration</option>
                       <option value="game" <?php if($oer_lrtype == 'game'){echo 'selected="selected"';}?>>Game</option>
                       <option value="interview" <?php if($oer_lrtype == 'interview'){echo 'selected="selected"';}?>>Interview</option>
                       <option value="lecture" <?php if($oer_lrtype == 'lecture'){echo 'selected="selected"';}?>>Lecture</option>
                       <option value="lesson plan" <?php if($oer_lrtype == 'lesson plan'){echo 'selected="selected"';}?>>Lesson Plan</option>
                       <option value="simulation" <?php if($oer_lrtype == 'simulation'){echo 'selected="selected"';}?>>Simulation</option>
                       <option value="presentation" <?php if($oer_lrtype == 'presentation'){echo 'selected="selected"';}?>>Presentation</option>
                       <option value="other" <?php if($oer_lrtype == 'other'){echo 'selected="selected"';}?>>Other</option>
                </select>
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Interactivity:
            </div>
            <div class="oer_fld">
            	<?php $oer_interactivity = strtolower(get_post_meta($post->ID, 'oer_interactivity', true)); ?>
                <select name="oer_interactivity">
					   <option value="interactive" <?php if($oer_interactivity == 'interactive'){echo 'selected="selected"';}?>>Interactive</option>
                       <option value="passive" <?php if($oer_interactivity == 'passive'){echo 'selected="selected"';}?>>Passive</option>
                       <option value="social" <?php if($oer_interactivity == 'social'){echo 'selected="selected"';}?>>Social</option>
                       <option value="prgorammatic" <?php if($oer_interactivity == 'prgorammatic'){echo 'selected="selected"';}?>>Prgorammatic</option>
                       <option value="one-on-one" <?php if($oer_interactivity == 'one-on-one'){echo 'selected="selected"';}?>>One-on-One</option>
                       <option value="async" <?php if($oer_interactivity == 'async'){echo 'selected="selected"';}?>>Async</option>
                       <option value="sync" <?php if($oer_interactivity == 'sync'){echo 'selected="selected"';}?>>Sync</option>
                       <option value="group" <?php if($oer_interactivity == 'group'){echo 'selected="selected"';}?>>Group</option>
                </select>
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Use Rights URL:
            </div>
            <div class="oer_fld">
            	<?php $oer_userightsurl = get_post_meta($post->ID, 'oer_userightsurl', true);?>
                <input type="text" name="oer_userightsurl" value="<?php echo $oer_userightsurl;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Is based on URL:
            </div>
            <div class="oer_fld">
            	<?php $oer_isbasedonurl = get_post_meta($post->ID, 'oer_isbasedonurl', true);?>
                <input type="text" name="oer_isbasedonurl" value="<?php echo $oer_isbasedonurl;?>" />
            </div>
        </div>

		<div class="oer_snglfld">
        	<div class="oer_txt">
            	Standards Alignment:
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
        	Author Information:
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Type:
            </div>
            <div class="oer_fld">
            	<?php $oer_authortype = strtolower(get_post_meta($post->ID, 'oer_authortype', true));?>
                <select name="oer_authortype">
                	<option value="person" <?php if($oer_authortype == 'person'){echo 'selected="selected"';}?>>Person</option>
                    <option value="organization" <?php if($oer_authortype == 'organization'){echo 'selected="selected"';}?>>Organization</option>
                </select>
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Name:
            </div>
            <div class="oer_fld">
            	<?php $oer_authorname = get_post_meta($post->ID, 'oer_authorname', true);?>
                <input type="text" name="oer_authorname" value="<?php echo $oer_authorname;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	URL:
            </div>
            <div class="oer_fld">
            	<?php $oer_authorurl = get_post_meta($post->ID, 'oer_authorurl', true);?>
                <input type="text" name="oer_authorurl" value="<?php echo $oer_authorurl;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Email Address:
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
                    Author Information:
                </div>

                <div class="oer_snglfld">
                    <div class="oer_txt">
                        Type:
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authortype2 = get_post_meta($post->ID, 'oer_authortype2', true);?>
                        <select name="oer_authortype2">
                            <option value="person" <?php if($oer_authortype2 == 'person'){echo 'selected="selected"';}?>>Person</option>
                            <option value="organization" <?php if($oer_authortype2 == 'organization'){echo 'selected="selected"';}?>>Organization</option>
                        </select>
                    </div>
                </div>

                <div class="oer_snglfld">
                    <div class="oer_txt">
                        Name:
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authorname2 = get_post_meta($post->ID, 'oer_authorname2', true);?>
                        <input type="text" name="oer_authorname2" value="<?php echo $oer_authorname2;?>" />
                    </div>
                </div>

                <div class="oer_snglfld">
                    <div class="oer_txt">
                        URL:
                    </div>
                    <div class="oer_fld">
                        <?php $oer_authorurl2 = get_post_meta($post->ID, 'oer_authorurl2', true);?>
                        <input type="text" name="oer_authorurl2" value="<?php echo $oer_authorurl2;?>" />
                    </div>
                </div>

                <div class="oer_snglfld">
                    <div class="oer_txt">
                        Email Address:
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
                <input type="button" class="button button-primary" value="Add Author" onClick="oer_addauthor(this);" data-url="<?php echo OER_URL.'/images/close.png'?>" />
            </div>
        <?php
		}
		?>

        <div class="oer_snglfld oer_hdngsngl">
        	Publisher Information:
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Name:
            </div>
            <div class="oer_fld">
            	<?php $oer_publishername = get_post_meta($post->ID, 'oer_publishername', true);?>
                <input type="text" name="oer_publishername" value="<?php echo $oer_publishername;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	URL:
            </div>
            <div class="oer_fld">
            	<?php $oer_publisherurl = get_post_meta($post->ID, 'oer_publisherurl', true);?>
                <input type="text" name="oer_publisherurl" value="<?php echo $oer_publisherurl;?>" />
            </div>
        </div>

        <div class="oer_snglfld">
        	<div class="oer_txt">
            	Email Address:
            </div>
            <div class="oer_fld">
            	<?php $oer_publisheremail = get_post_meta($post->ID, 'oer_publisheremail', true);?>
                <input type="text" name="oer_publisheremail" value="<?php echo $oer_publisheremail;?>" />
            </div>
        </div>

    </div>
</div>
<div class="clear"></div>
