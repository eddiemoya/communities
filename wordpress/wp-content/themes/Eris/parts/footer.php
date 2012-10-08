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
    <script type="text/javascript">
        var _gaq = _gaq || [];

        _gaq.push(['_setAccount', 'UA-35378729-1']);
        _gaq.push(['_trackPageview']);
        _gaq.push(['_addIgnoredRef', 'sso.shld.net']);

        if(typeof referer != 'undefined' && referer != '') {
          _gaq.push(['_setReferrerOverride', referer]);
        }

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
  </body>
</html>
