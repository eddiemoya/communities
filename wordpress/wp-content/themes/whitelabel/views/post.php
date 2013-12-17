<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    
    <div class="post-thumbnail">
        <?php if(had_post_thumbnail()) { get_post_thumbnail(); } ?>
    </div>

    
    <h2 class="post-title">
        <a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
            <?php the_title(); ?>
        </a>
    </h2>
    
    <!-- Display the post meta date (November 16th, 2009 format) and a link to other posts by this posts author. -->
    <div class="post-meta">
        <span class="post-author">
            by: <?php the_author_posts_link(); ?> 
        </span>
        
        <span class="post-time">
             on <?php the_time('F jS, Y'); ?> | <?php the_time('g:i a'); ?>
        </span>
    </div>
    
    
    <div class="post-content">
        <?php (!is_singular()) ? the_excerpt() : the_content(); ?>
    </div>
    

    <div class="post-footer">
        
        <!-- Optional widget section in the post footer-->
        <?php if (!dynamic_sidebar('Post Footer')) : ?>

            <span class="permalink">
                <a href="<?php the_permalink(); ?>">Permalink</a>
            </span>

            <?php if (!is_single()): ?>

                <span class="comments-link">
                    <?php comments_popup_link('Comments(0)', 'Comments(1)', 'Comments(%)'); ?>
                </span>

            <?php endif; ?>

        <?php endif; ?>
    </div>
    
</div>