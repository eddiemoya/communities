<?php
    global $excerptLength;

    $excerptLength = 140;

    $c = get_the_category();
    $cat = $c[0];
    $crest_options = array(
        "user_id" => $post->post_author
    );
    $post_actions = array(
        "id"        => $post->ID,
        "type"      => $post->post_type,
        "options"   => array( "flag", "share" ),
        "url"       => get_permalink( $post->ID )
    );
?>

<li class="post lone-result clearfix">

    <?php get_partial( 'parts/crest', $crest_options ); ?>

    <div class="span10">

        <time class="content-date" datetime="<?php echo the_time( "Y-m-d"); ?>" pubdate="pubdate"><?php the_time("F n, Y g:ia"); ?></time>

        <hgroup>
            <h3 class="content-category">
                <a href="<?php get_category_link(get_query_var($cat->term_id)); ?>" title="<?php echo $cat->cat_name; ?>">
                    <?php echo $cat->cat_name; ?>
                </a>
            </h3>
            <h2 class="content-headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </hgroup>

        <article>
            <p class="excerpt"><?php the_excerpt(); ?></p>
            <p class="content-comments">x answers | y replies | <?php comments_number(); ?></p>
        </article>
        
        <?php get_partial( 'parts/forms/post-n-comment-actions', $post_actions ); ?>

    </div>
</li>
