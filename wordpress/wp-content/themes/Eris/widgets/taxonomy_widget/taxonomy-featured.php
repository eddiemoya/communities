<header class="content-header">
	<h3>Featured Category</h3>
</header>
<section class="content-body clearfix">

	<div class="featured-image span6">
		<img title="<?php echo($data['info']->name); ?>" alt="<?php echo($data['info']->name); ?>" class="attachment-large wp-post-image" src="<?php echo((!empty($data['imgs'])) ? $data['imgs'][0]->guid : "http://path/to/noimg.png"); ?>">
	</div>

	<div class="featured-content span6">

	<div class="content-details clearfix">
		<span class="content-category"><a title="<?php echo($data['info']->name); ?>" href="<?php echo($data['link']); ?>"><?php echo($data['info']->name); ?></a></span>
		<time datetime="<?php echo(strftime('%Y-%m-%d', time())); ?>" pubdate="" class="content-date"><?php echo(strftime('%B %d, %Y', time())); ?></time>
	</div>
	<?php echo($data['info']->description); ?>
	
	</div>
</section>