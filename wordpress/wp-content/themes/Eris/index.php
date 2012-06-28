<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header.php');

loop();

if (is_single()) 
    { comments_template('parts/comments.php'); }

get_template_part('parts/footer.php');