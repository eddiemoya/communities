<?php

get_template_part('parts/header');

			global $post;
			//print_pre($post);
			$term = wp_get_object_terms($post->ID, $post->post_type);
			$term = $term[0];
			 $node = new WP_Node($term->term_id, $term->taxonomy);

			query_posts(
				array(
					'post_type' => 'post',
					'tax_query' => array(
						array(
							'taxonomy' => $term->taxonomy,
							'terms' => $term->term_id,
							'field' => 'id'
						)
					)
					)
				);
			WidgetPress_Controller_Widgets::display_dropzones($node->post->ID);
			wp_reset_query();
			


get_template_part('parts/footer');