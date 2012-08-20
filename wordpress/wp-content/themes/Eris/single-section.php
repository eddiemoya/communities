<?php 
//echo 'SECTION TEMPLATE';
get_template_part('parts/header');

if(function_exists('display_dropzones')){
    display_dropzones();
}

get_template_part('parts/footer');

// FUNCTIONTING TEMPLATE - NO TINKERING