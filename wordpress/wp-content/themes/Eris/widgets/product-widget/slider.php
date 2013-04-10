
<?php if ($instance["pw_title"] != "") { ?>
	<hgroup class="content-header">
		<h3><?php echo $instance["pw_title"]; ?></h3>
	</hgroup>
<?php } ?>
<section class="content-body product-slider-widget-content clearfix length<?php echo $instance["product_count"]; ?>">
	<div class="left-arrow"></div>
	<div class="product-slider-container">
		<?php foreach($data->products as $d) { ?><pre><?php //print_r($d); ?></pre>
		<div class="product">
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
		<?php } ?>
	</div>
	<div class="right-arrow"></div>
</section>




