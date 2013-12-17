<?php

/**
 * Allow rapid registration for new nav menu items.
 * Just instantiate an object and pass the last part of the id
 */
class Communities_Nav_Menu_Field {

	public $field_name;

	public function __construct($field_name){
		$this->field_name = $field_name;
		$this->add_actions();
	}

	public function add_actions(){
		add_action( 'wp_update_nav_menu_item', 	array( $this, 'nav_update')			,10, 3);
		add_filter( 'wp_setup_nav_menu_item', 	array( $this, 'nav_item' )			);
		add_filter( 'wp_edit_nav_menu_walker', 	array( $this, 'nav_edit_walker') 	,10,2 );
	}

	/*
	 * Saves new field to postmeta for navigation
	 */
	function nav_update($menu_id, $menu_item_db_id, $args ) {
	    if ( is_array($_REQUEST["menu-item-{$this->field_name}"]) ) {
	        $value = $_REQUEST["menu-item-{$this->field_name}"][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, "_menu_item_{$this->field_name}", $value );
	    }
	}

	/*
	 * Adds value of new field to $item object that will be passed to     Walker_Nav_Menu_Edit_Custom
	 */
	function nav_item($menu_item) {
		$field_name = $this->field_name;
	    $menu_item->$field_name = get_post_meta( $menu_item->ID, "_menu_item_{$this->field_name}", true );
	    return $menu_item;
	}

	
	function nav_edit_walker($walker,$menu_id) {
	    return 'Communities_Walker_Nav_Menu_Edit';
	}
}

$image = new Communities_Nav_Menu_Field('image');