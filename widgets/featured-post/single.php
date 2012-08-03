<?php get_template_part('parts/header', 'widget') ;?>

	<?php the_post(); ?>

    <?php if (is_widget()->show_thumbnail && has_post_thumbnail()) : ?>
        <div class="featured-image span6">
            <?php the_post_thumbnail(); ?>
            <?php $featured_post_span = 'span6'; ?>
        </div>
    <?php else :?>
        <?php $featured_post_span = 'span12';?>
    <?php endif; ?>


    <div class="featured-post <?php echo $featured_post_span; ?>">


        <?php if (is_widget()->show_category || is_widget()->show_date) : ?>
            <div class="content-details clearfix">


                <?php if (is_widget()->show_category) : ?>
                    <span class="content-category">
                        <a href="#" title="Kittens">
                            <?php $c = get_the_category(); echo $c[0]->cat_name; ?>
                        </a>
                    </span>
                <?php endif; //is_widget->show_category ?>



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
                <a href="#" title="Read More">Read more</a>
            <p>
        <?php endif; //is_widget_show_content ?>

    </div> <!-- featured-post -->

<?php get_template_part('parts/footer', 'widget') ;?>