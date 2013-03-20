<?php
    global $excerptLength; $excerptLength = 500;
    $queried_type = get_query_var('post_type');
    $post_type = (!is_array($queried_type) || !isset($queried_type[1])) ? $queried_type : 'post';
    $post_type = (is_array($post_type)) ? $post_type[0] : $post_type;
    $comments = get_comments_number();
    $comments_string = ($comments > 500) ? "500+ comments" : $comments . " " . _n( 'comment', 'comments', $comments );
	$has_featured_video = (($featured_video = get_post_meta(get_the_ID(), 'featured_video_url', true)) && ('video' == get_post_format(get_the_ID())));
    $inner_span = (has_post_thumbnail() || $has_featured_video) ? "span6" : "span12";
?>

<article class="content-container <?php echo $post_type ?>">

    <!--  // Pull everything from here out to another partial specific to featured posts  -->
    <section class="content-body clearfix">

    <?php if($has_featured_video) { ?>
        <div class="content-video <?php echo $inner_span; ?>">
            <!-- YOU HAVE TO INCLUDE PARAMETER 'wmode=opaque' OR VIDEO OVERLAPS SITE -->
            <?php echo get_oembed_thumbnail($featured_video); ?>
        </div>
    <?php } else if (has_post_thumbnail() || !$_GET['s']) { ?>
        <div class="featured-image <?php echo $inner_span; ?>">
            <?php the_post_thumbnail(); ?>
        </div>
    <?php } ?>


        <div class="<?php echo $inner_span; ?>">
			
			<div class="content-details clearfix">
                <?php $c = get_the_category(); ?>
                    <span class="content-category">
                        <a href="<?php echo get_category_link($c[0]); ?>" title="<?php echo $c[0]->name; ?>">
                            <?php echo $c[0]->cat_name; ?>
                        </a>
                    </span>
                <time class="content-date" pubdate datetime="<?php the_time('Y-m-d'); ?>">
                    <?php the_time('F j, Y'); ?>
                </time>

            </div> <!-- content-details -->

            <h1 class="content-headline">
                <a href="<?php the_permalink(); ?>">
                    <?php the_truncated_title(); ?>
                </a>
            </h1> <!-- content-headline -->

            <ul>
                <li class="content-author">By: <a href="<?php echo get_profile_url($post->post_author); ?>"><?php echo get_the_author(); ?></a></li>
      <!--           <li class="content-comments"><?php comments_number(); ?></li> -->
            </ul>

            <?php
                the_excerpt();
            ?>

            <p class="content-tags">
                <?php the_tags("Tags: ", ", "); ?>
            </p>

            <ul>
                <li class="content-comments"><?php echo $comments_string; ?></li> 
                <?php if(!$_GET['s']): ?>
                <li class="content-share"><?php get_partial( 'parts/share', array("url" => get_post_permalink( $post->ID ) ) ); ?></li>
                <?php endif; ?>
            </ul>

        </div> <!-- featured- -->

    </section> <!-- featured-post -->
</article>

<?php// get_template_part('parts/footer', 'widget') ;?>