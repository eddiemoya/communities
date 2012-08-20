
<?php get_template_part('parts/header', 'widget') ;?>
    <?php $c = get_the_category(); ?>
    <?php if (is_widget()->show_category || is_widget()->show_date) : ?>

        <div class="content-details clearfix">
            <?php if (is_widget()->show_category) : ?>
                <span class="content-category">
                    <a href="<?php get_category_link(get_query_var($c[0]->term->id)); ?>" title="<?php echo $c[0]->cat_name; ?>">
                        <?php echo $c[0]->cat_name; ?>
                    </a>
                </span>
            <?php endif; //show category; ?>

            <time class="content-date" datetime="<?php the_time('Y-m-d'); ?>">
                <?php the_time('F j, Y'); ?>
            </time>
        </div>

    <?php endif; //show category or show date ?>


    <?php if (is_widget()->show_thumbnail) : ?>
        <div class="category-image">
            <img src="<?php echo get_category_image_url($c[0]->term_id, true, true); ?>" />
        </div>
    <?php endif; ?>

    <h6 class="content-headline">
        <a href="<?php the_permalink(); ?>">
            Q:
            <?php the_title(); ?>
        </a>
    </h6>


    <ul class="content-comments">
        <li><?php comments_number(); ?></li>
    </ul>

<?php get_template_part('parts/footer', 'widget') ;?>
