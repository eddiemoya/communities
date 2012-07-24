<?php

  # @author Carl Albrecht-Buehler 
  $a_navigation = array(
    "Categories" => get_categories(), 
    "Q&A's" => list_terms_by_post_type('category', 'question'), 
    "Blog Posts" => list_terms_by_post_type(), 
    "Buying Guides" => list_terms_by_post_type('category', 'guides')
  );//print_pre($a_navigation); ?>



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

        
            <li><a href="<?php echo get_category_link($item->term_id); ?>"><?php echo $item->name; ?></a></li>

          <?php endforeach; ?>
          <?php if ($nav_label != "Categories"): ?>
            <li><a href="#">All <?php echo htmlentities( $nav_label ); ?><?php if ( $nav_item == "Blog" ) { echo " Posts"; } ?></a></li>
          <?php endif; ?>
        </ul>
      </li>
      <?php endforeach; ?>
  </ul>
</nav>


<?php 

/**
 *  Function to get terms only if they have posts by post type
 *  @param $taxonomy (string) taxonomy name eg: 'post_tag','category'(default),'custom taxonomy'
 *  @param $post_type (string) post type name eg: 'post'(default),'page','custom post type'
 *
 *
 *  Usage:
 *  list_terms_by_post_type('post_tag','custom_post_type_name');
 **/


function list_terms_by_post_type($taxonomy = 'category',$post_type = 'post'){
  //get a list of all post of your type
  $args = array(
    'posts_per_page' => -1,
    'post_type' => $post_type
  );
  $terms= array();
  $posts = get_posts($args);
  foreach($posts as $p){
    //get all terms of your taxonomy for each type
    $ts = wp_get_object_terms($p->ID,$taxonomy); 
    foreach ( $ts as $t ) {
      if (!in_array($t,$terms)){ //only add this term if its not there yet
        //$t->cat_name = ''
        $terms[] = $t;
      }
    }
  }
  

  wp_reset_postdata();

  return $terms; 
}