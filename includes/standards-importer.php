<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  ?>
<div id="col-container" class="oer_imprtrwpr">
	<form method="post" id="standards_form" action="<?php echo esc_url( admin_url('admin.php') ); ?>" onsubmit="return importStandards('#standards_form','#standards_submit')">
		<fieldset>
			<legend><div class="oer_heading"><?php _e("Import Academic Standards", OER_SLUG); ?></div></legend>
			<div class="oer-import-row">
				<div class="row-left">
					<?php esc_html_e("Resources can be easily tagged to standards to provide additional alignment information to viewers. Datasets for the standards listed below are included with the plugin.", OER_SLUG); ?>
				</div>
				<div class="row-right alignRight">
					<a href="http://asn.jesandco.org/resources/ASNJurisdiction/CCSS" target="_blank"><?php _e("ASN Standards Info", OER_SLUG); ?></a>
				</div>
			</div>
			<div class="oer-import-row">
				<div class="row-left">
					<div class="fields">
						<table class="form-table">
							<tbody>
								<tr>
									<td>
										<?php
											$math = oer_isStandardExisting("Math");
											$attr = "";
											$class = "";
											if ($math){
												$attr = "disabled";
												$class = "class='disabled'";
											}
											
										?>
										<input name="oer_common_core_mathematics" id="oer_common_core_mathematics" type="checkbox" value="1" <?php echo esc_attr($attr); ?>><label for="oer_common_core_mathematics" <?php echo esc_attr($class); ?>><strong>Common Core Mathematics</strong> <?php if ($math): ?><span class="prev-import">(<?php esc_html_e('previously imported',OER_SLUG); ?>)</span><?php endif; ?></label>
									</td>
								</tr>
								<tr>
									<td>
										<?php
											$english = oer_isStandardExisting("English");
											$attr = "";
											$class = "";
											if ($english){
												$attr = "disabled";
												$class = "class='disabled'";
											}
											
										?>
										<input name="oer_common_core_english" id="oer_common_core_english" type="checkbox" value="1" <?php echo esc_attr($attr); ?>><label for="oer_common_core_english" <?php echo esc_attr($class); ?>><strong>Common Core English Language Arts</strong> <?php if ($english): ?><span class="prev-import">(<?php esc_html_e('previously imported',OER_SLUG); ?>)</span><?php endif; ?></label>
									</td>
								</tr>
								<tr>
									<td>
										<?php
											$science = oer_isStandardExisting("Next Generation Science");
											$attr = "";
											$class = "";
											if ($science){
												$attr = "disabled";
												$class = "class='disabled'";
											}
											
										?>
										<input name="oer_next_generation_science" id="oer_next_generation_science" type="checkbox" value="1" <?php echo esc_attr($attr); ?>><label for="oer_next_generation_science" <?php echo esc_attr($class); ?>><strong>Next Generation Science Standards</strong> <?php if ($science): ?><span class="prev-import">(<?php esc_html_e('previously imported',OER_SLUG); ?>)</span><?php endif; ?></label>
									</td>
								</tr>
							</tbody>
						</table>
						<input type="hidden" value="" name="standards_import" />
					</div>
				</div>
				<div class="row-right">
					<div class="fields alignRight">
						<input type="hidden" name="action" value="import_standards">
						<?php wp_nonce_field( 'oer_standards_nonce_field' ); ?>
						<input type="submit" id="standards_submit" name="" value="<?php esc_attr(_e("Import", OER_SLUG)); ?>" class="button button-primary"/>
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>
