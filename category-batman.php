<?php
/**
 * Template Name: batman
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header');

loop();
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