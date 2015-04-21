<?php
/**
 * Default Category Template of Resource Post Type
 **/
/** Add default stylesheet for Resource page **/
wp_register_style( "resource-category-styles", OER_URL . "css/resource-category-style.css" );
wp_enqueue_style( "resource-category-styles" );
 
get_header();
?>
<div class="cntnr">
    <div class="resource_category_sidebar">
        <?php
            
           // Get Taxonomies of Resource Post Type
           $resource_taxonomies = get_object_taxonomies( 'resource' );
           
           // Check if there are taxonomies returned
           if ( count( $resource_taxonomies ) > 0 ) {
            foreach($resource_taxonomies as $resource_taxonomy) {
                //Only return all categories created under Resource Categories
                if ( $resource_taxonomy == 'resource-category' ) {
                    $args = array(
                        'show_option_all'    => '',
                        'orderby'            => 'name',
                        'order'              => 'ASC',
                        'style'              => 'list',
                        'show_count'         => 0,
                        'hide_empty'         => 0,
                        'use_desc_for_title' => 1,
                        'child_of'           => 0,
                        'feed'               => '',
                        'feed_type'          => '',
                        'feed_image'         => '',
                        'exclude'            => '',
                        'exclude_tree'       => '',
                        'include'            => '',
                        'hierarchical'       => 1,
                        'title_li'           => '',
                        'show_option_none'   => '',
                        'number'             => null,
                        'echo'               => 1,
                        'depth'              => 0,
                        'current_category'   => 0,
                        'pad_counts'         => 0,
                        'taxonomy'           => $resource_taxonomy,
                        'walker'             => null
                    );
                    echo "<ul class='resource-category'>";
                    wp_list_categories($args);
                    echo "</ul>";
                }
            }
           }
        ?>
        </ul>
    </div><!-- Category Sidebar -->
    <div class="rightcatcntr">
    <?php if ( have_posts() ) : ?>
            <header class="archive-header">
                <h1 class="archive-title"><?php printf( __( 'Category Archives: %s', 'wp-oer' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>

            <?php if ( category_description() ) : // Show an optional category description ?>
                    <div class="archive-meta"><?php echo category_description(); ?></div>
            <?php endif; ?>
        </header><!-- .archive-header -->
            This is a category with posts!
        <?php else : ?>
            This is a category with no posts!
        <?php endif; ?>
    </div><!-- Category Posts -->
</div>
<?php
get_footer();
?>