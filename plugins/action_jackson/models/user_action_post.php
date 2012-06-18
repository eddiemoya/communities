<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dasfisch
 * Date: 6/15/12
 * Time: 11:09 AM
 * To change this template use File | Settings | File Templates.
 */
    class UserActionPost {
        public $action;
        public $id;
        public $lastModified;
        public $objectId;
        public $objectType;
        public $objectSubType;
        public $total;
        public $userActionId;
        public $userId;

        public function __construct($post) {
            $this->action = $post->action_type;
            $this->id = $post->post_action_id;
            $this->lastModified = $post->last_modified;
            $this->objectId = $post->ID;
            $this->objectType= $post->object_type;
            $this->objectSubType = $post->asdf;
            $this->total = $post->action_total;
            $this->userActionId = $post->user_action_id;
            $this->userId = $post->user_id;
        }
    }