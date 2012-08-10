<?php
    $i = 0;
    $categories = get_the_category( $post->ID );
?>
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
        <ul class="actions">
            <li>Follow</li>
            <li>Share</li>
            <li>Flag</li>
        </ul>
    </div>
    
    <?php
        comments_template('/parts/commentForm.php');
        comments_template('/parts/comments.php');
    ?>
</article>
