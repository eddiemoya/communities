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
         
        //Used to destroy session used only to capture referer information
        session_destroy();       
        ?>

        <?php wp_footer(); ?>

      </footer>

    </div> <!-- #container -->
    <?php get_partial('parts/tooltip'); 
    global $current_user;
    
    $email_from = is_user_logged_in() ? $current_user->user_email : '';

    echo '
        <script type="text/javascript">
            var addthis_config = {
                ui_email_note: "Thought you might like this from My' . ucfirst( theme_option("brand") ) .' Community.",
                ui_email_from: "' . $email_from . '"
              }
        </script>
    ';?>
  </body>
</html>
