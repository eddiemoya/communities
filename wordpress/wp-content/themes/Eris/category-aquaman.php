<?php
/**
 * Template Name: batman
 * @package WordPress
 * @subpackage White Label
 */
	echo '<form method="POST" action="http://localhost/machina/v3/test.html" id="butt">';
	echo '<input type="text" value="pudding" name="dessert" />';
	echo '</form>';
	echo '<script type="text/javascript">';
	echo 'document.getElementById("butt").submit();';
	echo '</script>';
?>
