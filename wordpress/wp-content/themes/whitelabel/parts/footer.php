<?php
/**
 * @package WordPress
 * @subpackage White Label
 */ 
?>
                  
                </div> <!-- #content -->
                <div class="pagination">
                    <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts')); ?></div>
                    <div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>')); ?></div>
                </div>
                
                <?php do_action('content-after'); ?>
                <?php get_sidebar(); ?>
            </div><!-- #page -->
            
            <div id="wp_footer">

                <div id="shc_footer">
                    <?php do_action('content-bottom'); ?>
                </div>

                <?php wp_footer(); ?>

            </div>
            
        </div> <!-- #page_wrapper -->
        
    </body>
</html>



    

