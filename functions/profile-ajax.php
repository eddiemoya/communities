<?php
if(is_author()) {
 //Include javascript

}

function profile_paginate() {
	
	$type = $_POST['type'];
	$page = $_POST['page'];
	
	
}

add_action('wp_ajax_profile_paginate', 'profile_paginate');
add_action('wp_ajax_nopriv_profile_paginate', 'profile_paginate');