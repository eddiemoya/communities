<?php
/**
 * @package WordPress
 * @subpackage White Label
 */
get_template_part('parts/header');
loop();

//get_template_part('parts/subnav-widget');

//Wont show up on archive pages.
//comments_template('/parts/answers.php');
get_sidebar();
get_template_part('parts/footer');