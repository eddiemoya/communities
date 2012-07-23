<?php
/**
 * @package WordPress
 * @subpackage White Label
 */
get_template_part('parts/header');

loop('question');

//comments_template('/parts/answers.php');
//comments_template('/parts/flags.php');

get_template_part('parts/footer');