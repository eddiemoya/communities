<?php
    function get_google_analtyics_id() {
        $option = get_option('Yoast_Google_Analytics');

        echo $option['uastring'];
    }
    
    
function select_it($input) {
	
	$pos = (strrpos($input, '">') !== false) ? (strrpos($input, '">') + 1) : false;

	if($pos !== false) {
		
		$new_input = substr_replace($input, ' checked="checked"', $pos, 0);
	
		return $new_input;
	}
	
	return $input;
}

function is_selected($poll_cookie) {
	
	
}