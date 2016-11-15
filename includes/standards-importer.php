<?php
?>
<div id="col-container" class="oer_imprtrwpr">
	<form method="post" id="standards_form" onsubmit="return importStandards('#standards_form','#standards_submit')">
		<fieldset>
			<legend><div class="oer_heading"><?php _e("Import Academic Standards", OER_SLUG); ?></div></legend>
			<div class="oer-import-row">
				<div class="row-left">
					<?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis nunc tempor, maximus nulla nec, consectetur dolor.", OER_SLUG); ?>
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
											$math = isStandardExisting("Math");
											$attr = "";
											$class = "";
											if ($math){
												$attr = "disabled";
												$class = "class='disabled'";
											}
											
										?>
										<input name="oer_common_core_mathematics" id="oer_common_core_mathematics" type="checkbox" value="1" <?php echo $attr; ?>><label for="oer_common_core_mathematics" <?php echo $class; ?>><strong>Common Core Mathematics</strong> <?php if ($math): ?><span class="prev-import">(previously imported)</span><?php endif; ?></label>
									</td>
								</tr>
								<tr>
									<td>
										<?php
											$english = isStandardExisting("English");
											$attr = "";
											$class = "";
											if ($english){
												$attr = "disabled";
												$class = "class='disabled'";
											}
											
										?>
										<input name="oer_common_core_english" id="oer_common_core_english" type="checkbox" value="1" <?php echo $attr; ?>><label for="oer_common_core_english" <?php echo $class; ?>><strong>Common Core English Language Arts</strong> <?php if ($english): ?><span class="prev-import">(previously imported)</span><?php endif; ?></label>
									</td>
								</tr>
							</tbody>
						</table>
						<input type="hidden" value="" name="standards_import" />
					</div>
				</div>
				<div class="row-right">
					<div class="fields alignRight">
						<input type="submit" id="standards_submit" name="" value="<?php _e("Import", OER_SLUG); ?>" class="button button-primary"/>
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>
