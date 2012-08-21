<?php
$alinks = array(
    'post' => site_url('posts'),
    'question' => get_post_type_archive_link('question'),
    'guide' => get_post_type_archive_link('guide')
    );

  $terms = array(
    "question"  => get_terms_by_post_type('category', 'question'),
    "post"    => get_terms_by_post_type('category', 'post'),
    "guide" => get_terms_by_post_type('category', 'buying-guide'),
  );

  $labels = array (
    'question' => "Q&A's",
    'post' => 'Blog Posts',
    'guide' => 'Buying Guides'
    );

?>

<nav id="navigation">
    <ul id="header_nav" class="dropmenu clearfix">

        <li class="right_button">
            <a href="<?php echo '#'; ?>">Customer Care</a>
        </li>

        <li>
            <a href="<?php echo site_url(); ?>"><span>Categories</span></a>
            <ul>
                <?php wp_list_categories(array('parent' => 0, 'hide_empty' => true, 'depth' => 1, 'title_li'=>'')); ?>
            </ul>
        </li>

        <?php foreach($labels as $post_type => $label) : ?>
        <li>
            <a href="<?php echo $alinks[$post_type]; ?>"><?php echo $label; ?></a>
            <ul>
                <?php foreach($terms[$post_type] as $term) : ?>
                    <?php if($term->parent == 0) :?>
                        <li><a href="<?php echo esc_url( get_category_link($term->term_id) ); ?>"><?php echo $term->name; ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li> <a href="<?php echo $alinks[$post_type]; ?>"><?php echo $label; ?></a></li>
            </ul>
        </li>
        <?php endforeach;?>
    </ul>
</nav>



