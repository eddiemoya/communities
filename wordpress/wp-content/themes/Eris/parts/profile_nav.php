<?php
  
  $a_tabs = array(
    "Community Activity" => "#",
    "About Me" => "#",
  );
  
  $a_navigation = array(
    "1" => array(
      "type" => "Recent Activity",
      "url" => "#"
    ),
    "2" => array(
      "type" => "Questions",
      "url" => "#"
    ),
    "7" => array(
      "type" => "Posts",
      "url" => "#"
    ),
    "8" => array(
      "type" => "Buying Guides",
      "url" => "#"
    ),
    "3" => array(
      "type" => "Answers",
      "url" => "#"
    ),
    "4" => array(
      "type" => "Comments",
      "url" => "#"
    ),
    "5" => array(
      "type" => "Follows",
      "url" => "#"
    ),
    "6" => array(
      "type" => "Helpful Votes",
      "url" => "#"
    )
  );
  
?>

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

<?php if ( $current_tab != "About Me" ): ?>
  <nav class="bar clearfix">
    <ul class="clearfix">
      <?php
      foreach ( $a_navigation as $lone_nav_id => $lone_nav ) {
        if ( in_array($lone_nav_id, $a_actions_taken) ) {
          if ( $lone_nav_id == $current_nav ) {
            echo '<li class="active">' . $lone_nav["type"] . '</li>';
          }
          else {
            echo '<li><a href="' . $lone_nav["url"] . '">' . $lone_nav["type"] . '</a></li>';
          }
        }
      }
      ?>
    </ul>
  </nav> 
<?php endif; ?>
