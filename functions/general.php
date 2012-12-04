<?php
    function get_google_analtyics_id() {
        $option = get_option('Yoast_Google_Analytics');

        echo $option['uastring'];
    }
    

/**
 * select_it() - This function is used exclusively with WP Polls. It accepts input string of label and 
 * checkbox or radio button as used and formatted in polls and adds the checked attribute to the input.
 * 
 * @param string $input
 * @return string - input with the checked attribute added.
 * @author Dan Crimmins
 * 
 * @see comm_display_pollvote() -- located in filters-hooks.php. This is the only file where this function
 * is being used.
 * 
 */
function select_it($input) {
	
	$pos = (strrpos($input, '">') !== false) ? (strrpos($input, '">') + 1) : false;

	if($pos !== false) {
		
		$new_input = substr_replace($input, ' checked="checked"', $pos, 0);
	
		return $new_input;
	}
	
	return $input;
	
}

