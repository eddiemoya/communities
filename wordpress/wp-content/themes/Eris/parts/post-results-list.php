<?php
    $queried_type = get_query_var('post_type');
    $post_type = (!is_array($queried_type) || !isset($queried_type[1])) ? $queried_type : 'post';
    $post_type = (is_array($post_type)) ? $post_type[0] : $post_type;
    $inner_span = (has_post_thumbnail()) ? "span6" : "span12";
?>

<article class="content-container <?php //echo $post_type ?> <?php echo $post_type ?>">

    <!--  // Pull everything from here out to another partial specific to featured posts  -->
    <section class="content-body clearfix">

    <?php if (has_post_thumbnail()) : ?>
        <div class="featured-image <?php echo $inner_span; ?>">
            <?php the_post_thumbnail(); ?>
        </div>
    <?php endif; ?>


        <div class="featured-post <?php echo $inner_span; ?>">

            <div class="content-details clearfix">
                <?php $c = get_the_category(); ?>
                    <span class="content-category">
                        <a href="<?php echo get_category_link($c[0]); ?>" title="<?php echo $c[0]->name; ?>">
                            <?php echo $c[0]->cat_name; ?>
                        </a>
                    </span>
                <time class="content-date" datetime="<?php the_time('Y-m-d'); ?>">
                    <?php the_time('F j, Y'); ?>
                </time>

            </div> <!-- content-details -->

            <h1 class="content-headline">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h1> <!-- content-headline -->

            <ul>
                <li class="content-author">By: <?php echo get_the_author(); ?></li>
                <li class="content-comments"><?php comments_number(); ?></li>
            </ul>

            <?php the_excerpt(); ?>

            <p class="content-tags">
                <?php the_tags("Tags: ", ", "); ?>
            </p>

            <section class="post-actions">
                <?php get_partial( 'parts/share', array( "version" => "long", "url" => get_post_permalink( $post->ID ) ) ); ?>
            </section>

        </div> <!-- featured- -->

    </section> <!-- featured-post -->
</article>

<?php// get_template_part('parts/footer', 'widget') ;?>