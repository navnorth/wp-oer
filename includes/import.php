<?php
/** Import Page **/
?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2>Import - OER</h2>
    <?php settings_errors(); ?>
    <div class="oer-import-body">
	<div class="oer-import-row">
		<div class="row-left">
			<?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis nunc tempor, maximus nulla nec, consectetur dolor. Cras tempor fermentum dolor ut maximus. Suspendisse pellentesque lacus semper justo blandit, non interdum velit tempor. Aenean euismod viverra erat eu pretium. Proin ut molestie velit, sit amet vehicula tellus. Praesent et pretium lectus.", OER_SLUG); ?>
			<div class="oer-import-row">
			    <?php
				global $wpdb;
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
				<li><a href="#" target="_blank"><?php _e("WordPress Plugin Support Forums", OER_SLUG); ?></a></li>
				<li><?php _e("Navigation North <a href='#' target='_blank'>direct supprt</a>", OER_SLUG); ?></li>
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