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

    $ajQuery = new ActionJacksonQuery();

    $ajQuery->getUserActions(1, 'posts', 'ID');

//
//        public function updateUserAction($actionId,
//                                        $objId=0,
//                                        $objType='',
//                                        $objSubType='',
//                                        $action='') {

    //$uaQuery->updateUserAction(4, 12, 'term', 'something', 'downvote');