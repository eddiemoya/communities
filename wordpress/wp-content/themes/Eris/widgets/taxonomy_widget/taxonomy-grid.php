<header class="content-header">
	<h3>Departments</h3>
</header>

<section class="content-body clearfix">
<?php $ctr = 0; ?>
<ul class="span12">
<?php foreach($disp as $k => $d) { ?>
	<li class="span3<?php if(($ctr % 4) == 0) { ?> alpha<?php } ?>">
		<a href="<?php echo($d['link']); ?>" title="<?php echo($k); ?>"><img src="<?php echo($d['img']); ?>" alt="<?php echo($k); ?>" /></a>
		<h5><a href="<?php echo($d['link']); ?>" title="<?php echo($k); ?>"><?php echo($k); ?></a></h5>
	</li>
<?php $ctr++; } ?>
</ul>
	
<?php if(!empty($drop) && $dpdn) { ?>
	<div class="span12 taxonomy-drop-down-menu">
		<select class="input_select" name="department" shc:gizmo="drop-menu">
			<option value="default">--Click for <?php echo($ftxt); ?></option>
			<?php foreach($drop as $k => $d) { ?>
				<option value="<?php echo($d['link']); ?>"><?php echo($k); ?></option>
			<?php } ?>
		</select>
	</div>
<?php $ctr = 0; ?>
	<?php if(!empty($nsts)) { ?>
	<noscript>
		<ul class="span12">
			<?php foreach($nsts as $k => $d) { ?>
			<li class="span3<?php if(($ctr % 4) == 0) { ?> alpha<?php } ?>">
				<a href="<?php echo($d['link']); ?>" title="<?php echo($k); ?>"><img src="<?php echo($d['img']); ?>" alt="<?php echo($k); ?>" /></a>
				<h5><a href="<?php echo($d['link']); ?>" title="<?php echo($k); ?>"><?php echo($k); ?></a></h5>
			</li>
			<?php $ctr++;} ?>
		</ul>
	</noscript>
	<?php } ?>
<?php } ?>
</section>