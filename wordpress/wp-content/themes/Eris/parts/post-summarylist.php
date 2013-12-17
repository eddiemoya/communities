<?php
    if(is_widget()->list_style == "show-category") :
        $header_display = "";
        foreach(wp_get_post_terms($post->ID, 'category') as $cat) :
            if($cat->slug != "uncategorized") :
                $header_display = ucwords($cat->name);
            endif;
        endforeach;
    elseif(is_widget()->list_style == "post-type") :
        $header_display = ucwords(get_post_type_object(get_post_type())->labels->singular_name);
    endif;
?>
<article class="content-item span12">
    <div class="post-data span12">
        <?php if($header_display != "" || !empty(is_widget()->show_date)) : ?>
            <section class="clearfix">
                <?php if($header_display != "") : ?>
                    <p class="post-category left"><?php echo $header_display; ?></p>
                <?php endif; ?>
                <?php if(is_widget()->show_date == "on") : ?>
                    <p class="post-date right"><?php the_date(); ?></p>
                <?php endif; ?>
            </section>
        <?php endif; ?>
        <section class="clearfix">
            <p class="post-title"><a href="<?php the_permalink(); ?>"><?php the_truncated_title(); ?></a></p>
            <?php if(get_post_type() == "question") : ?>
                <p class="comments-count"><?php comments_number( 'no answers', '1 answer', '% answers' ); ?></p>
            <?php else: ?>
                <p class="comments-count"><?php comments_number( 'no comments', '1 comment', '% comments' ); ?></p>
            <?php endif; ?>
            <?php if(!empty(is_widget()->show_share)) : ?>
                <?php get_partial( 'parts/share', array( "version" => is_widget()->share_style, "url" => get_post_permalink( $post->ID ) ) ); ?>
            <?php endif; ?>
        </section>
    </div>
</article>