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
?>
<?php the_slideshow_title();?>
<div class="FA_overall_container_smoke FA_slider <?php the_slider_color();?>" style="<?php the_slider_width();?>"  id="<?php echo $FA_slider_id;?>">	
	<div class="FA_featured_articles" style="<?php the_slider_height();?>">
	<?php foreach ($postslist as $post):?>
		<div class="FA_article <?php the_fa_class();?>" style="<?php the_fa_background(); ?>; <?php the_slider_height();?>">
        	<div class="FA_wrap">	
				<?php //the_fa_title('<h2>', '</h2>');?>
<!--                <span class="FA_date"><?php //the_time(get_option('date_format')); ?></span>-->
                <?php the_fa_content('<p>', get_fa_read_more(). '</p>');?>
                <?php //the_fa_read_more();?>
            </div>			
		</div>
	<?php endforeach;?>			
    </div>
    <ul class="FA_navigation">
	<?php foreach ($postslist as $post):?>
		<li><a href="#" title="<?php the_title();?>">&bullet;</a></li>
	<?php endforeach;?>
	</ul>
</div>