<?php
    $i = 0;
    $categories = get_the_category( $post->ID );
?>    
<article class="post-n-comments">
 <!--   <header class="section-header">
        Breadcrumbs
    </header> -->
    <div class="content">
        <time class="date" datetime="<?php echo the_time( "Y-m-d"); ?>" pubdate="pubdate"><?php the_time("F j, Y g:ia"); ?></time>
        <a href="<?php echo get_category_link($categories[0]->term_id); ?>" class="category"><?php echo $categories[0]->name; ?></a>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php the_content(); ?></p>
        <p class="by">By <?php get_screenname_link( $post->post_author ); ?></p>
        <?php if ( get_the_tags() ): ?>
            <p class="tags">Tags: <?php the_tags('',', ', ''); ?></p>
        <?php endif; ?>
        <?php get_partial( 'parts/share', array( "version" => "long", "url" => get_permalink( $post->ID ) ) ); ?>
    </div>

    <?php
        comments_template('/parts/commentForm.php');
        comments_template('/parts/comments.php');
    ?>
</article>
