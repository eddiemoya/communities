<?php 
    // if(!is_ajax() && is_widget()->template == "featured") :
    //     get_template_part('parts/header', 'featured-results-list');
    // elseif(!is_ajax()) :
    //     get_template_part('parts/header', 'results-list');
    // endif;

if(!is_ajax())
    get_template_part('parts/header', 'results-list');

    $post_type = get_query_var('post_type');
    loop('results-list', array($post_type, 'post'),  'parts', 'parts/no-results.php');
    
    # Only show pagination if there are enough posts
    //if ( $wp_query->post_count >= $wp_query->query_vars['posts_per_page'] ) : if(is_widget()->template == "featured") : get_partial("parts/post-featured-post", array("widget" => is_widget())); // else :// endif;

if(!is_ajax())
    get_template_part('parts/footer', 'widget');

