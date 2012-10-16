<?php 
if(!is_ajax()) {
    get_template_part('parts/header', 'widget');
} 
 	$post_type = get_query_var('post_type');
    $post_type = (is_array($post_type)) ? $post_type[0] : $post_type;

    echo "<ul>";

    	loop('post', null, 'widgets/summary-list', 'parts/no-results');

    echo "</ul>";
    
    if(is_widget('filter-post-type') == 'question'):
    ?>
		<a href="<?php echo get_site_url(get_current_blog_id(), 'questions');?>">See More</a>
	<?php 
	endif;

if(!is_ajax()){ 
    get_template_part('parts/footer', 'widget');
}
?>