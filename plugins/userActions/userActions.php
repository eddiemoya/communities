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
	@define('USERACTIONS_BASE_DIR', 'userActions/');

	@define('USERACTIONS_ACTIONS_DIR', 'base/');
	@define('USERACTIONS_INSTANCES_DIR', 'controllers/instances/');
	@define('USERACTIONS_VIEWS_DIR', 'views/');
	
	//include(USERACTIONS_ACTIONS_DIR.'UserActionsViews.actions.php');
	//include(USERACTIONS_ACTIONS_DIR.'UserActionsAdmin.actions.php');
	include('base/UserActionQuery.php');

    $uaQuery = new UserActionQuery();
//
//        public function updateUserAction($actionId,
//                                        $objId=0,
//                                        $objType='',
//                                        $objSubType='',
//                                        $action='') {

    $uaQuery->updateUserAction(
        8,
        12,
        'term',
        'something',
        'downvote'
    );