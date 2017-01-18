<?php

	global $wpdb;
	$args = array(
		'orderby'            => 'name',
		'order'              => 'ASC',
		'show_count'         => 0,
		'hide_empty'         => 0,
		'use_desc_for_title' => 1,
		'child_of'           => 0,
		'parent'	         => 0,
		'hierarchical'       => 1,
		'number'             => null,
		'echo'               => 1,
		'taxonomy'           => 'resource-subject-area');

	$all_category = get_categories( $args );
	$i=1;
	?>

	<table class="wp-list-table widefat fixed pages">
		<thead>
			<th><?php _e("S.No", OER_SLUG); ?></th>
			<th><?php _e("Category Name", OER_SLUG); ?></th>
			<th><?php _e("Upload/Remove", OER_SLUG); ?></th>
			<th><?php _e("Upload/Remove on Hover", OER_SLUG); ?></th>
		</thead>
		<?php
			foreach($all_category as $category)
	  		{
				$table_postmeta =  $wpdb->prefix."postmeta";
				$image  ="";
				$image_hover="";
				$term_id= $category->term_id;
				$sqlq = $wpdb->prepare( "SELECT * FROM $table_postmeta  WHERE  meta_value=%s AND meta_key='category_image'" , $term_id );
				$getimage = $wpdb->get_results($sqlq);

				if(!empty($getimage))
				{
					$post=get_post($getimage[0]->post_id);
					$image = '<img src = "'.$post->guid.'" />';
				}
	    ?>
		<tr>
			<td><?php echo $i ?></td>
			<td><?php echo $category->cat_name; ?></td>
			<td>
				<span class="oer_spn_category" id="spn<?php echo $category->term_id;?>"><?php echo $image; ?></span>
				<a id="a_spn<?php echo $category->term_id; ?>" alt="spn<?php echo $category->term_id; ?>" href="javascript:void" class="upload_category_image" title="upload"><?php if(!empty($image)) { _e("Update", OER_SLUG); } else { _e("Upload", OER_SLUG); } ?></a>
				/
				<a id="r_spn<?php echo $category->term_id; ?>" onclick="remove_img(this);" title="remove" alt="spn<?php echo $category->term_id; ?>" href="javascript:void"><?php _e("Remove", OER_SLUG); ?></a>
			</td>
			<td>
				<?php
					$sqlq_hover = $wpdb->prepare( "SELECT * FROM $table_postmeta  WHERE  meta_value=%s AND meta_key='category_image_hover'" , $term_id );
					$getimage_hover = $wpdb->get_results($sqlq_hover);
					if(!empty($getimage_hover))
					{
						$post_hover=get_post($getimage_hover[0]->post_id);
						$image_hover = '<img src = "'.$post_hover->guid.'" />';
					}
				?>
				<span class="oer_spn_category" id="spn_hover<?php echo $category->term_id;?>"><?php echo $image_hover; ?></span>
				<a id="a_spn_hover<?php echo $category->term_id; ?>" alt="spn_hover<?php echo $category->term_id; ?>" href="javascript:void" class="upload_category_image" title="upload_hover"><?php if(!empty($image)) { _e("Update", OER_SLUG); } else { _e("Upload", OER_SLUG); } ?></a>
				/
				<a id="r_spn_hover<?php echo $category->term_id; ?>" title="remove_hover" onclick="remove_img(this);" alt="spn_hover<?php echo $category->term_id; ?>" href="javascript:void"><?php _e("Remove", OER_SLUG); ?></a>
			</td>
		</tr>
		<?php
			$i++;
	 		}
		?>
	<input type="hidden" id="currntspan"/>
	</table>
	<input type="hidden" id="plugin_path" value="<?php echo plugins_url('wp-oer'); ?>"/>

<script type="text/javascript">
jQuery(document).ready(function() {

	var current_span ="";
   	jQuery(document).on('click', '.upload_category_image', function() {
		var current_span = jQuery(this).attr("alt");
		if(jQuery(this).attr("title")=="upload" || jQuery(this).attr("title")=="upload_hover" )   // if user want to upload a new image
		{
			jQuery("#currntspan").val(current_span);
			tb_show('','media-upload.php?TB_iframe=true');
			return false;
		}
		else
		{
			save_image(jQuery(this));   // save or update image after selecting the image from media pop up
		}
	});

   if((window.original_tb_remove == undefined) && (window.tb_remove != undefined))
   {
      window.original_tb_remove = window.tb_remove;
      window.tb_remove = function() {
         window.original_tb_remove();
      };
   }
   window.original_send_to_editor = window.send_to_editor;

   window.send_to_editor = function(htmldata) {
	   var imgurl = jQuery('img',htmldata).attr('src');
	   setimage(htmldata);   // setting image in span
	   tb_remove();
   }
});
</script>
