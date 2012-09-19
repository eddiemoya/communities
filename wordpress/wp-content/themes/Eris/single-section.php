<?php
/**
 * Template Name: Section Front A
 *
 * @package WordPress
 * @subpackage White Label
 */
// global $post, $wp_query;
// echo $post->post_type . ": " . $post->ID;
// echo "<pre>";print_r($wp_query);echo "</pre>";
// echo '<!-- SECTION TEMPLATE -->';
get_template_part('parts/header');

if(function_exists('display_dropzones')){
    display_dropzones();
}

get_template_part('parts/footer');

// FUNCTIONING TEMPLATE - NO TINKERING