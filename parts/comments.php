<div class="allComments">
<?php
    $comments = get_comments(array('post_id' => $post->ID));

    if(isset($comments) && !empty($comments)) {
        foreach($comments as $comment) {
//            echo '<pre>';
//            var_dump($comment);
?>
            <div class="comment">
                <div class="rightSide">
                    <img src="" class="userImage" alt="" />
                    <p class="name"><?php echo $comment->comment_author; ?></p>
                    <!--<p class="location"></p> ask Dan if he is incorporating this -->
                </div>
                <div class="leftSide">
                    <p class="date"><?php echo date('M d, Y h:i A'); ?></p>
                    <p class="content"><?php echo $comment->comment_content; ?></p>
                    <div class="actions">
                        <div class="">
                            <p>
                                <a class="reply">Reply</a>
                                <div class="actions">
                                    <div class="action">
                                        <span class="upvote">Helpful</span> (0)
                                    </div>
                                    <div class="action">
                                        <span class="downvote"></span> (0)
                                    </div>
                                    <div class="action">
                                        <span class="flag"></span> (0)
                                    </div>
                                </div>
                            </p>
                            <!-- HIDE THIS SOMEHOW; <?php comments_template('/parts/commentForm.php'); ?> -->
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