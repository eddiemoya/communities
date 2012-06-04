<?php
    class UserActionQuery extends wpdb {
        public function __construct() {
            global $table_prefix;

            parent::__construct(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

            $this->set_prefix($table_prefix);

            $this->tables = array_merge($this->tables, array($this->prefix.'post_action', $this->prefix.'user_actions'));
        }

        public function addUserAction(
                                    int $objId,
                                    string $objType,
                                    string $action,
                                    string $objSubType='',
                                    int $userId=1) {
            if((int)$objId <= 0 || is_null($objId)) {
                Throw new Exception('You need to pass an object ID!');
            }

            if((string)$objType === '' || is_null($objType)) {
                Throw new Exception('You need to pass an object ID!');
            }

            $args = array(
                'user_id' => $userId,
                'action_type' => $action,
                'object_type' => $objType,
                'object_id' => $objId,
                'object_subtype' => $objSubType,
                'action_added' => strtotime('now')
           	);

            $result = $this->insert($this->prefix.'user_actions', $args);
        }

        public function updateUserAction(
                                        string $actionId,
                                        int $objId=0,
                                        string $objType='',
                                        string $objSubType='',
                                        string $action='') {
            $args = array(
                'action_type' => $action,
                'object_type' => $objType,
                'object_subtype' => $objSubType,
                'object_id' => $objId
           	);

            //$this->update( $table, $data, $where, $format = null, $where_format = null );
            $result = $this->update($this->prefix.'user_actions', $args, array('user_action_id' => $actionId));
        }
    }