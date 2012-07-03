<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cct-controller-comment-types
 *
 * @author emoya1
 */
class CCT_Controller_Comment_Types {
    
    /**
     * @var type 
     */
    static public $types = array();
    
    /**
     * Start your engines. 
     */
    public function init(){
        
        add_action( 'admin_menu',  array(__CLASS__, 'add_menus')  );
        add_action( 'admin_menu', array(__CLASS__, 'filter_builtin_comments') );
    }
    
    /**
     *
     * @param type $comment_type
     * @param type $args 
     */
    public function register_comment_type($comment_type, $args){
        
        if(!in_array($comment_type, self::$types)){
            
            $defaults = array(
                'labels' => array(),
                'description' => '', 
                'capabilities' => array(), 
                '_edit_link' => 'post.php?post=%d', 
                'public' => false, 
                'rewrite' => true, 
                'supports' => array(), 
                'menu_position' => null, 
                'menu_icon' => null,
            );
            
            $args = wp_parse_args($args, $defaults);
            
            
            self::$types[] = new CCT_Model_Comment_Type($comment_type, $args);
            //print_pre(self::$types);
        }
    }
    
    
    public function add_menus(){
        foreach(self::$types as $type){
            add_menu_page( 
                    $type->labels['plural'], 
                    $type->labels['menu_name'], 
                    $type->capability, 
                    $type->comment_type . '-page', 
                    array($type, 'custom_comment_page') ,
                    '', 
                    $type->menu_position
            );
        } 
    }
    
   
   public function filter_builtin_comments() {
       global $menu;
       $menu[25][2] = 'edit-comments.php?comment_type=comment';
   }
    
    
    public function custom_comment_page($comment) {
        
        //Create an instance of our package class...
        $comments_table = new Custom_Comments_List_Table($comment);
        
        //Fetch, prepare, sort, and filter our data...
        $comments_table->prepare_items();
        
        include_once (CCT_VIEWS . 'cct-view-comment-page.php');
    }
}


function register_comment_type($comment_type, $args){
    CCT_Controller_Comment_Types::register_comment_type($comment_type, $args);
}


function get_comment_types(){
    return CCT_Controller_Comment_Types::$types;
}