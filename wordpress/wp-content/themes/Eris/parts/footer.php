<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

// Can anyone else think of a better way to pass this parameter along
?>
                  

      </section> <!-- #content -->

      <footer id="footer" class="clearfix">

        <div id="shc_footer">
          <?php do_action('content-bottom'); ?>
        </div>
        <?php
        if (theme_option("brand") == "kmart") {
          get_template_part('parts/footer_navigation_kmart');
          get_template_part('parts/footer_anterior_kmart');
        }
        else {
          get_template_part('parts/footer_navigation');
          get_template_part('parts/footer_anterior');
        }
        ?>

        <?php wp_footer(); ?>

      </footer>

    </div> <!-- #container -->
<?php get_partial('parts/tooltip'); ?>
  </body>
</html>
