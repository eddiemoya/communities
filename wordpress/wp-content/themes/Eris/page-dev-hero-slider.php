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

				<hgroup class="content-header">
			  		<h3>Hero Slider</h3>
			  		<h4>THe hero community needs, not the hero it deserves.</h4>
			  	</hgroup>

				<section class="content-body clearfix">
					<!-- HERO SLIDER -->
					
					<div class='rotating-banner pre-load' shc:gizmo='responslide' shc:gizmo:options='{startingBanner:0, animate:true, autoSlideBanners:true, navigation:false, legend:false, useHashBang:false}'>
						
						<!-- IMAGE ONLY -->
				        <div shc:responslide='banner' id="slide_1">
				            <section class='banner'>
				            	
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
				        <!-- END IMAGE ONLY -->
				        
				        <!-- SIDEBAR -->
				        <div shc:responslide='banner' id="slide_1">
				            <section class='banner'>
				            	
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
				        <!-- END SIDEBAR -->
				        
				        <!-- HOVER -->
				        <div shc:responslide='banner' id="slide_1">
				            <section class='banner'>
				            	
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
				        <!-- END HOVER -->
				        
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