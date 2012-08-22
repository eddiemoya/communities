<?php
    $i = 0;

    $categories = get_the_category( $post->ID );

    $crest_options = array(
        "user_id" => $post->post_author
    );

    $post_actions = array(
        "id"        => $post->ID,
        "sub_type"  => $post->post_type,
        'type'      => 'posts',
        "options"   => array( "follow", "flag", "share" ),
        'actions'   => $post->actions
    );
    $post_date = strtotime( $post->post_date );
?>
<section class="span8">
    <article class="post-n-comments">
        <header class="section-header">
            Question
        </header>
        <?php get_partial( 'parts/crest', $crest_options ); ?>
        <div class="span10 info content-details">
            <time class="content-date" datetime="<?php echo date( "Y-m-d", $post_date ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $post_date ); ?></time>
            <h2><?php the_title(); ?></h2>
            <?php the_content(); ?>
            <?php get_partial( 'parts/forms/post-n-comment-actions', $post_actions ); ?>
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
