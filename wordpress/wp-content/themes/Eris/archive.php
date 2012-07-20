<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header');

loop('post', 'widget');

get_template_part('parts/footer');
?>