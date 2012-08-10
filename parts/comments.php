<div class="allComments">
<?php
    $comments = get_comments(array('post_id' => $post->ID));

    if(isset($comments) && !empty($comments)) {
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
            <div class="comment<?php echo $container_class; ?>">
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
                    <div class="actions">
                        <a class="reply">Reply</a>
                        <div class="action last">
                            <div class="button">
                                <span class="actionIcon flag"></span>
                            </div>
                        </div>
                        <div class="action further">
                            <div class="button">
                                <span class="actionIcon downvote"></span>
                            </div>
                            <p class="count">(0)</p>
                        </div>
                        <div class="action">
                            <div class="button">
                                <span class="text">Helpful</span>
                                <span class="actionIcon upvote"></span>
                            </div>
                            <p class="count">(0)</p>
                        </div>
                        <!-- HIDE THIS SOMEHOW; <?php //comments_template('/parts/commentForm.php'); ?> -->
                    </div>
                </div>
                <!-- Begin Children -->
                <div class="children">
                     <div class="comment<?php echo $container_class; ?>">
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
                             <div class="actions">
                                 <a class="reply">Reply</a>
                                 <div class="action last">
                                     <div class="button">
                                         <span class="actionIcon flag"></span>
                                     </div>
                                 </div>
                                 <div class="action further">
                                     <div class="button">
                                         <span class="actionIcon downvote"></span>
                                     </div>
                                     <p class="count">(0)</p>
                                 </div>
                                 <div class="action">
                                     <div class="button">
                                         <span class="text">Helpful</span>
                                         <span class="actionIcon upvote"></span>
                                     </div>
                                     <p class="count">(0)</p>
                                 </div>
                                 <!-- HIDE THIS SOMEHOW; <?php //comments_template('/parts/commentForm.php'); ?> -->
                             </div>
                         </div>
                     </div>
                </div>
            </div>

<?php
        }
    } else {
        echo 'none';
    }
?>
</div>
