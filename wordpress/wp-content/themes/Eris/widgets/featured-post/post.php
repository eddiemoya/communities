<?php 
//Inner span is span12, unless theres a thumbnail to show, or unless the the widget's span is already 6 or lower.
$inner_span = ((is_widget('show_thumbnail') && has_post_thumbnail()) || is_widget()->span <= 6) ? "span6" : "span12";?>

<?php if (is_widget('show_thumbnail') && has_post_thumbnail()) : ?>
    <div class="featured-image <?php echo $inner_span; ?>">
        <?php the_post_thumbnail('large'); ?>
    </div>
<?php endif; ?>

<div class="<?php echo $inner_span; ?>">

    <?php if (is_widget('show_category') || is_widget('show_date')) : ?>
        <section class="content-details clearfix">

            <?php if (is_widget('show_category')) : $c = get_the_category(); ?>
                <span class="content-category">
                    <a href="<?php echo get_category_link($c[0]); ?>" title="<?php echo $c[0]->name; ?>">
                        <?php echo $c[0]->cat_name; ?>
                    </a>
                </span>
            <?php endif; ?>

            <?php if(is_widget('show_date')) : ?>
                <time class="content-date" pubdate datetime="<?php the_time('Y-m-d'); ?>">
                    <?php the_time('F j, Y'); ?>
                </time>
            <?php endif; ?>


        </section> <!-- content-details -->
    <?php endif; //is_widget->show_category, show_date ?>



    <?php if (is_widget('show_title')) : ?>
        <h1 class="content-headline">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h1>
    <?php endif; //is_widget->show_title ?>

	<ul>
		<li class="content-author">By: <?php echo get_the_author(); ?></li>

		<?php if(is_widget('show_comment_count')): ?>

            <li class="content-comments"><?php comments_number(); ?></li>

        <?php endif; //is_widget->show_comment_count ?>
	</ul>

    <?php  if(is_widget('show_content')) : ?>
            <?php the_excerpt(); ?>
    <?php endif; //is_widget_show_content ?>


    <?php if (is_widget('show_tags')) : ?>
        <p class="content-tags">
            <?php the_tags("Tags: ", ", "); ?>
        </p>
    <?php endif; ?>

    <?php if(is_widget('show_share')): ?>
        <section class="post-actions">
            <?php get_partial( 'parts/share', array( "version" => is_widget('share_style'), "url" => get_post_permalink( $post->ID ) ) ); ?>
        </section>
    <?php endif; ?>

</div> <!-- featured-post -->






