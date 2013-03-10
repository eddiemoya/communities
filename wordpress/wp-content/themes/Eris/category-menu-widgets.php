<?php
/**
 * Template Name: batman
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header');

//loop();
?>
	
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/gardensolutionscenter.css" />
	
	<section class="span12">
		
		<section class="span8">
			
			
		</section>	
	
		<section class="span4">
			
			<!-- Weather Widget -->
			<article class="width content-container span12 weather-widget">
				<header class="content-header">
					<h3>5 Day Weather Forecast</h3>
				</header>
				
				<section class="content-body clearfix">
					<div class="forecast_current span12">
						
						<ul class="span12">
							<li class="span6 forecast_image">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/img/weather-widget/rainy.png" />
							</li>
							<li class="span6 forecast_details">
								<h5>Mon 1/17</h5>
								<div class="forecast_degrees">
									<span class="high">32&deg;</span><span class="low">/20&deg;</span>
								</div>
								<span class="forecast_description">Light rain shower</span>
								<span class="forecast_location">Chicago, IL</span>
								<span class="forecast_change-location"><a href="#">change location</a></span>
							</li>
						</ul>
						
					</div>
					
					<div class="span12 forecast_4-day">
						<ul class="span12">
							
							<li class="span3">
								<h5>Tues</h5>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/img/weather-widget/rainy.png" />
								<span class="high">22&deg;</span>
								<span class="low">14&deg;</span>
							</li>
							<li class="span3">
								<h5>Wed</h5>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/img/weather-widget/sunny.png" />
								<span class="high">22&deg;</span>
								<span class="low">14&deg;</span>
							</li>
							<li class="span3">
								<h5>Thurs</h5>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/img/weather-widget/chance-storm.png" />
								<span class="high">22&deg;</span>
								<span class="low">14&deg;</span>
							</li>
							<li class="span3">
								<h5>Fri</h5>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/img/weather-widget/cloudy.png" />
								<span class="high">22&deg;</span>
								<span class="low">14&deg;</span>
							</li>
							
						</ul>
					</div>
					
					<div class="span12 forecast_sponsor">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/img/weather-widget/wundergound-logo.png" />
					</div>
					
				</section>
				
			</article>
			
			<!-- END Weather Widget -->
			
			<!-- List Style Image Menu Widget -->
			<!--
				For list style widget add class 'image-list-style' to div with class 'subnav-items'
			-->
			<article class="widget content-container span12 communities_menu_widget menu-garden-solutions-center-top-garden-categories">
				<header class="content-header">
					<h3>Our Top Garden Categories</h3>
					<h4>Check out what's popular in the community</h4>
				</header>
				<section class="content-body clearfix">
					<div class="subnav-items image-list-style">
						<ul class="menu" id="menu-garden-solutions-center-top-garden-categories">
							<li class="first-menu-item menu-item menu-item-type-custom menu-item-object-custom menu-item-8485" id="menu-item-8485">
								<a href="http://www.google.com">
									<span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-widgets/garden-category_weed-insect-control.png" /></span>
									Weed &amp; Insect Control
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-8487" id="menu-item-8487">
								<a href="http://www.google.com">
									<span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-widgets/garden-category_yard-work.png" /></span>
									Yard Work
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-8486" id="menu-item-8486">
								<a href="http://www.google.com">
									<span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-widgets/garden-category_power.png" /></span>
									Power
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-8488" id="menu-item-8488">
								<a href="http://www.google.com">
									<span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-widgets/garden-category_bird.png" /></span>
									Bird
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-8489" id="menu-item-8489">
								<a href="http://www.google.com">
									<span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-widgets/garden-category_gardening.png" /></span>
									Gardening
								</a>
							</li>
						</ul>
					</div>
				</section>
			</article>
			
			<!-- END List Style Image Menu Widget -->
			
			<!-- Grid Style Image Menu Widget -->
			<!--
				For list style widget add class 'image-grid-style' to div with class 'subnav-items'
			-->
			<article class="widget content-container span12 communities_menu_widget menu-garden-solutions-center-knowledge-in-bloom">
				<header class="content-header">
					<h3>Knowledge in Bloom</h3>
					<h4>Helpful links to blossom from</h4>
				</header>
				<section class="content-body clearfix">
					<div class="subnav-items image-grid-style">
						<ul class="menu" id="menu-garden-solutions-center-knowledge-in-bloom">
							<li class="first-menu-item menu-item menu-item-type-custom menu-item-object-custom menu-item-8490" id="menu-item-8490">
								<a href="http://www.google.com">
									<span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-widgets/bloomiq_browse-collections.png" /></span>
									Collections
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-8491" id="menu-item-8491">
								<a href="http://www.google.com">
									<span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-widgets/bloomiq_plants.png" /></span>
									Plants
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-8492" id="menu-item-8492">
								<a href="http://www.google.com">
									<span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-widgets/bloomiq_inspired.png" /></span>
									Inspirations
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-8493" id="menu-item-8493">
								<a href="http://www.google.com">
									<span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-widgets/bloomiq_education.png" /></span>
									Education
								</a>
							</li>
						</ul>
					</div>
				</section>
			</article>
			<!-- END Grid Style Image Menu Widget -->
			
		</section>
		
	</section>
	
<?php
get_template_part('parts/footer');

?>