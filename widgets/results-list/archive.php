
<header class="content-header">
	<form method="post" action="<?php echo (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]==”on”) ? "https://" : "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>">
		<label for="sort-results">Sort By</label>
			<?php 
			wp_dropdown_categories(array(
				'depth'=> 1,
				'selected' => get_queried_object()->term_id,
				'hierarchical' => true,
				'hide_if_empty' => true,
				'class' => 'input_select',
				'name' => 'category',
				'id' => 'sort-results'
			));
			wp_dropdown_categories(array(
				'depth'=> 1,
				'child_of' => get_queried_object()->term_id,
				'hierarchical' => true,
				'hide_if_empty' => true,
				'class' => 'input_select',
				'name' => 'sub-category',
				'id' => 'sub-category'
			));
									?>
		<input type="submit" value="submit" name="submit" />
	</form>
</header>
<ol class="content-body">
	<?php loop_by_type('results-list'); ?>
</ol>

