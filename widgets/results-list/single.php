<?php 

if(!is_ajax())
	get_template_part('parts/header', 'results-list');

loop('post-results-list');

if(!is_ajax())
	get_template_part('parts/footer', 'widget') ;?>