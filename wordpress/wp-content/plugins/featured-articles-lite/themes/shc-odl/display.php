<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 * 
 * For more information on FeaturedArticles template functions, see: http://www.codeflavors.com/documentation/display-slider-file/
 */

function get_fa_read_more( $class = 'FA_read_more' ){
	// use the global $post since it's set by our loop and the original post gets restored after slider is displayed
	global $post;
	
	if( !$post ) return;
	
	$link_html = '<a class="%1$s" href="%2$s" title="%3$s" target="%4$s">%5$s</a>';
	
	return sprintf( $link_html,
		$class, // %1$s
		get_permalink($post->ID), // %2$s
		$post->post_title, // %3$s
		$post->link_target, // %4$s
		$post->fa_read_more // %5$s
	);	
}

function fa_odl_permalink(){
	// use the global $post since it's set by our loop and the original post gets restored after slider is displayed
	global $post;
	
	if( !$post ) return;

        if(empty($post->fa_read_more)){
            $link['url'] = get_permalink($post->ID);
            $link['target'] = 'target="_none"';
        } else {
            $link['url'] = $post->fa_read_more;
            $link['target'] = 'target="_blank"';
        }

	return $link;	
}

function get_fucking_image($post){
    $image = wp_get_attachment_image_src( get_post_meta($post->ID, '_fa_image', true) );
 
    return $image[0];
    
}



?>

<div class="FA_overall_container_smoke FA_slider <?php the_slider_color();?>" style="<?php the_slider_width();?>"  id="<?php echo $FA_slider_id;?>">
   
    <div class="FA_featured_articles" style="<?php the_slider_height(); ?>">
        <?php foreach ($postslist as $post): 
            $image = wp_get_attachment_image_src(get_post_meta($post->ID, '_fa_image', true), array(the_slider_width(false), the_slider_height(false) )); 
            $link = fa_odl_permalink();
        ?>
            <a href="<?php echo $link['url']; ?>" <?php echo $link['target']; ?> style="<?php echo "display: block !important;" . the_slider_width(false) . ';' . the_slider_height(false) . ';'; ?>">
                <div class="FA_article <?php the_fa_class(); ?>" style="background-image: url('<?php echo $image[0]; ?>'); background-repeat:no-repeat; background-position: top left; <?php the_slider_height(); ?>">
                    <div class="FA_wrap">
						<div class="scrollerTitle"><?php the_title(); ?></div>
                    </div>			
                </div>
            </a>
        <?php endforeach; ?>			
    </div>

    <ul class="FA_navigation" style="<?php the_slider_width();?>">
	<?php foreach ($postslist as $post):?>
		<li><a href="#" title="<?php the_title();?>"></a></li>   
	<?php endforeach;?>
    </ul>
</div>