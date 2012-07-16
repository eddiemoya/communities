<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"  xmlns="http://www.w3.org/1999/xhtml" xmlns:shc="http://www.sears.com" <?php language_attributes(); ?> > <!--<![endif]-->
<head>

    <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
		<meta http-equiv="X-UA-Compatible" content="IE=8" >

    <meta name="description" content="<?php bloginfo('description'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!--<link rel="shortcut icon" href="<?php //echo get_template_directory_uri(); ?>/images/favicon.ico" /> -->

    <?php wp_head(); ?>
      
  </head>

  <body <?php body_class(); ?>>
      <?php do_action('content-top'); ?>

      <?php get_template_part('parts/ribbon'); ?>

      <div id="container">

          <div id="persistent-header" shc:widget="persistr">

              <?php get_template_part('parts/head'); ?>

              <?php get_template_part('parts/navigation'); ?>
              
              <?php
              /**
							 * @Eddie: Uncomment your mom.
							 */
               
              /**
               * @Tim & @Carl: Un-comment the  line  below and look at how it works in the admin
               * 
               * Appearance > Menus
               * 
               * Add items via drag and drop, use Screen Options tab to enable all the extra fields for menu items.
               * It seems it would be a lot simpler than creating them manually as in parts/navigation.
               * 
               * Let me know what you think.
              
              
              
               wp_nav_menu( array(
                   'theme_location' => 'main-mavigation',
                   'menu' => '',
                   'menu_class' => 'clearfix dropmenu', 
                   'menu_id' => 'header_nav', 
                   'container_id' => 'navigation',
                   'fallback_cb' => 'wp_list_pages'
                 
                   ));
               */
              ?>

          </div>    
					
					<!-- #content -->
					<section id="content" class="clearfix">
					
	          <?php //wp_nav_menu( array( 'theme_location' => 'main-navigation', 'menu_id' => 'main-nav', 'container_id' => 'header_nav', 'container', 'container_class' => 'menu-{menu slug}-container cleafix') ); ?>
	
	          <?php //do_action('content-before'); ?>
	
	          <?php /* Subheaders */ ?>

