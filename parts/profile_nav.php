<?php

  
  $a_tabs = array(
    "Community Activity" => $url_no_qs . '?post-type=recent',
    "About Me" => $url_no_qs . '?post-type=aboutme',
  );
  
    $a_navigation = array(
        "recent" => array(
            "name" => "Recent Activity",
            "url" => $url_no_qs . '?post-type=recent'
        ),
        "question" => array(
            "name" => "Questions",
            "url" => $url_no_qs . '?post-type=question'
        ),
        "answer" => array(
            "name" => "Answers",
            "url" => $url_no_qs . '?post-type=answer'
        ),
        "comment" => array(
            "name" => "Comments",
            "url" => $url_no_qs . '?post-type=comment'
        ),
        "follow" => array(
            "name" => "Follows",
            "url" => $url_no_qs . '?post-type=follow'
        ),
        "upvote" => array(
            "name" => "Helpful Votes",
            "url" => $url_no_qs . '?post-type=upvote'
        )      ,
        "post" => array(
            "name" => "Posts",
            "url" => $url_no_qs . '?post-type=post'
        ),
        "guides" => array(
            "name" => "Buying Guides",
            "url" => $url_no_qs . '?post-type=guides'
        )
    );
  
?>

<?php if( $profile_type == 'myprofile' ): ?>

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

<?php if( $_REQUEST['post-type'] != 'aboutme' && !empty( $available_tabs ) ): ?>
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

<?php if( $_REQUEST['post-type'] != 'aboutme' && empty( $available_tabs ) ): ?>
    <section class="no-activity">
        Get out there and show us what you're all about!
    </section>
<?php endif; ?>