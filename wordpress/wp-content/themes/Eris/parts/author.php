<?php
        $last_post_date = return_last_post_date( $data->ID );

        //echo "<pre>";print_r($data);echo "</pre>";
        $answer_count   = get_comments( array( 'user_id' => $data->ID, 'status' => 'approved', 'count' => true, 'type' => 'answer' ) );
        $comment_count  = get_comments( array( 'user_id' => $data->ID, 'status' => 'approved', 'count' => true, 'type' => 'comment' ) );
        $post_count     = count_user_posts( $data->ID );
        
?>
 <article class="content-container user">
    <section class="content-body clearfix">
        <!-- <li class="member lone-result clearfix"> -->
            <?php get_partial( 'parts/crest', array( "user_id" => $data->ID, "width" => 'span2', "show_name" => false, "show_address" => false ) ); ?>
            <div class="span10 info">
                <h4><?php get_screenname_link( $data->ID ); ?></h4>
                <address><?php echo return_address( $data->ID ); ?></address>
                <article class="content-comments">
                    <?php if ( $last_post_date != 0 ): ?>
                    <p>Last posted on <time datetime="<?php echo date( 'Y-m-d',$last_post_date ); ?>" pubdate="pubdate"><?php echo date( 'M d, Y',$last_post_date ); ?></time>.</p>
                    <?php endif;?>
                    <ul>
                        <li><?php echo $answer_count . ' ' . _n( 'answer', 'answers', $answer_count ); ?></li>
                        <li><?php echo $post_count . ' ' . _n( 'post', 'posts', $post_count ); ?></li>
                        <li><?php echo $comment_count . ' ' . _n( 'comment', 'comments', $comment_count ); ?></li>
                    </ul>
                </article>
            </div>
        <!-- </li> -->
    </section>
</article>
