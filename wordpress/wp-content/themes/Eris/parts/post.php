<?php
    $i = 0;
    $categories = get_the_category( $post->ID );
?>    
<article class="post-n-comments">
 <!--   <header class="section-header">
        Breadcrumbs
    </header> -->
    <div class="content">
        <time class="date" datetime="<?php echo the_time( "Y-m-d"); ?>" pubdate="pubdate"><?php the_time("F j, Y g:ia"); ?></time>
        <a href="<?php echo get_category_link($categories[0]->term_id); ?>" class="category"><?php echo $categories[0]->name; ?></a>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php the_content(); ?></p>
        <p class="by">By <?php get_screenname_link( $post->post_author ); ?></p>
        <?php if ( get_the_tags() ): ?>
            <p class="tags">Tags: <?php the_tags('',', ', ''); ?></p>
        <?php endif; ?>
        <?php get_partial( 'parts/share', array( "version" => "long", "url" => get_permalink( $post->ID ) ) ); ?>
    </div>

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
                    $(this).parent().next("form.reply-to-form").slideToggle("slow");
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
</article>
