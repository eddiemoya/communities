
<?php 
if(!is_ajax())
	get_template_part('parts/header', 'results-list');

loop_by_type('results-list');

if(!is_ajax())
	get_template_part('parts/footer', 'widget') ;?>