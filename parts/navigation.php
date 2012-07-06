<?php
/**
 * @author Carl Albrecht-Buehler 
 */
  $a_navigation = array(
    "Categories", "Q&A's", "Blog Posts", "Buying Guides"
  );
?>
<nav id="navigation">
  <ul id="header_nav" class="dropmenu clearfix">
    <li class="right_button">
      <a href="#">Customer Care</a>
    </li>
     <?php foreach ( $a_navigation as $nav_item ): ?>
      <li>
        <a href="#"><span><?php echo htmlentities( $nav_item ); ?></span></a>
        <ul>
          <li><a href="#">Item 1</a></li>
          <li><a href="#">Item 2</a></li>
          <li><a href="#">Item 3</a></li>
          <?php if ($nav_item != "Categories"): ?>
            <li><a href="#">All <?php echo htmlentities( $nav_item ); ?><?php if ( $nav_item == "Blog" ) { echo " Posts"; } ?></a></li>
          <?php endif; ?>
        </ul>
      </li>
      <?php endforeach; ?>
  </ul>
</nav>
