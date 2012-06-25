<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dasfisch
 * Date: 6/15/12
 * Time: 11:09 AM
 * To change this template use File | Settings | File Templates.
 */
    class PostAction {
        public $action;
        public $id;
        public $lastModified;
        public $objectId;
        public $objectType;
        public $objectSubType;
        public $total;
        public $user;

        public function __construct($postAction) {
            $this->action = $postAction->action_type;
            $this->id = (int)$postAction->post_action_id;
            $this->lastModified = (int)$postAction->last_modified;
            $this->objectId = (int)$postAction->object_id;
            $this->objectType= $postAction->object_type;
            $this->objectSubType = $postAction->object_subtype;
            $this->total = (int)$postAction->action_total;

            $this->user = null;
        }
    }