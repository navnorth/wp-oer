<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class OER_Subject_Area_Widget extends WP_Widget{
    
    //Constructor
    function __construct(){
        parent::__construct(
                          false,
                          $name = __('Subject Area Widget', OER_SLUG),
                          array('description'=>__('This is the Subject Area widget', OER_SLUG))
                          );
    }
    
    //Widget Form Creation
    function form($instance) {
        //Check widget values
        if ($instance) {
            $title = esc_attr($instance['title']);
        } else {
            $title = '';
        }
        ?>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }
    
    //Widget Update
    function update($new_instance, $old_instance) {
        
        $instance = $old_instance;
        
        $instance['title'] = strip_tags(sanitize_text_field($new_instance['title']));
        
        return $instance;
    }
    
    //Widget Display
    function widget($args, $instance) {
        global $wpdb;
        
        extract($args);
        
        $title = apply_filters('widget_title', $instance['title']);
        
        echo $before_widget;
        
        //echo '<div class="wp_subject_area_widget">';
        
        //Get ID of Resource Category
        $term_id = get_queried_object_id();
        
        //Get Term based on Category ID
        //$term = get_the_title();
        $terms = get_term_by( "id" , $term_id , 'resource-subject-area' , object );
        $term = $terms->name;
        
        $rsltdata = get_term_by( "name", $term, "resource-subject-area", ARRAY_A );
        
        $parentid = array();
        if($rsltdata['parent'] != 0)
        {
                $parent = oer_get_parent_term($rsltdata['parent']);
                for($k=0; $k < count($parent); $k++)
                {
                        if ($parent[$k]) {
                                //$idObj = get_category_by_slug($parent[$k]);
                                $idObj = get_term_by('slug', $parent[$k], 'resource-subject-area');
                                $parentid[] = $idObj->term_id;
                        }
                }
        }
        
        ?>
        <div class="oer_resource_category_sidebar template_resource_category_sidebar">
	<?php
        
        if ($title){
            echo $before_title . $title . $after_title;
        }
        
	echo '<ul class="oer_resource_category">';
			$args = array('hide_empty' => 0, 'taxonomy' => 'resource-subject-area', 'parent' => 0);
			$categories= get_categories($args);
                        
			foreach($categories as $category)
			{
				$children = get_term_children($category->term_id, 'resource-subject-area');
				$getimage = $wpdb->get_results( $wpdb->prepare ( "SELECT * FROM ".$wpdb->prefix.'postmeta'." WHERE meta_key='category_image' AND meta_value=%s" , $category->term_id));
				if(!empty($getimage)){
                                    $attach_icn = get_post($getimage[0]->post_id);
                                } else {
                                    $attach_icn = array();
                                }
				
				if($rsltdata['term_id'] == $category->term_id)
				{
					$class = ' activelist current_class';	
				}
				elseif(in_array($category->term_id, $parentid))
				{
					$class = ' activelist current_class';
				}
				else
				{
					$class = '';
				}
				
				if( !empty( $children ) )
				{
					echo '<li class="oer-sub-category has-child'.esc_attr($class).'"><span onclick="toggleparent(this);"><a href="'. esc_url(site_url() .'/'.$category->taxonomy.'/'. $category->slug) .'" title="'. esc_attr($category->name) .'" >'. esc_html($category->name) .'</a></span>';
				}
				else
				{
					echo '<li class="oer-sub-category'.esc_attr($class).'"><span onclick="toggleparent(this);"><a href="'. esc_url(site_url() .'/'.$category->taxonomy.'/'. $category->slug) .'"  title="'. esc_attr($category->name) .'" >'. esc_html($category->name) .'</a></span>';
				}
				
				echo oer_get_category_child( $category->term_id, $rsltdata['term_id']);
				echo '</li>';
			}
	echo '</ul>';
	?>
        </div> <!--Left Sidebar-->
        <?php
        //echo '</div>';
        
        echo $after_widget;
    }
}
//REMOVED DEPRECATED
//add_action('widgets_init', create_function('', 'return register_widget("OER_Subject_Area_Widget");'));
function oa_social_login_init_widget (){
    return register_widget('OER_Subject_Area_Widget');
}
add_action ('widgets_init', 'oa_social_login_init_widget');