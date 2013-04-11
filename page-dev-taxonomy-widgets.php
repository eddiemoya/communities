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
			<article class="widget content-container taxonomy-select-widget span12">
				
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
			<article class="widget content-container result-list span12">
				
				<section class="content-body clearfix">
					
					<!-- Use class department -->
					<article class="content-container department">

    					<section class="content-body clearfix">

            				<div class="featured-image span6">
            					<img title="Refredigerators" alt="Refridgerator" src="<?php echo get_template_directory_uri(); ?>/assets/img/widgets/taxonomy/demo/icon_automotive-tires.jpg">        
            				</div>
            				
        					<div class="span6">
			
			<div class="content-details clearfix">
                                    <span class="content-category">
                        <a title="Lawn &amp; Garden" href="https://www.sears.com/community/category/lawn-garden/">
                            Lawn &amp; Garden                        </a>
                    </span>
                <time datetime="2013-04-10" pubdate="" class="content-date">
                    April 10, 2013                </time>

            </div> <!-- content-details -->

            <h1 class="content-headline">
                <a href="https://www.sears.com/community/lawn-garden/top-10-gifts-for-garden-enthusiasts/">
                    Top 10 Gifts for Garden Enthusiasts                </a>
            </h1> <!-- content-headline -->

            <ul>
                <li class="content-author">By: <a href="https://www.sears.com/community/?author=1373643">Lindsey Paholski</a></li>
      <!--           <li class="content-comments">No Comments</li> -->
            </ul>

            <p class="content-excerpt">by Lindsey Paholski</p>
<p class="content-excerpt">If you’re an avid gardener, you can’t wait to get your hands dirty. You’re probably already planning your garden and tasting the fresh vegetables you’ll harvest in a few months. Why not celebrate nature, dirt and the outdoors during Garden Week, and buy yourself &mdash; and all your garden enthusiast friends &mdash; the perfect garden gifts.</p>
<p class="content-excerpt">New plants</p>
<p class="content-excerpt">Every gardener has room for new plants. Botanists introduce new colors and varieties of plants every year, and you’re… <a href="https://www.sears.com/community/lawn-garden/top-10-gifts-for-garden-enthusiasts/" class="moretag">See More</a></p>

            <p class="content-tags">
                            </p>

            <ul>
                <li class="content-comments">0 comments</li> 
                                <li class="content-share"><ul class="addthis dropmenu">
    <li>
        <span class="sharebutton">Share</span>
        <ul class="sharemenulinks">
            <li><a addthis:title="Top 10 Gifts for Garden Enthusiasts" addthis:url="https://www.sears.com/community/lawn-garden/top-10-gifts-for-garden-enthusiasts/" class="addthis_button_facebook at300b" title="Facebook" href="#"><span class="at16nc at300bs at15nc at15t_facebook at16t_facebook"><span class="at_a11y">Share on facebook</span></span>Facebook</a></li>
            <li><a addthis:title="Top 10 Gifts for Garden Enthusiasts" addthis:url="https://www.sears.com/community/lawn-garden/top-10-gifts-for-garden-enthusiasts/" class="addthis_button_twitter at300b" title="Tweet" href="#"><span class="at16nc at300bs at15nc at15t_twitter at16t_twitter"><span class="at_a11y">Share on twitter</span></span>Twitter</a></li>
            <li><a title="Post this to your ShopYourWay account" style="cursor:pointer;" onclick="javascript:window.open(this.href,'ShopYourWay','width=650,height=350'); return false;" href="https://shopyourway.com/sharer/share?title=Top+10+Gifts+for+Garden+Enthusiasts&amp;link=https%3A%2F%2Fwww.sears.com%2Fcommunity%2Flawn-garden%2Ftop-10-gifts-for-garden-enthusiasts%2F&amp;sourceSiteUrl=&amp;sourceSiteAlias=&amp;content=&amp;imageUrl="><img alt="ShopYourWay Post" src="https://static.shopyourway.com/static/share-buttons/small-light.png">ShopYourWay</a></li>
            <li><a addthis:url="https://www.sears.com/community/lawn-garden/top-10-gifts-for-garden-enthusiasts/" class="addthis_button_email at300b" title="Email" href="#"><span class="at16nc at300bs at15nc at15t_email at16t_email"><span class="at_a11y">Share on email</span></span>Email</a></li>
        </ul>
    </li>
</ul>
</li>
                            </ul>

        </div> <!-- featured- -->

    </section> <!-- featured-post -->
</article>
										
				</section>
				
			</article>
			<!-- END TAXONOMY RESULTS LIST WIDGET -->
			
			
		</section>
	
		<section class="span4">
			

		</section>
		
	</section>
	
<?php
get_template_part('parts/footer');

?>