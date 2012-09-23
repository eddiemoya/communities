<article class="content-container user clearfix">
<?php 
$experts_settings = array(
		"user_id" => $user->ID, 
		//"width" => 'span4', 
		//"titling" => true, 
		//"show_name" => false, 
		//"show_address" => false
	);

	$user->answer_count  = get_comments( array( 'user_id' => $user->ID, 'status' => 'approved', 'count' => true, 'type' => 'answer' ) );
    $user->comment_count = get_comments( array( 'user_id' => $user->ID, 'status' => 'approved', 'count' => true, 'type' => 'comment' ) );
    $user->post_count    = return_post_count( $user->ID );

    $user->meta = get_user_meta($user->ID);
    $user->categories = get_terms('category', array('include' => $user->meta['um-taxonomy-category']));
	
	// if ( ( !empty( $user->categories ) ) ) {
	// 	$experts_settings['specializations'] = $user->categories;
	// }
	




    $last_post_date = return_last_post_date( $user_object->ID );

    if ( $last_post_date == 0) {
        $user->most_recent_post_date = false;
        $user->pubdate = false;
    }
    else {
        $user->most_recent_post_date = date( "M d, Y", $last_post_date );
        $user->pubdate = date( "Y-m-d", $last_post_date );
    }   

	$experts_settings['last_posted'] = $user->most_recent_post_date;




	

		$experts_settings['stats'] = array(
			"answers"		=> $user->answer_count . ' ' . _n( 'answer', 'answers', $user->answer_count ),
			"posts"			=> $user->post_count . ' ' . _n( 'post', 'posts', $user->post_count ),
			"comments"	=> $user->comment_count . ' ' . _n( 'comment', 'comments', $user->comment_count )
		);
	

	//echo "<pre>";print_r($experts_settings);echo "</pre>";
	
get_partial( 'parts/crest', $experts_settings ); 
//get_partial( 'parts/crest', array( "user_id" => $user->ID, "width" => 'span4', "titling" => true, "show_name" => false, "show_address" 

?></article>