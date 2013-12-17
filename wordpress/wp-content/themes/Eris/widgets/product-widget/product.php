<?php 
	$product_url = (((stripos(get_bloginfo('name'), 'sears')) !== false) ? 'http://www.sears.com/' : 'http://www.kmart.com/') . $d->meta->product_uri;
	$image_url = ((!empty($d->meta->imageurls)) ? str_replace("http", "https", $d->meta->imageurls[0]) : "")
?>

<div class="product <?php echo $class; ?>" data-pid="<?php echo $d->ID; ?>">
	<a href="<?php echo $product_url; ?>"><img src="<?php echo $image_url; ?>?&wid=115&op_sharpen=1" /></a>
	<p class="product-title">
		<a href="<?php echo $product_url; ?>">
			<?php echo($d->post_title); ?>
		</a>
	</p>
	<?php for($i=1; $i<=5; $i++) { ?>
		<span class="product-stars star-<?php echo(($i > $d->meta->rating) ? "de" : ""); ?>selected"></span>
	<?php } ?>
	<p class="product-price">
		<?php if(!empty($d->meta->saleprice) && ($d->meta->saleprice != $d->meta->regularprice)) { ?>
			$<?php echo($d->meta->saleprice); ?> <span class="old-price">$<?php echo($d->meta->regularprice); ?></span>
		<?php } else { ?>
			$<?php echo($d->meta->regularprice); ?>
		<?php } ?>
	</p>
</div>