<?php
/**
 * @package WordPress
 * @subpackage White Label
 */ 
?>
                  
                <!--<div class="pagination">
                    <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts')); ?></div>
                    <div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>')); ?></div>
                </div>-->
                
                <?php //do_action('content-after'); ?>
                <?php //get_sidebar(); ?>
					</section> <!-- #content -->
					
					<footer id="footer">
						
						<div id="shc_footer">
            	<?php do_action('content-bottom'); ?>
            </div>
            <?php get_template_part('parts/footer_navigation'); ?>
            <?php get_template_part('parts/footer_anterior'); ?>

            <?php wp_footer(); ?>
						
					</footer>

				</div> <!-- #container -->
                
			</body>
</html>



    

