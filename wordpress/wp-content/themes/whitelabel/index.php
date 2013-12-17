<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

get_header();

loop();

if (is_single()) 
    { comments_template('templates/comments.php'); }

get_footer();