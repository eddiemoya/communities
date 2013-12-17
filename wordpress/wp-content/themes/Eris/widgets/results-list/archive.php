<?php 

if(!is_ajax()) 
{
	get_template_part('parts/header', 'results-list');
}

$search_terms = isset($_GET['s']) ? trim($_GET['s']) : "";

$post_type = get_query_var('post_type');
$post_type = (is_array($post_type)) ? $post_type[0] : $post_type;
$post_type = (isset($_GET['s'])) ? 'question' : $post_type;

if(isset($_GET['s']) && empty($search_terms))
{
	get_template_part('parts/no-results');
}
else
{
	loop(array($post_type, 'post'), null, 'widgets/results-list', 'parts/no-results');
	
	if(have_posts())
	{
		echo '<section class="pagination">';
		echo posts_nav_link();
		echo '</section>';
	}
}

if(!is_ajax())
{
	get_template_part('parts/footer', 'widget');
}

?>