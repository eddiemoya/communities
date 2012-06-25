<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dasfisch
 * Date: 6/15/12
 * Time: 11:09 AM
 * To change this template use File | Settings | File Templates.
 */
    class UserAction {
        public $added;
        public $id;
        public $userId;

        public function __construct($userAction) {
            $this->added = (int)$userAction->action_added;
            $this->id = (int)$userAction->user_action_id;
            $this->userId = (int)$userAction->user_id;
        }
    }