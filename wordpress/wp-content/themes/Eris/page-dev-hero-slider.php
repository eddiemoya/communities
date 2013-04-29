<?php
/**
 * Template Name: batman
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header');

//loop();
?>

	<section class="span12">
		
		<!-- START MAIN CONTENT SECTION -->
		<section class="span8">
			
			
			<article class="span12 content-container hero-slider widget">
				<!-- 
					Just like any other widget, if no header/subheader is entered
					do not load <hgroup> element.
				-->
				<hgroup class="content-header">
			  		<h3>Hero Slider</h3>
			  		<h4>THe hero community needs, not the hero it deserves.</h4>
			  	</hgroup>

				<section class="content-body clearfix">
					<!-- HERO SLIDER -->
					
					<div id="master" class='rotating-banner pre-load' shc:gizmo='responslide' shc:gizmo:options="{animate:'slide', autoslide:true}">
						
						<!-- IMAGE -->
				        <div shc:responslide='banner' shc:responslide:url="http://www.google.com">
				            <section class='banner image'>
				            	
				                <section class="banner-image">
				                	<img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/demo/hero-slider/slide.jpg" alt="Image of Ty Pennington" />
				                </section>
				                
				            </section>
				        </div>
				        <!-- END IMAGE -->
				        
				        <!-- BOTTOM BAR -->
				        <div shc:responslide='banner' shc:responslide:url="http://www.google.com">
				            <section class='banner bottom-bar'>
				            	
				                <aside class="banner-content">
				                	
				                	<hgroup class="banner-headline">
					                	<!-- TITLE -->
					                	<h1 class="banner-title">Gas Vs. Charcoal</h1>
					                	<!-- SUBTITLE -->
					                	<h2 class="banner-subtitle">What Grill is Right For You?</h2>
					                </hgroup>
				                	

				                	
				                </aside>
				                
				                <section class="banner-image">
				                	<img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/demo/hero-slider/slide.jpg" alt="Image of Ty Pennington" />
				                </section>
				                
				            </section>
				        </div>
				        <!-- END BOTTOM BAR -->
				        
				        <!-- SIDE BAR -->
				        <div shc:responslide='banner' id="slide_1" shc:responslide:url="http://www.google.com">
				            <section class='banner side-bar'>
				            	
				                <aside class="banner-content">
				                	
				                	<hgroup class="banner-headline">
					                	<!-- TITLE -->
					                	<h1 class="banner-title">Gas Vs. Charcoal</h1>
					                	<!-- SUBTITLE -->
					                	<h2 class="banner-subtitle">What Grill is Right For You?</h2>
					                </hgroup>
				                	
				                	<!-- EXCERPT -->
				                	<p class="banner-excerpt">
				                		It&rsaquo;s not just a matter of smoky flavor vs. convenience. Cast your vote!
				                	</p>
				                	
				                	<!-- CALL TO ACTION -->
				                	<button class="banner-button">Read More</button>
				                	
				                </aside>
				                
				                <section class="banner-image">
				                	<img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/demo/hero-slider/slide.jpg" alt="Image of Ty Pennington" />
				                </section>
				                
				            </section>
				        </div>
				        <!-- END SIDE BAR -->
				        
				        <!-- VIDEO -->
				        <div shc:responslide='banner' id="slide_1" shc:responslide:url="http://www.google.com">
				            <section class='banner video'>
				            
				                <section class="banner-video">
				                	<iframe width="420" height="315" src="http://www.youtube.com/embed/InNeZBNZbr4?wmode=opaque" frameborder="0" allowfullscreen></iframe>
				                </section>
				                
				            </section>
				        </div>
				        <!-- VIDEO -->
				        
				    </div>
					<!-- END HERO SLIDER -->
				</section>			

			</article>
			
			
		</section>
		<!-- END MAIN CONTENT SECTION -->
		
		<!-- START RIGHT RAIL -->
		<section class="span4">
			
		</section>
		<!-- END RIGHT RAIL -->
		
	</section>
	
<?php
get_template_part('parts/footer');

?>