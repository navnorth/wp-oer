<?php
global $message, $type;

	if (isset($_REQUEST['settings-updated'])) {
		//When submitting settings tab
		if ($_REQUEST['tab']=="setup") {
			
			//Import Default Resources
			$import_resources = get_option('oer_import_sample_resources');
			if ($import_resources) {
				$response = oer_importResources(true);
				if ($response) {
					$message = $response["message"];
					$type = $response["type"];
				}
			}
			//Import Default Subject Areas
			$import_subject_areas = get_option('oer_import_default_subject_areas');
			if ($import_subject_areas){
				$response = oer_importSubjectAreas(true);
				if ($response) {
					$message .= $response["message"];
					$type .= $response["type"];
				}
			}
			//Import CCSS Standards
			$import_ccss = get_option('oer_import_ccss');
			if ($import_ccss) {
				$response = oer_importDefaultStandards();
				if ($response) {
					$message .= $response["message"];
					$type .= $response["type"];
				}
			}
			
			//Set Default screenshots to disabled
			update_option('oer_disable_screenshots', 1);
			
			//Redirect to main settings page
			wp_safe_redirect( admin_url( 'edit.php?post_type=resource&page=oer_settings&setup=true' ) );
			exit();
		}
		if ($_REQUEST['tab']=="reset") {
			$delete_standards_data = get_option('oer_delete_standards_data');
			if($delete_standards_data){
				oer_delete_standards();
			}
		}
	}
	
	if ($_REQUEST['setup']=='true'){
		$message = "The plugin has successfully loaded the default data.";
		$type = "success";
	}
?>
<div class="wrap">
    
    <div id="icon-themes" class="oer-logo"><img src="<?php echo OER_URL ?>images/wp-oer-admin-logo.png" /></div>
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
	<?php if ($active_tab=="reset") { ?>
        <a href="?post_type=resource&page=oer_settings&tab=reset" class="nav-tab <?php echo $active_tab == 'reset' ? 'nav-tab-active' : ''; ?>">Reset</a>
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
		case "reset":
			show_reset_settings();
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
			<?php _e("Adjust settings below as necessary for your installation. For additional information on these options check the support forums or <a href='https://www.wp-oer.com/' target='_blank'>wp-oer.com</a>", OER_SLUG); ?>
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
		<form method="post" class="oer_settings_form" action="options.php"  onsubmit="return processInitialSettings(this)">
			<fieldset>
				<legend><div class="oer_hdng"><?php _e("Screenshot Utility", OER_SLUG); ?></div></legend>
				<?php settings_fields("oer_general_settings"); ?>
				<?php do_settings_sections("oer_settings"); ?>
				<?php submit_button(); ?>
			</fieldset>
		</form>
	</div>
</div>

<?php
}

function show_styles_settings() {
?>
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<?php _e("Use the options below to make adjustments to the look and feel of the OER pages. For more fine-tune customizations, additional CSS can be provided to include on all OER pages.", OER_SLUG); ?>
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
		<form method="post" class="oer_settings_form" action="options.php"  onsubmit="return processInitialSettings(this)">
			<?php settings_fields("oer_styles_settings"); ?>
			<?php do_settings_sections("styles_settings_section"); ?>
			<?php submit_button(); ?>
		</form>
	</div>
</div>
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
		<form method="post" class="oer_settings_form" action="options.php"  onsubmit="return processInitialSettings(this)">
			<?php settings_fields("oer_setup_settings"); ?>
			<?php do_settings_sections("setup_settings_section"); ?>
			<?php submit_button('Continue', 'primary setup-continue'); ?>
		</form>
	</div>
</div>
<?php
}

function show_reset_settings() {
	?>
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<h3><?php _e("Reset OER Plugin Setup", OER_SLUG); ?></h3>
			<?php _e("This function is provided just to assist with testing. Be careful with the options below as each of them will remove data from your Wordpress site.", OER_SLUG); ?>
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
		<form method="post" class="oer_settings_form reset-form" action="options.php"  onsubmit="return confirm_deletion(this)">
			<?php settings_fields("oer_reset_settings"); ?>
			<?php do_settings_sections("reset_settings_section"); ?>
			<?php submit_button('Submit', 'primary setup-continue'); ?>
		</form>
	</div>
</div>
<?php
}
oer_display_loader();
?>