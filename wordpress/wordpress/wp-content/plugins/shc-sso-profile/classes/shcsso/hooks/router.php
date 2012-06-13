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
        add_action('send_headers', array(__CLASS__, 'route'));
    }

    public static function route()
    {
        global $wp_query;

        preg_match('/sso(\/[^\/]+\/?)$/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) , $matches);

        if ( ! empty($matches))
        {
            $wp_query->is_404 = FALSE;

            if (isset($matches[1]))
            {
                $action = trim(strtolower(trim($matches[1])), '/');
            }
            else
            {
                $action = 'login';
            }

            try
            {
                call_user_func(array(Shcsso_Plugin::factory('Shcsso_Controller_Sso'), $action));
                die;
            }
            catch(\Exception $e)
            {
                $wp_query->is_404 = TRUE;
            }
        }
    }

}
