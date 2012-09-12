<?php 
    if(!is_ajax() && is_widget()->template == "featured") :
        get_template_part('parts/header', 'widget');
    elseif(!is_ajax()) :
        get_template_part('parts/header', 'results-list');
    endif;
    if (!have_posts()) :
?>
        <section class="no-results">
            <p class="header">Sorry, We could not find any matches for "<?php echo $s; ?>"</p>
            <p>Please try your search again</p>
            
            <p class="header">Search Tips:</p>
            <ul class="bullets">
                <li>Double check your search for typos or spelling errors.</li>
                <li>Try one of the suggested keywords displayed when you type in the search field.</li>
                <li>Try using a different word or more general word or phrase.</li>
                <li>If you searched an acronym, try using the full name.</li>
            </ul>
        </section>

<?php
    endif;
    
    # Only show pagination if there are enough posts
    if ( $wp_query->post_count > $wp_query->query_vars['posts_per_page'] ) :
?>
        <section class="pagination">
             <?php echo posts_nav_link(); ?>
        </section>

<?php 
    endif;
    
    if(is_widget()->template == "featured") :
        get_partial("parts/post-featured-post", array("widget" => is_widget()));
    else :
        loop('post-results-list');
    endif;
    
    # Only show pagination if there are enough posts
    if ( $wp_query->post_count > $wp_query->query_vars['posts_per_page'] ) :
?>
        <section class="pagination">
             <?php echo posts_nav_link(); ?>
        </section>

<?php
    endif;
    if(!is_ajax())
        get_template_part('parts/footer', 'widget');

?>
