<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-custom-comment-admin-page
 *
 * @author emoya1
 */
class Custom_Comment_Admin_Page {

    function admin_page($comment) {
        
        //Create an instance of our package class...
        $comments_table = new Custom_Comments_List_Table($comment);
        
        //Fetch, prepare, sort, and filter our data...
        $comments_table->prepare_items();
        
        include_once CCT_PATH . 'views/admin.php';
    }

}