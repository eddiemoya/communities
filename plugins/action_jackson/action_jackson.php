<?php
	/*
	Plugin Name: UserActions
	Plugin URL: http://wordpress.org/
	Description: Tracking of all User Actions
	Author: Sebastian Frohm
	Version: 0.1
	*/

	//include base definitions
	//include('globals/USERACTIONS.constants.php');
	@define('ACTIONJACSON_BASE_DIR', 'action_jackson/');

	@define('ACTIONJACSON_ACTIONS_DIR', 'base/');
	@define('ACTIONJACSON_INSTANCES_DIR', 'controllers/instances/');
	@define('ACTIONJACSON_VIEWS_DIR', 'views/');

	//include(USERACTIONS_ACTIONS_DIR.'UserActionsViews.actions.php');
	//include(USERACTIONS_ACTIONS_DIR.'UserActionsAdmin.actions.php');
	include('base/action_jackson_query.php');
    include('models/user_action_post.php');

    function get_actions() {
        global $current_user;
        get_currentuserinfo();

        $ajQuery = new ActionJacksonQuery();

        $ajQuery->getUserActions('posts', 'ID', $current_user->ID);
        //$ajQuery->getUserActions('term', 'term_id', $current_user->ID, null, null, null, 'category');
        //$ajQuery->addUserAction(4, 'term', 'upvote', 'something', 1);
    }

//    add_action('init', 'get_actions');

    add_filter( 'posts_clauses', array('ActionJacksonQuery', 'getUserActions'), 2, 3);
    add_filter('posts_results', 'afterwards');
    add_filter('pre_get_posts', 'dont_suppress_filters');

function afterwards($posts) {
    $existingIds = array();

    foreach($posts as $post) {
        $actions[] = new UserActionPost($post);
    }

    foreach($posts as $key=>$post) {
        if(!in_array($post->ID, $existingIds)) {
            $existingIds[] = $post->ID;
        } else {
            unset($posts[$key]);
        }
    }

    foreach($posts as $key=>$post) {
        $newOrder[] = $post;
    }

    $posts = $newOrder;

    foreach($posts as $post) {
        foreach($actions as $action) {
            if($action->objectId == $post->ID) {
                $post->actions[] = $action;
            }
        }
    }

    return $posts;
}

function dont_suppress_filters($query){
    $query->query_vars['suppress_filters'] = false;
    return $query;
}

function addUserAction() {
    $ajQuery = new ActionJacksonQuery();

    $result = $ajQuery->addUserAction((int)$_POST['id'], $_POST['type'], $_POST['userAction'], $_POST['subtype'], (int)$_POST['user']);

    echo json_encode($result);
    exit;
}

add_action('wp_ajax_add_user_action', 'addUserAction');
add_action('wp_ajax_nopriv_add_user_action', 'addUserAction');


