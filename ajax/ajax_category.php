<?php

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once($parse_uri[0].'/wp-load.php');

global $wpdb;
extract($_POST);

if($action == "insert_image" || $action== "insert_hover_image")
{

	$catergory_key ="";
	// checking hover imgage is to be uploaded
	if($action == "insert_hover_image")
	{
		$catergory_key="category_image_hover";
	}
	else
	{
		$catergory_key ="category_image";
	}

	$filetype = wp_check_filetype( basename( $image_path ), null );
	$my_post = array(
	  'post_title'    => basename( $image_path ),
	  'post_status'   => 'inherit',
	  'guid'		  => $image_path,
	  'post_mime_type'=> $filetype['type']
	);
	$post_id = wp_insert_attachment( $my_post, $image_path,'' );
	require_once( $parse_uri[0] . '/wp-admin/includes/image.php' );
	$attach_data = wp_generate_attachment_metadata( $post_id, $image_path );
	wp_update_attachment_metadata( $post_id, $attach_data );

	//wp_insert_post( $my_post );   // inserting image as post
	//$post_id = $wpdb->insert_id; // get inserted post id

	$table =  $wpdb->prefix."postmeta";
	//Checking this category already have an image
	$get_post_meta = $wpdb->get_var("SELECT * FROM $table WHERE meta_key='$catergory_key' AND meta_value='$term_id'");

	if($get_post_meta > 0)
	{
		// if category alredy have an image
		$wpdb->query("UPDATE $table SET post_id='$post_id',meta_value ='$term_id' WHERE  meta_key='$catergory_key' AND meta_value='$term_id'");
	}
	else
	{
		// if new image is assing to category
		add_post_meta($post_id, "$catergory_key", $term_id);
	}
}

if($action == "remove_image" || $action == "remove_image_hover")
{
	$table =  $wpdb->prefix."postmeta";

	$catergory_key ="";
	// Checking hover is to be removed
	if($action == "remove_image_hover")
	{
		$catergory_key="category_image_hover";
	}
	else
	{
		$catergory_key ="category_image";
	}
	$wpdb->query("UPDATE $table SET meta_value ='' WHERE  meta_key='$catergory_key' AND meta_value='$term_id'");
}
?>
