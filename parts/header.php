<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
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
		
		
		<?php
			$options = get_option("theme_options");
		?>
		<nav id="ribbon">
			<ul>
				<li class="ribbon-icon" id="ribbon_shop-your-way"><a href="http://www.shopyourway.com" title="Shop Your Way" rel="external">Shop Your Way</a></li>
				<!-- IF SEARS -->
				<li class="ribbon-icon" id="ribbon_kmart"><a href="http://www.kmart.com?intcmp=xsite_Sears" title="Kmart" rel="external">Kmart</a></li>
				<!-- IF KMART -->
				<li class="ribbon-icon" id="ribbon_sears"><a href="http://www.sears.com?intcmp=xsite_Kmart" title="Sears" rel="external">Sears</a></li>
				<li class="ribbon-icon" id="ribbon_shop-your-way-rewards"><a href="http://www.shopyourwayrewards.com" title="Shop Your Way Rewards" rel="external">Shop Your Way Rewards</a></li>
				<li class="ribbon-icon" id="ribbon_my-gofer"><a href="http://www.mygofer.com?intcmp=xsite_Sears" title="myGofer" rel="external">myGofer</a></li>
				<li class="ribbon-icon" id="ribbon_craftsman"><a href="http://www.craftsman.com?intcmp=xsite_Sears" title="myGofer" rel="external">myGofer</a></li>
			</ul>
		</nav>
        
        <!-- SHC Header from SHC Header-Footer Plugin -->
        <div id="shc_header">
            <?php do_action('content-top'); ?>
        </div>
        
        <!-- #page-wrapper -->
        <div id="page_wrapper">

            <div id="wp_header">
                <a href="<?php echo site_url(''); ?>" id="logo"></a>
                <?php wp_nav_menu( array( 'theme_location' => 'main-navigation', 'menu_id' => 'main-nav') ); ?>
            </div>
            
            <!-- #page -->
            <div id="page">
                
                <?php do_action('content-before'); ?>
                
                <div id="subheader">
                    <?php /* Subheaders */ ?>
                </div>
                
                <!-- #content -->
                <div id="content">