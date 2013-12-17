<article class="span12 content-container recent_reviews">

				<hgroup class="content-header">
			  		<h3><?php echo $recent_reviews_title;?></h3>
			  	</hgroup>

				<section class="content-body clearfix">
				
				<?php if($reviews):?>
					<ul class="recent-reviews-list">
						<?php foreach($reviews as $review):?>
						<li class="clearfix">
							<ul>
								<li class="recent-review_title">
									<!--
										Title limited to 73 characters. Need to add horizontal ellipse.
										Link to all reviews page
									-->
									<a href="<?php echo $review->all_reviews_url;?>"><?php echo truncated_text(trim($review->reviews[0]->summary), 73);?></a>
								</li>
								
								<li class="recent-review_reviewer"><?php echo $review->reviews[0]->author->screenName . ', ' . $review->reviews[0]->author->city . ', ' . $review->reviews[0]->author->state;?></li>
								<li class="recent-review_review">
									<!-- 
										We will have to add the horizontal ellipse.
										Text limited to 80 characters.
										'more' links to all reviews page.
									-->
									<?php echo truncated_text(trim($review->reviews[0]->content), 80);?> <a href="<?php echo $review->all_reviews_url;?>">more</a>
								</li>
								<li class="recent-review_name-rating">
									<span class="recent-review_name"><?php echo strlen($review->reviews[0]->target_description) ? $review->reviews[0]->target_description : 'No Product Details';?></span>
									<div class="rating">
							          <div class="rating-stars">
							            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/stars1.png" alt="<?php echo (float)$review->reviews[0]->attribute_rating[0]->value; ?>/5 stars" />
							          </div>
							          <div class="rating-bar" style="width: <?php echo ((float)$review->reviews[0]->attribute_rating[0]->value / 5) * 100 ?>%;">&nbsp;</div>
							        </div>
								</li>								
							</ul>
						</li>
						<?php endforeach;?>
					</ul>
					<?php else:?>
						Sorry, no reviews available yet.
					<?php endif;?>
				</section>			
</article>