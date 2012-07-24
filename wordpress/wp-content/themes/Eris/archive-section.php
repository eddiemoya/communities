<?php
/**
 * Template Name: Section Front A
 *
 * @package WordPress
 * @subpackage White Label
 */

echo '<!-- SECTION TEMPLATE -->';
get_template_part('parts/header');

if(function_exists('display_dropzones')){
    display_dropzones();
}

get_template_part('parts/footer');