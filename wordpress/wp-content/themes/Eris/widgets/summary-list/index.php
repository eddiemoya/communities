<?php get_template_part('parts/header', 'widget-questionlist'); ?>
    <section class="content-body content-list clearfix">
        <?php loop('post', 'summarylist'); ?>
    </section>
<?php get_template_part('parts/footer', 'widget') ;?>