<?php

/*function getScreenshotFile_mlt($url)
{
	$upload_dir = wp_upload_dir();
	$path = $upload_dir['basedir'].'/resource-images/';

	if(!file_exists($path))
	{
		mkdir($path, 0777, true);
	}
	$file = $path.'Screenshot'.preg_replace('/https?|:|#|\//i', '-', $url).'.jpg';
	if(!file_exists($file))
	{
		$oer_python_script_path = get_option("oer_python_path");
		$oer_python_install = get_option("oer_python_install");

		// create screenshot
		$params = array(
			'xvfb-run',
			'--auto-servernum',
			'--server-num=1',
			$oer_python_install,
			$oer_python_script_path,
			escapeshellarg($url),
			$file,
		);

		$lines = array();
		$val = 0;

		$output = exec(implode(' ', $params), $lines, $val);
	}
	return $file;
}*/

?>

<div class="oer_imprtrwpr">
    <form method="post" enctype="multipart/form-data" onsubmit="return processImport('#resource_submit','resource_import')">
	<fieldset>
		<legend><div class="oer_heading"><?php _e("Import Resources", OER_SLUG); ?></div></legend>
		<div class="oer-import-row">
			<div class="row-left">
				<?php _e("For bulk upload of resources. Import file must match the spreadsheet template. If screenshot processing is enabled, a maximum of 50 records per transaction is suggested.", OER_SLUG); ?>
			</div>
			<div class="row-right alignRight">
				<a href="<?php echo OER_URL."samples/resource_import_sample_data.xls"; ?>" target="_blank"><?php _e("Download Spreadsheet Template", OER_SLUG); ?></a>
			</div>
		</div>
		<div class="oer-import-row">
			<div class="row-left">
				<div class="fields">
					<input type="file" id="resource_import" name="resource_import"/>
					<input type="hidden" value="" name="resrc_imprt" />
					<div class="resource-upload-notice"></div>
				</div>
			</div>
			<div class="row-right">
				<div class="fields alignRight">
					<input type="submit" id="resource_submit" name="resource_submit" value="<?php _e("Import", OER_SLUG); ?>" class="button button-primary"/>
				</div>
			</div>
		</div>
	</fieldset>
    </form>
</div>
