<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
get_template_part('parts/header');

//loop();
?>

	<section class="span12">
		
		<section class="span8">
			<article class="content-container span12 error404">
				<section class="content-body">
					
					<h1>Yikes! We&rsquo;re sorry but that page is no longer available.</h1>
					<h2>Here&rsquo;s why:</h2>
					<ul class="bullets">
						<li>The page may no longer exist.</li>
						<li>Your session may have timed out.</li>
						<li>Or worse, evil trolls have stolen the page. (Not again!)</li>
					</ul>
					<p>Evil trolls or not, we&rsquo;re here to help you find just what you need.</p>
					<ul>
						<li><a href="<?php echo get_permalink(get_page_by_path('customer-care'));?>">Community Customer Care</a></li>
						<li>or</li>
						<li><a href="<?php echo get_site_url('');?>"><?php echo (strtolower(theme_option("brand")) == "sears") ? "MySears Community" : "MyKmart Community";?></a></li>
					</ul>
				</section>
				
			</article>
			
			
			
		</section>
	
		<section class="span4">
			
			
		</section>
		
	</section>
	
<?php
get_template_part('parts/footer');

?>