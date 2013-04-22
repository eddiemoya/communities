<?php
/**
 * Template Name: batman
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header');
?>

	<section class="span12">
		
		<section class="span8">
			
			
			<!-- START TAXONOMY SELECT WIDGET -->
			<article class="widget content-container taxonomy-widget_grid span12">
				
				<header class="content-header">
					<h3>Departments</h3>
				</header>
				
				<section class="content-body clearfix">
					<!--
						We will need to find a way they can set 'title' attributes
						on the links, as well as 'alt' on the images.
					-->
					<ul class="span12">
						<!--
							Need to add class 'alpha' for n, and n + 4, where n = 1 (so 1, 5, 9, 13, etc.)
						-->
						<li class="span3 alpha">
							<a href="#" title="Appliance Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_appliances.jpg" alt="Refrigerator" /></a>
							<h5><a href="#" title="Appliance Reviews">Appliances</a></h5>
						</li>
						<li class="span3">
							<a href="#" title="Baby Clothes and Accessories Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_baby.jpg" alt="Baby Clothes" /></a>
							<h5><a href="#" title="Baby Clothes and Accessories Reviews">Baby</a></h5>
						</li>
						<li class="span3">
							<a href="#" title="Bed, Bath and Home Decor Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_bed-bath-home.jpg" alt="Bedding" /></a>
							<h5><a href="#" title="Bed, Bath and Home Decor Reviews">Bed, Bath &amp; Home</a></h5>
						</li>
						<li class="span3">
							<a href="#" title="Automotive Accessories and Tires Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_automotive-tires.jpg" alt="Tire" /></a>
							<h5><a href="#" title="Automotive Accessories and Tires Reviews">Automotive &amp; Tires</a></h5>
						</li>
						
						<li class="span3 alpha">
							<a href="#" title="Books and Magazines Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_books-magazines.jpg" alt="Book" /></a>
							<h5><a href="#" title="Books and Magazines Reviews">Books &amp; Magazines</a></h5>
						</li>
						<li class="span3">
							<a href="#" title="Christmas Decor and Gifts Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_christmas-gifts.jpg" alt="Christmas Tree" /></a>
							<h5><a href="#" title="Christmas Decor and Gifts Reviews">Christmas &amp; Gifts</a></h5>
						</li>
						<li class="span3">
							<a href="#" title="Electronics, TV and Office Equipment Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_electronics-tv-office.jpg" alt="DSLR Camera" /></a>
							<h5><a href="#" title="Electronics, TV and Office Equipment Reviews">Electronics, TV &amp; Office</a></h5>
						</li>
						<li class="span3">
							<a href="#" title="Fashion Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_fashion.jpg" alt="Purses" /></a>
							<h5><a href="#" title="Fashion Reviews">Fashion</a></h5>
						</li>
						
						
						<!--
							Any category that isn't listed above is listed out inside the noscript tag.
						-->
						<noscript>
							<ul class="span12">
								<li class="span3 alpha">
									<a href="#" title="Automotive Accessories and Tires Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_automotive-tires.jpg" alt="Tire" /></a>
									<h5><a href="#" title="Automotive Accessories and Tires Reviews">Automotive &amp; Tires</a></h5>
								</li>
								<li class="span3">
									<a href="#" title="Books and Magazines Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_books-magazines.jpg" alt="Book" /></a>
									<h5><a href="#" title="Books and Magazines Reviews">Books &amp; Magazines</a></h5>
								</li>
								<li class="span3">
									<a href="#" title="Christmas Decor and Gifts Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_christmas-gifts.jpg" alt="Christmas Tree" /></a>
									<h5><a href="#" title="Christmas Decor and Gifts Reviews">Christmas &amp; Gifts</a></h5>
								</li>
								<li class="span3">
									<a href="#" title="Electronics, TV and Office Equipment Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_electronics-tv-office.jpg" alt="DSLR Camera" /></a>
									<h5><a href="#" title="Electronics, TV and Office Equipment Reviews">Electronics, TV &amp; Office</a></h5>
								</li>
								
								<li class="span3 alpha">
									<a href="#" title="Fashion Reviews"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_fashion.jpg" alt="Purses" /></a>
									<h5><a href="#" title="Fashion Reviews">Fashion</a></h5>
								</li>
							</ul>
						</noscript>
						
					</ul>
					
					<!--
						LIST ALL DEPARTMENTS HERE
						The value for each option should be the path of the element, minus protocol and host:
						i.e. http://www.sears.com/community/article123 => community/article123
					-->
					<div class="span12 taxonomy-drop-down-menu">
						<select class="input_select" name="department" shc:gizmo="drop-menu">
							<option value="default" selected="selected">All Departments</option>
							<option value="ducks.html">Ducks</option>
							<option value="badgers.html">Badgers</option>
							<option value="cobras.html">Cobras</option>
							<option value="spiders.html">Spiders</option>
							<option value="wasps.html">Wasps</option>
							<option value="unicorns.html">Unicorns</option>
						</select>
					</div>
										
				</section>
				
			</article>
			<!-- END TAXONOMY SELECT WIDGET -->
			
			<article class="widget content-container span12 page-title">
				<section class="content-body">
					<h1>Appliances</h1>
					<h2>Research top-rated items or reviews on the products you own.</h2>
				</section>
			</article>
			
			<!-- START TAXONOMY RESULTS LIST WIDGET -->
			<article class="widget content-container taxonomy-widget_list span12">
				
				<section class="content-body clearfix">
					
					<!-- Use class department -->
					<!-- DEPARTMENT -->
					<article class="content-container department">

    					<section class="content-body clearfix">

            				<div class="featured-image span3">
            					<img title="Refredigerators" alt="Refridgerator" src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/result_fridge.jpg">        
            				</div>
            				
        					<div class="span9 featured-content">

					            <h1 class="content-headline">
					                <a href="#" title="Refrigerator Reviews">Refrigerators</a>
					            </h1>

								<!--
									You will have to add the <sarcasm>always helpful, and very informative</sarcasm> 'see more' link on the back end.
								-->
								<p class="content-excerpt">Lorem ipsum dolor sit amet, sit senserit similique cotidieque ex, libris postea corpora nam. Sea vidit pro. et, sit senserit.</p>
								<p class="content-excerpt">Lorem ipsum dolor sit amet, sit senserit similique cotidieque ex, libris postea corpora nam. Sea vidit pro. et, sit senserit&hellip; <a href="#" class="moretag">See More</a></p>

        					</div>

    					</section>
					
					</article>
					<!-- /DEPARTMENT -->
					
					<!-- DEPARTMENT -->
					<article class="content-container department">

    					<section class="content-body clearfix">

            				<div class="featured-image span3">
            					<img title="Refredigerators" alt="Refridgerator" src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/result_fridge.jpg">        
            				</div>
            				
        					<div class="span9 featured-content">

					            <h1 class="content-headline">
					                <a href="#" title="Refrigerator Reviews">Refrigerators</a>
					            </h1>

								<!--
									You will have to add the <sarcasm>always helpful, and very informative</sarcasm> 'see more' link on the back end.
								-->
								<p class="content-excerpt">Lorem ipsum dolor sit amet, sit senserit similique cotidieque ex, libris postea corpora nam. Sea vidit pro. et, sit senserit.</p>
								<p class="content-excerpt">Lorem ipsum dolor sit amet, sit senserit similique cotidieque ex, libris postea corpora nam. Sea vidit pro. et, sit senserit&hellip; <a href="#" class="moretag">See More</a></p>

        					</div>

    					</section> 
					
					</article>
					<!-- /DEPARTMENT -->
										
				</section>
				
			</article>
			<!-- END TAXONOMY RESULTS LIST WIDGET -->
			
			<!-- FEATURE TAXONOMY SPAN 12 -->
			<article class="widget content-container span12 taxonomy-widget">
        
            	<header class="content-header">
                	<h3>Featured Blog Post</h3>
				</header>
				<section class="content-body clearfix">

					<div class="featured-image span6">
						<img title="Rebuilding Together and Sears Heroes at Home Renovates Home For Veteran" alt="Rebuilding Together and Sears Heroes at Home Renovates Home For Veteran" class="attachment-large wp-post-image" src="https://www.sears.com/community/wp-content/uploads/2013/04/Patrick-Horan-sitting-with-wife-Patty-1024x681.jpg">
					</div>

					<div class="featured-content span6">

           				 <div class="content-details clearfix">
                            <span class="content-category"><a title="Heroes at Home" href="https://www.sears.com/community/category/heroes-at-home/">Heroes at Home</a></span>
                            <time datetime="2013-04-04" pubdate="" class="content-date">April 4, 2013</time>
            			</div>
    					<h1 class="content-headline"><a href="https://www.sears.com/community/heroes-at-home/captain-patrick-horan-inspires-through-recovery-from-injury/">Captain Patrick Horan Inspires through Recovery from Injury</a></h1>
    
    					<ul>
        					<li class="content-author">By: <a href="https://www.sears.com/community/author/SHC-MichaelM/">SHC-MichaelM</a></li>
							<li class="content-comments">1 comment</li>
            			</ul>

                		<p class="content-excerpt">Captain Patrick Horan was born and raised in Springfield, Virginia. He attended Radford University, where he met his wife Patty. Pat graduated in the spring of 1997 and enlisted in the Infantry later that… <a href="https://www.sears.com/community/heroes-at-home/captain-patrick-horan-inspires-through-recovery-from-injury/" class="moretag">See More</a></p>
					</div>
    			</section>
			</article>
			<!-- END FEATURE TAXONOMY SPAN12 -->
			
			<!-- FEATURE TAXONOMY SPAN6 -->
			<article class="widget content-container span6 taxonomy-widget">
        
            	<header class="content-header">
                	<h3>Featured Blog Post</h3>
				</header>
				<section class="content-body clearfix">

					<div class="featured-image span6">
						<img title="Rebuilding Together and Sears Heroes at Home Renovates Home For Veteran" alt="Rebuilding Together and Sears Heroes at Home Renovates Home For Veteran" class="attachment-large wp-post-image" src="https://www.sears.com/community/wp-content/uploads/2013/04/Patrick-Horan-sitting-with-wife-Patty-1024x681.jpg">
					</div>

					<div class="featured-content span6">

           				 <div class="content-details clearfix">
                            <span class="content-category"><a title="Heroes at Home" href="https://www.sears.com/community/category/heroes-at-home/">Heroes at Home</a></span>
                            <time datetime="2013-04-04" pubdate="" class="content-date">April 4, 2013</time>
            			</div>
    					<h1 class="content-headline"><a href="https://www.sears.com/community/heroes-at-home/captain-patrick-horan-inspires-through-recovery-from-injury/">Captain Patrick Horan Inspires through Recovery from Injury</a></h1>
    
    					<ul>
        					<li class="content-author">By: <a href="https://www.sears.com/community/author/SHC-MichaelM/">SHC-MichaelM</a></li>
							<li class="content-comments">1 comment</li>
            			</ul>

                		<p class="content-excerpt">Captain Patrick Horan was born and raised in Springfield, Virginia. He attended Radford University, where he met his wife Patty. Pat graduated in the spring of 1997 and enlisted in the Infantry later that… <a href="https://www.sears.com/community/heroes-at-home/captain-patrick-horan-inspires-through-recovery-from-injury/" class="moretag">See More</a></p>
					</div>
    			</section>
			</article>
			<!-- END FEATURE TAXONOMY SPAN6 -->
			
		</section>
	
		<section class="span4">
			

		</section>
		
	</section>
	
<?php
get_template_part('parts/footer');

?>