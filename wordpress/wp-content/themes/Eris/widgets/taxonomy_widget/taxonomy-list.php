<?php if(!empty($titl) || !empty($subt)) { ?>
<header class="content-header">
	<?php if(!empty($titl)) { ?><h3><?php echo($titl); ?></h3><?php } ?>
	<?php if(!empty($subt)) { ?><h4><?php echo($subt); ?></h4><?php } ?>
</header>
<?php } ?>

<?php foreach($disp as $k => $d) { ?>
	<article class="content-container department">	
		<section class="content-body clearfix">
			<div class="featured-image span3">
				<?php if(!empty($d['img'])) { ?>
					<img title="<?php echo($k); ?>" alt="<?php echo($k); ?>" src="<?php echo($d['img']); ?>">   
				<?php } ?>    
			</div>

			<div class="span9 featured-content">

			    <h1 class="content-headline">
			        <a href="<?php echo($d['link']); ?>" title="<?php echo($k); ?>"><?php echo($k); ?> Reviews</a>
			    </h1>

				<!--
					You will have to add the <sarcasm>always helpful, and very informative</sarcasm> 'see more' link on the back end.
				-->

				<?php echo($d['desc']); ?>
			</div>
		</section>				
	</article>
<?php } ?>