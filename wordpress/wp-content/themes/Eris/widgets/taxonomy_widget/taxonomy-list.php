<?php foreach($disp as $k => $d) { ?>
	<article class="content-container department">	
		<section class="content-body clearfix">
			<div class="featured-image span3">
				<img title="<?php echo($k); ?>" alt="<?php echo($k); ?>" src="<?php echo($d['img']); ?>">        
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