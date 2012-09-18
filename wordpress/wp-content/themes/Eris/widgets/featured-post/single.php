<?php 

get_template_part('parts/header', 'widget') ;
    //global $excerptLength; $excerptLength = 200;

    $post_type = get_query_var('post_type');
    $post_type = (is_array($post_type)) ? $post_type[0] : $post_type;
    loop(array($post_type, 'post'), 'featured', 'parts');


get_template_part('parts/footer', 'widget');