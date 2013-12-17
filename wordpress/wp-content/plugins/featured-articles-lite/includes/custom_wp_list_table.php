<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */

/**
 * Load WP_List_Table class
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Display slideshows list in a standard Wordpress table.
 */
class FA_List_Table extends WP_List_Table {
    
    /**
     * Constructor. Takes as argument the singular/plural of items 
     * and whether the table is ajax based
     * @param array $args
     */
    function __construct($args = array()){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( $args );
        
    }    
    /**
     * The default table column if custom function is not defined.
     * Tries to determine if column is date or author name and returns the corresponding value
     * @param array $item
     * @param string $column_name
     */
    function column_default($item, $column_name){
        
    	if( !array_key_exists($column_name, $item) ){
    		return print_r($item,true);
    	}else{
    		if( strstr($column_name, 'date') ){
    			$item[$column_name] = get_the_time( 'Y/m/d', $item['ID'] ).( array_key_exists('post_status', $item) ? '<br />'.ucfirst($item['post_status']) : '' );
    		}
    		if( strstr($column_name, 'author') ){
    			$author = get_userdata( $item['post_author'] );
    			$item[$column_name] = !empty($author->user_nicename) ? $author->user_nicename : $author->user_login;
    		}
    		
    		return $item[$column_name];
    	}  	
    	
    }    
        
    /**
     * Edit page. Use it as page.php, the rest of the link will be completed by the function.
     * Returned link is admin.php?page=$edit_page&id=item_id&action=edit 
     * @var string
     */
    var $edit_page = null;
    /**
     * Manages column for post_title
     * @param array $item
     */
    function column_post_title($item){
        
    	$raw_del_url = sprintf('?page=%s&action=%s&item_id=%s&noheader=true', $_REQUEST['page'],'delete',$item['ID']);
    	$del_url = wp_nonce_url($raw_del_url);
    	
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&id=%s&action=edit">'.__('Edit', 'falite').'</a>',$this->edit_page,$item['ID']),
            'delete'    => '<a href="'.$del_url.'" onclick="return confirm(\''.__('Do you really want to delete this item?', 'falite').'\')">'.__('Delete', 'falite').'</a>',
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ sprintf('<a href="?page=%s&id=%s&action=edit">%s</a>', $this->edit_page, $item['ID'], $item['post_title']),
            /*$2%s*/ $this->row_actions($actions)
        );
    }
    
    /**
     * The checkbox for bulk actions column
     * @param array $item
     */
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="item_id[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],
            /*$2%s*/ $item['ID']
        );
    }
    
    /**
     * The columns that should be displayed
     * @var array
     */
    var $columns = array();
    /**
     * Returns the columns of the table as specified
     */
    function get_columns(){
        
    	if( empty($this->columns) ){
    		wp_die('You must set the columns to be displayed for the table.');
    	}
    	
    	return $this->columns;
    }
    
    /**
     * The sortable columns
     * @var array
     */
 	var $sortable_columns = array();
 	/**
 	 * (non-PHPdoc)
 	 * @see WP_List_Table::get_sortable_columns()
 	 */
    function get_sortable_columns() {        
    	return $this->sortable_columns;
    }
    
    /**
     * Sets the bulk actions available in table
     * @var array
     */        
    var $bulk_actions = array();    
    /**
     * (non-PHPdoc)
     * @see WP_List_Table::get_bulk_actions()
     */
    function get_bulk_actions() {
    	
    	if( empty( $this->bulk_actions ) ){
    		$this->bulk_actions = $actions = array(
	            'delete'    => __('Delete', 'falite')
	        );
    	}    	
        return $this->bulk_actions;
    }
    
    /**
     * (non-PHPdoc)
     * @see WP_List_Table::prepare_items()
     */    
    function prepare_items($post_type) {
        
    	$columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $per_page = 20;
    	$current_page = $this->get_pagenum();
        
        $q = array(
			'post_type'=>$post_type,
			'orderby' => 'date',
		    'order' => 'DESC',
			'posts_per_page'=>$per_page,
	    	'offset'=>($current_page-1)*$per_page
		);
		if( isset($_GET['orderby']) && array_key_exists($_GET['orderby'], $columns) ){
			$q['orderby'] = $_GET['orderby'];
		}
		if( isset($_GET['order']) && ('asc' == $_GET['order'] || 'desc'==$_GET['order'] )){
			$q['order'] = $_GET['order'];
		}	
		
		$query = new WP_Query($q);
		
		$data = array();    
        if( $query->posts ){
        	foreach($query->posts as $k=>$item){
        		$data[$k] = (array)$item;
        	}
        }
        
        $total_items = $query->found_posts;
        $this->items = $data;
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  
            'per_page'    => $per_page,                     
            'total_pages' => ceil($total_items/$per_page)  
        ) );
    }  
}
?>