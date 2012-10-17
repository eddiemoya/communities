<?php
    $i = 0;
    $categories = get_the_category( $post->ID );
    $badge_page = get_page_by_title( 'Types of Badges' );
?>
<!-- BREADCRUMB WIDGET -->
<!-- <nav class="span12 breadcrumb">
	<ul class="clearfix">
		<li><a href="#" title="The Fellowship of the Ring">The Fellowship of the Ring</a></li>
		<li><a href="#" title="The Old Forest">The Old Forest</a></li>
	</ul>
</nav> -->
<!-- END BREADCRUMB WIDGET -->
<section class="span12">
	<section class="span8">
		<article class="content-container post span12">
			<section class="content-body clearfix">
				
				<div class="content-details clearfix">
					<span class="content-category"><a href="<?php echo get_category_link($categories[0]->term_id); ?>" title="<?php ec?>"><?php echo $categories[0]->name; ?></a></span>
					<?php get_partial( 'parts/space_date_time', array( "timestamp" => get_the_time( "U" ), "remove_hms" => true ) ); ?>
				</div>
				
				<h1 class="content-headline"><?php the_title(); ?></h1>
				
				<div class="link-emulator" shc:gizmo:options="{moodle: {width:580, target:'badgesInfo', method:'local'}}" shc:gizmo="moodle">clickme</div>

                <div id="badgesInfo" class="hide">
                    <h2><?php echo $badge_page->post_title; ?></h2>
                    <?php echo $badge_page->post_content; ?>
                </div>
				
				<?php the_content(); ?>
				
				
				<div class="clearfix">&nbsp;</div>
				
				<p class="content-author">
					By: <?php get_screenname_link( $post->post_author ); ?>
				</p>
				
				<?php if ( get_the_tags() ): ?>
				<p class="content-tags">
					Tags: <?php the_tags('',', ', ''); ?>
				</p>
				<?php endif; ?>
				<p>
		    <?php get_partial( 'parts/share', array( "version" => "long", "url" => get_permalink( $post->ID ) ) ); ?>
		    </p>
		    <p class="content-comments">
		    <?php
		        $commentCount = get_custom_comment_count($comment_type, $post->ID);
		        echo $commentCount . ' ' . _n( 'comment', 'comments', $commentCount );
		    ?>
		    </p>
			</section>
		</article>
	    <?php
	        comments_template('/parts/commentForm.php');
	        comments_template('/parts/comments.php');
	    ?>
	</section>

	<section class="span4">
		<section class="dropzone-inner-wrapper border-left">
			<?php display_dropzone("single-{$post->post_type}-right-rail"); ?>
		</section>
	</section>
	
</section>
<?php
    if(is_user_logged_in()) {
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $.each($(".ugc-comment-answer_form form"), function() {
                    if (($(this).get(0)).style.display != 'block') {
                        $(this).addClass('hide');
                    }
                });
                $(".ugc-comment-answer_form a").on('click', function(event) {
                	event.preventDefault();
                	$(this).parents(".ugc-comment-answer_form").children("form").slideToggle("slow");
                });

                // Make the cancel buttons collapse the forms, too.
                $('.ugc-comment-answer_form .azure').on('click', function(event) {
                		event.preventDefault();
                    $(this).parents(".ugc-comment-answer_form").children("form").slideToggle("slow");
                });
            });
        </script>
<?php
    } 
?>
<!--</article>-->
