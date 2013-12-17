<?php

/**
 * Custom Comment Type
 * 
 * @package Custom Comment Type
 * @since 1.0
 */
class CCT_Model_Comment_Type {

    var $comment_type;
    var $labels = array();
    var $parent_domain = 'post';
    var $parent_type;
    var $capability = 'administrator';
    var $moderation_queue;
    var $menu_icon;
    var $menu_position = '28';
    var $template;
    public $notice_color = '';
    

     function __construct($comment_type, $args = array()){
        
         if (!empty($args)) {
            $args = (object) $args;

            $this->comment_type = $comment_type;
            $this->labels = $args->labels;
            $this->parent_domain = $args->parent_domain;
            $this->parent_type = $args->parent_type;
            $this->capability = $args->capability;
            $this->menu_position = $args->menu_position;
            $this->menu_icon = $args->menu_icon;
            //$this->template = $args->form_template;
        } else {
            $this->get_settings($comment_type);
        }
        
        
        
        $this->add_actions();
    }
    
    function add_actions(){
        add_action( 'preprocess_comment',  array(&$this,'set_commentdata')  );
    }

    /**
     * 
     */
    function set_commentdata($commentdata){
        
        static $parent = '';
        
        //We need to post parent to check it's post type, but no need to do it for each CCT, so its static.
        $parent = get_post($commentdata['comment_parent']);
        
        //Allow developers to apply custom logic to determine when their CCTs are applied - should return boolean
        $filter_args = array(true, $this, $commentdata, $parent);
        $custom_logic = apply_filters_ref_array("cct_condition_{$this->comment_type}", $filter_args);
        
        //Lets test the outcome of any custom logic - if is false, return $commentdata unchanged.
        if(!$custom_logic)
            return $commentdata;
        
        //Lets check to make sure this comment was made on on the correct post type
        //if($parent->post_type == $this->parent_type) 
        $commentdata['comment_type'] = $this->comment_type;
        
        
        return $commentdata;  
    }
 
    /**
     * This is what happens when you don't strategize before you start writing.
     * 
     * Clusterfucks.
     * 
     * @param type $cct_name 
     */
    private function get_settings($comment_type) {

        $settings = array();
        $options = get_option('cct_options');

        for ($n = 1; $n < $options['comment_type_count'] + 1; $n++) {

            $singular_name = $options['cct_name_singular_' . $n];
            $plural_name = $options['cct_name_plural_' . $n];
            $moderation = $options['cct_moderation_panel_' . $n];
            $menu_position = $options['cct_menu_position_' . $n];
            $icon_url = $options['cct_icon_url_' . $n];
            $slug = str_replace(' ', '_', strtolower($plural_name));
            $post_types = array();

            $possible_post_types = get_post_types(array('public' => true), 'objects');

            foreach ($possible_post_types as $possible_post_type) {
                $post_type_options_slug = 'cct_post_type_' . $slug . '_' . $possible_post_type->name;
                if (isset($options[$post_type_options_slug]))
                    ;
                $post_types[] = $options[$possible_post_type];
            }

            $post_types = $options['cct_name_plural_' . $n];

            if (!empty($singular_name) && !empty($plural_name) && $comment_type == $slug) {


                $this->labels = array(
                    'name' => $plural_name,
                    'singular_name' => $singular_name,
                    'add_new' => "Add New",
                    'add_new_item' => "Add New {$singular_name}",
                    'edit_item' => "Edit {$singular_name}",
                    'new_item' => "New {$singular_name}",
                    'all_items' => "All {$plural_name}",
                    'view_item' => "View {$plural_name}",
                    'search_items' => "Search {$plural_name}",
                    'not_found' => "No {$plural_name} found",
                    'not_found_in_trash' => "No {$plural_name} found in Trash",
                    'parent_item_colon' => "{$singular_name}:",
                    'menu_name' => $singular_name
                );

                $this->moderation_queue = $moderation;
                $this->parent_types = $post_types;
                $this->icon_url = $icon_url;
                $this->menu_position = $menu_position;

            }
        }
    }
    
    public function custom_comment_page(){
        apply_filters('cct_admin_page', CCT_Controller_Comment_Types::custom_comment_page( $this ), $this);   
    }

}
