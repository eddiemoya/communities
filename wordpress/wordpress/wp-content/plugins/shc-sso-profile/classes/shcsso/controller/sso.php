<?php defined('SHCSSO_PATH') or die('Cannot be accessed directly');
/**
 *
 * @package Shcsso
 * @category Hooks
 * @version $id$
 */
class Shcsso_Controller_Sso {

    public function check()
    {
    }

    public function login()
    {
        $auth = new Shcsso_Service_Auth();
        $auth->callback('http://communities/sso/receive');
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

        if ($wp_user = get_user_by('email', $email))
        {
            wp_authenticate($username, $password);
        }
        else
        {
            // New user here.
            wp_insert_user(array(
                'user_pass'     => $password,
                'user_email'    => $email,
                'user_login'    => $username,
            ));

            wp_authenticate($username, $password);
        }

        $refferer = site_url($_SERVER['REQUEST_URI']);
        $redirect = ( ! isset($_GET['redirect_url'])) ? home_url() : $_GET['redirect_url'];

        exit;

        header('Refferer: ' . $refferer);
        header('Location: ' . $redirect);
    }

}
