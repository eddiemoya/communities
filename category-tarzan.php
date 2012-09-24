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
			
			
			
			<article class="widget content-container span12 summary-list">
				
				<header class="content-header">
        	<h3>Related Stories</h3>
        </header>
        
        <section class="content-body clearfix">
        	
        	<ul>
        		
        		<li class="summary_list-item summary-list_question clearfix">
        			<ul>
        				<li class="summary_type-date">
        					<span class="summary_type">Questions</span>
        					<span class="summary_date">July 5, 2012</span>
        				</li>
        				
        				<li class="summary_title">
        					<a href="#">
        						Question headline goes here and here. This is a really long title for a post with many characters and needs to be truncated&hellip;
        					</a>
        				</li>
        				
        				<li class="summary_comments">
        					<span class="summary_comment-count">4 answers | 2 community team answers</span>
        				</li>
        				
        				<li class="summary_see-more">
        					<a href="#">See More</a>
        				</li>
        			</ul>
        		</li>
        		
        		<li class="summary_list-item summary-list_post clearfix">
        			<ul>
        				<li class="summary_type-date">
        					<span class="summary_type">Blog Posts</span>
        					<span class="summary_date">July 5, 2012</span>
        				</li>
        				
        				<li class="summary_title">
        					<a href="#">
        						Question headline goes here and here. This is a really long title for a post with many characters and needs to be truncated&hellip;
        					</a>
        				</li>
        				
        				<li class="summary_comments">
        					<span class="summary_author">By: Dick Headless</span>
        					<span class="summary_comment-count">2 community team answers</span>
        				</li>
        				
        				<li class="summary_see-more">
        					<a href="#">See More</a>
        				</li>
        			</ul>
        		</li>
        		
        		<li class="summary_list-item summary-list_guide clearfix">
        			<ul>
        				<li class="summary_type-date">
        					<span class="summary_type">Buying Guides</span>
        					<span class="summary_date">July 5, 2012</span>
        				</li>
        				
        				<li class="summary_title">
        					<a href="#">
        						Question headline goes here and here. This is a really long title for a post with many characters and needs to be truncated&hellip;
        					</a>
        				</li>
        				
        				<li class="summary_comments">
        					<span class="summary_author">By: Dick Headless</span>
        					<span class="summary_comment-count">2 community team answers</span>
        				</li>
        				
        				<li class="summary_see-more">
        					<a href="#">See More</a>
        				</li>
        			</ul>
        		</li>
        		
        		
        	</ul>
        	
        </section>
				
			</article>
			
			
			
			<!-- BEGIN EXPERTS LIST -->
			
			<div class="archive_users span12">
				<article class="widget content-container span12 results-list">
					<header class="content-header">
						<form action="" method="post">
							<label for="filter-results-posts">View</label>
							<select class="filter-results-posts" id="filter-results" name="filter-category">
								<option value="1" class="level-0">General</option>
								<option value="23" class="level-0">Customer Care</option>
								<option selected="selected" value="436" class="level-0">Appliances</option>
								<option value="437" class="level-0">Electronics</option>
								<option value="438" class="level-0">Fitness &amp; Sports</option>
								<option value="441" class="level-0">Lawn &amp; Garden</option>
								<option value="819" class="level-0">Experts</option>
							</select>
							<label for="sort-results-posts">Sort by</label>
							<select class="sort-results-posts" id="sort-results" name="sort-results">
								<option value="DESC">Oldest First</option>
								<option value="ASC">Newest First</option>
							</select>
							<input type="hidden" name="widget" class="widget_name" value="results-list">
							<input type="hidden" name="post_type" class="post_type" value="question">
						</form>
					</header>
					<section class="content-body">
						
						<article class="content-container user clearfix">
							
							<ul class="member_details">
								
								<li class="member_avatar span6">
									<a href="#">
										<img src="http://localhost:100/wp-content/themes/Eris/assets/img/avatar.jpg?" />
										<span class="badge">Subscriber</span>
									</a>
								</li>
								
								<li class="member_profile span6">
									<ul>
										<li class="member_screen-name"><a href="">TimothySteele</a></li>
										<li class="member_location">Chicago, IL</li>
									</ul>
								</li>
								
								<li class="member_content span12">
									<ul>
										<li class="member_last-posted">Last Posted on September 17, 2012</li>
										<li class="member_answers">8 answers</li>
										<li class="member_posts">3 blog posts</li>
										<li class="member_comments">6 comments</li>
									</ul>
								</li>
								
							</ul>	
							
						</article>
					
						<article class="content-container user clearfix">
							
							<ul class="member_details">
								
								<li class="member_avatar span6">
									<a href="#">
										<img src="http://localhost:100/wp-content/themes/Eris/assets/img/avatar.jpg?" />
										<span class="badge">Subscriber</span>
									</a>
								</li>
								
								<li class="member_profile span6">
									<ul>
										<li class="member_screen-name"><a href="">TimothySteele</a></li>
										<li class="member_location">Chicago, IL</li>
										<li class="member_specialties">
											Specializes in:
											<ul>
												<li><a href="#">Specialty 1</a></li>
												<li><a href="#">Specialty 2</a></li>
												<li><a href="#">Specialty 3</a></li>
											</ul>
										</li>
									</ul>
								</li>
								
								<li class="member_posts span12">
									<ul>
										<li class="member_last-posted">Last Posted on September 17, 2012</li>
										<li class="member_answers">8 answers</li>
										<li class="member_posts">3 blog posts</li>
										<li class="member_comments">6 comments</li>
									</ul>
								</li>
								
							</ul>	
							
						</article>
						
					
					</section>
					<section class="pagination"> </section>
				</article>
			</div>
			
			<!-- END EXPERTS LIST -->
			
			<section class="span12 featured-members content-container">
				
				<hgroup class="content-header">
					<h3>Meet the Community Team</h3>
		      <h4>Whatever your question or issue, we're here to help</h4>
				</hgroup>
				
				<section class="content-body clearfix">
					
					<ul class="member-list">
						
						<li class="span4"> <!-- BEGIN MEMBER LISTING -->
							<div class="member-wrapper clearfix">
								<ul class="member_details">
									
									<li class="member_avatar span6">
										<a href="#">
											<img src="http://localhost:100/wp-content/themes/Eris/assets/img/avatar.jpg?" />
											<span class="badge">Subscriber</span>
										</a>
									</li>
									
									<li class="member_profile span6">
										<ul>
											<li class="member_screen-name"><a href="">TimothySteele</a></li>
											<li class="member_location">Chicago, IL</li>
											<li class="member_specialties">
												Specializes in:
												<ul>
													<li><a href="#">Specialty 1</a></li>
													<li><a href="#">Specialty 2</a></li>
													<li><a href="#">Specialty 3</a></li>
												</ul>
											</li>
										</ul>
									</li>
									
									<li class="member_posts span12">
										<ul>
											<li class="member_last-posted">Last Posted on September 17, 2012</li>
											<li class="member_answers">8 answers</li>
											<li class="member_posts">3 blog posts</li>
											<li class="member_comments">6 comments</li>
										</ul>
									</li>
									
								</ul> <!-- END MEMBER DETAILS -->
							</div> <!-- END MEMBER WRAPPER -->
						</li> <!-- END MEMBER LISTING -->
						
						<li class="span4"> <!-- BEGIN MEMBER LISTING -->
							<div class="member-wrapper clearfix">
								<ul class="member_details">
									
									<li class="member_avatar span6">
										<a href="#">
											<img src="http://localhost:100/wp-content/themes/Eris/assets/img/avatar.jpg?" />
											<span class="badge">Subscriber</span>
										</a>
									</li>
									
									<li class="member_profile span6">
										<ul>
											<li class="member_screen-name"><a href="">TimothySteele</a></li>
											<li class="member_location">Chicago, IL</li>
											<li class="member_specialties">
												Specializes in:
												<ul>
													<li><a href="#">Specialty 1</a></li>
													<li><a href="#">Specialty 2</a></li>
													<li><a href="#">Specialty 3</a></li>
												</ul>
											</li>
										</ul>
									</li>
									
									<li class="member_posts span12">
										<ul>
											<li class="member_last-posted">Last Posted on September 17, 2012</li>
											<li class="member_answers">8 answers</li>
											<li class="member_posts">3 blog posts</li>
											<li class="member_comments">6 comments</li>
										</ul>
									</li>
									
								</ul> <!-- END MEMBER DETAILS -->
							</div> <!-- END MEMBER WRAPPER -->
						</li> <!-- END MEMBER LISTING -->
						
						<li class="span4"> <!-- BEGIN MEMBER LISTING -->
							<div class="member-wrapper clearfix">
								<ul class="member_details">
									
									<li class="member_avatar span6">
										<a href="#">
											<img src="http://localhost:100/wp-content/themes/Eris/assets/img/avatar.jpg?" />
											<span class="badge">Subscriber</span>
										</a>
									</li>
									
									<li class="member_profile span6">
										<ul>
											<li class="member_screen-name"><a href="">TimothySteele</a></li>
											<li class="member_location">Chicago, IL</li>
											<li class="member_specialties">
												Specializes in:
												<ul>
													<li><a href="#">Specialty 1</a></li>
													<li><a href="#">Specialty 2</a></li>
													<li><a href="#">Specialty 3</a></li>
												</ul>
											</li>
										</ul>
									</li>
									
									<li class="member_posts span12">
										<ul>
											<li class="member_last-posted">Last Posted on September 17, 2012</li>
											<li class="member_answers">8 answers</li>
											<li class="member_posts">3 blog posts</li>
											<li class="member_comments">6 comments</li>
										</ul>
									</li>
									
								</ul> <!-- END MEMBER DETAILS -->
							</div> <!-- END MEMBER WRAPPER -->
						</li> <!-- END MEMBER LISTING -->
						
					</ul>
					
				</section>
				
			</section>
			
			<div class="archive_questions">
				<article class="widget content-container span12 results-list">
					<header class="content-header">
						<form action="" method="post">
							<label for="filter-results-posts">View</label>
							<select class="filter-results-posts" id="filter-results" name="filter-category">
								<option value="1" class="level-0">General</option>
								<option value="23" class="level-0">Customer Care</option>
								<option selected="selected" value="436" class="level-0">Appliances</option>
								<option value="437" class="level-0">Electronics</option>
								<option value="438" class="level-0">Fitness &amp; Sports</option>
								<option value="441" class="level-0">Lawn &amp; Garden</option>
								<option value="819" class="level-0">Experts</option>
							</select>
							<label for="sort-results-posts">Sort by</label>
							<select class="sort-results-posts" id="sort-results" name="sort-results">
								<option value="DESC">Oldest First</option>
								<option value="ASC">Newest First</option>
							</select>
							<input type="hidden" name="widget" class="widget_name" value="results-list">
							<input type="hidden" name="post_type" class="post_type" value="question">
						</form>
					</header>
					<section class="content-body">
						
						<article class="content-container question">
							
							<section class="content-body clearfix">
								
								<ul class="member_details span2">
									
									<li class="member_avatar span6">
										<a href="#">
											<img src="http://localhost:100/wp-content/themes/Eris/assets/img/avatar.jpg?" />
											<span class="badge">Subscriber</span>
										</a>
									</li>
									
									<li class="member_profile span6">
										<ul>
											<li class="member_screen-name"><a href="">TimothySteele</a></li>
											<li class="member_location">Chicago, IL</li>
										</ul>
									</li>
									
								</ul> <!-- END MEMBER DETAILS -->
								

								<div class="span10">
									<div class="content-details clearfix"> <span class="content-category"><a title="Appliances" href="">Appliances</a></span>
										<time datetime="2012-09-14" pubdate="" class="content-date">September 14, 2012 <span class="time-stamp">7:37 pm</span></time>
									</div>
									<h1 class="content-headline"><a href="http://uxint-sears/community/questions/dadasdasd/">dadasdasd</a></h1>
									<p class="content-excerpt"> </p>
									<ul>
										<li class="content-comments">0 answers | 0 replies | 0 comments</li>
									</ul>
									<form action="" method="post" id="comment-actions-1916" class="actions clearfix">
										<button class="flag" id="flag-comment-1916" title="Flag this question" value="flag" name="button1" type="button">flag</button>
										<ul class="addthis dropmenu">
											<li> <span class="sharebutton">Share</span>
												<ul class="sharemenulinks">
													<li><a addthis:url="http://uxint-sears/community/questions/dadasdasd/" class="addthis_button_facebook at300b" title="Facebook" href="#"><span class="at16nc at300bs at15nc at15t_facebook at16t_facebook"><span class="at_a11y">Share on facebook</span></span>Facebook</a></li>
													<li><a addthis:url="http://uxint-sears/community/questions/dadasdasd/" class="addthis_button_twitter at300b" title="Tweet This" href="#"><span class="at16nc at300bs at15nc at15t_twitter at16t_twitter"><span class="at_a11y">Share on twitter</span></span>Twitter</a></li>
													<li><a addthis:title="ShopYourWay this" addthis:url="http://www.sears.com" class="addthis_button_shopyourway" href="http://www.addthis.com/bookmark.php?v=250&amp;winname=addthis&amp;pub=wp-5058a65b62695333&amp;source=tbx-250,wpp-264&amp;lng=en-US&amp;s=shopyourway&amp;url=http%3A%2F%2Fwww.sears.com&amp;title=ShopYourWay%20this&amp;ate=AT-wp-5058a65b62695333/-/-/5058a325a5b300d3/2&amp;frommenu=1&amp;uid=5058a325c0bc0660&amp;pre=http%3A%2F%2Fuxint-sears%2Fcommunity%2Fquestions%2Fthis-is-no-good%2F&amp;tt=0&amp;captcha_provider=nucaptcha" target="_blank" title="Shopyourway"><img src="http://uxint-sears/wp-content/themes/Eris/assets/img/shopyourway_small.jpg">ShopYourWay</a></li>
													<li><a addthis:url="http://uxint-sears/community/questions/dadasdasd/" class="addthis_button_email at300b" title="Email" href="#"><span class="at16nc at300bs at15nc at15t_email at16t_email"><span class="at_a11y">Share on email</span></span>Email</a></li>
												</ul>
											</li>
										</ul>
									</form>
									<div class="hide" id="flagForm-1916">
										<form shc:gizmo="transFormer" method="post" id="commentForm-1916" class="flag-form">
											<textarea shc:gizmo:form="{required: true}" aria-required="true" name="comment" cols="19" rows="5" class="flagField"></textarea>
											<input type="submit" value="Flag" class="kmart_button">
											<input type="reset" onclick="jQuery('.tooltip').hide();" value="Cancel" class="kmart_button azure">
											<input type="hidden" value="" name="comment_post_ID">
											<input type="hidden" value="1916" name="comment_parent">
											<input type="hidden" value="flag" name="comment_type">
										</form>
									</div>
								</div>
							</section>
						</article>
					</section>
					<section class="pagination"> </section>
				</article>
			</div>
			
			
			
			
		</section> <!-- END SPAN8 -->
	
		<section class="span4">
			
			<!-- NEW RECENT ACTIVITY -->
			<article class="widget content-container span12 summary-list">
				
				<header class="content-header">
        	<h3>Related Stories</h3>
        </header>
        
        <section class="content-body clearfix">
        	
        	<ul>
        		
        		<li class="summary_list-item summary-list_question clearfix">
        			<ul>
        				<li class="summary_type-date">
        					<span class="summary_type">Questions</span>
        					<span class="summary_date">July 5, 2012</span>
        				</li>
        				
        				<li class="summary_title">
        					<a href="#">
        						Question headline goes here and here. This is a really long title for a post with many characters and needs to be truncated&hellip;
        					</a>
        				</li>
        				
        				<li class="summary_comments">
        					<span class="summary_comment-count">4 answers | 2 community team answers</span>
        				</li>
        				
        				<li class="summary_see-more">
        					<a href="#">See More</a>
        				</li>
        			</ul>
        		</li>
        		
        		<li class="summary_list-item summary-list_post clearfix">
        			<ul>
        				<li class="summary_type-date">
        					<span class="summary_type">Blog Posts</span>
        					<span class="summary_date">July 5, 2012</span>
        				</li>
        				
        				<li class="summary_title">
        					<a href="#">
        						Question headline goes here and here. This is a really long title for a post with many characters and needs to be truncated&hellip;
        					</a>
        				</li>
        				
        				<li class="summary_comments">
        					<span class="summary_author">By: Dick Headless</span>
        					<span class="summary_comment-count">2 community team answers</span>
        				</li>
        				
        				<li class="summary_see-more">
        					<a href="#">See More</a>
        				</li>
        			</ul>
        		</li>
        		
        		<li class="summary_list-item summary-list_guide clearfix">
        			<ul>
        				<li class="summary_type-date">
        					<span class="summary_type">Buying Guides</span>
        					<span class="summary_date">July 5, 2012</span>
        				</li>
        				
        				<li class="summary_title">
        					<a href="#">
        						Question headline goes here and here. This is a really long title for a post with many characters and needs to be truncated&hellip;
        					</a>
        				</li>
        				
        				<li class="summary_comments">
        					<span class="summary_author">By: Dick Headless</span>
        					<span class="summary_comment-count">2 community team answers</span>
        				</li>
        				
        				<li class="summary_see-more">
        					<a href="#">See More</a>
        				</li>
        			</ul>
        		</li>
        		
        		
        	</ul>
        	
        </section>
				
			</article>
			
			
			
			<article class="widget content-container span12 summary-list">
				<article class="content-container questionlist columns">
	
	        <header class="content-header">
	        	<h3>Related Stories</h3>
	        </header>
	
	        <section class="content-body clearfix">
	    			<section class="content-body content-list clearfix">
	        		<article class="content-item span12">
	    					<div class="post-data span12">
	              	<section class="clearfix">
	                	<p class="post-category left">Question</p>
	                  <p class="post-date right">September 21, 2012</p>
	                </section>
	                <section class="clearfix">
	            			<p class="post-title"><a href="http://uxint-sears/community/questions/test-test/">Test test</a></p>
	                  <p class="comments-count">no answers</p>
	                                        
	                </section>
	    					</div>
							</article>
							<article class="content-item span12">
	    					<div class="post-data span12">
	              	<section class="clearfix">
	                	<p class="post-category left">Question</p>
	                  <p class="post-date right"></p>
	                </section>
	                <section class="clearfix">
		            		<p class="post-title"><a href="http://uxint-sears/community/questions/does-sears-sell-rain-coats/">Does sears sell rain coats?</a></p>
		                <p class="comments-count">5 answers</p>
	            		</section>
	    					</div>
							</article>
				    </section>
	    		</section>
				</article>
			</article>
				
			<!-- END NEW RECENT ACTIVITY -->
			
			<!-- ORIGINAL RECENT ACTIVITY -->
			
			<section class="span12">
    		<section class="content-container recent-activity">

        	<hgroup class="content-header">
        		<h3>Recent Activities</h3>
        	</hgroup>

        	<ol class="content-body result clearfix">

            <li class="clearfix">
                
							<div class="crest span4">
    						<a href="http://uxint-sears/community/author/Jasonthegreat/"><img alt="" class="photo" src="http://uxint-sears/wp-content/themes/Eris/assets/img/avatar.jpg?"></a>
    
    						<h4><a href="http://uxint-sears/community/author/Jasonthegreat/">Jasonthegreat</a></h4>
    						<address>Carol Stream, IL</address>
    					</div>
    					
             	<div class="span8">
              	<h3>
              		<span>Asked this: </span>
									<time datetime="2012-09-19" pubdate="" class="content-date">Sep 19 5:18pm</time>
                  <a class="category" href="http://uxint-sears/community/category/customer-care/maintenance-parts/">Repairs, Maintenance &amp; Parts</a>
                  <a href="http://uxint-sears/community/questions/where-can-i-find-manuals-for-tv/">where can i find manuals for tv?</a>
                </h3>
              </div>
            </li>

            <li class="clearfix">
                
							<div class="crest span4 labeled">
    						<a href="http://uxint-sears/community/author/hamidxjs/"><img alt="" class="photo" src="http://uxint-sears/community/wp-content/blogs.dir/2/files/userphoto/4.jpg?1346446016"></a>
    
    						<h4><a href="http://uxint-sears/community/author/hamidxjs/">Expert</a></h4>

    						<h5><a href="http://uxint-sears/community/author/hamidxjs/">hamidxjs</a></h5>
    						<address>Villa Park, ILLINOIS</address>
    					</div>
              <div class="span8">
              	<h3>
                	<span>Asked this: </span>
                        
									<time datetime="2012-09-19" pubdate="" class="content-date">Sep 19 5:13pm</time>
                  <a class="category" href="http://uxint-sears/community/category/general/">General</a>
                  <a href="http://uxint-sears/community/questions/frank-is-great-right/">frank is great right?</a>
                </h3>
              </div>
            </li>
        	</ol>
    		</section>
			</section>
			
			<!-- END ORIGINAL RECENT ACTIVITY -->
			
			
			
			<section class="span12 featured-members content-container">
				
				<hgroup class="content-header">
					<h3>Meet the Community Team</h3>
		      <h4>Whatever your question or issue, we're here to help</h4>
				</hgroup>
				
				<section class="content-body clearfix">
					
					<ul class="member-list">
						
						<li class="span4"> <!-- BEGIN MEMBER LISTING -->
							<div class="member-wrapper clearfix">
								<ul class="member_details">
									
									<li class="member_avatar span6">
										<a href="#">
											<img src="http://localhost:100/wp-content/themes/Eris/assets/img/avatar.jpg?" />
											<span class="badge">Subscriber</span>
										</a>
									</li>
									
									<li class="member_profile span6">
										<ul>
											<li class="member_screen-name"><a href="">TimothySteele</a></li>
											<li class="member_location">Chicago, IL</li>
										</ul>
									</li>
									
									<li class="member_posts span12">
										<ul>
											<li class="member_last-posted">Last Posted on September 17, 2012</li>
										</ul>
									</li>
									
								</ul> <!-- END MEMBER DETAILS -->
							</div> <!-- END MEMBER WRAPPER -->
						</li> <!-- END MEMBER LISTING -->
						
						<li class="span4"> <!-- BEGIN MEMBER LISTING -->
							<div class="member-wrapper clearfix">
								<ul class="member_details">
									
									<li class="member_avatar span6">
										<a href="#">
											<img src="http://localhost:100/wp-content/themes/Eris/assets/img/avatar.jpg?" />
											<span class="badge">Subscriber</span>
										</a>
									</li>
									
									<li class="member_profile span6">
										<ul>
											<li class="member_screen-name"><a href="">TimothySteele</a></li>
											<li class="member_location">Chicago, IL</li>
										</ul>
									</li>
									
									<li class="member_posts span12">
										<ul>
											<li class="member_last-posted">Last Posted on September 17, 2012</li>
									</li>
									
								</ul> <!-- END MEMBER DETAILS -->
							</div> <!-- END MEMBER WRAPPER -->
						</li> <!-- END MEMBER LISTING -->
						
						<li class="span4"> <!-- BEGIN MEMBER LISTING -->
							<div class="member-wrapper clearfix">
								<ul class="member_details">
									
									<li class="member_avatar span6">
										<a href="#">
											<img src="http://localhost:100/wp-content/themes/Eris/assets/img/avatar.jpg?" />
											<span class="badge">Subscriber</span>
										</a>
									</li>
									
									<li class="member_profile span6">
										<ul>
											<li class="member_screen-name"><a href="">TimothySteele</a></li>
											<li class="member_location">Chicago, IL</li>
										</ul>
									</li>
									
									<li class="member_posts span12">
										<ul>
											<li class="member_last-posted">Last Posted on September 17, 2012</li>
										</ul>
									</li>
									
								</ul> <!-- END MEMBER DETAILS -->
							</div> <!-- END MEMBER WRAPPER -->
						</li> <!-- END MEMBER LISTING -->
						
					</ul>
					
				</section>
				
			</section>
			
		</section>
		
	</section>
	
<?php
get_template_part('parts/footer');

?>