<?php
/**
 * Forums Head
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="forums-head">
	<?php if(bbp_is_forum_archive()):?>
	
		<h2>[Forums logo] Forums Header</h2>
		
	<?php else: ?>
	
		<h2><?php header_breadcrumbs();?></h2>
		
	<?php endif;?>
</div>