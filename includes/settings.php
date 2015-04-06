<?php
	if(isset($_POST['cat_template']))
	{
		update_option("oer_category_template",$_POST['category_template']);
	}

	if(isset($_POST['path_save']))
	{
		update_option("oer_python_path",$_POST['python_path']);
	}

	if(isset($_POST['python_install_save']))
	{
		update_option("oer_python_install",$_POST['python_install']);
	}

	$templates 			= get_page_templates();
	$slct_template 		= get_option("oer_category_template");
	$oer_python_path 	= get_option("oer_python_path");
	$oer_python_install = get_option("oer_python_install");

	// Removed the concatenation shorthand as the variable didn't exist above this code yet
	$options = "<option value=''>--- Select Template ---</option>";
	foreach ( $templates as $template_name => $template_filename )
	{
		if($slct_template == $template_filename)
		{
			$slct = 'selected="selected"';
		}
		else
		{
			$slct = '';
		}
		$options .= "<option $slct value='$template_filename'>$template_name</option>";
	}
?>
<div class="oer_imprtrwpr">
	<div class="oer_hdng">
    	Assign Page Template to Category Pages
    </div>
    <form method="post">
        <div class="fields">
            <select name="category_template">
				<?php echo $options; ?>
			</select>
            <input type="submit" name="cat_template" value="Save" class="button button-primary"/>
        </div>
    </form>
</div>

<div class="oer_imprtrwpr">
	<div class="oer_hdng">
    	Set Path For Python Excutable Script
    </div>
    <form method="post">
        <div class="fields">
            <input type="text" name="python_path" value="<?php echo $oer_python_path;?>" />
            <input type="submit" name="path_save" value="Save" class="button button-primary"/>
        </div>
    </form>
</div>

<div class="oer_imprtrwpr">
	<div class="oer_hdng">
    	Set Path For Python Installation
    </div>
    <form method="post">
        <div class="fields">
            <input type="text" name="python_install" value="<?php echo $oer_python_install;?>" />
            <input type="submit" name="python_install_save" value="Save" class="button button-primary"/>
        </div>
    </form>
</div>
