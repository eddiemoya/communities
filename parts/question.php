<?php
    $i = 0;
    $categories = get_the_category( $post->ID );
?>
<article class="post-n-comments">
    <header class="section-header">
        Question
    </header>
    <div class="span2 badge">
        <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
        <h4><a href="#"><?php the_author_link(); ?></a></h4>
        <address>Chicago, IL</address>
    </div>
    <div class="span10 info content-details">
        <time datetime="<?php echo date( "Y-m-d" ); ?>" pubdate="pubdate"><?php echo the_date(); ?></time>
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
        <form class="actions clearfix" method="post" action="">
            <button type="button" name="button1" value="flag" id="flag-comment-<?php echo $comment->comment_ID; ?>" class="flag">Flag</button>
            <button type="button" name="button1" value="Follow" id="flag-comment-<?php echo $comment->comment_ID; ?>" class="follow">Follow</button>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>

            <div class="addthis_toolbox">
                <div class="share_menu_links_text">
                    <span class="share_button">Share</span>
                </div>
                <div class="share_menu_links">
                    <a class="addthis_button_email">Email</a>
                    <a class="addthis_button_twitter">Twitter</a>
                    <a class="addthis_button_facebook">Facebook</a>
                    <a class="addthis_button_shopyourway" addthis:url="http://www.sears.com" addthis:title="ShopYourWay this"><img src="<?php echo get_template_directory_uri() ?>/assets/img/shopyourway_small.jpg" />ShopYourWay</a>
                </div>
            </div>

            <script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/assets/js/addthis.js"></script>
        </form>
    </div>
    
    <?php
        comments_template('/parts/commentForm.php');
        comments_template('/parts/comments.php');
    ?>
</article>
