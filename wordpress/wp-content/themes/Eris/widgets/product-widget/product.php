<?php 
	//$product_url = $d->guid;
	$product_url = "http://www.sears.com/{$d->post_name}/p-{$d->meta->partnumber}";
?>

<div class="product <?php echo $class; ?>" data-pid="<?php echo $d->ID; ?>">
	<a href="<?php echo($d->guid); ?>"><img src="<?php echo((!empty($d->meta->imageurls)) ? $d->meta->imageurls[0] : ""); ?>?&wid=115&op_sharpen=1" /></a>
	<p class="product-title">
		<a href="<?php echo $product_url; ?>">
			<?php echo($d->post_title); ?>
		</a>
	</p>
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