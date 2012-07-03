<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"  xmlns="http://www.w3.org/1999/xhtml" xmlns:shc="http://www.sears.com" <?php language_attributes(); ?> > <!--<![endif]-->
	<head>
  	
  	
    <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="<?php bloginfo('description'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" />
    
    <?php wp_head(); ?>
      
  </head>

	<body <?php body_class(); ?>>
		<?php do_action('content-top'); ?>
		 
		<?php get_template_part('parts/ribbon'); ?>
		
    <div id="persistent-header">
    	
    	<header id="header" class="<?php echo theme_option("brand"); ?> clearfix">
    		
    		<div class="<?php echo (theme_option("brand") == "sears")? "span4":"span3"; ?>">
    			<a href="<?php echo site_url(''); ?>" id="logo" class="invisible"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo ucfirst(theme_option("brand")); ?>/logo.png" /></a>
    		</div>
    		
    		<div class="<?php echo (theme_option("brand") == "sears")? "span5":"span6"; ?>">
    			<form id="search">
    				<input type="text" class="input_text icon_search" name="search" id="input_search" />
    				<button type="submit" id="button_search">Go</button>
    			</form>
    		</div>
    		
    		<aside class="span3">
    			<ul>
    				<!-- LOGGED IN 
    				<li></li>-->
    				<!-- LOGGED OUT -->
    				<li>
    					
    					<ul>
    						<li id="header_login"><a href="#" title="Login" class="bold">Login</a></li>
    						<li id="header_join"><a href="#" title="Sign Up: Join the Community" class="bold">Join the community</a></li>
    					</ul>
    					
    				</li>
    				
    				<li>
    					
    					<ul>
    						<li id="header_feedback"><a href="#" title="Feedback">Feedback</a></li>
    						<li id="header_shopping">Go Shopping: <a href="<a href="http://www.<?php echo ucfirst(theme_option("brand")); ?>.com" title="<?php echo ucfirst(theme_option("brand")); ?>" rel="external" title="<?php echo ucfirst(theme_option("brand")); ?>" class="bold"><?php echo ucfirst(theme_option("brand")); ?></a></li>
    					</ul>
    					
    				</li>
    			</ul>
    		</aside>
    		
    	</header>
    	
    </div>    
    
        <!-- #page-wrapper -->
        <div id="page_wrapper">

            <div id="wp_header">
                <a href="<?php echo site_url(''); ?>" id="logo"></a>
                <?php wp_nav_menu( array( 'theme_location' => 'main-navigation', 'menu_id' => 'main-nav') ); ?>
            </div>
            
            <nav>
                <ul id="header_nav" class="clearfix">
                  <li class="right_button">
                    <a href="#">Customer Care</a>
                  </li>
                  <li>
                    <a href="#"><span>Categories</span></a>
                    <ul>
                      <li><a href="#">Item 1</a></li>
                      <li><a href="#">Item 2</a></li>
                      <li><a href="#">Item 3</a></li>
                    </ul>
                  </li>
                  <li>
                    <a href="#"><span>Q&amp;A's</span></a>
                    <ul>
                      <li><a href="#">Item 4</a></li>
                      <li><a href="#">Item 5</a></li>
                      <li><a href="#">Item 6</a></li>
                    </ul>
                  </li>
                  <li>
                    <a href="#"><span>Blog</span></a>
                    <ul>
                      <li><a href="#">Item 7</a></li>
                      <li><a href="#">Item 8</a></li>
                      <li><a href="#">Item 9</a></li>
                    </ul>
                  </li>
                  <li>
                    <a href="#"><span>Buying Guides</span></a>
                    <ul>
                      <li><a href="#">Item a</a></li>
                      <li><a href="#">Item b</a></li>
                      <li><a href="#">Item c</a></li>
                    </ul>
                  </li>
                </ul>
            </nav>
            
            <!-- #page -->
            <div id="page">
                
                <?php //do_action('content-before'); ?>
                
                <div id="subheader">
                    <?php /* Subheaders */ ?>
                </div>
                
                <!-- #content -->
                <div id="content">