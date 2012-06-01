<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of custom-comment-type
 *
 * @author emoya1
 */
class Comment_Type {
    

    public $comment_type;
    public $parent_domain;
    public $parent_type;
    public $labels = array();
    public $menu_position = '28'; 
    public $capability;
    public $notice_color = '';
    

    function __construct($comment_type, $args){
        
        $args = (object) $args;
        
        $this->comment_type     = $comment_type;
        $this->labels           = $args->labels;
        $this->parent_domain    = $args->parent_domain;
        $this->parent_type      = $args->parent_type;
        $this->capability       = $args->capability;
        $this->menu_position    = $args->menu_position;
        
        $this->add_actions();
    }
    
    function add_actions(){
        add_action( 'preprocess_comment',  array(&$this,'set_commentdata')  );
    }
    
    function set_commentdata($commentdata){
        
        static $parent = '';
        
        //We need to post parent to check it's post type, but no need to do it for each CCT, so its static.
        $parent = get_post($commentdata['comment_parent']);
        
        //Allow developers to apply custom logic to determine when their CCTs are applied - should return boolean
        $custom_logic = apply_filters('cct_logic_' . $this->comment_type, true, $commentdata, $parent);
        
        //Lets test the outcome of any custom logic - if is false, return $commentdata unchanged.
        if(!$custom_logic)
            return $commentdata;
        
        //Lets check to make sure this comment was made on on the correct post type
        if($parent->post_type == $this->parent_type) 
            $commentdata['comment_type'] = $this->comment_type;
        
        
        return $commentdata;  
    }
    
    function custom_comment_page(){
        apply_filters('cct_admin_page', Custom_Comment_Admin_Page::admin_page( $this ), $this);   
    }
    

    
}
?>
