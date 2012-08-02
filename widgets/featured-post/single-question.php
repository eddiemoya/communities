<?php
if (have_posts()) { while (have_posts()) { the_post(); ?>

<!--/Users/emoya1/Public/Projects/comm/wordpress/wp-content/themes/Eris/widgets/parts/category.php -->
<article class="content-container featured-question">
    <?php if(function_exists('is_widget')) : ?>
    <?php if (is_widget()->show_title && !empty(is_widget()->widget_title)) : ?>

        <header class="content-header">
            <h3><?php echo is_widget()->widget_title; ?></h3>
            <?php if(is_widget()->show_subtitle) : ?>
                <h4><?php echo is_widget()->widget_subtitle; ?></h4>
            <?php endif; ?>
        </header>

    <?php endif; ?>

    <section class="content-body clearfix">

        <?php if (is_widget()->show_category || is_widget()->show_date) : ?>
            <div class="content-details clearfix">

                <?php if (is_widget()->show_category) : ?>
                    <span class="content-category">
                        <a href="#" title="Kittens">
                            <?php 
                                $c = get_the_category(); 
                                echo $c[0]->cat_name; 
                            ?>
                        </a>
                    </span>
                <?php endif; ?>

                <time class="content-date" datetime="<?php the_time('Y-m-d'); ?>">
                    <?php the_time('F j, Y'); ?>
                </time>

            </div>
        <?php endif; ?>



        <div class="category-image">
            <?php //get_category_image_url($c[0]->term_id, true, true); ?>
        </div>

        <h6 class="content-headline">
            <a href="<?php the_permalink(); ?>">
                Q:
                <?php the_title(); ?>
            </a>
        </h6>

        <ul class="content-comments">
            <li><?php comments_number(); ?></li>
        </ul>


        </div>

    </section>
<?php endif; ?>
</article> 
<?php
}}