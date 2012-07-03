<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header');

get_template_part('parts/post');

if (is_single()) 
    { comments_template('parts/comments'); }

get_template_part('parts/footer');