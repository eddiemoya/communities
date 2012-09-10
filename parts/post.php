<?php
    $i = 0;
    $categories = get_the_category( $post->ID );
?>
<!-- BREADCRUMB WIDGET -->
<!-- <nav class="span12 breadcrumb">
	<ul class="clearfix">
		<li><a href="#" title="The Fellowship of the Ring">The Fellowship of the Ring</a></li>
		<li><a href="#" title="The Old Forest">The Old Forest</a></li>
	</ul>
</nav> -->
<!-- END BREADCRUMB WIDGET -->
<article class="content-container post span12">
	<section class="content-body clearfix">
		
		<div class="content-details clearfix">
			<span class="content-category"><a href="<?php echo get_category_link($categories[0]->term_id); ?>" title="Fantasy"><?php echo $categories[0]->name; ?></a></span>
			<time class="content-date" pubdate datetime="<?php echo the_time( "Y-m-d"); ?>"><?php the_time("F j, Y g:ia"); ?></time>
		</div>
		
		<h1 class="content-headline"><?php the_title(); ?></h1>
		
		<?php the_content(); ?>
		
		<p class="content-author">
			By: <?php get_screenname_link( $post->post_author ); ?>
		</p>
		
		<?php if ( get_the_tags() ): ?>
		<p class="content-tags">
			Tags: <?php the_tags('',', ', ''); ?>
		</p>
		<?php endif; ?>
    <?php get_partial( 'parts/share', array( "version" => "long", "url" => get_permalink( $post->ID ) ) ); ?>
	</section>
</article>
    
<!--<article class="post-n-comments">-->
    <?php
        comments_template('/parts/commentForm.php');
        comments_template('/parts/comments.php');
    ?>
<?php
    if(is_user_logged_in()) {
?>
        <script type="text/javascript">
            $(document).ready(function() {
                // Toggle the answer form
                $(".commentForm form").hide();
                $(".leaveComment").click(function () {
                    $(".commentForm form").slideToggle("slow");
                });

                // Toggle reply forms
                $.each($(".reply-to-form"), function() {
                    if (($(this).get(0)).style.display != 'block') {
                        $(this).addClass('hide');
                    }
                });
                $(".reply").on('click', function() {
                	$(this).parent().parent().children("form.reply-to-form").slideToggle("slow");
                });

                // Make the cancel buttons collapse the forms, too.
                $('input[type="reset"]').on('click', function () {
                    $(this).parent().parent().slideToggle("slow");
                });
            });
        </script>
<?php
    } else {
?>
    <script type="text/javascript">
        $(".commentForm form").hide();
        $(".reply-to-form").hide();
    </script>
<?php
    }
?>
<!--</article>-->
