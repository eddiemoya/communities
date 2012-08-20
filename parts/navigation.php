<?php
  # @author Carl Albrecht-Buehler 
  $a_navigation = array(
    "Categories"    => get_categories(array('parent' => 0, 'hide_empty' => true, 'depth' => 1)), 
    "Q&A's"         => get_terms_by_post_type('category', 'question'),
    "Blog Posts"    => get_terms_by_post_type('category', 'post'),
    "Buying Guides" => get_terms_by_post_type('category', 'buying-guide'),
  );//print_pre($a_navigation); 
  $post_types = array(
    "Categories" => 'category', 
    "Q&A's" => 'question', 
    "Blog Posts" => 'post', 
    "Buying Guides" => 'guide'
  );
  ?>



<nav id="navigation">
  <ul id="header_nav" class="dropmenu clearfix">
    <li class="right_button">
      <a href="#">Customer Care</a>
    </li>
     <?php foreach ( $a_navigation as $nav_label => $nav_items ): ?>
      <li>
        <a href="#"><span><?php echo htmlentities( $nav_label ); ?></span></a>
        <ul>
          <?php foreach($nav_items as $item) : ?>

            <?php if($item->parent == 0) : ?>
                <li><a href="
                    <?php 
                    $post_type = ("category" != $post_types[$nav_label]) ? "?post_type=".$post_types[$nav_label] : '' ;
                    echo get_category_link($item->term_id) . $post_type; ?>">
                    <?php echo $item->name; ?>
                </a>
              </li>
          <?php endif; ?>

          <?php endforeach; ?>
          <?php if ($nav_label != "Categories"): ?>
            <li><a href="#">All <?php echo htmlentities( $nav_label ); ?><?php if ( $item == "Blog" ) { echo " Posts"; } ?></a></li>
          <?php endif; ?>
        </ul>
      </li>
      <?php endforeach; ?>
  </ul>
</nav>