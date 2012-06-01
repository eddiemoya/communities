<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of custom-comment-list-table
 *
 * @author emoya1
 */
class Custom_Comments_List_Table extends WP_Comments_List_Table {

    private $comment_type;
    private $comment;

    function __construct($comment) {
        global $post_id, $status, $page;
        
        $this->comment = $comment;

        $post_id = isset($_REQUEST['p']) ? absint($_REQUEST['p']) : 0;

        if (get_option('show_avatars'))
            add_filter('comment_author', 'floated_admin_avatar');

        parent::__construct(array(
            'plural' => $comment->labels['plural'],
            'singular' => $comment->labels['singular'],
            'ajax' => true,
        ));
    }

    function prepare_items() {

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 10;


        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();


        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);


        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();






        /*         * *********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         * 
         * In a real-world situation, this is where you would place your query.
         * 
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         * ******************************************************************** */
        global $post_id, $comment_status, $search, $comment_type;

        $comment_status = isset($_REQUEST['comment_status']) ? $_REQUEST['comment_status'] : 'all';
        if (!in_array($comment_status, array('all', 'moderated', 'approved', 'spam', 'trash')))
            $comment_status = 'all';

        $comment_type = $this->comment->comment_type;

        $search = ( isset($_REQUEST['s']) ) ? $_REQUEST['s'] : '';

        $user_id = ( isset($_REQUEST['user_id']) ) ? $_REQUEST['user_id'] : '';

        $orderby = ( isset($_REQUEST['orderby']) ) ? $_REQUEST['orderby'] : '';
        $order = ( isset($_REQUEST['order']) ) ? $_REQUEST['order'] : '';

        $comments_per_page = 5; //$this->get_per_page( $comment_status );

        $doing_ajax = defined('DOING_AJAX') && DOING_AJAX;

        if (isset($_REQUEST['number'])) {
            $number = (int) $_REQUEST['number'];
        } else {
            $number = $comments_per_page + min(8, $comments_per_page); // Grab a few extra
        }

        $page = $this->get_pagenum();

        if (isset($_REQUEST['start'])) {
            $start = $_REQUEST['start'];
        } else {
            $start = ( $page - 1 ) * $comments_per_page;
        }

        if ($doing_ajax && isset($_REQUEST['offset'])) {
            $start += $_REQUEST['offset'];
        }

        $status_map = array(
            'moderated' => 'hold',
            'approved' => 'approve'
        );

        $args = array(
            'status' => isset($status_map[$comment_status]) ? $status_map[$comment_status] : $comment_status,
            'search' => $search,
            'user_id' => $user_id,
            'offset' => $start,
            'number' => $number,
            'post_id' => $post_id,
            'type' => $comment_type,
            'orderby' => $orderby,
            'order' => $order,
        );
        $_comments = get_comments($args);

        update_comment_cache($_comments);

        $this->items = array_slice($_comments, 0, $comments_per_page);
        $this->extra_items = array_slice($_comments, $comments_per_page);

        $total_comments = get_comments(array_merge($args, array('count' => true, 'offset' => 0, 'number' => 0)));

        $_comment_post_ids = array();
        foreach ($_comments as $_c) {
            $_comment_post_ids[] = $_c->comment_post_ID;
        }

        $_comment_post_ids = array_unique($_comment_post_ids);

        $this->pending_count = get_pending_comments_num($_comment_post_ids);

        $this->set_pagination_args(array(
            'total_items' => $total_comments,
            'per_page' => $comments_per_page,
        ));


        //print_pre($this->items);
    }

    function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
    }

    function get_columns() {
        global $post_id;

        $columns = array();

        if ($this->checkbox)
            $columns['cb'] = '<input type="checkbox" />';

        $columns['author'] = __('Author');
        $columns['comment'] = _x('Comment', 'column name');
        $columns['comment_type'] = 'Comment Type';

        if (!$post_id)
            $columns['response'] = _x('In Response To', 'column name');

        return $columns;
    }

    function column_comment_type($comment) {
        echo $comment->comment_type;
    }

}
