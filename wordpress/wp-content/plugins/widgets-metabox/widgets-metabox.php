<?php /*
Plugin Name: Widgets Metabox
Plugin URI: http://wordpress.org/extend/plugins/media-categories-2
Description:  Allows users to assign categories to media with a clean and simplified, filterable category meta box and use shortcodes to display category galleries
Version: 1.3
Author: Eddie Moya
Author URL: http://eddiemoya.com
*/

define(WMB_PATH, WP_PLUGIN_DIR . '/widgets-metabox/');

class Widgets_Metabox {
    
    public function __construct(){
        global $post;
       
        add_action('admin_head', array($this, 'register_sidebars'));
        
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 11); 
        add_action('save_post', array($this, 'save'));
       
        
    }
    
    
    public function add_meta_boxes(){
        global $post;
        if($this->is_enabled()){
            add_meta_box('widgets-metabox', 'Widgets', array($this, 'widgets_metabox'), 'post', 'normal', 'low', $post);
        }
        
        add_meta_box('widgets-metabox-enabled', 'Enable Widgets', array($this, 'enable_widgets_metabox'), 'post', 'side', 'low', $post);
    }
    
    public function enable_widgets_metabox(){  
        $this->include_file('views/enable-widgets');
    }
    
    public function widgets_metabox($post, $widgets_settings){
        $this->include_file('views/widgets');
    }

    public function register_sidebars(){
        if($this->is_enabled()){
            $this->create_page_sidebar();
        }
    }
    public function create_page_sidebar(){
        global $post;
        
        print_r($post);
        register_sidebar( array(
            'name'          => $post->post_title . ' Widgets',
            'id'            => $this->get_sidebar_id(),
            'description'   => sprintf(__('Widget Area specifically for "%s"'), $post->post_title),
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>' 
            
        ));
    }
    
    function is_enabled(){
        global $post_id;
        $meta = get_post_meta($post_id, 'widgets-metabox-enable', true);
        return ( $meta == "on" );
    }
    
    function get_sidebar_id(){
        global $post_id;
        return 'widget-metabox-'. $post_id;
    }
    
    
    function save( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    update_post_meta($post_id, 'widgets-metabox-enable', $_POST['widgets-metabox-enable']);

    }

    private function include_file($path){
        include (WMB_PATH . $path . '.php');
    }

}

$widgets = new Widgets_Metabox();
