<?php
	function get_the_post_thumbnail_data($postID = 0) {
	    if($postID == 0) :
	        return $postID;
	    endif;
		$thumbnail_id = get_post_thumbnail_id($postID);
		return wp_get_attachment_image_src($thumbnail_id, array(625,300));
	}
	
	get_template_part('parts/header', 'widget');
	
	$posts_in = array();
	for($i=0; $i < $instance['limit']; $i++) :
		$current_item = $i+1;
		array_push($posts_in, $instance["post__in_".$current_item]);
	endfor;

	$args = array("post__in" => $posts_in, "post_type" => array("guide", "page", "post", "question", "attachment"), "post_status" => array("publish", "inherit"));
	$sliderContent = new WP_Query($args);
	
	foreach ($sliderContent->posts as $post) :
		$posts[$post->ID] = $post;
	endforeach;
?>
<section class="hero-slider-container">
	<div class="rotating-banner pre-load" shc:name="hero-slider" shc:options="{animate:true, navigation:true, arrowNavigationOnly:true, autoSlideBanners:true}" shc:gizmo="responslide">
		<?php 
			$counter = 1;
			foreach ($posts_in as $postID) :
				if ($posts[$postID] == "") :
					continue;
				endif;
			//foreach ($sliderContent->posts as $post):
				if ($posts[$postID]->post_type == "attachment") :
					$image = $posts[$postID]->guid;
					$link = get_post_meta( $posts[$postID]->ID, 'slide-link-url', true );
				else :
					$image = MultiPostThumbnails::get_post_thumbnail_url($posts[$postID]->post_type, 'hero-slider-image', $posts[$postID]->ID);
					$link = get_post_permalink($posts[$postID]->ID);
				endif;
				
				if ($link != "") :
					$link_class=" banner_link";
				endif;
		?>
				<div class="banner slide_<?php echo $counter.$link_class; ?>" shc:shard="banner" shc:url="<?php echo $link; ?>">
					<?php if (get_post_meta( $posts[$postID]->ID, 'slide-hide-title', true) != true) : ?>
						<h1 class="content-headline"><?php echo $posts[$postID]->post_title; ?></h1>
					<?php endif; ?>
					<img src="<?php echo $image; ?>" alt="<?php $posts[$postID]->post_title; ?>" />
				</div>
		<?php
				$link_class="";
				$counter++;
			endforeach;
		?>
	</div>
</section>

<?php
	get_template_part('parts/footer', 'widget');
