<?php
/** Subject Resources block version 2.0 **/
function wp_oer_register_resources_block(){
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "create-block/my-oer-block" block first.'
		);
	}

	// Main Script for the Block
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'wp-oer-resources-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_set_script_translations( 'wp-oer-resources-block', OER_SLUG );

	// Backend styles
	$editor_css = 'build/index.css';
	wp_register_style(
		'wp-oer-resources-block-editor-style',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	// Frontend styles
	$style_css = 'build/style-index.css';
	wp_register_style(
		'wp-oer-resources-block-frontend-style',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'wp-oer-plugin/wp-oer-resources-block', array(
		'editor_script' => 'wp-oer-resources-block-editor',
		'editor_style'  => 'wp-oer-resources-block-editor-style',
		'style'         => 'wp-oer-resources-block-frontend-style',
		'render_callback' => 'wp_oer_display_subject_resources'
	) );
}
add_action( 'init', 'wp_oer_register_resources_block' );

function register_api_routes(){
	register_rest_route(
		'oer/v2',
		'subjects',
		array(  'methods'=>'GET',
			'callback'=>'wp_oer_get_subject_areas',
			'permission_callback' => function(){
				return current_user_can('edit_posts');
			}
		)
	);
}
add_action( 'rest_api_init' , 'register_api_routes' );

function wp_oer_display_subject_resources( $attributes ){
	return $attributes;
}

function wp_oer_get_subject_areas() {
	$subjects_areas = array();

	// Get Top Level Subject Areas
	$subject_args = array(
		'taxonomy' => 'resource-subject-area',
		'hide_empty' => false,
		'parent' => 0,
		'number' => 0
	);

	$subject_query = new WP_Term_Query($subject_args);

	if (!empty($subject_query->terms)){
		$index = 0;
		foreach($subject_query->terms as $subject) {
			$subject_areas[$index]['term_id'] = $subject->term_id;
			$subject_areas[$index]['name'] = $subject->name;
			$subject_areas[$index]['type'] = 'parent';
			$subject_areas[$index]['parent'] = $subject->parent;
			$subject_areas[$index]['slug'] = $subject->slug;
			$subject_areas[$index]['count'] = $subject->count;
			$index++;

			// Get Child Subject Areas
			$child_subject_args = array(
				'taxonomy' => 'resource-subject-area',
				'parent' => $subject->term_id,
				'hide_empty' => false,
				'number' => 0
			);

			$child_subject_query = new WP_Term_Query($child_subject_args);
			if (!empty($child_subject_query->terms)) {
				foreach($child_subject_query->terms as $childSubject){
					$subject_areas[$index]['term_id'] = $childSubject->term_id;
					$subject_areas[$index]['name'] = $childSubject->name;
					$subject_areas[$index]['type'] = 'child';
					$subject_areas[$index]['parent'] = $childSubject->parent;
					$subject_areas[$index]['slug'] = $childSubject->slug;
					$subject_areas[$index]['count'] = $childSubject->count;
					$index++;
				}
			}
		}
	}
	return $subject_areas;
}
