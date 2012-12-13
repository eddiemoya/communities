<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 * 
 * For more information on FeaturedArticles template functions, see: http://www.codeflavors.com/documentation/display-slider-file/
 */
?>
<?php the_slideshow_title();?>
<div class="FA_overall_container_title_nav FA_slider <?php the_slider_color();?>" style="<?php the_slider_width()?>" id="<?php echo $FA_slider_id;?>">	
	<div class="FA_featured_articles" style="<?php the_slider_height();?>">
	<?php foreach ($postslist as $post):?>
		<div class="FA_article  <?php the_fa_class();?>" style="<?php the_fa_background(false);?>; <?php the_slider_height();?>">	
			<div class="FA_wrap">
				<?php the_fa_image();?>
                <?php the_fa_title('<h2>', '</h2>');?>
                <span class="FA_date"><?php the_time(get_option('date_format')); ?></span>
                <?php the_fa_content('<p>', '</p>');?>
                <?php the_fa_read_more();?>
			</div>                			
		</div>	
	<?php endforeach;?>			
	</div>
	<ul class="FA_navigation" style="<?php the_slider_height();?>">
	<?php foreach ($postslist as $post):?>
		<li><a href="#" title="<?php the_title();?>"><?php the_title();?></a></li>
	<?php endforeach;?>
	</ul>
</div>