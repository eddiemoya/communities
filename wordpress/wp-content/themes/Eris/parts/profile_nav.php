<?php

    $concat = (strpos($author_url, '?') !== false) ? '&post-type=' : '?post-type=';
    
    if ( user_can( $profile_user->ID, "show_badge" ) ) {
        $badge_page = get_page_by_title( 'Types of Badges' );
    }
    
    // Get the stats
    $profile_user->answer_count  = get_comments( array( 'user_id' => $profile_user->ID, 'status' => 'approved', 'count' => true, 'type' => 'answer' ) );
    $profile_user->comment_count = get_comments( array( 'user_id' => $profile_user->ID, 'status' => 'approved', 'count' => true, 'type' => 'comment' ) );
    $profile_user->post_count    = return_post_count( $profile_user->ID );
    $profile_user->review_count = $user_activities->num_reviews;

    $a_tabs = array(
        "Community Activity" => $author_url . $concat . 'recent',
        "About Me" => $author_url . $concat . 'aboutme'
    );
  
    $a_navigation = array(
        "recent" => array(
            "name" => "Recent Activity",
            "url" => $author_url . $concat . 'recent'
        ),
        "question" => array(
            "name" => "Questions",
            "url" => $author_url . $concat . 'question'
        ),
        
        "" => array(
            "name" => "Comments",
            "url" => $author_url . $concat . ''
        ),
         "comment" => array(
            "name" => "Comments",
            "url" => $author_url . $concat . 'comment'
        ),
        
        "answer" => array(
            "name" => "Answers",
            "url" => $author_url . $concat . 'answer'
        ),

        "review" => array(
            "name" => "Reviews",
            "url" => $author_url . $concat . 'review'
        ),
       
        "follow" => array(
            "name" => "Follows",
            "url" => $author_url . $concat . 'follow'
        ),
        "upvote" => array(
            "name" => "Helpful Votes",
            "url" => $author_url . $concat . 'upvote'
        )      ,
        "post" => array(
            "name" => "Posts",
            "url" => $author_url . $concat . 'post'
        ),
        "guides" => array(
            "name" => "Buying Guides",
            "url" => $author_url . $concat . 'guides'
        )
    );
  
?>

<?php
    # if a user is looking at another's profile, show the crest
    if( $profile_type != 'myprofile' && $_REQUEST['post-type'] != 'aboutme' ):
?>
<div class="profile-summary clearfix">
<?php
    // $crest_options = array(
        // "user_id"   => $profile_user->ID, 
        // //"width"     => 'span2'
    // );
    // if ( $profile_user->roles[0] == 'communityexpert' ) {
        // $crest_options["titling"] = true;
    // }
//     
    // get_partial( 'parts/crest', $crest_options );
    
		$experts_settings = array(
				"user_id" => $profile_user->ID, 
				//"width" => 'span4', 
				//"titling" => true, 
				//"show_name" => false, 
				//"show_address" => false
			);
    	
			if ( ( $show_specializations === 'on' ) && ( !empty( $profile_user->categories ) ) ) {
				$experts_settings['specializations'] = $profile_user->categories;
			}
			
			if ( $profile_user->most_recent_post_date ) {
				$experts_settings['last_posted'] = $profile_user->most_recent_post_date;
			}
						
			$profile_user->answer_count 	= (empty($profile_user->answer_count))? 0:$profile_user->answer_count;
			$profile_user->post_count 		= (empty($profile_user->post_count))? 0:$profile_user->post_count;
			$profile_user->comment_count 	= (empty($profile_user->comment_count ))? 0:$profile_user->comment_count ;
			
			$experts_settings['stats'] = array(
				"answers"		=> $profile_user->answer_count . ' ' . _n( 'answer', 'answers', $profile_user->answer_count ),
				"posts"			=> $profile_user->post_count . ' ' . _n( 'post', 'posts', $profile_user->post_count ),
				"comments"	=> $profile_user->comment_count . ' ' . _n( 'comment', 'comments', $profile_user->comment_count ),
				"reviews"	=> $profile_user->review_count . ' ' . _n('review', 'reviews', $profile_user->review_count)
			);
			
    	get_partial( 'parts/crest', $experts_settings ); 
?>
    
    <?php 
    if(get_the_author_meta('user_description',$profile_user->ID)) : ?>
        <section class="member_bio">
            <h3>About <?php get_screenname($profile_user->ID); ?></h3>
            <p>
                <?php the_author_meta('user_description',$profile_user->ID); ?>
            </p>
        </section>
    <?php endif; ?>
		<?php if ( user_can( $profile_user->ID, "show_badge" ) ): ?>
        <div class="link-emulator badge-descriptor" shc:gizmo:options="{moodle: {width:540, target:'badgesInfo', method:'local'}}" shc:gizmo="moodle">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/more-info.png" />
        </div>
        <div id="badgesInfo" class="hide">
            <h2><?php echo $badge_page->post_title; ?></h2>
            <?php echo $badge_page->post_content; ?>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php
    # if a user is looking at themselves, show the tabs
    if( $profile_type == 'myprofile' ):
?>
<nav class="clearfix">
  <ul class="tabs clearfix">
    <?php
    foreach ( $a_tabs as $lone_tab => $tab_url ) {
      if ( $lone_tab == $current_tab ) {
        echo '<li class="active">' . $lone_tab . '</li>';
      }
      else {
        echo '<li><a href="' . $tab_url . '">' . $lone_tab . '</a></li>';
      }
    }
    ?>
  </ul>
</nav>
<?php endif; ?>

<?php
    # show the sub tab bar only if a user is not on the About Me page and if they actually have any activity
    if( $_REQUEST['post-type'] != 'aboutme' && !empty( $available_tabs ) ): 
?>
<nav class="bar clearfix">
    <ul class="clearfix">
<?php
    if($current_tab == 'Community Activity') {

        foreach ($a_navigation as $lone_nav_id => $lone_nav) {

            if ( in_array($lone_nav_id, $available_tabs) ) {

                if ( $lone_nav_id == $current_nav ) {

                    echo '<li class="active">' . $lone_nav["name"] . '</li>';
                }

                else {

                    echo '<li><a href="' . $lone_nav["url"] . '">' . $lone_nav["name"] . '</a></li>';

                }
            }
        }
    }
?>
    </ul>
</nav>
<?php endif; ?>

<?php
    # catch the circumstance where the user has not yet done anything on the site.
    if( $_REQUEST['post-type'] != 'aboutme' && empty( $available_tabs ) && $profile_type == 'myprofile' ):
?>
    <section class="no-activity">
        Get out there and show us what you're all about!
    </section>
<?php endif; ?>