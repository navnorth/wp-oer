<?php
/**
 * Default Category Template of Resource Post Type
 **/
get_header();
?>
<div class="cntnr">
    <div class="category_sidebar">
        <?php
            
           // Get Taxonomies of Resource Post Type
           $resource_taxonomies = get_object_taxonomies( 'resource' );
           var_dump($resource_taxonomies);
           
           // Check if there are taxonomies returned
           if ( count( $resource_taxonomies ) > 0 ) {
            foreach($resource_taxonomies as $resource_taxonomy) {
                //Only return all categories created under Resource Categories
                if ( $resource_taxonomy == 'resource-category' ) {
                     $args = array(
                    'orderby' => 'name',
                    'show_count' => 0,
                    'pad_counts' => 0,
                    'hierarchical' => 1,
                    'taxonomy' => $resource_taxonomy,
                    'title_li' => ''
                  );
                //
                //wp_list_categories( $args );
                    $resource_categories = get_categories( $args );
                    var_dump($resource_categories);
                    
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