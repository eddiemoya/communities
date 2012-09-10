<?php
/**
 * Template Name: batman
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header');

//loop();
?>

	<style type="text/css">
		.tim, .single {}
			
			.tim #content, .single #content {}
			
					.tim #content > section.span12, .single #content > section.span12 {
						border-top:solid 1px #d5d5d5;
					}
					
					.tim #content > section.span12 > section[class*="span"] > [class*="span"]:first-child,
					.tim #content > section.span12 > section[class*="span"] > [class*="span"]:first-child header,
					.single #content > section.span12 > section[class*="span"] > [class*="span"]:first-child, 
					.single #content > section.span12 > section[class*="span"] > [class*="span"]:first-child header {
						border-top:none;
					}
			
			
			
			
			.blog-post {}
			
				.blog-post .article-data {
					margin-top:.625em;
				}
				
				.blog-post .article-data cite {
					float:left;
					font-size:.8125em;
					font-weight:bold;
				}
				
				.blog-post .article-data time {
					color:#d5d5d5;
					float:right;
					font-size:.6875em;
					line-height:1.5em;
				}
				
				.blog-post.content-container .content-header {
					border:none;
					padding:0px;
				}
				
				.sears .blog-post h1 {
					color:#067BB5;
				}
				
				.kmart .blog-post h1 {
					color:#980017;
				}
				
				.blog-post h1 {
					font-size:1.25em;
					font-weight:bold;
					line-height:1.1em;
					margin:.75em 0;
				}
				
				.blog-post.content-container .content-body {
					padding:0px;
				}
				
					.blog-post.content-container .content-body p {
						margin-bottom:1.5em;
					}
				
				
	</style>

	<section class="span12">
		
		<section class="span8">
		
			<!-- FEATURED BLOG/BUYING GUIDE WIDGET -->
			<article class="content-container featured-post span12">
			
				<header class="content-header">
					<h3>Featured Blog Post</h3>
				</header>
		
				<section class="content-body clearfix">
					
					<div class="featured-image span6">
					  <img src="/wp-content/uploads/2012/07/blogmodule.jpg">
					</div>
					
					<div class="span6">
					  
					  <section class="content-details clearfix">
							<span class="content-category"><a href="#" title="Fantasy">Fantasy</a></span>
							<time class="content-date" pubdate datetime="1954-07-24">July 24, 1954</time>
						</section>
	  				
					  <h1 class="content-headline"><a href="#">Nusquam copiosae accusata quo ad, in mei eius neglege ntur, vel lucilius sententiae et.  Ne vim mazim menandri effi ciendi.  Ludico virtute elabora ret vis in.</a></h1>
					 
					  <ul>
					  	<li class="content-author">By: Loren Ipsum</li>
					  	<li class="content-comments">4 comments</li>
					  </ul>

					  <p class="content-excerpt">Lorem ipsum dolor sit amet, sit senserit similique cotidieque ex, libris postea copora nam.  Sea vidit pro. et, sit senserit similique... <a href="#" title="Read More">Read more</a><p>
					</div>
					
				</section>
		
			</article>
			<!-- END FEATURED BLOG/BUYING GUIDE WIDGET -->
			
			<!-- 6 SPAN FEATURED BLOG/BUYING GUIDE WIDGET -->
			<article class="content-container featured-post span6">
			
				<header class="content-header">
					<h3>Featured Blog Post</h3>
				</header>
		
				<section class="content-body clearfix">
					
					<div class="featured-image span12">
					  <img src="/wp-content/uploads/2012/07/blogmodule.jpg">
					</div>
					
					<div class="span12">
					  
					  <section class="content-details clearfix">
							<span class="content-category"><a href="#" title="Fantasy">Fantasy</a></span>
							<time class="content-date" pubdate datetime="1954-07-24">July 24, 1954</time>
						</section>
	  				
					  <h1 class="content-headline"><a href="#">Nusquam copiosae accusata quo ad, in mei eius neglege ntur, vel lucilius sententiae et.  Ne vim mazim menandri effi ciendi.  Ludico virtute elabora ret vis in.</a></h1>
					 
					  <ul>
					  	<li class="content-author">By: Loren Ipsum</li>
					  	<li class="content-comments">4 comments</li>
					  </ul>
					</div>
					
				</section>
		
			</article>
			<!-- END 6 SPAN FEATURED BLOG/BUYING GUIDE WIDGET -->
			<!-- 6 SPAN FEATURED BLOG/BUYING GUIDE WIDGET -->
			<article class="content-container featured-question span6">
				
				<header class="content-header">
					<h3>Featured Question</h3>
				</header>
		
				<section class="content-body clearfix">
					
					<div class="content-details clearfix">
						<span class="content-category"><a href="#" title="Fantasy">Fantasy</a></span>
							<time class="content-date" pubdate datetime="1954-07-24">July 24, 1954</time>
					</div>
					
					<h1 class="content-headline">
						Q:
						<a href="#" title="Lorem ipsum dolor sit amet, ut pri congue viven scaevola. Primis verear honestatis vel no, habeo prima delicata te pro. Quodsi omnesque iracundia et ius, munere viderer?">Lorem ipsum dolor sit amet, ut pri congue viven scaevola. Primis verear honestatis vel no, habeo prima delicata te pro. Quodsi omnesque iracundia et ius, munere viderer?</a>
					</h1>
					
					<ul>
						<li class="content-comments">8 answers | 2 community team answers</li>
					</ul>

				</section>
		
			</article>
			<!-- END 6 SPAN FEATURED BLOG/BUYING GUIDE WIDGET -->
			
			<!-- FEATURED QUESTION -->
			<article class="content-container featured-question span12">
				
				<header class="content-header">
					<h3>Featured Question</h3>
				</header>
		
				<section class="content-body clearfix">
					
					<div class="content-details clearfix">
						<span class="content-category"><a href="#" title="Fantasy">Fantasy</a></span>
							<time class="content-date" pubdate datetime="1954-07-24">July 24, 1954</time>
					</div>
					
					<h1 class="content-headline">
						Q:
						<a href="#" title="Lorem ipsum dolor sit amet, ut pri congue viven scaevola. Primis verear honestatis vel no, habeo prima delicata te pro. Quodsi omnesque iracundia et ius, munere viderer?">Lorem ipsum dolor sit amet, ut pri congue viven scaevola. Primis verear honestatis vel no, habeo prima delicata te pro. Quodsi omnesque iracundia et ius, munere viderer?</a>
					</h1>
					
					<ul class="clearfix">
						<li class="content-comments">8 answers | 2 community team answers</li>
					</ul>

				</section>
		
			</article>
			<!-- END FEATURED QUESTION -->
			
			<!-- BREADCRUMB WIDGET -->
			<nav class="span12 breadcrumb">
				<ul class="clearfix">
					<li><a href="#" title="The Fellowship of the Ring">The Fellowship of the Ring</a></li>
					<li><a href="#" title="The Old Forest">The Old Forest</a></li>
				</ul>
			</nav>
			<!-- END BREADCRUMB WIDGET -->
			
			<!-- ARTICLE CONTENT AREA -->
			<article class="content-container blog-post span12">
				
				<section class="content-body clearfix">
					
					<div class="content-details clearfix">
						<span class="content-category"><a href="#" title="Fantasy">Fantasy</a></span>
						<time class="content-date" pubdate datetime="1954-07-24">July 24, 1954</time>
					</div>
					
					<h1 class="content-headline">Chapter 6 The Old Forest</h1>
					
					<p>
						Frodo woke suddenly. It was still dark in the room. Merry was standing there with a candle in one hand, and banging on the door with the other. &quot;All right! What is it?&quot; said Frodo, still shaken and bewildered.
					</p>
					<p>
						&quot;What is it!&quot; cried Merry. &quot;It is time to get up. It is half past four and very foggy. Come on! Sam is already getting breakfast ready. Even Pippin is up. I am just going to saddle the ponies, and fetch the one that is to be the baggage&ndash;carrier. Wake that sluggard Fatty! At least he must get up and see us off.&quot;
					</p>
					<p>
						Soon after six o&rsquo;clock the five hobbits were ready to start. Fatty Bolger was still yawning. They stole quietly out of the house. Merry went in front leading a laden pony, and took his way along a path that went through a spinney behind the house, and then cut across several fields. The leaves of trees were glistening, and every twig was dripping; the grass was grey with cold dew. Everything was still, and far&ndash;away noises seemed near and clear: fowls chattering in a yard, someone closing a door of a distant house.
					</p>
					<p>
						In their shed they found the ponies; sturdy little beasts of the kind loved by hobbits, not speedy, but good for a long day&rsquo;s work. They mounted, and soon they were riding off into the mist, which seemed to open reluctantly before them and close forbiddingly behind them. After riding for about an hour, slowly and without talking, they saw the Hedge looming suddenly ahead. It was tall and netted over with silver cobwebs. &quot;How are you going to get through this?&quot; asked Fredegar. &quot;Follow me!&quot; said Merry, &quot;and you will see.&quot; He turned to the left along the Hedge, and soon they came to a point where it bent inwards, running along the lip of a hollow. A cutting had been made, at some distance from the Hedge, and went sloping gently down into the ground. It had walls of brick at the sides, which rose steadily, until suddenly they arched over and formed a tunnel that dived deep under the Hedge and came out in the hollow on the other side.
					</p>
					<p>
						Here Fatty Bolger halted. &quot;Good&ndash;bye, Frodo!&quot; he said. &quot;I wish you were not going into the Forest. I only hope you will not need rescuing before the day is out. But good luck to you. today and every day!&quot;
					</p>
					<p>
						By: <a href="#">Loren Ipsum</a>
					</p>
					<p>Tags:</p>
					
					<div class="addthis_toolbox addthis_default_style clearfix">
				    <a addthis:url="http://sears-communities.qa.ch4.s.com/community/general/top-tips-for-cheaper-driving/" class="addthis_button_facebook_like at300b" title="Facebook_like" href="#"><fb:like send="false" href="http://sears-communities.qa.ch4.s.com/community/general/top-tips-for-cheaper-driving/" font="arial" width="90" action="like" show_faces="false" layout="button_count" ref="" class=" fb_edge_widget_with_comment fb_iframe_widget"><span style="height: 21px; width: 73px;"><iframe scrolling="no" id="fed221d413aa18" name="f833187068b32" style="border: medium none; overflow: hidden; height: 21px; width: 73px;" title="Like this content on Facebook." class="fb_ltr " src="http://www.facebook.com/plugins/like.php?action=like&amp;api_key=172525162793917&amp;channel_url=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D11%23cb%3Df18e63ede4ea1ea%26origin%3Dhttp%253A%252F%252Fsears-communities.qa.ch4.s.com%252Ff113677575b2844%26domain%3Dsears-communities.qa.ch4.s.com%26relation%3Dparent.parent&amp;extended_social_context=false&amp;font=arial&amp;href=http%3A%2F%2Fsears-communities.qa.ch4.s.com%2Fcommunity%2Fgeneral%2Ftop-tips-for-cheaper-driving%2F&amp;layout=button_count&amp;locale=en_US&amp;node_type=link&amp;sdk=joey&amp;send=false&amp;show_faces=false&amp;width=90"></iframe></span></fb:like></a>
				    <a addthis:url="http://sears-communities.qa.ch4.s.com/community/general/top-tips-for-cheaper-driving/" class="addthis_button_tweet at300b" title="Tweet" href="#"><iframe scrolling="no" frameborder="0" allowtransparency="true" src="http://platform.twitter.com/widgets/tweet_button.1347008535.html#_=1347302982094&amp;count=horizontal&amp;counturl=http%3A%2F%2Fsears-communities.qa.ch4.s.com%2Fcommunity%2Fgeneral%2Ftop-tips-for-cheaper-driving%2F&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=http%3A%2F%2Fsears-communities.qa.ch4.s.com%2Fcommunity%2Fgeneral%2Ftop-tips-for-cheaper-driving%2F&amp;size=m&amp;text=Top%20Tips%20for%20Cheaper%20Driving%20%C2%AB%20Sears%20Communities%3A&amp;url=http%3A%2F%2Fsears-communities.qa.ch4.s.com%2Fcommunity%2Fgeneral%2Ftop-tips-for-cheaper-driving%2F" class="twitter-share-button twitter-count-horizontal" style="width: 107px; height: 20px;" title="Twitter Tweet Button" data-twttr-rendered="true"></iframe></a>
				    <a addthis:url="http://sears-communities.qa.ch4.s.com/community/general/top-tips-for-cheaper-driving/" class="addthis_button_email at300b" title="Email" href="#"><img src="http://sears-communities.qa.ch4.s.com/community/wp-content/themes/Eris/assets/img/email.png"></a>
				    <a addthis:url="http://sears-communities.qa.ch4.s.com/community/general/top-tips-for-cheaper-driving/" addthis:title="ShopYourWay this" class="addthis_button_www.shopyourway.com at300b" href="http://www.addthis.com/bookmark.php?v=250&amp;winname=addthis&amp;pub=wp-504e361d46602fd7&amp;source=tbx-250,wpp-264&amp;lng=en-US&amp;s=www.shopyourway.com&amp;url=http%3A%2F%2Fsears-communities.qa.ch4.s.com%2Fcommunity%2Fgeneral%2Ftop-tips-for-cheaper-driving%2F&amp;title=ShopYourWay%20this&amp;ate=AT-wp-504e361d46602fd7/-/-/504e3644af6bc9bd/2&amp;frommenu=1&amp;uid=504e3644e72948b3&amp;ufbl=1&amp;pre=http%3A%2F%2Fsears-communities.qa.ch4.s.com%2Fcommunity%2Fcategory%2Fgeneral%2F%3Fpost_type%3Dpost&amp;tt=0" target="_blank" title="Www.shopyourway.com"><img src="http://sears-communities.qa.ch4.s.com/community/wp-content/themes/Eris/assets/img/shopyourway_large.png"></a>
					</div>
					<p>
						4 Comments
					</p>
				</section>
				
				<section class="article-data clearfix">
					<cite><a href="#" title="Fantasy">Fantasy</a></cite>
					<time pubdate datetime="1954-07-24">July 24, 1954</time>
				</section>
				
				<header class="content-header">
					<hgroup>
						<h1>Chapter 6 The Old Forest</h1>
					</hgroup>
				</header>
				<section class="content-body clearfix">
					<p>
						Frodo woke suddenly. It was still dark in the room. Merry was standing there with a candle in one hand, and banging on the door with the other. &quot;All right! What is it?&quot; said Frodo, still shaken and bewildered.
					</p>
					<p>
						&quot;What is it!&quot; cried Merry. &quot;It is time to get up. It is half past four and very foggy. Come on! Sam is already getting breakfast ready. Even Pippin is up. I am just going to saddle the ponies, and fetch the one that is to be the baggage&ndash;carrier. Wake that sluggard Fatty! At least he must get up and see us off.&quot;
					</p>
					<p>
						Soon after six o&rsquo;clock the five hobbits were ready to start. Fatty Bolger was still yawning. They stole quietly out of the house. Merry went in front leading a laden pony, and took his way along a path that went through a spinney behind the house, and then cut across several fields. The leaves of trees were glistening, and every twig was dripping; the grass was grey with cold dew. Everything was still, and far&ndash;away noises seemed near and clear: fowls chattering in a yard, someone closing a door of a distant house.
					</p>
					<p>
						In their shed they found the ponies; sturdy little beasts of the kind loved by hobbits, not speedy, but good for a long day&rsquo;s work. They mounted, and soon they were riding off into the mist, which seemed to open reluctantly before them and close forbiddingly behind them. After riding for about an hour, slowly and without talking, they saw the Hedge looming suddenly ahead. It was tall and netted over with silver cobwebs. &quot;How are you going to get through this?&quot; asked Fredegar. &quot;Follow me!&quot; said Merry, &quot;and you will see.&quot; He turned to the left along the Hedge, and soon they came to a point where it bent inwards, running along the lip of a hollow. A cutting had been made, at some distance from the Hedge, and went sloping gently down into the ground. It had walls of brick at the sides, which rose steadily, until suddenly they arched over and formed a tunnel that dived deep under the Hedge and came out in the hollow on the other side.
					</p>
					<p>
						Here Fatty Bolger halted. &quot;Good&ndash;bye, Frodo!&quot; he said. &quot;I wish you were not going into the Forest. I only hope you will not need rescuing before the day is out. But good luck to you. today and every day!&quot;
					</p>
				</section>
			</article>
			<!-- END ARTICLE CONTENT -->
			
		</section>
	
		<section class="span4">
			
			<!-- ABOUT US (CONTENT BLURB) WIDGET -->
			<article class="content-container content-blurb span12">
			
				<header class="content-header">
					<h3>About</h3>
				</header>
		
				<section class="content-body clearfix">
					
					<p>
						Although trying to avoid it, the hobbits get lost and travel to the River Withywindle, the &quot;queerest part of the whole wood&quot;. Merry and Pippin are trapped inside Old Man Willow, and are freed only when Tom Bombadil arrives.
					</p>
					
				</section>
		
			</article>
			<!-- END ABOUT US (CONTENT BLURB) WIDGET -->
			
			<!-- MANUAL LIST WIDGET -->
			<article class="content-container manual-list span12">
			
				<header class="content-header">
					<h3>First time here?</h3>
					<h4>Here&rsquo;s our best stuff</h4>
				</header>
		
				<section class="content-body clearfix">
					
					<ul>
						<li><a href="http://lotr.wikia.com/wiki/Main_Page" title="Lord of the Rings Wiki" rel="external">Lord of the Rings Wiki</a></li>
						<li><a href="http://en.wikipedia.org/wiki/The_Lord_of_the_Rings_(film_series)" title="Wikipedia Lord of the Rings">Lord of the Rings on Wikipedia</a></li>
						<li><a href="http://www.lordoftherings.net/" title="Lord of the Rings Official Site">Lord of the Rings Official Site</a></li>
						<li><a href="http://www.imdb.com/title/tt0120737/" title="Lord of Rings on IMDB">IMDB &ndash; Lord of the Rings: Fellowship of the Ring</a></li>
					</ul>
					
				</section>
		
			</article>
			<!-- END MANUAL LIST WIDGET -->
			
			<!-- MANUAL LIST WIDGET -->
			<article class="content-container manual-list span12">
			
				<header class="content-header">
					<h3>Related Stories</h3>
				</header>
		
				<section class="content-body clearfix">
					
					<ul>
						<li><a href="#" title="The Hobbit">The Hobbit</a></li>
						<li><a href="#" title="The Two Towers">Lord of the Rings on Wikipedia</a></li>
						<li><a href="http://www.lordoftherings.net/" title="Lord of the Rings Official Site">Lord of the Rings Official Site</a></li>
						<li><a href="http://www.imdb.com/title/tt0120737/" title="Lord of Rings on IMDB">IMDB &ndash; Lord of the Rings: Fellowship of the Ring</a></li>
					</ul>
					
				</section>
		
			</article>
			<!-- END MANUAL LIST WIDGET -->
			
		</section>
		
	</section>
	
<?php
get_template_part('parts/footer');

?>