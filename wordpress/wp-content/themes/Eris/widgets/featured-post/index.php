<?php get_template_part('parts/header', 'widget'); ?>


<?php if (have_posts()) { while (have_posts()) { the_post(); ?>

<section class="span6">
    <article class="content-container question">

        <section class="content-body clearfix">
   
            <?php get_partial( 'parts/crest', array( "user_id" => get_the_author_meta('ID'), "width" => 'span3' ) ); ?>

            <div class="span9">

                <div class="content-details clearfix">
                    <time class="content-date" pubdate="" datetime="<?php the_time('Y-m-d'); ?>">
                        <?php the_time('F jS, Y'); ?>
                        <span class="time-stamp">
                            <?php the_time('g:ia'); ?>
                        </span>
                    </time>
                </div>
                <h1 class="content-headline">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h1>

                <?php the_excerpt(); ?>

                <ul class="content-comments">
                    <li class="content-comments"><?php echo $answer_count . ' ' . _n( 'answer', 'answers', $answer_count ); ?> | <?php comments_number('0 replies', '1 reply', '% replies'); ?> | <?php echo $comment_count . ' ' . _n( 'comment', 'comments', $user->comment_count ); ?></li>
                </ul>

            </div>

        </section>

    </article>

</section>

<?php }} ?>

<?php get_template_part('parts/footer', 'widget');

