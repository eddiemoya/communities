<?php
/**
 * @author Eddie Moya
 * 
 * Ajax callback for getting subcategories
 */
function get_subcategories_ajax(){

    if(isset($_POST['category_id'])){
        $parent = absint((int)$_POST['category_id']);

        wp_dropdown_categories(array(
            'depth'=> 1,
            'child_of' => $parent,
            'hierarchical' => true,
            'hide_if_empty' => true,
            'class' => 'input_select',
            'name' => 'sub-category',
            'id' => 'sub-category',
            //'echo' => false
        ));
         exit;
    }
}
add_action('wp_ajax_get_subcategories_ajax', 'get_subcategories_ajax');
add_action('wp_ajax_nopriv_get_subcategories_ajax', 'get_subcategories_ajax');