<?php
    get_template_part('parts/header', 'widget');

    if (have_posts()) {
        global $excerptLength;

        $excerptLength = 140;

        while (have_posts()) {
            the_post();
?>
<li class="featured">
    <section class="column clearfix">
        <?php get_partial( 'parts/crest', array( "user_id" => get_the_author_meta('ID'), "width" => 'span4' ) ); ?>
        <div class="span8 info content-details">
            <time class="content-date" datetime="<?php the_time('Y-m-d'); ?>">
                <?php the_time('F jS, Y g:ia'); ?>
            </time>
            
            <ul class="content-comments">
                <li><?php comments_number(); ?></li>
            </ul>
            
            <h4 class="content-headline">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
        </div>

    </section>
</li>
<?php
        } ?>
        <section class="pagination">
             <?php echo posts_nav_link(); ?>
        </section>
  <?php  }

    get_template_part('parts/footer', 'widget');

