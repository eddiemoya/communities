
<?php
if(!is_ajax())
	get_template_part('parts/header', 'results-list');


foreach($users as $user){
	$user = get_userdata($user->ID);
	get_partial('parts/author', $user);
}

if(!is_ajax())
	get_template_part('parts/footer', 'widget') ;

;