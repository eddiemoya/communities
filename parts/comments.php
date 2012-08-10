<?php
    $comments = get_comments(array('post_id' => $post->ID));

    if ( isset( $comments ) && !empty( $comments ) ) {
        $comment_type = get_post_type( $post->ID ) == 'question' ? 'answer' : 'comment';
?>
<header class="section-header comments-header clearfix">
    <h3><?php echo ucfirst( $comment_type ); ?>s</h3>
    <h4><?php comments_number( '', '1 ' . $comment_type, '% ' . $comment_type . 's' ); ?></h4>
</header>
<ol id="allComments">
<?php
        foreach($comments as $comment) {
            $user = get_userdata( $comment->user_id );
            $container_class = '';
            $badge_class = '';
            $badge_titling = '
            <h4><a href="#">' . $comment->comment_author . '</a></h4>
';
            if ( user_can( $comment->user_id, "administrator") ) {
                $container_class = ' expert';
                $badge_class = ' labeled';
                $badge_titling = '
                <h4><a href="#">' . $user->roles[0] . '</a></h4>
                <div class="badge-tail">&nbsp;</div>
                <h5><a href="#">' . $comment->comment_author . '</a></h5>
';
            }
?>
        <li class="comment clearfix<?php echo $container_class; ?>">
            <div class="span2 badge<?php echo $badge_class; ?>">
                <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
                <?php echo $badge_titling; ?>
                <address>Chicago, IL</address>
            </div>
            <div class="span10">
                <time class="content-date" datetime="<?php echo date( "Y-m-d" ); ?>" pubdate="pubdate"><?php echo date( "F n, Y g:ia" ); ?></time>
                <article>
                    <?php echo $comment->comment_content; ?>
                </article>
                <form class="actions clearfix" action="" id="comment-<?php echo $comment->comment_ID; ?>">
                    <div class="reply">
                        <a href="#">Reply</a>
                    </div>
                    <input type="button" name="button1" value="Flag" id="flag-<?php echo $comment->comment_ID; ?>" class="flag" />
                    <label class="metainfo" for="downvote-<?php echo $comment->comment_ID; ?>">(0)</label>
                    <input type="button" name="button1" value="Down Vote" id="downvote-<?php echo $comment->comment_ID; ?>" class="downvote" />
                    <label class="metainfo" for="upvote-<?php echo $comment->comment_ID; ?>">(0)</label>
                    <input type="button" name="button1" value="Up Vote" id="upvote-<?php echo $comment->comment_ID; ?>" class="upvote" />
                    <span class="text">Helpful</span>
                    
                    <!-- <div class="button">
                                            <a href="#" class="action flag">Flag</a>
                                        </div>
                                        <label class="metainfo" for="Down Votes">(0)</label>
                                        <div class="button">
                                            <a href="#" class="action downvote">Down vote</a>
                                        </div>
                                        <label class="metainfo" for="Up Votes">(0)</label>
                                        <div class="button helpful">
                                            <span class="text">Helpful</span>
                                            <a href="#" class="action upvote">Up vote</a>
                                        </div> -->
                </form>
                
            </div>
            <!-- Begin Children 
            <ol class="children">
                 <li class="comment clearfix<?php echo $container_class; ?>">
                     <div class="span2 badge<?php echo $badge_class; ?>">
                         <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
                         <?php echo $badge_titling; ?>
                         <address>Chicago, IL</address>
                     </div>
                     <div class="span10">
                         <time class="content-date" datetime="<?php echo date( "Y-m-d" ); ?>" pubdate="pubdate"><?php echo date( "F n, Y g:ia" ); ?></time>
                         <article>
                             <?php echo $comment->comment_content; ?>
                         </article>
                         <div class="actions clearfix">
                             <div class="reply">
                                 <a href="#">Reply</a>
                             </div>
                             <div class="button">
                                 <a href="#" class="action flag">Flag</a>
                             </div>
                             <span class="metainfo">(0)</span>
                             <div class="button">
                                 <a href="#" class="action downvote">Down vote</a>
                             </div>
                             <span class="metainfo">(0)</span>
                             <div class="button helpful">
                                 <span class="text">Helpful</span>
                                 <a href="#" class="action upvote">Up vote</a>
                             </div>
                         </div>
                     </div>
                 </li>
            </ol>-->
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

?>
