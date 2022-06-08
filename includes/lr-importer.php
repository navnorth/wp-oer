<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
$max_time = ini_get('max_execution_time');
?>
<div class="oer_imprtrwpr">
    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url('admin.php') ); ?>" onsubmit="return processLRImport('#lr_submit','lr_import')">
	<fieldset>
		<legend><div class="oer_heading"><?php esc_html_e("Import Resources from the Learning Registry (beta)", OER_SLUG); ?></div></legend>
		<div class="oer-import-row">
			<div class="row-left">
				<?php esc_html_e("For bulk imports of resources from the Learning Registry, with automatic matching of subjects and grades, similar to the spreadsheet importer.", OER_SLUG); ?>
			</div>
			<div class="row-right alignRight">
				<a href="<?php echo esc_url('https://www.learningregistry.org/') ?>" target="_blank"><?php esc_html_e("Learning Registry Info", OER_SLUG); ?></a>
			</div>
		</div>
		<div class="oer-import-row">
			<div class="row2-left">
				<div class="fields">
					<input type="text" id="lr_import" name="lr_import" class="large-text"/>
					<input type="hidden" value="" name="lr_resrc_imprt" />
					<div class="lr-resource-import-notice"></div>
				</div>
			</div>
			<div class="row2-right">
				<div class="fields alignRight">
				    <input type="hidden" name="action" value="import_lr_resources">
				    <?php wp_nonce_field( 'oer_lr_nonce_field' ); ?>
					<input type="submit" id="lr_submit" name="lr_submit" value="<?php esc_attr(_e("Import", OER_SLUG)); ?>" data-max-time="<?php echo esc_attr($max_time); ?>" class="button button-primary"/>
				</div>
			</div>
		</div>
	</fieldset>
    </form>
</div>
