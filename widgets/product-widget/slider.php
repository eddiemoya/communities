<?php
	$count = 1;
	$span = ($pw_template == "products-vertical") ? "span12" : "span12";
	$layout = ($pw_template == "products-vertical") ? "vertical" : "horizontal";
?>

<?php if ($instance["pw_title"] != "") { ?>
	<hgroup class="content-header">
		<h3><?php echo $instance["pw_title"]; ?></h3>
	</hgroup>
<?php } ?>
<section class="content-body product-slider-widget-content clearfix viewport_size_3 <?php echo($span); ?>" shc:gizmo="carousel" shc:gizmo:options="{viewportsize:3,itemcount:4,autoSlideInterval:2000}">
	<div class="left-arrow"></div>
	<div class="product-slider-container layout_<?php echo($layout); ?>">
		<?php foreach($data->products as $d) { ?>
		<div class="product <?php if($count <= 3) { ?>inactive_left<?php } else if($count <= 6) { ?>active<?php } else { ?>inactive_right <?php } ?>">
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