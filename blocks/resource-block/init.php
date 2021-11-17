<?php
/**
 * Plugin Name:       OER Resource Block
 * Description:       Display single resource block anywhere on the page.
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-oer-resource-block
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */
function oer_create_block_wp_oer_resource_block_init() {
    $dir = dirname( __FILE__ );

    $script_asset_path = "$dir/build/index.asset.php";
    if ( ! file_exists( $script_asset_path ) ) {
        throw new Error(
            'You need to run `npm start` or `npm run build` for the "create-block/wp-oer-subjects-index" block first.'
        );
    }
    $index_js     = 'build/index.js';
    $script_asset = require( $script_asset_path );
    wp_register_script(
        'wp-oer-resource-block-editor',
        plugins_url( $index_js, __FILE__ ),
        $script_asset['dependencies'],
        $script_asset['version']
    );
    wp_localize_script( 'wp-oer-resource-block-editor', 'oer_resource', array( 'home_url' => home_url(), 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_set_script_translations( 'wp-oer-resource-block-editor', 'wp-oer-resource-block' );

    $editor_css = 'build/index.css';
    wp_register_style(
        'wp-oer-resource-block-editor-style',
        plugins_url( $editor_css, __FILE__ ),
        array(),
        filemtime( "$dir/$editor_css" )
    );

    $style_css = 'build/style-index.css';
    wp_register_style(
        'wp-oer-resource-block-style',
        plugins_url( $style_css, __FILE__ ),
        array(),
        filemtime( "$dir/$style_css" )
    );

    register_block_type( 'wp-oer-plugin/wp-oer-resource-block', array(
        'editor_script' => 'wp-oer-resource-block-editor',
        'editor_style'  => 'wp-oer-resource-block-editor-style',
        'style'         => 'wp-oer-resource-block-style',
        'render_callback' => 'oer_display_resource_block'
    ) );
}
add_action( 'init', 'oer_create_block_wp_oer_resource_block_init' );

function oer_get_resources_api(){
    register_rest_route( 'oer-resource-block/v1', 'resources', array(
        'methods' => 'GET',
        'callback' => 'oer_get_resources_for_options',
        'permission_callback' => function(){
            return current_user_can('edit_posts');
        }
    ));
}
add_action( 'rest_api_init' , 'oer_get_resources_api' );

// Get Only ID, and Title of Resource
function oer_get_resources_for_options(){
    $resources = [];
    $args = array(
        'post_type' => 'resource',
        'posts_per_page' => -1
    );
    $query = new WP_Query( $args );

    foreach($query->posts as $resource){
        $rs = array();
        $rs['title'] = $resource->post_title;
        $rs['id'] = $resource->ID;
        $resources[] = $rs;
    }
    $response = new WP_REST_Response($resources, 200);
    $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
    return $response;
}

function oer_display_resource_block( $attributes ){
    return json_encode($attributes);
}
