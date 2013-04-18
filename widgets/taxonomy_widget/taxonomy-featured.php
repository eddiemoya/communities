<div class="featured-image span3">
	<img title="<?php echo($data['info']->name); ?>" alt="<?php echo($data['info']->name); ?>" src="<?php echo((!empty($data['imgs'])) ? $data['imgs'][0]->guid : "http://path/to/noimg.png"); ?>">        
</div>

<div class="span9 featured-content">

    <h1 class="content-headline">
        <a href="<?php echo($data['post']->guid); ?>" title="<?php echo($data['post']->post_title); ?>"><?php echo($data['post']->post_title); ?></a>
    </h1>

	<!--
		You will have to add the <sarcasm>always helpful, and very informative</sarcasm> 'see more' link on the back end.
	-->

	<?php echo($data['post']->post_content); ?>
</div>