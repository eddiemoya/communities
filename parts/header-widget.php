<!-- <article class="content-container featured-<?php //echo $wp_query->post->post_type ?> <?php //echo (!is_single())? 'columns' : '';?>">
 -->
        <?php if (is_widget()->show_title && !empty(is_widget()->widget_title)) : ?>

            <header class="content-header">
                <h3><?php echo is_widget()->widget_title; ?></h3>

                <?php if(is_widget()->show_subtitle && is_widget()->widget_subtitle != "") : ?>
                    <h4><?php echo is_widget()->widget_subtitle; ?></h4>
                <?php endif; ?>
            </header>


        <?php endif; ?>


        <!-- << echo (is_single()) ? 'section' : 'ol'; ?> class="content-body clearfix"> -->
        <section class="content-body clearfix">
