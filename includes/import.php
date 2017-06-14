<?php
/** Import Page **/
global $wpdb;
require_once OER_PATH.'includes/oer-functions.php';

$message = null;
$type = null;

//Resource Import
if(isset($_POST['resrc_imprt']))
{
	$import_response = oer_importResources();
	if ($import_response){
	    $message = $import_response["message"];
	    $type = $import_response["type"];
	}
}

//Subject Areas Bulk Import
if(isset($_POST['bulk_imprt']))
{
    $import_response = oer_importSubjectAreas();
    if ($import_response){
	$message = $import_response["message"];
	$type = $import_response["type"];
    }
}

//Categories Bulk Import
//Standards Bulk Import
if(isset($_POST['standards_import']))
{
    $files = array();

    if (isset($_POST['oer_common_core_mathematics'])){
	   $files[] = OER_PATH."samples/CCSS_Math.xml";
    }

    if (isset($_POST['oer_common_core_english'])){
	   $files[] = OER_PATH."samples/CCSS_ELA.xml";
    }

    if (isset($_POST['oer_next_generation_science'])){
	   $files[] = OER_PATH."samples/NGSS.xml";
    }

    foreach ($files as $file) {
    	$import = oer_importStandards($file);
    	if ($import['type']=="success") {
    	    if (strpos($file,'Math')) {
    		$message .= "Successfully imported Common Core Mathematics Standards. \n";
    	    } elseif (strpos($file,'ELA')) {
    		$message .= "Successfully imported Common Core English Language Arts Standards. \n";
    	    } else {
    		$message .= "Successfully imported Next Generation Science Standards. \n";
    	    }
    	}
    	$type = $import['type'];
    }
}
?>
<div class="wrap">
    <div id="icon-themes" class="oer-logo"><img src="<?php echo OER_URL ?>images/wp-oer-admin-logo.png" /></div>
    <p class="oer_heading">Import - OER</p>
    <?php settings_errors(); ?>
    <div class="oer-import-body">
	<div class="oer-import-row">
		<div class="row-left">
			<?php _e("Use the options below to import data sets to the OER tool. Additional information can be found on the support forums or wp-oer.com.", OER_SLUG); ?>
			<div class="oer-import-row">
			<h2 class="hidden"></h2>
			<?php if ($message) { ?>
    			<div class="notice notice-<?php echo $type; ?> is-dismissible">
    			    <p><?php echo $message; ?></p>
    			</div>
			<?php } ?>
			</div>
			<div class="oer-import-row">
			    <?php
				$table_name = $wpdb->prefix . "resource_csv";
				include_once(OER_PATH.'includes/resources-importer.php');
			    ?>
			</div>
			<div class="oer-import-row">
			    <?php
				include_once(OER_PATH.'includes/categories-importer.php');
			    ?>
			</div>
			<div class="oer-import-row">
			    <?php
				include_once(OER_PATH.'includes/standards-importer.php');
			    ?>
			</div>
		</div>
		<div class="row-right">
			<strong><?php _e("Support Options", OER_SLUG); ?></strong>
			<ul>
				<li><a href="https://www.wp-oer.com/get-help/" target="_blank"><?php _e("WP OER Plugin Support", OER_SLUG); ?></a></li>
			</ul>
		</div>
	</div>
    </div>
</div><!-- /.wrap -->
<div class="plugin-footer">
	<div class="plugin-info"><?php echo OER_PLUGIN_NAME . " " . OER_VERSION .""; ?></div>
	<div class="plugin-link"><a href='http://www.navigationnorth.com/portfolio/oer-management/' target='_blank'><?php _e("More info", OER_SLUG); ?></a></div>
	<div class="clear"></div>
</div>
<?php oer_display_loader(); ?>
