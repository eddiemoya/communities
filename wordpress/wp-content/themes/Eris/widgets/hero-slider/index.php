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
REMEMBER TO MOVE INTO STYLESHEET
<style>
	.dropzone.span8 .hero-slider {
		background: url(/wp-content/themes/Eris/assets/img/hero-slider-bg.png) center center;
		width: 620px;
		height: 360px;
		position: relative;
	}
	.rotating-banner {
		overflow:hidden;
		position:relative;
		width: 579px;
		z-index:25;
		left:20px;
		top:-5px;
	}
	.rotating-banner .content-headline {
		position: absolute;
		bottom: 10px;
		width: 100%;
		background: #000000;
		background: rgba(0,0,0,.5);
		color: #ffffff;
		font-weight: normal;
		padding: 5px 10px 5px 15px;
	}
	.rotating-banner > * {
		/*height:710px;*/
		left:100%;
		/*padding-top:155px;*/
		position:absolute;
		width:100%;
		top:0
	}
	.rotating-banner>.active {
		display:block;
		left:auto;
		position:relative;
		top:auto;
		zoom:1
	}

	.rotating-banner .rotating-banner-navigation {
		bottom:1em;
		left:50%;
		margin-left:-5%;
		position:absolute;
		top:auto;
		z-index:25
	}
	.rotating-banner .rotating-banner-navigation a {
		background-color:#ffffff;
		display:block;
		float:left;
		height:1em;
		margin-right:.5em;
		width:1em;
		z-index:30
	}
	.hero-slider a.prev-slide {
		background: url(/wp-content/themes/Eris/assets/img/hero-slide-left.png) center center transparent;
		width: 40px;
		height: 100px;
		position: absolute;
		top: 115px;
		left: 0px;
		z-index:30;
	}
	.hero-slider a.next-slide {
		background: url(/wp-content/themes/Eris/assets/img/hero-slide-right.png) center center transparent;
		width: 40px;
		height: 100px;
		position: absolute;
		top: 115px;
		right: 1px;
		z-index:30;
	}
	.rotating-banner .banner {
		cursor: pointer;
	}
	.rotating-banner .rotating-banner-navigation .active {
		background:#ff0033
	}
	.rotating-banner .in-transit {
		z-index:24
	}
	.pre-load > *:first-child {
		display:block;
		left:auto;
		position:relative;
		top:auto
	}
	@media only screen and (min-width: 731px) and (max-width: 864px) {

	}
	@media only screen and (max-width: 730px) {
		.rotating-banner .rotating-banner-navigation a {
			height:.75em;
			width:.75em
		}
	}
</style>
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
	</div>
</section>

<?php
	get_template_part('parts/footer', 'widget');
