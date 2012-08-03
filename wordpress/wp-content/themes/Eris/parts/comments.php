<div class="allComments" xmlns="http://www.w3.org/1999/html">
<?php
    $comments = get_comments(array('post_id' => $post->ID));

    if(isset($comments) && !empty($comments)) {
        foreach($comments as $comment) {
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
            <!-- Begin Children -->
            <div class="children">
                <div class="rightSide">
                    <img src="" class="userImage" alt="" />
                    <p class="name"><?php echo comment_author(); ?> COMM AUTHOR</p>
                    <!--<p class="location"></p> ask Dan if he is incorporating this -->
                </div>
                <div class="leftSide">
                    <p class="date"><?php echo date('M d, Y h:i A'); ?></p>
                    <p class="content"><?php echo $comment->comment_content; ?></p>
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
<?php
        }
    } else {
        echo 'none';
    }
?>
</div>