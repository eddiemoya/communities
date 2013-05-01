<?php if(!empty($titl) || !empty($subt)) { ?>
<header class="content-header">
	<?php if(!empty($titl)) { ?><h3><?php echo($titl); ?></h3><?php } ?>
	<?php if(!empty($subt)) { ?><h4><?php echo($subt); ?></h4><?php } ?>
</header>
<?php } ?>

<section class="content-body clearfix">

	<div class="featured-image span6">
		<?php if(!empty($imgs)) { ?>
			<img title="<?php echo($data['info']->name); ?>" alt="<?php echo($data['info']->name); ?>" class="attachment-large wp-post-image" src="<?php echo($imgs); ?>">
		<?php } ?>
	</div>

	<div class="featured-content span6">

		<div class="content-details clearfix">
			<span class="content-category"><a title="<?php echo($data['info']->name); ?>" href="<?php echo($data['link']); ?>"><?php echo($data['info']->name); ?></a></span>
			<time datetime="<?php echo(strftime('%Y-%m-%d', time())); ?>" pubdate="" class="content-date"><?php echo(strftime('%B %d, %Y', time())); ?></time>
		</div>
		<?php echo($data['info']->description); ?>
	
	</div>
</section>