<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();

            $post_thumbnail_id = get_post_thumbnail_id( $post_id );
            if ($post_thumbnail_id) :
                $thumbnail_src = wp_get_attachment_image_src($post_thumbnail_id, "large");
            ?>
                <div class="featured-image <?php echo $featured_img_span; ?>">
                    <img src="<?php echo $thumbnail_src[0]; ?>" alt="<?php echo get_the_title(); ?>" />
                </div>
            <?php
            endif;
            ?>


        <div class="featured-post <?php echo $featured_post_span; ?>">
                <div class="content-details clearfix">

                        <span class="content-category">
                            <a href="<?php echo get_category_link($c[0]); ?>" title="<?php echo $c[0]->name; ?>">
                                <?php echo $c[0]->cat_name; ?>
                            </a>
                        </span>

                    <time class="content-date" datetime="<?php the_time('Y-m-d'); ?>">
                        <?php the_time('F j, Y'); ?>
                    </time>

                </div> <!-- content-details -->



                <p class="content-headline">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </p>



            <p class="content-byline">By: <?php echo get_the_author(); ?> </p>


                <p class="content-comments"><?php comments_number(); ?></p>


                <p class="content-excerpt">
                    <?php the_excerpt(); ?>
                    <!-- <a href="<?php the_permalink(); ?>" title="Read More">Read more</a> -->
                </p>
                
                <?php
                    $share_options = array();
                    if ( $widget->share_style == 'long' ) { $share_options["version"] = 'long'; }
                ?>
                <section class="post-actions">
                    <?php get_partial( 'parts/share', array( "version" => "long", "url" => get_post_permalink( $post->ID ) ) ); ?>
                </section>

                <span class="content-tags">
                    <?php 
                        $tags = get_the_tags(); 
                        foreach((object)$tags as $tag) {
                            $output[] = '<a href="' . get_tag_link($tag->term_id) . '" title="' . $tag->name . '"> ' . $tag->name . '</a>';
                        }
                        if (count($output) > 0)
                            echo "Tags: " . implode(', ', $output);
                    ?>
                </span>

        </div> <!-- featured-post --> 
<?php
    }
}

wp_reset_query();
?>