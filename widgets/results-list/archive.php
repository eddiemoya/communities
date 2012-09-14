<?php 
if(!is_ajax()) {
    get_template_part('parts/header', 'results-list');
}

    $post_type = get_query_var('post_type');
    $post_type = (is_array($post_type)) ? $post_type[0] : $post_type;
    loop('results-list', array($post_type, 'post'),  'parts', 'parts/no-results.php');
    

if(!is_ajax()){ 
    get_template_part('parts/footer', 'widget');
}
