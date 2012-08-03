<!--/Users/emoya1/Public/Projects/comm/wordpress/wp-content/themes/Eris/widgets/parts/category.php -->
<?php get_template_part('parts/header', 'widget') ;?>


		<?php if (have_posts()) { while (have_posts()) { the_post(); ?>

			<li class="featured">
	        	<section class="column clearfix">
	        		<div class="span4 badge">
	        			<!-- user image -->
	        			<img src="<?php echo get_template_directory_uri()  //get author meta user thumbnail ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
	        			<h4><a href="<?php the_author_link(); ?>"><?php the_author(); ?></a></h4>
	        			<address><?php echo 'Chicago, IL'; //get author meta for user location?></address>
	        		</div>

	        		<div class="span8 info content-details">
		                <time class="content-date" datetime="<?php the_time('Y-m-d'); ?>">
		                    <?php the_time('F jS, Y g:ia'); ?>
		                </time>	              		<h4 class="content-headline">
	              			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	              		</h4>

				        <ul class="content-comments">
				            <li><?php comments_number(); ?></li>
				        </ul>
				    </div>

	        	</section>
			</li>


		<?php }} ?>

<?php get_template_part('parts/footer', 'widget') ;?>