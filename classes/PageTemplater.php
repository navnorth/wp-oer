<?php
/**
 * This is the Page Templater Object
 **/
class Page_Templater {
    /**
     * A Unique Plugin Identifier
     **/
    protected $_slug;
    
    /**
     * Object Instance
     **/
    private static $_instance;
    
    /**
     * Array of Templates for this plugin
     **/
    protected $_templates;
    
    /**
     * Get Instance of Page_Templater object
     **/
    public static function get_instance(){
        //Check if instance is set
        if ( null == self::$_instance ){
            self::$_instance = new Page_Templater();
        }
        return self::$_instance;
    }
    
    /**
     * Initializing the Page_Templater object
     **/
    private function __construct(){
        $this->_templates = array();
        
        // Add a filter to the template include to determine if the page hasbeen assigned
        add_filter( 'template_include' , array( $this , 'view_project_template' ) );
        
        // Add templates to array
        $this->_templates = array( 'templates/single-resource.php' => 'Resource' );
    }
    
    /**
     * Check if the template is assigned to a page
     **/
    public function view_project_template( $template ){
        global $post;
        
        // Checks page is assigned to this template
        if ( !isset( $this->_templates[get_post_meta( $post->ID , '_wp_page_template' , true )] ) ){
            return $template;
        }
        
        // Get plugin template file
        $file = plugin_dir_path(__FILE__) . get_post_meta( $post->ID , '_wp_page_template' , true );
        
        // Checks if the file exists
        if ( file_exists( $file ) ){
            return $file;
        }
        else {
            echo $file;
        }
        
        return $template;
    }
}
add_action( 'plugins_loaded' , array( 'Page_Templater' , 'get_instance' ) );
?>