<!--/Users/emoya1/Public/Projects/comm/wordpress/wp-content/themes/Eris/parts/post-widget.php -->
<article class="content-container featured-post">

    <?php if (is_widget()->show_title && !empty(is_widget()->widget_title)) : ?>

        <header class="content-header">
            <h3><?php echo is_widget()->widget_title; ?></h3>
        </header>

    <?php endif; ?>

    <section class="content-body clearfix">

        <?php if (is_widget()->show_thumbnail && has_post_thumbnail()) : ?>
            <div class="featured-image span6">
                <?php the_post_thumbnail(); ?>
            </div>
        <?php endif; ?>

        <div class="featured-post span6">

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

                    <?php// if (is_widget()->show_date) : ?>
                        <time class="content-date" datetime="<?php the_time('Y-m-d'); ?>">
                            <?php the_time('F j, Y'); ?>
                        </time>
                    <?php// endif; ?>

                </div>
            <?php endif; ?>

            <?php if (is_widget()->show_title) : ?>
                <p class="content-headline">
                    <a href="">
                        <?php the_title(); ?>
                    </a>
                </p>
            <?php endif; ?>

            <p class="content-byline">By: <?php echo get_the_author(); ?> </p>

            <?php if (is_widget()->show_comment_count): ?>
                <p class="content-comments"><?php comments_number(); ?></p>
            <?php endif; ?>
                
            <?php  if(is_widget()->show_content) : ?>
                <p class="content-excerpt">
                    <?php the_excerpt(); ?>
                    <a href="#" title="Read More">Read more</a>
                <p>
            <?php endif; ?>
        </div>

    </section>

</article>