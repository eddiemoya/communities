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
    "posts" => array(
      "name" => "Posts",
      "url" => $url_no_qs . '?post-type=posts'
    ),
    "guides" => array(
      "name" => "Buying Guides",
      "url" => $url_no_qs . '?post-type=guides'
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
    "votes" => array(
      "name" => "Helpful Votes",
      "url" => $url_no_qs . '?post-type=votes'
    )
  );
  
?>

<?php if($profile_type == 'myprofile'): ?>

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

<?php endif;?>


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



