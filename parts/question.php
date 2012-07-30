<!--/Users/emoya1/Public/Projects/comm/wordpress/wp-content/themes/Eris/parts/post-widget.php -->
<article class="content-container span12">
    <div class="breadcrumbs">
        Breadcrumbs
    </div>
    <div class="single span8">
        <?php
            $i = 0;

            $categories = get_the_category($post->ID);
        ?>
        <a href="<?php get_category_link($categories[0]->term_id); ?>"><?php echo $categories[0]->name; ?></a>
        <h2><?php echo the_title(); ?></h2>
        <div class="content">
            <p><?php echo the_content(); ?></p>
            <p class="by">By <?php the_author(); ?></p>
            <p class="tags">Tags:
                <?php
                    $posttags = get_the_tags();
                    if ($posttags) {
                        foreach($posttags as $tag) {
                            echo '<a href="'.bloginfo().'">'.$tag->name.'</a>';
                        }
                    }
                ?>
            </p>
            <!-- Insert social here -->
        </div>
        <div class="comments">
            GETTING COMMENTS
            <?php comments_template('/parts/comments.php'); ?>
        </div>
    </div>
</article>