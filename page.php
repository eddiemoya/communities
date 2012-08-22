<?php
/**
 * @package WordPress
 * @subpackage White Label
 */
get_template_part('parts/header');

loop();

if(function_exists('display_dropzones')){
    display_dropzones();
}

get_template_part('parts/footer');