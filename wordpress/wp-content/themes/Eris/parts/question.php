<article class="content-container span12">
    <div class="breadcrumbs">
        Breadcrumbs
    </div>
    <div class="single span8">
        <?php
            $i = 0;

            $categories = get_the_category($post->ID);
        ?>
        <div class="top">
            <a href="<?php get_category_link($categories[0]->term_id); ?>"><?php echo $categories[0]->name; ?></a>
            <span class="date"><?php echo the_date(); ?></span>
        </div>
        <div class="content">
            <h2><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h2>
            <p><?php echo the_content(); ?></p>
            <p class="by">By <?php the_author_link(); ?></p>
            <p class="tags">Tags:
                <?php
                    $posttags = get_the_tags();
                    if ($posttags) {
                        foreach($posttags as $tag) {
                            echo '<a href="'.get_bloginfo('siteurl').'">'.$tag->name.'</a> ';
                        }
                    }
                ?>
            </p>
            <!-- Insert social here -->
        </div>
        <div class="comments">
            <?php
                comments_template('/parts/commentForm.php');
                comments_template('/parts/comments.php');
            ?>
        </div>
    </div>
</article>