<?php
    $alinks = array(
        'post'     => site_url('posts'),
        'question' => get_post_type_archive_link('question'),
        'guide'    => get_post_type_archive_link('guide'),
        'reviews'  => (get_page_by_path( 'reviews' )) ? get_permalink(get_page_by_path( 'reviews' )) : null   );
    $terms = array(
        'question' => get_terms_by_post_type('category', array('post_type' => 'question')),
        'post'     => get_terms_by_post_type('category', array('post_type' => 'post')),
        'guide'    => get_terms_by_post_type('category', array('post_type' => 'guide')),
    );
    $labels = array (
        'question' => "Q&A's",
        'post'     => 'Blog Posts',
        'guide'    => 'Guides',
        'reviews'   => 'Reviews'
    );
    
   
    if(is_null($alinks['reviews'])){
        unset($labels['reviews']);
        unset($alinks['reviews']);
    }

?>

<nav id="navigation">
    <ul id="header_nav" class="dropmenu clearfix">

        <li class="right_button">
            <a href="<?php echo get_category_link( get_category_by_path( 'customer-care' ) ); ?>">Customer Care</a>
        </li>

        <li>
            <a href="<?php echo site_url(); ?>"><span>Categories</span></a>
            <ul>
                <?php wp_list_categories(array('parent' => 0, 'hide_empty' => true, 'depth' => 1, 'title_li'=>'', 'order'=>'ASC')); ?>
            </ul>
        </li>

        <?php foreach($labels as $post_type => $label) : ?>
        <li>
            <a href="<?php echo $alinks[$post_type]; ?>"><span><?php echo $label; ?></span></a>
            <?php if(isset($terms[$post_type])) : ?>
            <ul>
                <?php foreach($terms[$post_type] as $term) : ?>
                    <li>
                        <a href="<?php echo esc_url( get_category_link($term->term_id) ).$post_type; ?>">
                            <?php echo $term->name; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li> <a href="<?php echo $alinks[$post_type]; ?>">All <?php echo $label; ?></a></li>
            </ul>
            <?php endif; ?>
        </li>
        <?php endforeach;?>
    </ul>
</nav>

