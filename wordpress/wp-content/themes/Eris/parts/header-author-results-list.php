<article class="content-container result-list">
<header class="content-header author-result-list">
    <?php
        foreach ($variables as $user) :
            if ($user->terms != "") :
                $cats = explode(",", $user->terms);
                foreach ($cats as $cat) {
                    $include_terms[$cat] = true;
                }
            endif;
        endforeach;
        
        $terms = get_terms( 'category' );
        
        foreach ($terms as $term) :
            if ($include_terms[$term->term_id] != true) :
                $exclude_terms .= $term->term_id.",";
            endif;
        endforeach;
    ?>
	<form method="post" action="">
		<label for="sort-results">Sort by</label>
			<?php 
			// Get the normal queried category term id.
			$category = get_queried_object()->term_id;
			$subcategory = null;

			// Check if either category or sub-category have been passed as get or post variables.
			if( isset($_REQUEST['filter-category']) || isset($_REQUEST['filter-sub-category']) ) {
				$category = (isset($_REQUEST['filter-category'])) ? absint($_REQUEST['filter-category']) : '';
				$subcategory = (isset($_REQUEST['filter-sub-category'])) ? absint($_REQUEST['filter-sub-category']) : '';

			//If not..
			} else {
				//... get the current term object (as opposed to just the ID above)...
				$term_object = get_the_category();
				//...and see if its parent is empty.
				if( !empty($term_object[0]->parent) ){

					//... If its not empty, then were in a subcategory, and we need to set the two vars accordingly.
					$subcategory = $term_object[0]->term_id;
					$category = $term_object[0]->parent;
				}
			}

			wp_dropdown_categories(array(
				'depth'=> 1,
				'selected' => $category,
				'hierarchical' => true,
				'hide_if_empty' => true,
				'orderby'	=> 'name',
				'order'	=> 'ASC',
				'class' => 'input_select',
				'name' => 'filter-category',
				'id' => 'sort-results',
				'exclude' => $exclude_terms,
				'show_option_none' => "Show All"
			));
			if(!empty($subcategory)){
				wp_dropdown_categories(array(
					'depth'=> 1,
					'selected' => $subcategory,
					'child_of' => $category,
					'hierarchical' => true,
					'hide_if_empty' => true,
					'orderby'	=> 'name',
					'order'	=> 'ASC',
					'class' => '',
					'name' => 'filter-sub-category',
					'id' => 'sub-category',
					'exclude' => $exclude_terms,
					'show_option_none' => "Show All"
				));
			}
			?>
		<input type="hidden" value="results-list" name="widget" />
		<input type="hidden" value="author-filter" name="specificity" />
	</form>
</header>
<ol class="content-body">