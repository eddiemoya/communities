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

    $answer_count = (function_exists('get_custom_comment_count')) ? get_custom_comment_count('answer') : '';
    $comment_count = (function_exists('get_custom_comment_count')) ? get_custom_comment_count('comment') : '';

?>

<li class="post lone-result clearfix">

    <?php get_partial( 'parts/crest', $crest_options ); ?>

    <div class="span10">

        <time class="content-date" datetime="<?php echo the_time( "Y-m-d"); ?>" pubdate="pubdate"><?php the_time("F n, Y g:i a"); ?></time> <!-- <time class="content-date" datetime="<?php //echo the_time( "Y-m-d"); ?>" pubdate="pubdate"><?php //the_time("g:i a"); ?></time>-->
        
		
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
            <p class="content-comments"><?php echo $answer_count; ?> answers | <?php comments_number('0 replies', '1 reply', '% replies'); ?> | <?php echo $comment_count; ?> comments</p>
        </article>
        
        <?php get_partial( 'parts/forms/post-n-comment-actions', $post_actions ); ?>

    </div>
</li>
