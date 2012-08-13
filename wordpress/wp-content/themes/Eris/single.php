<?php
/**
 * @package WordPress
 * @subpackage White Label
 */
get_template_part('parts/header');

loop('post');

get_template_part('parts/footer');
