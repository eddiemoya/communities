<?php
	function get_the_post_thumbnail_data($postID = 0) {
	    if($postID == 0) {
	        return $postID;
	    }
		$thumbnail_id = get_post_thumbnail_id($postID);
		return wp_get_attachment_image_src($thumbnail_id, array(625,300));
	}
	
	get_template_part('parts/header', 'widget');
	
	$posts_in = array();
	for($i=0; $i < $instance['limit']; $i++):
		$current_item = $i+1;
		array_push($posts_in, $instance["post__in_".$current_item]);
	endfor;

	$args = array("post__in" => $posts_in, "post_type" => array("guide", "page", "post", "question", "attachment"), "post_status" => array("publish", "inherit"));
	$sliderContent = new WP_Query($args);
?>
<section class="hero-slider-container">
	<div class="rotating-banner pre-load" shc:name="hero-slider" shc:options="{animate:true, navigation:true, arrowNavigationOnly:true, autoSlideBanners:true}" shc:gizmo="responslide">
		<?php 
			$counter = 1;
			foreach ($sliderContent->posts as $post):
				if ($post->post_type == "attachment") {
					$image = $post->guid;
					$link = get_post_meta( $post->ID, 'slide-link-url', true );
				} else {
					$image = MultiPostThumbnails::get_post_thumbnail_url($post->post_type, 'hero-slider-image', $post->ID);
					$link = get_post_permalink($post->ID);
				}
		?>
				<div class="banner slide_<?php echo $counter; ?>" shc:shard="banner" shc:url="<?php echo $link; ?>">
					<h1 class="content-headline"><?php echo $post->post_title; ?></h1>
					<img src="<?php echo $image; ?>" alt="<?php $post->post_title; ?>" />
				</div>
		<?php
				$counter++;
			endforeach;
		?>
	</div>
</section>

<?php
	get_template_part('parts/footer', 'widget');
