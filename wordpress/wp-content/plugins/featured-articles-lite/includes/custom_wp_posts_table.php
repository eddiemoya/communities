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
 * Displays a list of pages to allow selection of content for a slideshow
 */
class FA_List_Posts_Table extends WP_List_Table {
    
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
     * Post title column formatting
     */
    function column_post_title($item){
    	return sprintf('<label for="content_%1$d" id="label_content_%1$d">%2$s</label>', $item['ID'], apply_filters('the_title', $item['post_title']));    	
    }
    
    /**
     * The checkbox for bulk actions column
     * @param array $item
     */
    function column_cb($item){
    	return sprintf(
            '<input type="checkbox" name="display_content[]" value="%1$s" id="content_%1$s" class="%2$s" />',
            /*$1%s*/ $item['ID'],
            /*$2%s*/ 'page'        			
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
     * (non-PHPdoc)
     * @see WP_List_Table::prepare_items()
     */    
    function prepare_items() {
        
    	$columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->post_type = 'page';
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $per_page = 10;
    	$current_page = $this->get_pagenum();
        
        $q = array(
			'post_type'=>'page',
			'orderby' => 'date',
		    'order' => 'DESC',
	    	'posts_per_page'=>$per_page,
	    	'offset'=>($current_page-1)*$per_page
		);
		
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