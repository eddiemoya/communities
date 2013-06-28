<?php

get_template_part('parts/header');
	
	$term = wp_get_object_terms($post->ID, $post->post_type);
	$term = $term[0];
	$node = new WP_Node($term->term_id, $term->taxonomy);

	$filter = get_query_var('sf_filter');
	if(!empty($filter)){
		$filter = get_query_var('sf_filter');
	} else {
		$filter = 'category';
	}
	
	$layout_setting = $node->get_meta_data("sf_{$filter}_layout");
	$layout_id = ($layout_setting > 0 ) ? $layout_setting : $node->post->ID;

	$tax_query[] = array(
		'taxonomy' => $node->term->taxonomy,
		'terms' => $node->term->term_id,
		'field' => 'id'
	);

	$post_type = '';
	switch($filter){
		case 'post': 
		case 'guide':
		case 'question':
			$post_type = $filter; 
			break;
		case 'category':
			$post_type = array('post', 'guide', 'question');
			break;
		case 'video':
			$tax_query[] = array(
				'taxonomy' => 'post_format',
				'terms' => $filter,
				'field' => 'slug'
			);
			$tax_query['relation'] = 'AND';
			$post_type = array('post', 'guide', 'question');
		break;
	}

	$query = array(
		'post_type' => $post_type,
		'tax_query' => $tax_query
	);

	query_posts($query);
	
	WidgetPress_Controller_Widgets::display_dropzones($layout_id);
	wp_reset_query();
			


get_template_part('parts/footer');
