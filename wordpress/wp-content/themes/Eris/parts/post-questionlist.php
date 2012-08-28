<?php
    if(is_widget()->category) :
        $current_category = "";
        foreach(wp_get_post_terms($post->ID, 'category') as $cat) :
            if($cat->slug != "uncategorized") :
                $current_category = $cat->name;
            endif;
        endforeach;
    endif;
?>
<article class="content-item span12">
    <div class="post-data span12">
        <?php if($current_category != "") : ?>
            <p class="post-category"><?php echo $current_category; ?></p>
        <?php endif; ?>
        <p class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
        <p class="comments-count"><?php comments_number( 'no answers', '1 answer', '% answers' ); ?></p>
        <?php get_partial( 'parts/share', array( "version" => is_widget()->share_style, "url" => get_post_permalink( $post->ID ) ) ); ?>
    </div>
</article>