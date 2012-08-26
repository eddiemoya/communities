<?php get_template_part('parts/header', 'widget') ;?>

	<?php the_post(); ?>

    <?php if (is_widget()->show_thumbnail && has_post_thumbnail()) :
        $widget_span = is_widget()->span;
        $widget_span = str_replace("span", "", $widget_span);
        if ($widget_span <= 6) :
            $featured_img_span = "span12";
            $featured_post_span = "span12";
        else:
            $featured_img_span = "span6";
            $featured_post_span = "span6";
        endif;
    ?>
        <div class="featured-image <?php echo $featured_img_span; ?>">
            <?php the_post_thumbnail(); ?>
        </div>
    <?php else :?>
        <?php $featured_post_span = 'span12';?>
    <?php endif; ?>


    <div class="featured-post <?php echo $featured_post_span; ?>">
        <?php if (is_widget()->show_category || is_widget()->show_date) : ?>
            <div class="content-details clearfix">

                <?php if (is_widget()->show_category) : $c = get_the_category(); ?>
                    <span class="content-category">
                        <a href="<?php echo get_category_link($c[0]); ?>" title="<?php echo $c[0]->name; ?>">
                            <?php echo $c[0]->cat_name; ?>
                        </a>
                    </span>
                <?php endif; ?>

                <time class="content-date" datetime="<?php the_time('Y-m-d'); ?>">
                    <?php the_time('F j, Y'); ?>
                </time>

            </div> <!-- content-details -->
        <?php endif; //is_widget->show_category, show_date ?>



        <?php if (is_widget()->show_title) : ?>
            <p class="content-headline">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </p>
        <?php endif; //is_widget->show_title ?>



        <p class="content-byline">By: <?php echo get_the_author(); ?> </p>


        <?php if (is_widget()->show_comment_count): ?>
            <p class="content-comments"><?php comments_number(); ?></p>
        <?php endif; //is_widget->show_comment_count ?>
        


        <?php  if(is_widget()->show_content) : ?>
            <p class="content-excerpt">
                <?php the_excerpt(); ?>

                <a href="<?php the_permalink(); ?>" title="Read More">Read more</a>

            </p>
        <?php endif; //is_widget_show_content ?>

        <?php if (is_widget()->show_tags) : ?>
            <span class="content-category">
                <?php $tags = get_the_tags(); foreach($tags as $tag) : ?>
                <a href="<?php echo get_tag_link($tag->term_id); ?>" title="<?php echo $tag->name ?>">
                    <?php echo $tag->name ?>
                </a>
                <?php endforeach; ?>
            </span>
        <?php endif; ?>

        <section class="post-actions">
            <?php get_partial( 'parts/share' ); ?>
        </section>

    </div> <!-- featured-post -->

<?php get_template_part('parts/footer', 'widget') ;?>