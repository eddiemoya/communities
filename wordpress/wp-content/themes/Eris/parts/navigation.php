<?php
    $alinks = array(
        'post'     => site_url('tips-ideas'),
        'question' => get_post_type_archive_link('question'),
        //'guide'    => get_post_type_archive_link('guide'),
        'reviews'  => (get_page_by_path( 'reviews' )) ? get_permalink(get_page_by_path( 'reviews' )) : null  
		//'tips'		=> get_post_type_archive_link('tips')
	);
    $terms = array(
        'question' => get_terms_by_post_type('category', array('post_type' => 'question')),
        'post'     => get_terms_by_post_type('category', array('post_type' => 'post')),
        //'guide'    => get_terms_by_post_type('category', array('post_type' => 'guide')),
        //'tips'    => get_terms_by_post_type('category', array('post_type' => 'tips'))
    );
    $labels = array (
        'question' => "Q&A's",
        'post'     => 'Tips & Ideas',
        //'guide'    => 'Guides',
		//'tips'		=> "Tips & Ideas",
        'reviews'   => 'Reviews'
    );
    
   
    if(is_null($alinks['reviews'])){
        unset($labels['reviews']);
        unset($alinks['reviews']);
    }

?>

<nav id="navigation">
    <ul id="header_nav" class="dropmenu clearfix">
        
		<li>
            <a href="<?php echo site_url(); ?>"><span>Communities</span></a>
            <ul>
                <?php wp_list_categories(array('parent' => 0, 'hide_empty' => true, 'depth' => 1, 'title_li'=>'', 'order'=>'ASC')); ?>
            </ul>
        </li>
		
		<li>
			<a href="<?php if (function_exists("bbp_forums_url")) bbp_forums_url(); ?>">Forums</a>
			<div id="forum_tooltip">				
				<div class="close">X</div>
				<div class="tick"></div>
				<div class="banner_new"></div>
				
				<p>Welcome to <b>Forums</b>, the newest feature in the <?php echo ucfirst(theme_option("brand")); ?> Community.</p>
				<form id="new_modal_form">
					<p>
						<input type="checkbox" id="message_hide" name="message_hide"/><label for="message_hide">Don't show this message again.</label>
					</p>
				</form>
			</div>
		</li>

        <?php foreach($labels as $post_type => $label) : ?>
        <li>
            <?php if(isset($terms[$post_type])) : ?>
            <a href="<?php echo $alinks[$post_type]; ?>"><span><?php echo $label; ?></span></a>
            <ul>
                <?php foreach($terms[$post_type] as $term) : ?>
                    <li>
                        <a href="<?php echo esc_url( get_category_link($term->term_id) ). $post_type; ?>">
                            <?php echo $term->name; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li> <a href="<?php echo $alinks[$post_type]; ?>">All <?php echo $label; ?></a></li>
            </ul>
            <?php else: ?>
			<a href="<?php echo $alinks[$post_type]; ?>"><?php echo $label; ?></a>
			<?php endif; ?>
        </li>
        <?php endforeach;?>
    </ul>
</nav>

