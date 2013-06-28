<?php

get_template_part('parts/header');

	$term = wp_get_object_terms($post->ID, $post->post_type);
	$term = $term[0];
	$node = new WP_Node($term->term_id, $term->taxonomy);

	$filter = get_query_var('sf_filter');
	if(isset($filter)){
		$filter = get_query_var('sf_filter');
	} else {
		$filter = 'category';
	}
	
	$layout_setting = $node->get_meta_data("sf_category_layout");
	$layout_id = ($layout_setting > 0 ) ? $layout_setting : $node->post->ID;

	query_posts(
		array(
			'tax_query' => array(
				array(
					'taxonomy' => $term->taxonomy,
					'terms' => $term->term_id,
					'field' => 'id'
				)
			)
			)
		);
	WidgetPress_Controller_Widgets::display_dropzones($layout_id);
	wp_reset_query();
			


get_template_part('parts/footer');
