<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('templates/header.php');

get_template_part('templates/post.php');

if (is_single()) 
    { comments_template('templates/comments.php'); }

get_template_part('templates/footer.php');