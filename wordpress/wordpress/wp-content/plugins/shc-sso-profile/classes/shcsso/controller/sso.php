<?php defined('SHCSSO_PATH') or die('Cannot be accessed directly');
/**
 *
 * @package Shcsso
 * @category Hooks
 * @version $id$
 */
class Shcsso_Controller_Sso {

    public function __construct()
    {
        $this->settings = Shcsso_Plugin::option('settings');
    }

    public function check()
    {
    }

    public function login()
    {
        $auth = new Shcsso_Service_Auth();
        $auth->callback(site_url('sso/receive'));
        $auth->endpoint('https://sso.shld.net/');

        $params = array(
            'auth'      => $auth,
            'action'    => NULL,
        );

        if ( ! empty($_POST))
        {
            $auth->action('shcLogin');
            $auth->query(array(
                'loginId'       => $_POST['loginId'],
                'logonPassword' => $_POST['logonPassword'],
                'sourceSiteId'  => $_POST['sourceSiteId'],
                'service'       => $_POST['service'].'?password='.$_POST['logonPassword'],
                'renew'         => 'true',
            ));

            $auth->redirect();
        }

        echo Shcsso_Plugin::view('login/form', $params);
    }

    public function logout()
    {
        $auth = new Shcsso_Service_Auth();
        $auth->callback(site_url('sso/receive'))
            ->endpoint()
            ->action('shcLogout');
    }

    public function receive()
    {
        $auth = new Shcsso_Service_Auth();
        $auth->endpoint('https://sso.shld.net/');
        $auth->callback(site_url($_SERVER['REQUEST_URI']));
        $user = $auth->receive($_POST['ticket']);

        $email = $user->authenticationSuccess->user;
        $username = preg_replace('/@.*?$/', '', $email);
        $password = (isset($_GET['password']) AND ! empty($_GET['password'])) ? $_GET['password'] :
            ((isset($_POST['logonPassword']) AND ! empty($_POST['logonPassword'])) ? $_POST['logonPassword'] : NULL);

        $wp_user = get_user_by('email', $email);

        if ( ! $wp_user)
        {
            // New user here.
            $user_id = wp_insert_user(array(
                'user_pass'     => $password,
                'user_email'    => $email,
                'user_login'    => $username,
            ));

            if ( ! is_wp_error($user_id))
            {
                $wp_user = get_user_by('id', $user_id);
            }
        }

        wp_set_current_user($wp_user->ID, $wp_user->user_login);
        wp_set_auth_cookie($wp_user->ID);
        do_action('wp_login', $wp_user->user_login);

        $refferer = site_url($_SERVER['REQUEST_URI']);
        $redirect = ( ! isset($_GET['redirect_url'])) ? home_url() : $_GET['redirect_url'];

        exit;

        header('Refferer: ' . $refferer);
        header('Location: ' . $redirect);
    }

}
