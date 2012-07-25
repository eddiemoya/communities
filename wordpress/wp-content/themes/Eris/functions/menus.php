<?php 

add_action( 'init', 'register_menus' );

/**
 * Create Dynamic Menu Location, and create a Menu to use in that location
 * 
 * @author Eddie Moya
 */
function register_menus() {
  register_nav_menus( array(
      'main-mavigation' => __( 'Main Navigation'),
      'footer-navigation' => _( 'Footer Navigation' )
      ));
}