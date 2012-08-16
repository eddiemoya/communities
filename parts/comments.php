<?php
    $comments = get_comments(array('post_id' => $post->ID));
    
    $comment_type = get_post_type( $post->ID ) == 'question' ? 'answer' : 'comment';
    
    if ( isset( $comments ) && !empty( $comments ) ) {

?>

<header class="section-header comments-header clearfix">
    <h3><?php echo ucfirst( $comment_type ); ?>s</h3>
    <h4><?php comments_number( '', '1 ' . $comment_type, '% ' . $comment_type . 's' ); ?></h4>
</header>
<ol id="allComments">
<?php
        foreach($comments as $comment) {
            $comment_date = strtotime( $comment->comment_date );
            $container_class = '';
            
            $crest_options = array(
                "user_id"   => $comment->user_id
            );
            
            if ( user_can( $comment->user_id, "administrator") ) {
                $container_class = ' expert';
                $crest_options["titling"] = true;
            }
            
            $comment_actions = array(
                "id"        => $comment->comment_ID,
                "type"      => $comment_type,
                "options"   => array( "reply", "flag", "upvote", "downvote" )
            );
?>
        <li class="comment clearfix<?php echo $container_class; ?>">
            
            <?php get_partial( 'parts/crest', $crest_options ); ?>
            
            <div class="span10">
                <time class="content-date" datetime="<?php echo date( "Y-m-d", $comment_date ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $comment_date ); ?></time>
                <article>
                    <?php echo $comment->comment_content; ?>
                </article>
                <?php get_partial( 'parts/forms/post-n-comment-actions', $comment_actions ); ?>
            </div>
            <!-- <ol class="children">
                 <li class="comment clearfix<?php echo $container_class; ?>">
                     <?php get_partial( 'parts/crest', $crest_options ); ?>
                     <div class="span10">
                         <time class="content-date" datetime="<?php echo date( "Y-m-d", $comment_date ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $comment_date ); ?></time>
                         <article>
                             <?php echo $comment->comment_content; ?>
                         </article>
                         <?php get_partial( 'parts/forms/post-n-comment-actions', $comment_actions ); ?>
                     </div>
                 </li>
            </ol> -->
        </li>
<?php
        }
?>
</ol>
<?php
    }
    # No Comments.
    else {
?>
<section>
    No <?php echo $comment_type; ?>s yet.
</section>
<?php
    }
