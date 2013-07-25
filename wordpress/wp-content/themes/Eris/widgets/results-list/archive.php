<?php 

if(!is_ajax()) 
{
	get_template_part('parts/header', 'results-list');
}

$search_terms = (!empty($_GET['s'])) ? trim($_GET['s']) : "";

$post_type = get_query_var('post_type');
$post_type = (is_array($post_type)) ? $post_type[0] : $post_type;
$post_type = (isset($_GET['s'])) ? 'question' : $post_type;

if(!empty($search_terms))
{
	loop(array($post_type, 'post'), null, 'widgets/results-list', 'parts/no-results');
	
	if(have_posts())
	{
		echo '<section class="pagination">';
		echo posts_nav_link();
		echo '</section>';
	}
}
else
{
	get_template_part('parts/no-results');
}

if(!is_ajax())
{
	get_template_part('parts/footer', 'widget');
}

?>