<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */

// wp class extension to display tables
require_once FA_dir('includes/custom_wp_list_table.php');
	
$sliders_table = new FA_List_Table(array(
	'singular'=>'slider', 
	'plural'=>'sliders'
));
// set columns to be displayed
$columns = array(
	'cb'        	=> '<input type="checkbox" />', //Render a checkbox instead of text
   	'post_title' 	=> __('Title', 'falite'),
   	'ID'			=> __('Slider ID', 'falite'),
   	'post_author'   => __('Author', 'falite'),
   	'post_date'  	=> __('Date', 'falite')
);
$sliders_table->columns = $columns;   
// set sortable columns
$sortable_columns = array(
   	'post_title'     => array('post_title',false),     //true means its already sorted
   	'post_author'    => array('post_author',false),
   	'post_date'  	 => array('post_date',true)
);    
// set delete and edit pages
$sliders_table->edit_page = 'featured-articles-lite';
    
$sliders_table->sortable_columns = $sortable_columns;
// get the records from DB
$sliders_table->prepare_items('fa_slider');
?>
<div class="wrap">
	<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
	<h2>Featured Articles Sliders <a class="button add-new-h2" href="<?php menu_page_url('featured-articles-lite-new-slideshow');?>"><?php _e('Add New', 'falite');?></a> </h2>
	<form method="post" action="<?php echo $current_page.'&noheader=true';?>">
		<?php wp_nonce_field('featured-articles-sliders-bulk-delete', 'FA_bulk_del');?>
		<?php $sliders_table->display();?>
    </form>       
	<br class="clear">
</div>