<?php
global $message, $type;

	if (isset($_REQUEST['settings-updated'])) {
		//When submitting settings tab
		if ($_REQUEST['tab']=="setup") {
			//Import Default Resources
			$import_resources = get_option('oer_import_sample_resources');
			if ($import_resources) {
				$response = importResources(true);
				if ($response) {
					$message = $response["message"];
					$type = $response["type"];
				}
			}
			//Import Default Subject Areas
			$import_subject_areas = get_option('oer_import_default_subject_areas');
			if ($import_subject_areas){
				$response = importSubjectAreas(true);
				if ($response) {
					$message .= $response["message"];
					$type .= $response["type"];
				}
			}
			//Import CCSS Standards
			$import_ccss = get_option('oer_import_ccss');
			if ($import_ccss) {
				$response = importDefaultStandards();
				if ($response) {
					$message .= $response["message"];
					$type .= $response["type"];
				}
			}
			//Redirect to main settings page
			exit( wp_safe_redirect( admin_url( 'edit.php?post_type=resource&page=oer_settings&setup=true' ) ) );
		}
	}
	if ($_REQUEST['setup']=='true'){
		$message = "The plugin has successfully loaded the default data.";
		$type = "success";
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
	<?php if ($active_tab=="setup") { ?>
        <a href="?post_type=resource&page=oer_settings&tab=setup" class="nav-tab <?php echo $active_tab == 'setup' ? 'nav-tab-active' : ''; ?>">Setup</a>
	<?php } ?>
    </h2>
    
    <?php
	switch ($active_tab) {
		case "general":
			show_general_settings();
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
<div class="oer-plugin-footer">
	<div class="oer-plugin-info"><?php echo OER_PLUGIN_NAME . " " . OER_VERSION .""; ?></div>
	<div class="oer-plugin-link"><a href='http://www.navigationnorth.com/portfolio/oer-management/' target='_blank'><?php _e("More info", OER_SLUG); ?></a></div>
	<div class="clear"></div>
</div>

<?php
function show_general_settings() {
	global $message, $type;
?>
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis nunc tempor, maximus nulla nec, consectetur dolor. Cras tempor fermentum dolor ut maximus. Suspendisse pellentesque lacus semper justo blandit, non interdum velit tempor. Aenean euismod viverra erat eu pretium. Proin ut molestie velit, sit amet vehicula tellus. Praesent et pretium lectus.", OER_SLUG); ?>
			<div class="oer-import-row">
			<h2 class="hidden"></h2>
			<?php if ($message) { ?>
			<div class="notice notice-<?php echo $type; ?> is-dismissible">
			    <p><?php echo $message; ?></p>
			</div>
			<?php } ?>
			</div>
		</div>
		<div class="oer-row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="#" target="_blank"><?php _e("WordPress Plugin Support Forums", OER_SLUG); ?></a></li>
				<li><?php _e("Navigation North <a href='#' target='_blank'>direct supprt</a>", OER_SLUG); ?></li>
			</ul>
		</div>
	</div>
	<div class="oer-plugin-row">
		<form method="post" class="oer_settings_form" action="options.php">
			<fieldset>
				<legend><div class="oer_hdng"><?php _e("Screenshot Utility", OER_SLUG); ?></div></legend>
				<?php settings_fields("oer_general_settings"); ?>
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
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis nunc tempor, maximus nulla nec, consectetur dolor. Cras tempor fermentum dolor ut maximus. Suspendisse pellentesque lacus semper justo blandit, non interdum velit tempor. Aenean euismod viverra erat eu pretium. Proin ut molestie velit, sit amet vehicula tellus. Praesent et pretium lectus.", OER_SLUG); ?>
		</div>
		<div class="oer-row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="#" target="_blank"><?php _e("WordPress Plugin Support Forums", OER_SLUG); ?></a></li>
				<li><?php _e("Navigation North <a href='#' target='_blank'>direct supprt</a>", OER_SLUG); ?></li>
			</ul>
		</div>
	</div>
	<div class="oer-plugin-row">
		<form method="post" class="oer_settings_form" action="options.php">
			<?php settings_fields("oer_styles_settings"); ?>
			<?php do_settings_sections("styles_settings_section"); ?>
			<?php submit_button(); ?>
		</form>
	</div>
</div>
<!--<form method="post" class="oer_settings_form">
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
</form>-->
<?php
}

function show_setup_settings() {
	?>
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<?php _e("When first setting up the plugin, the following options will give you the base set of data to see how everything works. All of these options will be available to you in other settings and features at a later time if you want to skip any or all of these options.", OER_SLUG); ?>
			<div class="oer-import-row">
			<h2 class="hidden"></h2>
			<?php if ($message) { ?>
			<div class="notice notice-<?php echo $type; ?> is-dismissible">
			    <p><?php echo $message; ?></p>
			</div>
			<?php } ?>
			</div>
		</div>
		<div class="oer-row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="#" target="_blank"><?php _e("WordPress Plugin Support Forums", OER_SLUG); ?></a></li>
				<li><?php _e("Navigation North <a href='#' target='_blank'>direct supprt</a>", OER_SLUG); ?></li>
			</ul>
		</div>
	</div>
	<div class="oer-plugin-row">
		<form method="post" class="oer_settings_form" action="options.php">
			<?php settings_fields("oer_setup_settings"); ?>
			<?php do_settings_sections("setup_settings_section"); ?>
			<?php submit_button('Continue'); ?>
		</form>
	</div>
</div>
<?php
}
?>
