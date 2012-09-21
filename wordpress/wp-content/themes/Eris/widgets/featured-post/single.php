<?php 

get_template_part('parts/header', 'widget') ;
    //global $excerptLength; $excerptLength = 200;
    global $wp_query;
    loop(array($wp_query->post->post_type, 'post'), null, 'widgets/featured-post');


get_template_part('parts/footer', 'widget');