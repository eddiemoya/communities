<?php
    global $excerptLength; $excerptLength = 140;

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
<article class="content-container question">
	<section class="content-body clearfix">
		
		<?php get_partial( 'parts/crest', $crest_options ); ?>
		
		<div class="span10">
			
			<div class="content-details clearfix">
				<span class="content-category"><a href="<?php echo get_category_link($cat->term_id); ?>" title="<?php echo $cat->cat_name; ?>"><?php echo $cat->cat_name; ?></a></span>
				<?php get_partial( 'parts/space_date_time', array( "timestamp" => get_the_time( 'U' ) ) ); ?>
			</div>
			
			<h1 class="content-headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			
			<p class="content-excerpt">
				<?php the_excerpt(); ?>
			</p>
			
			<ul>
		  	<li class="content-comments"><?php echo $answer_count . ' ' . _n( 'answer', 'answers', $answer_count ); ?> | <?php comments_number('0 replies', '1 reply', '% replies'); ?> | <?php echo $comment_count . ' ' . _n( 'comment', 'comments', $user->comment_count ); ?></li>
		  </ul>
			
      <?php get_partial( 'parts/forms/post-n-comment-actions', $post_actions ); ?>
			
		</div>
		
		
	</section>
</article>
