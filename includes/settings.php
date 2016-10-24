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
		update_option("oer_use_bootstrap",isset($_POST['use_bootstrap'])?$_POST['use_bootstrap']:false);
	}

	$templates 		= get_page_templates();
	$slct_template 		= get_option("oer_category_template");
	$oer_python_path 	= get_option("oer_python_path");
	$oer_python_install = get_option("oer_python_install");
	//Enable Screenshot Option
	$enable_screenshot 	= get_option("oer_enable_screenshot");
	$use_xvfb 		= get_option("oer_use_xvfb");
	$debug_mode 		= get_option("oer_debug_mode");
	$use_bootstrap 		= get_option("oer_use_bootstrap");

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
<div class="wrap">
    
    <div id="icon-themes" class="icon32"></div>
    <h2>Settings - OER Management</h2>
    <?php settings_errors(); ?>
     
	<?php
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
	?>
     
    <h2 class="nav-tab-wrapper">
        <a href="?post_type=resource&page=oer_settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
        <a href="?post_type=resource&page=oer_settings&tab=styles" class="nav-tab <?php echo $active_tab == 'styles' ? 'nav-tab-active' : ''; ?>">Styles</a>
        <a href="?post_type=resource&page=oer_settings&tab=setup" class="nav-tab <?php echo $active_tab == 'setup' ? 'nav-tab-active' : ''; ?>">Setup</a>
    </h2>
    
    <?php
	switch ($active_tab) {
		case "general":
			show_general_settings($options);
			break;
		case "styles":
			show_styles_settings();
			break;
		case "setup":
			show_setup_settings();
			break;
		default:
			break;
	}
    ?>
</div><!-- /.wrap -->
<div class="plugin-footer">
	<div class="plugin-info"><?php echo OER_PLUGIN_NAME . " " . OER_VERSION .""; ?></div>
	<div class="plugin-link"><a href='http://www.navigationnorth.com/portfolio/oer-management/' target='_blank'><?php _e("More info", OER_SLUG); ?></a></div>
	<div class="clear"></div>
</div>

<?php
function show_general_settings($options) {
?>
<div class="plugin-body">
	<div class="plugin-row">
		<div class="row-left">
			<?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis nunc tempor, maximus nulla nec, consectetur dolor. Cras tempor fermentum dolor ut maximus. Suspendisse pellentesque lacus semper justo blandit, non interdum velit tempor. Aenean euismod viverra erat eu pretium. Proin ut molestie velit, sit amet vehicula tellus. Praesent et pretium lectus.", OER_SLUG); ?>
		</div>
		<div class="row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="#" target="_blank"><?php _e("WordPress Plugin Support Forums", OER_SLUG); ?></a></li>
				<li><?php _e("Navigation North <a href='#' target='_blank'>direct supprt</a>", OER_SLUG); ?></li>
			</ul>
		</div>
	</div>
	<div class="plugin-row">
		<form method="post" class="oer_settings_form">
			<fieldset>
				<legend><div class="oer_hdng"><?php _e("Screenshot Utility", OER_SLUG); ?></div></legend>
				<?php settings_fields("oer_settings"); ?>
				<?php do_settings_sections("oer_settings"); ?>
				<?php submit_button(); ?>
			</fieldset>
		</form>
	</div>
</div>
<!--<div class="oer_imprtrwpr">
	<div class="oer_hdng">
		<?php _e("Assign Page Template to Category Pages", OER_SLUG); ?>
    </div>
    <form method="post">
        <div class="fields">
            <select name="category_template">
				<?php echo $options; ?>
			</select>
            <input type="submit" name="cat_template" value="<?php _e("Save", OER_SLUG); ?>" class="button button-primary"/>
        </div>
    </form>
</div>

<div class="oer_imprtrwpr">
	<div class="oer_hdng">
		<?php _e("Set Path For Python Excutable Script", OER_SLUG); ?>
    </div>
    <form method="post">
        <div class="fields">
            <input type="text" name="python_path" value="<?php echo $oer_python_path;?>" />
            <input type="submit" name="path_save" value="<?php _e("Save", OER_SLUG); ?>" class="button button-primary"/>
        </div>
    </form>
</div>

<div class="oer_imprtrwpr">
	<div class="oer_hdng">
		<?php _e("Set Path For Python Installation", OER_SLUG); ?>
    </div>
    <form method="post">
        <div class="fields">
            <input type="text" name="python_install" value="<?php echo $oer_python_install;?>" />
            <input type="submit" name="python_install_save" value="<?php _e("Save", OER_SLUG); ?>" class="button button-primary"/>
        </div>
    </form>
</div>-->
<?php
}

function show_styles_settings() {
?>
<div class="plugin-body">
	<div class="plugin-row">
		<div class="row-left">
			<?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis nunc tempor, maximus nulla nec, consectetur dolor. Cras tempor fermentum dolor ut maximus. Suspendisse pellentesque lacus semper justo blandit, non interdum velit tempor. Aenean euismod viverra erat eu pretium. Proin ut molestie velit, sit amet vehicula tellus. Praesent et pretium lectus.", OER_SLUG); ?>
		</div>
		<div class="row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="#" target="_blank"><?php _e("WordPress Plugin Support Forums", OER_SLUG); ?></a></li>
				<li><?php _e("Navigation North <a href='#' target='_blank'>direct supprt</a>", OER_SLUG); ?></li>
			</ul>
		</div>
	</div>
	<div class="plugin-row">
		<form method="post" class="oer_settings_form">
			
		</form>
	</div>
</div>
<form method="post" class="oer_settings_form">
	<fieldset>
		<legend><div class="oer_hdng"><?php _e("Screenshot and Display Settings", OER_SLUG); ?></div></legend>
		<div class="oer_imprtrwpr">
			<div class="fields">
				<input type="checkbox" name="enable_screenshot" id="enable_screenshot" <?php checked( $enable_screenshot, 'on') ?> /> <span class="oer_chck_label"><?php _e("Enable Screenshots?", OER_SLUG); ?></span>
			</div>
			<div class="fields">
				<input type="checkbox" name="use_xvfb" id="use_xvfb" <?php checked( $use_xvfb, 'on') ?> /> <span class="oer_chck_label"><?php _e("Use xvfb?", OER_SLUG); ?></span>
			</div>
			<div class="fields">
				<input type="checkbox" name="debug_mode" id="debug_mode" <?php checked( $debug_mode, 'on') ?> /> <span class="oer_chck_label"><?php _e("Enable Debug Mode?", OER_SLUG); ?></span>
			</div>
			<div class="fields">
				<input type="checkbox" name="use_bootstrap" id="use_bootstrap" <?php checked( $use_bootstrap, 'on') ?> /> <span class="oer_chck_label"><?php _e("Use Bootstrap?", OER_SLUG); ?></span>
			</div>
		</div>
		<div class="oer_imprtrwpr">
			<div class="fields">
				<input type="submit" name="enable_screenshot_save" value="<?php _e("Save", OER_SLUG); ?>" class="button button-primary"/>
			</div>
		</div>
	</fieldset>
</form>
<?php
}

function show_setup_settings() {
	echo "Upcoming Setup Settings!";
}
?>
