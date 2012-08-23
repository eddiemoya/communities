<?php 
    get_template_part('parts/header', 'widget');
    $category = get_category_by_slug(is_widget()->category);
?>
    <section class="content-body content-list clearfix">
        <?php loop('post', 'contentlist'); ?>

        <?php if($category) : ?>
            <div class="more-link"><a href="<?php echo get_category_link($category->term_id); ?>">See All <?php echo $category->name; ?> Articles</a></div>
        <?php endif; ?>

    </section>
    
<?php get_template_part('parts/footer', 'widget') ;?>