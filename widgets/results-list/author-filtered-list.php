<?php
if(!is_ajax())
    get_partial('parts/header-author-results-list', $users);

foreach($users as $user){
	$user = get_userdata($user->ID);
	get_partial('parts/author', $user);
}

if(!is_ajax())
	get_template_part('parts/footer', 'widget') ;

;