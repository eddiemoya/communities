<?php 
global $excerptLength; $excerptLength = 200;

//Inner span is span12, unless theres a thumbnail to show, or unless the the widget's span is already 6 or lower.
$comments = get_comments_number();
$comments_string = ($comments > 500) ? "500+ comments" : $comments . " " . _n( 'comment', 'comments', $comments );

//echo "<pre>";print_r(is_widget());echo "</pre>";
$has_featured_image = ( is_widget('show_thumbnail') && has_post_thumbnail() );
$has_featured_video = ( ($featured_video = get_post_meta(get_the_ID(), 'featured_video_url', true)) && ('video' == get_post_format(get_the_ID())) );


$inner_span = ($has_featured_image || $has_featured_video) ? "span6" : "span12";?>

<?php if ($has_featured_image && !$has_featured_video ) : ?>
    <div class="featured-image <?php echo $inner_span; ?>">
        <?php the_post_thumbnail('large'); ?>
    </div>
<?php elseif ($has_featured_video): ?>
    <div class="featured-image <?php echo $inner_span; ?>">
        <div class="content-video">
            <?php echo wp_oembed_get($featured_video); ?>
        </div>
    </div>
<?php endif; ?>

<div class="featured-content <?php echo $inner_span; ?>">

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



    <?php if (is_widget('show_title')) : ?>
        <h1 class="content-headline">
            <a href="<?php the_permalink(); ?>">
                <?php the_truncated_title(); ?>
            </a>
        </h1>
    <?php endif; //is_widget->show_title ?>

    <ul>
        <li class="content-author">By: <a href="<?php echo get_profile_url($post->post_author); ?>"><?php echo get_the_author(); ?></a></li>

        <?php if(is_widget('show_comment_count')): ?>

            <li class="content-comments"><?php echo $comments_string; ?></li>

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