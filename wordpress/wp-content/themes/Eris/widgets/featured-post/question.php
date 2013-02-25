<?php 
    global $excerptLength; $excerptLength = 140; $c = get_the_category(); 
    $answer_count = (function_exists('get_custom_comment_count')) ? get_custom_comment_count('answer') : '';
    $answer_count_string = ($answer_count > 500) ? "500+ answers" : $answer_count . " " . _n( ' answer', ' answers', $answer_count );
?>
    <?php if (is_widget('show_category') || is_widget('show_date')) : ?>
        <div class="content-details clearfix">

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

        </div> <!-- content-details -->

    <?php endif; //is_widget->show_category, show_date ?>
    <?php //get_partial( 'parts/space_date_time', array( "timestamp" => get_the_time('U'), "remove_hms" => true ) ); ?>

    <?php if (is_widget('show_thumbnail') && function_exists('get_category_image_url') &&  $image = get_category_image_url((int)$c[0]->term_id, false)) : ?>
        <div class="category-image">
            <img src="<?php echo $image; ?>" />
        </div>
    <?php endif; ?>

    <h1 class="content-headline">
        Q: <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_truncated_title(75); ?></a>
    </h1>

    <?php if(is_widget('show_comment_count')): ?>
        <ul class="clearfix">
            <li class="content-comments"><?php echo $answer_count_string; ?></li>
        </ul>
    <?php endif; ?>


    <section class="post-actions">
        <?php get_partial( 'parts/share', array( "version" => is_widget('share_style'), "url" => get_post_permalink( $post->ID ) ) ); ?>
    </section>