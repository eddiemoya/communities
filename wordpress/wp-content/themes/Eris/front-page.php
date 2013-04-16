<?php //echo '<!-- SECTION TEMPLATE -->';
get_template_part('parts/header');

global $excerptLength;

$excerptLength = 95;

if(function_exists('display_dropzones')){
    display_dropzones();
}

get_template_part('parts/footer');

// FUNCTIONING TEMPLATE - NO TINKERING