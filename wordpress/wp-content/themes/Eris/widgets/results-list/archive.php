<?php 

if(!is_ajax()) {
    get_template_part('parts/header', 'results-list');
}

    $post_type = get_query_var('post_type');
    $post_type = (is_array($post_type)) ? $post_type[0] : $post_type;
<<<<<<< HEAD
    loop(array($post_type, 'post'), null, 'widgets/results-list', 'parts/no-results.php');

=======

    loop(array($post_type, 'post'), 'results-list', 'parts', 'parts/no-results.php');
    
>>>>>>> bce18119b202f2fe9998271be6c8d18efd284fbd
if(!is_ajax()){ 
    get_template_part('parts/footer', 'widget');

}?>

<section class="pagination">
     <?php echo posts_nav_link(); ?>
</section>
