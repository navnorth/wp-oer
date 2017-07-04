<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="oer_imprtrwpr">
    <form method="post" enctype="multipart/form-data" onsubmit="return processImport('#subject_submit','bulk_import')">
	<fieldset>
		<legend><div class="oer_heading"><?php _e("Import Subject Areas", OER_SLUG); ?></div></legend>
		<div class="oer-import-row">
			<div class="row-left">
				<?php _e("Easily setup resource subject areas. See the spreadsheet template for data format specifications.", OER_SLUG); ?>
			</div>
			<div class="row-right alignRight">
				<a href="<?php echo OER_URL."samples/subject_area_import.xls"; ?>" target="_blank"><?php _e("Download Spreadsheet Template", OER_SLUG); ?></a>
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
					<input type="submit" id="subject_submit" name="" value="<?php _e("Import", OER_SLUG); ?>" class="button button-primary"/>
				</div>
			</div>
		</div>
	</fieldset>
    </form>
</div>
