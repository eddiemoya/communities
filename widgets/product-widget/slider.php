<?php
	$count = 1;
	$span = ($pw_template == "products-vertical") ? "span12" : "span12";
	$layout = ($pw_template == "products-vertical") ? "vertical" : "horizontal";
	$animation = $instance['pw_animation_duration'];
	$total = count($data->products);

	$prodRows = $data->products;
	$flNumber = 1;

	for($i=0; $i<$flNumber; $i++)
	{
		$ar = array_pop($prodRows);
		array_unshift($prodRows, $ar);
	}
	
?>

<?php if ($instance["pw_title"] != "") { ?>
	<hgroup class="content-header">
		<h3><?php echo $instance["pw_title"]; ?></h3>
	</hgroup>
<?php } ?>

<section class="content-body product-slider-widget-content clearfix viewport_size_<?php echo($pw_fields_to_show); ?> <?php echo($span); ?>" shc:gizmo="carousel" 
	shc:gizmo:options="{viewportsize:<?php echo($pw_fields_to_show); ?>,itemcount:<?php echo($total); ?>,autoSlideInterval:<?php echo $animation; ?>}">

	<div class="left-arrow"></div>
	<div class="product-slider-container layout_<?php echo($layout); ?>">
		<?php foreach($prodRows as $d) { ?>
		<div class="product <?php if($count <= $flNumber) { ?>inactive-left<?php } else if($count <= $flNumber + $pw_fields_to_show) { ?>active<?php } else { ?>inactive-right <?php } ?>">
			<a href="<?php echo($d->guid); ?>"><img src="<?php echo((!empty($d->meta->imageurls)) ? $d->meta->imageurls[0] : ""); ?>&wid=115&op_sharpen=1" /></a>
			<p class="product-title"><a href="<?php echo($d->guid); ?>"><?php echo($d->post_title); ?></a></p>
			<?php for($i=1; $i<=5; $i++) { ?>
				<span class="product-stars star-<?php echo(($i > $d->meta->rating) ? "de" : ""); ?>selected"></span>
			<?php } ?>
			<p class="product-price">
				<?php if(!empty($d->meta->saleprice)) { ?>
					$<?php echo($d->meta->saleprice); ?> <span class="old-price">$<?php echo($d->meta->regularprice); ?></span>
				<?php } else { ?>
					$<?php echo($d->meta->regularprice); ?>
				<?php } ?>
			</p>
		</div>
		<?php $count++;} ?>
	</div>
	<div class="right-arrow"></div>
</section>