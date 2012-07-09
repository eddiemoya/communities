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
        add_action( 'admin_menu', array(__CLASS__, 'edit_comment_types') );
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
            
            
            self::$types[$comment_type] = new CCT_Model_Comment_Type($comment_type, $args);
            //print_pre(self::$types);
        }
    }

    public function add_menus() {
        foreach (self::$types as $type) {
            add_menu_page(
                    $type->labels['plural'], $type->labels['menu_name'], $type->capability, $type->comment_type . '-page', array($type, 'custom_comment_page'), $type->menu_icon, $type->menu_position
            );
        }
    }

    public function edit_comment_types() {
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

    public function custom_comment_form($comment_type) {
        global $id;

        if (null === $post_id)
            $post_id = $id;
        else
            $id = $post_id;

        $commenter = wp_get_current_commenter();
        $user = wp_get_current_user();
        $user_identity = !empty($user->ID) ? $user->display_name : '';

        $req = get_option('require_name_email');
        $aria_req = ( $req ? " aria-required='true'" : '' );
        $fields = array(
            'author' => '<p class="comment-form-author">' . '<label for="author">' . __('Name') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
            '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
            'email' => '<p class="comment-form-email"><label for="email">' . __('Email') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
            '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>',
            'url' => '<p class="comment-form-url"><label for="url">' . __('Website') . '</label>' .
            '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>',
        );

        $required_text = sprintf(' ' . __('Required fields are marked %s'), '<span class="required">*</span>');
        $defaults = array(
            'fields' => apply_filters('comment_form_default_fields', $fields),
            'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x('Comment', 'noun') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
            'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
            'logged_in_as' => '<p class="logged-in-as">' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
            'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published.') . ( $req ? $required_text : '' ) . '</p>',
            'comment_notes_after' => '<p class="form-allowed-tags">' . sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s'), ' <code>' . allowed_tags() . '</code>') . '</p>',
            'id_form' => 'commentform',
            'id_submit' => 'submit',
            'title_reply' => __('Leave a Reply'),
            'title_reply_to' => __('Leave a Reply to %s'),
            'cancel_reply_link' => __('Cancel reply'),
            'label_submit' => __('Post Comment'),
        );

        $args = wp_parse_args($args, apply_filters('comment_form_defaults', $defaults));

        if (comments_open()) {
            do_action('comment_form_before'); ?>

                <div id="respond">
                    <h3 id="reply-title">
                        <?php comment_form_title($args['title_reply'], $args['title_reply_to']); ?> 
                        <small><?php cancel_comment_reply_link($args['cancel_reply_link']); ?></small>
                    </h3> 
                    
                    <?php 
                    if (get_option('comment_registration') && !is_user_logged_in()) {
                        
                        echo $args['must_log_in'];
                        do_action('comment_form_must_log_in_after');
                 
                    } else {
    
                        ?><form action="<?php echo site_url('/wp-comments-post.php'); ?>" method="post" id="<?php echo esc_attr($args['id_form']); ?>"><?php 
                
                        do_action('comment_form_top');
                        
                        if (is_user_logged_in()) {
                            
                            echo apply_filters('comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity);
                            do_action('comment_form_logged_in_after', $commenter, $user_identity);
                            
                        } else {
                            
                            echo $args['comment_notes_before'];
                            do_action('comment_form_before_fields');
                            
                            foreach ((array) $args['fields'] as $name => $field) {
                                
                                echo apply_filters("comment_form_field_{$name}", $field) . "\n";
                    }
                    do_action('comment_form_after_fields');
                    
                    }
                echo apply_filters('comment_form_field_comment', $args['comment_field']);
                echo $args['comment_notes_after']; 
                
                ?> 
                    <p class="form-submit">
                        <input name="submit" type="submit" id="<?php echo esc_attr($args['id_submit']); ?>" value="<?php echo esc_attr($args['label_submit']); ?>" />
                    </p>

                    <?php do_action('comment_form', $post_id);?>
                                
                </form>
               <?php
               
             }
             
             ?></div><!-- #respond --><?php
             
        do_action('comment_form_after');
        } else {
            do_action('comment_form_comments_closed');
        }
    }

}

function register_comment_type($comment_type, $args) {
    CCT_Controller_Comment_Types::register_comment_type($comment_type, $args);
}

function get_comment_types() {
    return CCT_Controller_Comment_Types::$types;
}

function custom_comment_form($comment_type){
    
}