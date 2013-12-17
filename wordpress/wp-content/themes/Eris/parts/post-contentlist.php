        <article class="content-item span12">
            <div class="post-data span7">
                <p class="post-title"><a href="<?php the_permalink(); ?>"><?php the_truncated_title(); ?></a></p>
                <p class="byline">By: <?php echo get_the_author(); ?></p>
                <p class="comments-count"><?php comments_number(); ?></p>
            </div>
            <time class="content-date post-date span5" datetime="<?php the_time('Y-m-d'); ?>">
            	<?php the_time('F j, Y'); ?>
            </time>
        </article>