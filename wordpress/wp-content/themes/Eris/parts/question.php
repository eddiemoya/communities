<!--/Users/emoya1/Public/Projects/comm/wordpress/wp-content/themes/Eris/parts/post-widget.php -->
<article class="content-container span12">
    <div class="breadcrumbs">
        Breadcrumbs
    </div>
    <div class="single span8">
        <?php
            $i = 0;

            $categories = get_the_category($post->ID);
            $catCount = count($categories);

            foreach($categories as $category) :
                if($i < ($catCount - 1)):
        ?>
                    <a href="<?php get_category_link($category->term_id); ?>"><?php echo $category->name; ?> > </a>
        <?php
                else:
        ?>
                    <a href="<?php get_category_link($category->term_id); ?>"><?php echo $category->name; ?></a>
        <?php
                endif;

                $i++;
            endforeach;
        ?>
        <h2><?php echo the_title(); ?></h2>
    </div>
</article>