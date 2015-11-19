<?php
	if(isset($_POST['cat_template']))
	{
		update_option("oer_category_template",$_POST['category_template']);
	}

	if(isset($_POST['path_save']))
	{
		update_option("oer_python_path",$_POST['python_path']);
	}

	if(isset($_POST['python_install_save']))
	{
		update_option("oer_python_install",$_POST['python_install']);
	}

	if(isset($_POST['enable_screenshot_save']))
	{
		update_option("oer_enable_screenshot",isset($_POST['enable_screenshot'])?$_POST['enable_screenshot']:false);
		update_option("oer_use_xvfb",isset($_POST['use_xvfb'])?$_POST['use_xvfb']:false);
		update_option("oer_debug_mode",isset($_POST['debug_mode'])?$_POST['debug_mode']:false);
	}

	$templates 			= get_page_templates();
	$slct_template 		= get_option("oer_category_template");
	$oer_python_path 	= get_option("oer_python_path");
	$oer_python_install = get_option("oer_python_install");
	//Enable Screenshot Option
	$enable_screenshot 	= get_option("oer_enable_screenshot");
	$use_xvfb 		= get_option("oer_use_xvfb");
	$debug_mode 		= get_option("oer_debug_mode");

	// Removed the concatenation shorthand as the variable didn't exist above this code yet
	$options = "<option value=''>--- Select Template ---</option>";
	foreach ( $templates as $template_name => $template_filename )
	{
		if($slct_template == $template_filename)
		{
			$slct = 'selected="selected"';
		}
		else
		{
			$slct = '';
		}
		$options .= "<option $slct value='$template_filename'>$template_name</option>";
	}
?>
<div class="oer_imprtrwpr">
	<div class="oer_hdng">
    	Assign Page Template to Category Pages
    </div>
    <form method="post">
        <div class="fields">
            <select name="category_template">
				<?php echo $options; ?>
			</select>
            <input type="submit" name="cat_template" value="Save" class="button button-primary"/>
        </div>
    </form>
</div>

<div class="oer_imprtrwpr">
	<div class="oer_hdng">
    	Set Path For Python Excutable Script
    </div>
    <form method="post">
        <div class="fields">
            <input type="text" name="python_path" value="<?php echo $oer_python_path;?>" />
            <input type="submit" name="path_save" value="Save" class="button button-primary"/>
        </div>
    </form>
</div>

<div class="oer_imprtrwpr">
	<div class="oer_hdng">
    	Set Path For Python Installation
    </div>
    <form method="post">
        <div class="fields">
            <input type="text" name="python_install" value="<?php echo $oer_python_install;?>" />
            <input type="submit" name="python_install_save" value="Save" class="button button-primary"/>
        </div>
    </form>
</div>

<form method="post" class="oer_settings_form">
	<fieldset>
		<legend><div class="oer_hdng">Screenshot Settings</div></legend>
		<div class="oer_imprtrwpr">
			<div class="fields">
				<input type="checkbox" name="enable_screenshot" id="enable_screenshot" <?php checked( $enable_screenshot, 'on') ?> /> <span class="oer_chck_label">Enable Screenshots?</span>
			</div>
			<div class="fields">
				<input type="checkbox" name="use_xvfb" id="use_xvfb" <?php checked( $use_xvfb, 'on') ?> /> <span class="oer_chck_label">Use xvfb?</span>
			</div>
			<div class="fields">
				<input type="checkbox" name="debug_mode" id="debug_mode" <?php checked( $debug_mode, 'on') ?> /> <span class="oer_chck_label">Enable Debug Mode?</span>
			</div>
		</div>
		<div class="oer_imprtrwpr">
			<div class="fields">
				<input type="submit" name="enable_screenshot_save" value="Save" class="button button-primary"/>
			</div>
		</div>
	</fieldset>
</form>

<div class="plugin-footer">
	<div class="plugin-info"><?php echo OER_PLUGIN_NAME . " " . OER_VERSION .""; ?></div>
	<div class="plugin-link"><a href='http://www.navigationnorth.com/portfolio/oer-management/' target='_blank'>More info</a></div>
	<div class="clear"></div>
</div>