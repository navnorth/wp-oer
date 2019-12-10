<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $message, $type;

	if (isset($_REQUEST['settings-updated'])) {
		if (!current_user_can('manage_options')) {
			wp_die( "You don't have permission to access this page!" );
		}
		
		//When submitting settings tab
		if (isset($_REQUEST['tab']) && $_REQUEST['tab']=="setup") {
			
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
			
			//Import Default Resources
			$import_resources = get_option('oer_import_sample_resources');
			if ($import_resources) {
				$response = oer_importResources(true);
				if ($response) {
					$message = $response["message"];
					$type = $response["type"];
				}
			}
			
			//Set Default screenshots to disabled
			update_option('oer_disable_screenshots', 1);
			
			//Redirect to main settings page
			wp_safe_redirect( admin_url( 'edit.php?post_type=resource&page=oer_settings&setup=true' ) );
			exit();
		}
		if (isset($_REQUEST['tab']) && $_REQUEST['tab']=="reset") {
			
			$delete_standards_data = get_option('oer_delete_standards_data');
			if($delete_standards_data){
				$response = oer_delete_standards();
				if ($response) {
					$message .= $response["message"];
					$type .= $response["type"];
				}
			}
			
			$delete_subject_area_taxomonies = get_option('oer_delete_subject_areas_taxonomies');
			if ($delete_subject_area_taxomonies){
				$response = oer_delete_subject_areas();
				if ($response) {
					$message .= $response["message"];
					$type .= $response["type"];
				}
			}
			
			$delete_resources = get_option('oer_delete_resources');
			if ($delete_resources){
				$response = oer_delete_resources();
				if ($response) {
					$message .= $response["message"];
					$type .= $response["type"];
				}
			}
			
			$delete_resource_media = get_option('oer_delete_resource_media');
			if ($delete_resource_media) {
				$response = oer_delete_resource_media();
				if ($response) {
					$message .= $response["message"];
					$type .= $response["type"];
				}
			}
			
			$remove_all_settings = get_option('oer_remove_all_settings');
			if ($remove_all_settings){
				$response = oer_remove_plugin_settings();
				if ($response) {
					$message .= $response["message"];
					$type .= $response["type"];
				}
			}
			
			$deactivate_plugin = get_option('oer_deactivate_plugin');
			if ($deactivate_plugin){
				oer_deactivate_plugin();
				
				//Redirect to plugins page
				wp_safe_redirect( admin_url( 'plugins.php' ) );
				exit();
			}
			
			$delete_plugin_files = get_option('oer_delete_plugin_files');
			if ($delete_plugin_files){
				oer_deactivate_plugin();
				oer_delete_plugin_files();
				
				//Redirect to plugins page
				wp_safe_redirect( admin_url( 'plugins.php' ) );
				exit();
			}
		}
	}
	
	if (isset($_REQUEST['setup']) && $_REQUEST['setup']=='true'){
		$message = "The plugin has successfully loaded the default data.";
		$type = "success";
	}
?>
<div class="wrap">
    
    <div id="icon-themes" class="oer-logo"><img src="<?php echo OER_URL ?>images/wp-oer-admin-logo.png" /></div>
    <h2>Settings - WP OER</h2>
    <?php settings_errors(); ?>
     
	<?php
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
	?>
     
    <h2 class="nav-tab-wrapper">
        <a href="?post_type=resource&page=oer_settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
        <a href="?post_type=resource&page=oer_settings&tab=styles" class="nav-tab <?php echo $active_tab == 'styles' ? 'nav-tab-active' : ''; ?>">Styles</a>
	<a href="?post_type=resource&page=oer_settings&tab=metadata" class="nav-tab <?php echo $active_tab == 'metadata' ? 'nav-tab-active' : ''; ?>">Metadata Fields</a>
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
			oer_show_general_settings();
			break;
		case "styles":
			oer_show_styles_settings();
			break;
		case "metadata":
			oer_show_metadata_settings();
			break;
		case "setup":
			oer_show_setup_settings();
			break;
		case "reset":
			oer_show_reset_settings();
			break;
		default:
			break;
	}
    ?>
</div><!-- /.wrap -->
<div class="oer-plugin-footer">
	<div class="oer-plugin-info"><?php echo OER_ADMIN_PLUGIN_NAME . " " . OER_VERSION .""; ?></div>
	<div class="oer-plugin-link"><a href='https://www.wp-oer.com/' target='_blank'><?php _e("More Information", OER_SLUG); ?></a></div>
	<div class="clear"></div>
</div>

