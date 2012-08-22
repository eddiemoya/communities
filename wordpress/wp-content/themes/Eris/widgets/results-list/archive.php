
<?php 

if(!is_ajax())
	get_template_part('parts/header', 'results-list');

if (!have_posts()) {
    echo "<p>No Results</p>";
}

loop('post-results-list');

if(!is_ajax())
	get_template_part('parts/footer', 'widget') ;?>