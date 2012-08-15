<?php
    $i = 0;
    $categories = get_the_category( $post->ID );
    $post_date = strtotime( $post->post_date );
?>    
<article class="post-n-comments">
    <header class="section-header">
        Breadcrumbs
    </header>
    <div class="content">
        <time class="date" datetime="<?php echo date( "Y-m-d", $post_date ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $post_date ); ?></time>
        <a href="<?php get_category_link($categories[0]->term_id); ?>" class="category"><?php echo $categories[0]->name; ?></a>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php the_content(); ?></p>
        <p class="by">By <?php the_author_link(); ?></p>
        
        <?php
            $posttags = get_the_tags();
            if ( $posttags ) :
        ?>
        <p class="tags">Tags:
        <?php
            foreach( $posttags as $tag ) {
                echo '<a href="'.get_bloginfo('siteurl').'">'.$tag->name.'</a> ';
            }
        ?>
        </p>      
        <?php
            endif;
        ?>
        <?php get_partial( 'parts/share', array( "version" => "long" ) ); ?>
    </div>

    <?php
        comments_template('/parts/commentForm.php');
        comments_template('/parts/comments.php');
    ?>
</article>
