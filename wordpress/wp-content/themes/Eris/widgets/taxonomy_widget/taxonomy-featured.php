<div>
	<h4><a href="/link/to/<?php echo($data['info']->slug); ?>"><?php echo($data['info']->name); ?></a></h4>
	<h6>Taxonomy: <?php echo($data['info']->taxonomy); ?></h6>
	<?php if(!empty($data['info']->description)) { ?>
		<div><?php echo($data['info']->description); ?></div>
	<?php } ?>
</div>