<?php
/**
 * @package WordPress
 * @subpackage White Label
 */
echo (__FILE__);
get_template_part('parts/header');
loop();

//Wont show up on archive pages.
//comments_template('/parts/answers.php');
//get_sidebar();
get_template_part('parts/footer');

// FUNCTIONING TEMPLATE - PLEASE DONT COMMIT ANY TINKERINGS

