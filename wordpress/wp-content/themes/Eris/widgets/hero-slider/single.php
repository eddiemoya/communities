<?php
get_template_part('parts/header', 'widget');
$posts_in = array();
for($i=0; $i < $instance['limit']; $i++) {
	$current_item = $i+1;
	array_push($posts_in, $instance["post__in_".$current_item]);
}
$args = array("post__in" => $posts_in, "post_type" => array("guide", "page", "post", "question"));
$sliderContent = new WP_Query($args);

function get_the_post_thumbnail_data($intID = 0) {
    if($intID == 0) {
        return $intID;
    }
    $objDom = new SimpleXMLElement(get_the_post_thumbnail($intID));
    $arrDom = (array)$objDom;
    return (array)$arrDom['@attributes'];
}




?>


<script>
<div class="hero_slider">
   
    <div class="hero_slider_container">
        <?php foreach ($sliderContent->posts as $post): 
			$image = get_the_post_thumbnail_data($post->ID);
        ?>
            <a href="<?php echo $link['url']; ?>" <?php echo $link['target']; ?> style="<?php echo "display: block !important;" . the_slider_width(false) . ';' . the_slider_height(false) . ';'; ?>">
                <div class="hero_slider_item" style="background-image: url('<?php echo $image["src"]; ?>'); background-repeat:no-repeat; background-position: top left; <?php the_slider_height(); ?>">
                    <div class="hero_slider_title">
						<div class="scrollerTitle"><?php the_title(); ?></div>
                    </div>			
                </div>
            </a>
        <?php endforeach; ?>			
    </div>
</div>


<?php
get_template_part('parts/footer', 'widget');

