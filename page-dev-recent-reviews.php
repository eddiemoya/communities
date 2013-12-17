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
		
		<section class="span8">
			
			
			
		</section>
	
		<section class="span4">
			
			<!-- RECENT REVIEWS WIDGET -->
			
			<article class="span12 content-container recent_reviews">

				<hgroup class="content-header">
			  		<h3>Recent Reviews</h3>
			  	</hgroup>

				<section class="content-body clearfix">
					<ul class="recent-reviews-list">

						<li class="clearfix">
							<ul>
								<li class="recent-review_title">
									<!--
										Title limited to 73 characters. Need to add horizontal ellipse.
										Link to all reviews page
									-->
									<a href="#">Really Impressed</a>
								</li>
								<li class="recent-review_reviewer">Steve Jones, Los Angeles, CA</li>
								<li class="recent-review_review">
									<!-- 
										We will have to add the horizontal ellipse.
										Text limited to 80 characters.
										'more' links to all reviews page.
									-->
									Completely plagiarize tactical niches after unique growth strategies. Intrinsicly&hellip; <a href="#">more</a>
								</li>
								<li class="recent-review_name-rating">
									<span class="recent-review_name">Kenmore French Door</span>
									<div class="rating">
							          <div class="rating-stars">
							            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/stars1.png" alt="<?php echo $activity->attribute_rating[0]->value; ?>/5 stars" />
							          </div>
							          <div class="rating-bar" style="width: 90%;">&nbsp;</div>
							          <!-- <div class="rating-bar" style="width: <?php echo ((int)$activity->attribute_rating[0]->value / 5) * 100 ?>%;">&nbsp;</div> -->
							        </div>
								</li>								
							</ul>
						</li>

						<li class="clearfix">
							<ul>
								<li class="recent-review_title">
									<!--
										Title limited to 73 characters. Need to add horizontal ellipse.
										Link to all reviews page
									-->
									<a href="#">Nice features on Amana&hellip;</a>
								</li>
								<li class="recent-review_reviewer">Glen Matlock, Naples, FL</li>
								<li class="recent-review_review">
									<!-- 
										We will have to add the horizontal ellipse.
										Text limited to 80 characters.
										'more' links to all reviews page.
									-->
									Completely plagiarize tactical niches after unique growth strategies. Intrinsicly&hellip; <a href="#">more</a>
								</li>
								<li class="recent-review_name-rating">
									<span class="recent-review_name">Amana Microwave</span>
									<div class="rating">
							          <div class="rating-stars">
							            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/stars1.png" alt="<?php echo $activity->attribute_rating[0]->value; ?>/5 stars" />
							          </div>
							          <div class="rating-bar" style="width: 90%;">&nbsp;</div>
							          <!-- <div class="rating-bar" style="width: <?php echo ((int)$activity->attribute_rating[0]->value / 5) * 100 ?>%;">&nbsp;</div> -->
							        </div>
								</li>								
							</ul>
						</li>
						
						<li class="clearfix">
							<ul>
								<li class="recent-review_title">
									<!--
										Title limited to 73 characters. Need to add horizontal ellipse.
										Link to all reviews page
									-->
									<a href="#">Does the light stay on when&hellip;</a>
								</li>
								<li class="recent-review_reviewer">Paul Cook, New York, NY</li>
								<li class="recent-review_review">
									<!-- 
										We will have to add the horizontal ellipse.
										Text limited to 80 characters.
										'more' links to all reviews page.
									-->
									Completely plagiarize tactical niches after unique growth strategies. Intrinsicly&hellip; <a href="#">more</a>
								</li>
								<li class="recent-review_name-rating">
									<span class="recent-review_name">Kenmore French Door</span>
									<div class="rating">
							          <div class="rating-stars">
							            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/stars1.png" alt="<?php echo $activity->attribute_rating[0]->value; ?>/5 stars" />
							          </div>
							          <div class="rating-bar" style="width: 90%;">&nbsp;</div>
							          <!-- <div class="rating-bar" style="width: <?php echo ((int)$activity->attribute_rating[0]->value / 5) * 100 ?>%;">&nbsp;</div> -->
							        </div>
								</li>								
							</ul>
						</li>
						
					</ul>
		
				</section>			

			</article>

			<!-- END RECENT REVIEWS WIDGET -->

		</section>
		
	</section>
	
<?php
get_template_part('parts/footer');

?>