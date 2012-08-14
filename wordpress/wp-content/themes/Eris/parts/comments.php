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
            $badge_options = array(
                "user_id" => $comment->user_id
            );
            if ( user_can( $comment->user_id, "administrator") ) {
                $container_class = ' expert';
                $badge_options["titling"] = true;
            }
?>
        <li class="comment clearfix<?php echo $container_class; ?>">
            <?php get_partial( 'parts/badge', $badge_options ); ?>
            <div class="span10">
                <time class="content-date" datetime="<?php echo date( "Y-m-d", $comment_date ); ?>" pubdate="pubdate"><?php echo date( "F n, Y g:ia", $comment_date ); ?></time>
                <article>
                    <?php echo $comment->comment_content; ?>
                </article>
                <form class="actions clearfix" id="comment-<?php echo $comment->comment_ID; ?>" method="post" action="">
                    <div class="reply">
                        <a href="#">Reply</a>
                    </div>
                    <button type="button" name="button1" value="flag" title="Flag this <?php echo $comment_type?>" id="flag-comment-<?php echo $comment->comment_ID; ?>" class="flag">flag</button>
                    <label class="metainfo" for="downvote-comment-<?php echo $comment->comment_ID; ?>">(0)</label>
                    <button type="button" name="button1" value="down vote" title="Down vote this <?php echo $comment_type?>" id="downvote-comment-<?php echo $comment->comment_ID; ?>" class="downvote">down vote</button>
                    <label class="metainfo" for="upvote-comment-<?php echo $comment->comment_ID; ?>">(0)</label>
                    <button type="button" name="button1" value="helpful" title="Up vote this <?php echo $comment_type?>" id="upvote-comment-<?php echo $comment->comment_ID; ?>" class="upvote">helpful</button>
                </form>
            </div>
            <ol class="children">
                 <li class="comment clearfix<?php echo $container_class; ?>">
                     <?php get_partial( 'parts/badge', $badge_options ); ?>
                     <div class="span10">
                         <time class="content-date" datetime="<?php echo date( "Y-m-d", $comment_date ); ?>" pubdate="pubdate"><?php echo date( "F n, Y g:ia", $comment_date ); ?></time>
                         <article>
                             <?php echo $comment->comment_content; ?>
                         </article>
                         <form class="actions clearfix" action="" id="comment-<?php echo $comment->comment_ID; ?>">
                             <div class="reply">
                                 <a href="#">Reply</a>
                             </div>
                             <button type="button" name="button1" value="flag" title="Flag this <?php echo $comment_type?>" id="flag-comment-<?php echo $comment->comment_ID; ?>" class="flag">flag</button>
                             <label class="metainfo" for="downvote-comment-<?php echo $comment->comment_ID; ?>">(0)</label>
                             <button type="button" name="button1" value="down vote" title="Down vote this <?php echo $comment_type?>" id="downvote-comment-<?php echo $comment->comment_ID; ?>" class="downvote">down vote</button>
                             <label class="metainfo" for="upvote-comment-<?php echo $comment->comment_ID; ?>">(0)</label>
                             <button type="button" name="button1" value="helpful" title="Up vote this <?php echo $comment_type?>" id="upvote-comment-<?php echo $comment->comment_ID; ?>" class="upvote">helpful</button>
                         </form>
                     </div>
                 </li>
            </ol>
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
