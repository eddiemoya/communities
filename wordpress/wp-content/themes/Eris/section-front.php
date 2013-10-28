<?php

get_template_part('parts/header');
	
	if(class_exists('WP_Node_Factory') && class_exists('WidgetPress_Controller_Widgets')){
		$term = wp_get_object_terms($post->ID, $post->post_type);
		$term = $term[0];
		$node = new WP_Node_Factory($term->taxonomy);
		$node->create_node($term->term_id);

		$filter = get_query_var('sf_filter');
		
		$layout_id = $node->get_node_meta("sf_{$filter}_template");
		//$layout_id = ($layout_setting > 0 ) ? $layout_setting : $node->post->ID;

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
	} 
	else 
	{
		if(current_user_can('activate_plugins'))
		{
			if(!class_exists('WP_Node_Factory'))
			{
				echo "<h1>Error: This template requires the WP Node plugin to be turned on.</h1>";
			}
			
			if(!class_exists('WidgetPress_Controller_Widgets'))
			{
				echo "<h1>Error: This template requires the WP Dropzones plugin to be turned on.</h1>";
			}
		}
	}
			


get_template_part('parts/footer');
