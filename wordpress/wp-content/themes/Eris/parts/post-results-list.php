<?php 
$c = get_the_category(); 
$cat = $c[0];
?>
<li class="post lone-result clearfix">

    <div class="span2 badge">
        <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
        <h4><a href="#"><?php the_author(); ?></a></h4>
        <address>Chicago, IL</address>
    </div>

    <div class="span10">

        <time class="content-date" datetime="<?php echo date( "Y-m-d" ); ?>" pubdate="pubdate"><?php echo date( "F n, Y g:ia" ); ?></time>

        <hgroup>
            <h3 class="content-category">
                <a href="<?php get_category_link(get_query_var($cat->term_id)); ?>" title="<?php echo $cat->cat_name; ?>">
                    <?php echo $cat->cat_name; ?>
                </a>
            </h3>
            <h2 class="content-headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </hgroup>

        <article>
            <p class="excerpt"><?php the_excerpt(); ?></p>
            <p class="content-comments">x answers | y replies | <?php comments_number(); ?></p>
        </article>

        <section class="post-actions">
            <div class="flag"><a href="#"><img src="<?php echo get_template_directory_uri() ?>/assets/img/icon-flag.png" alt="Flag this post" title="Flag this post" /></a></div>
            <div class="share"><a href="#">Share</a></div>
        </section>

    </div>
</li>