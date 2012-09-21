<?php 

if(!is_ajax()) {
    get_template_part('parts/header', 'results-list');
}

    $post_type = get_query_var('post_type');
    $post_type = (is_array($post_type)) ? $post_type[0] : $post_type;
    loop(array($post_type, 'post'), null, 'widgets/results-list', 'parts/no-results');
    

if(!is_ajax()){ 
    get_template_part('parts/footer', 'widget');
}?>

<section class="pagination">
     <?php echo posts_nav_link(); ?>
</section>