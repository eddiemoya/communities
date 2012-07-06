<?php
/**
 * @author Carl Albrecht-Buehler 
 */
  $a_navigation = array(
    "Accounts & Orders", "Customer Service", "Sell on Sears", "About Sears", "Legal", "More Sears Sites"
  );
?>
<nav id="footer_navigation">
  <ul id="footer_nav" class="dropmenu clearfix">
    <?php foreach ( $a_navigation as $nav_item ): ?>
    <li>
      <a href="#"><span><?php echo htmlentities( $nav_item, ENT_QUOTES ); ?></span></a>
      <ul>
        <li><a href="#">Item 1</a></li>
        <li><a href="#">Item 2</a></li>
        <li><a href="#">Item 3</a></li>
      </ul>
    </li>
    <?php endforeach; ?>
  </ul>
</nav>
