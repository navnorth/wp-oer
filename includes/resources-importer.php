<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="oer_imprtrwpr">
    <form method="post" enctype="multipart/form-data" onsubmit="return processImport('#resource_submit','resource_import')">
	<fieldset>
		<legend><div class="oer_heading"><?php _e("Import Resources", OER_SLUG); ?></div></legend>
		<div class="oer-import-row">
			<div class="row-left">
				<?php _e("For bulk upload of resources. Import file must match the spreadsheet template. If screenshot processing is enabled, a maximum of 50 records per transaction is suggested.", OER_SLUG); ?>
			</div>
			<div class="row-right alignRight">
				<a href="<?php echo esc_url(OER_URL."samples/resource_import_sample_data.xls"); ?>" target="_blank"><?php _e("Download Spreadsheet Template", OER_SLUG); ?></a>
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
					<input type="submit" id="resource_submit" name="resource_submit" value="<?php esc_attr(_e("Import", OER_SLUG)); ?>" class="button button-primary"/>
				</div>
			</div>
		</div>
	</fieldset>
    </form>
</div>
