<?php if(!empty($titl) || !empty($subt)) { ?>
<header class="content-header">
	<?php if(!empty($titl)) { ?><h3><?php echo($titl); ?></h3><?php } ?>
	<?php if(!empty($subt)) { ?><h4><?php echo($subt); ?></h4><?php } ?>
</header>
<?php } ?>

<section class="content-body clearfix">
<?php $ctr = 0; ?>
<ul class="span12">
<?php foreach($disp as $k => $d) { ?>
	<li class="span3<?php if(($ctr % 4) == 0) { ?> alpha<?php } ?>">
		<a href="<?php echo($d['link']); ?>" title="<?php echo($k); ?>">
			<?php if(!empty($d['img'])) { ?>
				<img src="<?php echo($d['img']); ?>" alt="<?php echo($k); ?>" />
			<?php } ?>
		</a>
		<h5><a href="<?php echo($d['link']); ?>" title="<?php echo($k); ?>"><?php echo($k); ?></a></h5>
	</li>
<?php $ctr++; } ?>
</ul>
	
<?php if(!empty($drop) && $dpdn) { ?>
	<div class="span12 taxonomy-drop-down-menu">
		<select class="input_select" name="department" shc:gizmo="drop-menu">
			<option value="default">--All Departments</option>
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