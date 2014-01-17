<?php
    global $excerptLength; $excerptLength = 140;

    $c = get_the_category();
    $cat = $c[0];
    $crest_options = array(
        "user_id" => $post->post_author,
        "width"		=> "span2"
    );

    $post_actions = array(
        "id"        => $post->ID,
        "type"      => 'post',
        "sub_type"   => $post->post_type,
        "options"   => array( "flag", "share" ),
        'actions'   => $post->actions,
        'post_id'   => $post->ID,
        "url"       => get_permalink( $post->ID )
    );

    $answer_count = (function_exists('get_custom_comment_count')) ? get_custom_comment_count('answer') : '';
    $answer_count_string = ($answer_count > 500) ? "500+ answers" : $answer_count . " " . _n( ' answer', ' answers', $answer_count );
?>
<article class="content-container <?php echo $post->post_type; ?>">
	<section class="content-body clearfix">
		
		<?php get_partial( 'parts/crest', $crest_options ); ?>
		
		<div class="span10">
			
			<div class="content-details clearfix">
				<span class="content-category">
				<?php if(empty($cat->parent)) { ?>
					<a href="<?php echo get_category_link($cat->term_id); ?>" title="<?php echo $cat->cat_name; ?>"><?php echo $cat->cat_name; ?></a>
				<?php } else { ?>
					<?php echo($cat->cat_name); ?>
				<?php } ?>
				</span>
				<?php get_partial( 'parts/space_date_time', array( "timestamp" => get_the_time( 'U' ) ) ); ?>
			</div>
			
			<h1 class="content-headline"><a href="<?php the_permalink(); ?>"><?php the_truncated_title(75); ?></a></h1>
			
			<p class="content-excerpt">
				<?php the_excerpt(); ?>
			</p>
			
			<ul>
		  		<li class="content-comments"><?php echo $answer_count_string; ?></li>
		  	</ul>
			
      		<?php 
      			if(!is_search()){
      				get_partial( 'parts/forms/post-n-comment-actions', $post_actions ); 
      			}
      		?>
			
		</div>
		
		
	</section>
</article>
