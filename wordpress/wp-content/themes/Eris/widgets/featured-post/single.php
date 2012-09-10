<?php get_template_part('parts/header', 'widget') ;?>
	<?php the_post(); ?>
    <?php if ((is_widget()->show_thumbnail && has_post_thumbnail()) || ($is_widget_override && has_post_thumbnail())) :
        //$widget_span = (!$is_widget_override) ? is_widget()->span : "12";
        //$widget_span = str_replace("span", "", $widget_span);
        if (is_widget()->span > 6) :
            $inner_span = "span12";
        else:
            $inner_span = "span6";
        endif;
        
        $post_thumbnail_id = get_post_thumbnail_id( $post_id );
        if ($post_thumbnail_id) :
            $thumbnail_src = wp_get_attachment_image_src($post_thumbnail_id, "large");
        ?>
            <div class="featured-image <?php echo $inner_span; ?>">
                <img src="<?php echo $thumbnail_src[0]; ?>" alt="<?php echo get_the_title(); ?>" />
            </div>
        <?php
        endif;
        ?>
    <?php else :?>
        <?php $featured_post_span = 'span12';?>
    <?php endif; ?>


    <div class="featured-post <?php echo $inner_span; ?>">
        <?php if (is_widget()->show_category || is_widget()->show_date || $is_widget_override) : ?>
            <div class="content-details clearfix">

                <?php if (is_widget()->show_category || $is_widget_override) : $c = get_the_category(); ?>
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



        <?php if (is_widget()->show_title || $is_widget_override) : ?>
            <p class="content-headline">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </p>
        <?php endif; //is_widget->show_title ?>



        <p class="content-byline">By: <?php echo get_the_author(); ?> </p>


        <?php if (is_widget()->show_comment_count || $is_widget_override): ?>
            <p class="content-comments"><?php comments_number(); ?></p>
        <?php endif; //is_widget->show_comment_count ?>
        


        <?php  if(is_widget()->show_content || $is_widget_override) : ?>
            <p class="content-excerpt">
                <?php the_excerpt(); ?>
                <!-- <a href="<?php the_permalink(); ?>" title="Read More">Read more</a> -->
            </p>
        <?php endif; //is_widget_show_content ?>

        <?php if (is_widget()->show_tags || $is_widget_override) : ?>
            <span class="content-tags">
                <?php 
                    $tags = get_the_tags(); 
                    foreach((object)$tags as $tag) {
                        $output[] = '<a href="' . get_tag_link($tag->term_id) . '" title="' . $tag->name . '"> ' . $tag->name . '</a>';
                    }
                    if (count($output) > 0)
                        echo "Tags: " . implode(', ', $output);
                ?>
            </span>
        <?php endif; ?>

        <?php
            $share_options = array();
            if ( is_widget()->share_style == 'long' ) { $share_options["version"] = 'long'; }
        ?>
        <section class="post-actions">
            <?php get_partial( 'parts/share', array( "version" => is_widget()->share_style, "url" => get_post_permalink( $post->ID ) ) ); ?>
        </section>

    </div> <!-- featured-post -->

<?php get_template_part('parts/footer', 'widget') ;?>