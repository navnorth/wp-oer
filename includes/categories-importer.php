<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $_nalrc;
$label = "subject";
if ($_nalrc)
	$label = "topic";
?>
<div class="oer_imprtrwpr">
    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url('admin.php') ); ?>" onsubmit="return processImport('#subject_submit','bulk_import')">
	<fieldset>
		<legend><div class="oer_heading"><?php if ($_nalrc): esc_html_e("Import Topic Areas", OER_SLUG); else: esc_html_e("Import Subject Areas", OER_SLUG); endif; ?></div></legend>
		<div class="oer-import-row">
			<div class="row-left">
				<?php esc_html_e("Easily setup resource $label areas. See the spreadsheet template for data format specifications.", OER_SLUG); ?>
			</div>
			<div class="row-right alignRight">
				<a href="<?php echo esc_url(OER_URL."samples/subject_area_import.xls"); ?>" target="_blank"><?php _e("Download Spreadsheet Template", OER_SLUG); ?></a>
			</div>
		</div>
		<div class="oer-import-row">
			<div class="row-left">
				<div class="fields">
					<input type="file" id="bulk_import" name="bulk_import"/>
					<input type="hidden" value="" name="bulk_imprt" />
					<div class="resource-upload-notice"></div>
				</div>
			</div>
			<div class="row-right">
				<div class="fields alignRight">
				    <input type="hidden" name="action" value="import_subjects">
				    <?php wp_nonce_field( 'oer_subject_area_nonce_field' ); ?>
					<input type="submit" id="subject_submit" name="" value="<?php esc_html_e("Import", OER_SLUG); ?>" class="button button-primary"/>
				</div>
			</div>
		</div>
	</fieldset>
    </form>
</div>
