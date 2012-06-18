<?php defined('SHCSSO_PATH') or die('Cannot be accessed directly');
/**
 *
 * @package Shcsso
 * @category Hooks
 * @version $id$
 */
class Shcsso_Hooks_Router {

    public static function execute()
    {
        add_action('wp_logout', array(Shcsso_Plugin::factory('Shcsso_Controller_Sso'), 'logout'));
    }

}
