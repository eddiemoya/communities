<?php
    class ActionJacksonQuery {
        private $_wpdb;

        public function __construct() {
            global $wpdb;

            $this->_wpdb = $wpdb;

            $this->_wpdb->tables = array_merge($this->_wpdb->tables, array($this->_wpdb->prefix.'post_action', $this->_wpdb->prefix.'user_actions'));
        }

        public function addUserAction(
                                    $objId,
                                    $objType,
                                    $action,
                                    $objSubType=null,
                                    $userId=null) {
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

            $result = $this->_wpdb->insert($this->_wpdb->prefix.'user_actions', $args);
        }

        public function updateUserAction(
                                        $actionId,
                                        $objId=null,
                                        $objType=null,
                                        $objSubType=null,
                                        $action=null) {
            $args = array(
                'action_type' => $action,
                'object_type' => $objType,
                'object_subtype' => $objSubType,
                'object_id' => $objId
           	);

            //$this->update( $table, $data, $where, $format = null, $where_format = null );
            $result = $this->_wpdb->update($this->_wpdb->prefix.'user_actions', $args, array('user_action_id' => $actionId));
        }

        public function getUserActions($object_type, $object_type_id_key_name, $user_id=1, $user_action_id=null, $action_type=null, $object_id=null, $object_subtype=null, $limit=10, $page=1) {
            global $wp_query;
            echo '<pre>';
            var_dump($wp_query);
            echo '</pre>';

            if(
                ((isset($objId) && is_int($objId) && $objId > 0 ) && (!isset($objType) || is_null($objType) || $objType == ''))
                ||
                ((!isset($objId) ||!is_int($objId) || $objId <= 0) && (isset($objType) && is_string($objType) && $objType != ''))
            ) {
                Throw new Exception('If you want to get a user\'s action based an object, you need to pass in an object type and object ID!');
            }

            /**
             * This must be here, otherwise other non-argument variables will be included.
             */
            $args = get_defined_vars();

            unset($args['limit']);
            unset($args['object_type_id_key_name']);
            unset($args['page']);

            foreach($args as $key=>$arg) {
                if(is_null($arg) || empty($arg)) {
                    unset($args[$key]);
                }
            }

            $argCount = count($args);

            $startLimit = ($page * $limit) - $limit;

            $query = 'SELECT
                            ua.*, '.$object_type.'.*
                        FROM
                            '.$this->_wpdb->prefix.'user_actions'.' ua
                        JOIN
                            '.$this->_wpdb->prefix.''.$object_type.' '.$object_type.'
                                ON
                                    ua.object_id='.$object_type.'.'.$object_type_id_key_name.'
                        WHERE ';

            foreach($args as $key=>$arg) {
                if(!is_null($arg) && !empty($arg)) {
                    $arg = is_string($arg) ? '"'.$arg.'"' : $arg;

                    $query .= ($i < ($argCount - 1)) ? 'ua.'.$key.'='.$arg.' AND ' : 'ua.'.$key.'='.$arg;

                    $i++;
                }
            }

            $query .= ' LIMIT '.$startLimit.','.$limit;

            $results = $this->_wpdb->get_results($query);

            //create  model
            //return model
        }

        private function _addPostAction($objId, $objType, $action) {

        }
    }