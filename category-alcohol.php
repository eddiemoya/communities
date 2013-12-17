<?php
/**
 * @package WordPress
 * @subpackage White Label
 */
get_template_part('parts/header');
//print_pre($wp_query->query);
//print_pre(get_queried_object());
$args = array(
    "posts_per_page" => 5,
    "cat" => get_queried_object()->term_taxonomy_id,
    "paged" => get_query_var("paged")
);
query_posts($args);
//while (have_posts()) : the_post();
loop('question');
//endwhile;
?>

<?php
get_template_part('parts/footer');
?>