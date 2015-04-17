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
        
        // Add a filter to the attributes metabox to inject template into the cache
        add_filter( 'page_attributes_dropdown_pages_args' , array( $this , 'register_project_templates' ) );
        
        // Add a filter to the save post to inject template into the page cache
        add_filter( 'wp_insert_post_data' , array( $this , 'register_project_templates' ) );
    }
}

?>