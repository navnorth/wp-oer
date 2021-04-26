<?php

function oer_create_template()
{
	$myfile = fopen(get_template_directory()."/oer_resource_template.php", "w") or die("Unable to open file!");
	$txt = "<?php
/*
 *Template Name: Resource Template
 */
?>";
	fwrite($myfile, $txt);
}

?>
