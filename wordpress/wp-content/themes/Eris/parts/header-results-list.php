<?php  ///echo "<pre>";print_r(is_widget('show_filters'));echo "</pre>";
	if(is_widget('query_type') == 'users'){
		$post_type = 'users';
	} else {
		$queried_type = get_query_var('post_type');
		$post_type = (!is_array($queried_type) || !isset($queried_type[1])) ? $queried_type : '';
		$post_type = (is_array($post_type)) ? $post_type[0] : $post_type;
	}

	$class = ($post_type == 'users') ? '-users' : '-posts';
	
if(have_posts() || !is_widget()) : ?>

<?php if(is_widget('show_filters') || is_widget('show_sort')) : ?>

	<header class="content-header">
		<form method="post" action="">
			<?php if(is_widget('show_filters') || !is_widget()) : ?>
				<label for="filter-results<?php echo $class; ?>">View</label>
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
						'show_option_all' => 'All Categories',
						'depth'=> 1,
						'selected' => $category,
						'hierarchical' => true,
						'hide_if_empty' => true,
						'orderby'	=> 'name',
						'order'	=> 'ASC',
						//'class' => 'input_select',
						'class' => 'filter-results'.$class,
						'name' => 'filter-category',
						'id' => 'filter-results'
					));
					if(!empty($subcategory)){
						wp_dropdown_categories(array(
							'show_option_all' => 'All Categories',
							'depth'=> 1,
							'selected' => $subcategory,
							'child_of' => $category,
							'hierarchical' => true,
							'hide_if_empty' => true,
							'orderby'	=> 'name',
							'order'	=> 'ASC',
							'class' => '',
							'name' => 'filter-sub-category',
							'id' => 'sub-category'
						));
					} ?>
			<?php else: ?>
				<input type="hidden" value="<?php echo get_queried_object()->term_id; ?>" name="filter-category" />
			<?php endif; //if is_widget('show_filters') ?>

			<?php if(is_widget('show_sort') || !is_widget()) : ?>

				<label for="sort-results<?php echo $class; ?>">Sort by</label>
				<select name="sort-results" id="sort-results" class="sort-results<?php echo $class; ?>">
					<option value="DESC"><?php echo ( $post_type!="users" ) ? "Newest" : "Z - A"; ?></option>
					<option value="ASC"><?php echo ( $post_type!="users" ) ? "Oldest" : "A - Z"; ?></option>
					<?php if($post_type!="users"): ?>
						<option value="comment_count">Most Responses</option>
					<?php endif; ?>
				</select>

			<?php endif; ?>

			<input type="hidden" value="results-list" class="widget_name" name="widget" />
			<input type="hidden" value="<?php echo $post_type; ?>" class="post_type" name="post_type" />
		</form>
	</header>
<?php endif; ?>

<section class="content-body">

<?php endif; ?>

