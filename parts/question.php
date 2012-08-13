<?php
    $i = 0;
    $categories = get_the_category( $post->ID );
    $badge_options = array(
        "user_id" => $post->post_author
    );
?>
<section class="span8">
    <article class="post-n-comments">
        <header class="section-header">
            Question
        </header>
        <div class="span2 badge">
            <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
            <h4><a href="#"><?php the_author_link(); ?></a></h4>
            <address>Chicago, IL</address>
        </div>
        <div class="span10 info content-details">
            <time datetime="<?php echo date( "Y-m-d" ); ?>" pubdate="pubdate"><?php echo the_date(); ?></time>
            <h2><?php the_title(); ?></h2>
            <?php the_content(); ?>
            <form class="actions clearfix" method="post" action="">
                <button type="button" name="button1" value="flag" id="flag-comment-<?php echo $comment->comment_ID; ?>" class="flag">Flag</button>
                <button type="button" name="button1" value="Follow" id="flag-comment-<?php echo $comment->comment_ID; ?>" class="follow">Follow</button>
                <?php get_partial( 'parts/share' ); ?>
            </form>
        </div>

        <?php
            comments_template('/parts/commentForm.php');
            comments_template('/parts/comments.php');
        ?>
    </article>
</section>
<section class="span4">
<?php
    //get_sidebar();
?>
</section>
