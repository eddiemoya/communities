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
    include('models/post_action.php');
    include('models/user_action.php');

    function get_actions() {
        global $current_user;
        get_currentuserinfo();

        $ajQuery = new ActionJacksonQuery();

        $ajQuery->getUserActions('posts', 'ID', $current_user->ID);
        //$ajQuery->getUserActions('term', 'term_id', $current_user->ID, null, null, null, 'category');
        //$ajQuery->addUserAction(4, 'term', 'upvote', 'something', 1);
    }

//    add_action('init', 'get_actions');

    add_filter('posts_results', 'getUserActions');
    //add_filter('pre_get_posts', 'dont_suppress_filters');

function getUserActions($posts) {
    global $current_user;

    $existingIds = array();
    $userId = (is_user_logged_in()) ? $current_user->ID : '1';

    /**
     * Check to see if there is anything in the $_GET
     */
    $actionType = (isset($_GET['action']) && !empty($_GET['action'])) ? $_GET['action'] : null;
    $page = (isset($_GET['paged']) && !empty($_GET['paged'])) ? $_GET['paged'] : 1;

    foreach($posts as $post) {
        $ids[] = $post->ID;

        //$actions[] = new UserActionPost($post);
    }
//    public function getPostAction($object_type, $object_id, $post_action_id=null, $object_sub_type=null, $action_type=null, $limit=10, $page=1) {
    $ajQuery = new ActionJacksonQuery();
    $postActions = $ajQuery->getPostAction('posts', $ids, null, null, null, null, null, false);

    $ids = array();

    foreach($postActions as $postAction) {
        $ids[] = $postAction->post_action_id;

        $actions[] = new PostAction($postAction);
    }

    $userActions = $ajQuery->getUserActions($ids, $userId, $page, 10);

    foreach($userActions as $userAction) {
        foreach($actions as $action) {
            if($action->id == $userAction->object_id) {
                $action->user = $userAction;
                $action->user = new UserAction($userAction);
            }
        }
    }

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


function asdf($user) {
    echo 'in asdf <pre>';
    var_dump($user);
    exit;
}

add_filter('pre_user_query', 'asdf');

