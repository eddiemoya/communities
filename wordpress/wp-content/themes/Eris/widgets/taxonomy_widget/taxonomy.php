<?php foreach($disp as $k => $d) { ?>
	<div>
		<img src="<?php echo($d['img']); ?>" />
		<a href="<?php echo($d['link']); ?>"><?php echo($k); ?></a>
		<?php if($desc && !empty($d['desc'])) { ?>
			<span><?php echo($d['desc']); ?></span>
		<?php } ?>
<?php } ?>

<?php if(!empty($drop) && $dpdn) { ?>

<?php } ?>		
<?php if(!empty($drop) && $dpdn) { ?>
	<br />
	<select>
		<option value="">--Click for <?php echo($ftxt); ?></option>
		<?php foreach($drop as $k => $d) { ?>
			<option value="<?php echo($d['link']); ?>"><?php echo($k); ?></option>
		<?php } ?>
	</select>

	<?php if(!empty($nsts)) { ?>
		<noscript><?php echo($nsts); ?></noscript>
	<?php } ?>
<?php } ?> 