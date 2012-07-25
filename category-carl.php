<?php
/**
 * @package WordPress
 * @subpackage Eris
 */

  get_template_part('parts/header');

  loop();
  
  $self_lookup = true;
  
  $current_tab = "Community Activity";
  $current_nav = "Recent Activity";
  
  $a_tabs = array(
    "Community Activity" => "#",
    "About Me" => "#",
  );
  
  $a_navigation = array(
    "Recent Activity" => "#",
    "Q&A's" => "#",
    "Blog Posts" => "#",
    "Buying Guides" => "#"
  );
?>
  
  <section class="span<?php echo ( $self_lookup ? "12" : "8" ); ?>">
    <section class="profile">
      <nav class="clearfix">
        <ul class="tabs clearfix">
          <?php
          foreach ( $a_tabs as $lone_tab => $tab_url ) {
            if ( $lone_tab == $current_tab ) {
              echo '<li class="active">' . $lone_tab . '</li>';
            }
            else {
              echo '<li><a href="' . $tab_url . '">' . $lone_tab . '</a></li>';
            }
          }
          ?>
        </ul>
      </nav>
      <nav class="bar clearfix">
        <ul class="clearfix">
          <?php
          foreach ( $a_navigation as $lone_nav => $nav_url ) {
            if ( $lone_nav == $current_nav ) {
              echo '<li class="active">' . $lone_nav . '</li>';
            }
            else {
              echo '<li><a href="' . $nav_url . '">' . $lone_nav . '</a></li>';
            }
          }
          ?>
        </ul>
      </nav>
    </section>
  </section>
  
  <?php if ( !$self_lookup ): ?>
  <section class="span4">
    <!-- Widgets Go Here -->
  </section>
  <?php endif; ?>

<?php
  get_template_part('parts/footer');