<?php
    $buttons = array();
    if ( isset( $options ) && ( !empty( $options ) ) ) {
        if ( in_array( "reply", $options ) ) {
            $buttons[] = '<div class="reply"><a href="#">Reply</a></div>';
        }
        if ( in_array( "follow", $options ) ) {
            $buttons[] = '<button type="button" name="button1" value="follow" title="Follow this ' . $type . '" id="follow-question-' . $id . '" class="follow">follow</button>';
        }
        if ( in_array( "share", $options ) ) {
            $buttons[] = return_partial( 'parts/share' );
        }
        if ( in_array( "flag", $options ) ) {
            $buttons[] = '<button type="button" name="button1" value="flag" title="Flag this ' . $type . '" id="flag-comment-' . $id . '" class="flag">flag</button>';
        }
        if ( in_array( "downvote", $options ) ) {
            $buttons[] = '<label class="metainfo" for="downvote-comment-' . $id . '">(0)</label><button type="button" name="button1" value="down vote" title="Down vote this ' . $type . '" id="downvote-comment-' . $id . '" class="downvote">down vote</button>';
        }
        if ( in_array( "upvote", $options ) ) {
            $buttons[] = '<label class="metainfo" for="upvote-comment-' . $id . '">(0)</label><button type="button" name="button1" value="helpful" title="Up vote this ' . $type . '" id="upvote-comment-' . $id . '" class="upvote">helpful</button>';
        }
    }
    
    if ( !empty( $buttons ) ) {
?>

<form class="actions clearfix" id="comment-<?php echo $id; ?>" method="post" action="">

    <?php echo implode( $buttons, "\r    " ) ?>

</form>

<?php
    }
?>