<?php
function oer_show_general_settings() {
	global $message, $type;
?>
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<?php _e("Adjust settings below as necessary for your installation. For additional information on these options check the support forums or <a href='https://www.wp-oer.com/' target='_blank'>wp-oer.com</a>", OER_SLUG); ?>
			<div class="oer-import-row">
			<h2 class="hidden"></h2>
			<?php if ($message) { ?>
			<div class="notice notice-<?php echo esc_attr($type); ?> is-dismissible">
			    <p><?php echo $message; ?></p>
			</div>
			<?php } ?>
			</div>
		</div>
		<div class="oer-row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="https://www.wp-oer.com/get-help/" target="_blank"><?php _e("WP OER Plugin Support", OER_SLUG); ?></a></li>
			</ul>
		</div>
	</div>
	<form method="post" class="oer_settings_form" action="options.php"  onsubmit="return processInitialSettings(this)">
		<?php settings_fields("oer_general_settings"); ?>
		<div class="oer-plugin-row plugin-row-first">
			<fieldset>
				<legend><div class="oer_hdng"><?php _e("PDF Embeds", OER_SLUG); ?></div></legend>
				<?php do_settings_sections("embed_settings"); ?>
			</fieldset>
		</div>
		<div class="oer-plugin-row">
			<fieldset>
				<legend><div class="oer_hdng"><?php _e("Screenshot Utility", OER_SLUG); ?></div></legend>
				<?php do_settings_sections("oer_settings"); ?>
			</fieldset>
		</div>
		<?php submit_button(); ?>
	</form>
</div>

<?php
}

function oer_show_styles_settings() {
?>
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<?php _e("Use the options below to make adjustments to the look and feel of the OER pages. For more fine-tune customizations, additional CSS can be provided to include on all OER pages.", OER_SLUG); ?>
		</div>
		<div class="oer-row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="https://www.wp-oer.com/get-help/" target="_blank"><?php _e("WP OER Plugin Support", OER_SLUG); ?></a></li>
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

function oer_show_metadata_settings() {
	$metas = oer_get_all_meta("resource");
	$metadata = null;
	
	foreach($metas as $met){
		if (strpos($met['meta_key'],"oer_")!==false){
			$metadata[] = $met['meta_key'];
		}
	}
	
	$meta = array_unique($metadata);
	
	// Save Option
	if ($_POST){
		if ($_REQUEST['tab'] && $_REQUEST['tab']=="metadata") {
			// Remove meta key enabled option
			foreach($metas as $met){
				if (strpos($met['meta_key'],"oer_")!==false){
					delete_option($met['meta_key']."_enabled");
				}
			}
			oer_save_metadata_options($_POST);
		}
	}
?>
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<?php _e("Use the options below to update metadata field options.", OER_SLUG); ?>
		</div>
		<div class="oer-row-right">
		</div>
	</div>
	<div class="oer-plugin-row">
		<form method="post" class="oer_settings_form" onsubmit="return processInitialSettings(this)">
			<table class="table">
				<thead>
					<tr>
						<th>Field Name</th>
						<th>Label</th>
						<th>Enabled</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($meta as $key) {
						$label = "";
						$enabled = "0";
						$option_set = false;
						if ($key!=="oer_resourceurl"){
							if (get_option($key."_label")){
								$label = get_option($key."_label");
								$option_set = true;
							}
							else
								$label = oer_get_meta_label($key);
							
							if (get_option($key."_enabled"))
								$enabled = (get_option($key."_enabled")=="1")?true:false;
							elseif ($option_set==false)
								$enabled = "1";
							
					?>
					<tr>
						<td><?php echo $key; ?></td>
						<td><input type="text" name="<?php echo $key."_label"; ?>" value="<?php echo $label; ?>" /></td>
						<td><input type="checkbox" name="<?php echo $key."_enabled"; ?>" value="1" <?php checked($enabled,"1",true); ?>/></td>
					</tr>
					<?php 	}
					} ?>
				</tbody>
			</table>
			<?php submit_button("Save Metadata Options"); ?>
		</form>
	</div>
</div>
<?php
}

function oer_show_setup_settings() {
	global $message, $type;
	?>
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<?php _e("When first setting up the plugin, the following options will give you the base set of data to see how everything works. All of these options will be available to you in other settings and features at a later time if you want to skip any or all of these options.", OER_SLUG); ?>
			<div class="oer-import-row">
			<h2 class="hidden"></h2>
			<?php if ($message) { ?>
			<div class="notice notice-<?php echo esc_attr($type); ?> is-dismissible">
			    <p><?php echo $message; ?></p>
			</div>
			<?php } ?>
			</div>
		</div>
		<div class="oer-row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="https://www.wp-oer.com/get-help/" target="_blank"><?php _e("WP OER Plugin Support", OER_SLUG); ?></a></li>
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

function oer_show_reset_settings() {
	global $message, $type;
	?>
<div class="oer-plugin-body">
	<div class="oer-plugin-row">
		<div class="oer-row-left">
			<h3><?php _e("Reset OER Plugin Setup", OER_SLUG); ?></h3>
			<?php _e("This function is provided just to assist with testing. Be careful with the options below as each of them will remove data from your Wordpress site.", OER_SLUG); ?>
			<div class="oer-import-row">
			<h2 class="hidden"></h2>
			<?php if ($message) { ?>
			<div class="notice notice-<?php echo esc_attr($type); ?> is-dismissible">
			    <p><?php echo $message; ?></p>
			</div>
			<?php } ?>
			</div>
		</div>
		<div class="oer-row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="https://www.wp-oer.com/get-help/" target="_blank"><?php _e("WP OER Plugin Support", OER_SLUG); ?></a></li>
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