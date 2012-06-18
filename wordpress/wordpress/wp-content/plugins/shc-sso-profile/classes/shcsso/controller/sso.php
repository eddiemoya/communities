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

    public function register()
    {
        $auth = new Shcsso_Service_Auth($this->settings['environment']);
        $auth->callback(site_url('sso/receive'));

        $params = array(
            'auth'      => $auth,
            'action'    => NULL,
        );

        if ( ! empty($_POST))
        {
            $auth->register($_POST['loginId'], $_POST['logonPassword'])
                ->redirect();
        }

        echo Shcsso_Plugin::view('register/form', $params);
    }

    public function login()
    {
        $auth = new Shcsso_Service_Auth($this->settings['environment']);
        $auth->callback(site_url('sso/receive'));

        $params = array(
            'auth'      => $auth,
            'action'    => NULL,
        );

        if ( ! empty($_POST))
        {
            $auth->login($_POST['loginId'], $_POST['logonPassword'], $_POST['zipcode'])
                ->redirect();
        }

        echo Shcsso_Plugin::view('login/form', $params);
    }

    public function logout()
    {
        $auth = new Shcsso_Service_Auth();
        $auth->callback(site_url('sso/receive_logout'))
            ->logout()
            ->redirect();
    }

    public function receive_logout()
    {
        wp_logout();

        $refferer = site_url($_SERVER['REQUEST_URI']);
        $redirect = home_url();

        header('Refferer: ' . $refferer);
        header('Location: ' . $redirect);

        die;
    }

    public function receive()
    {
        $auth = new Shcsso_Service_Auth($this->settings['environment']);
        $auth->callback(site_url($_SERVER['REQUEST_URI']));

        try
        {
            // Validate the ticket and get the user.
            // If any exception is thrown here it's because the ticket
            // is invalid OR the SSO is not responding.
            $user = $auth->receive($_POST['ticket']);
        }
        catch(\Exception $e)
        {
            //handle error here.
        }

        $email = $user->authenticationSuccess->user;

        // Split the email to get a "username" for wordpress.
        $username = preg_replace('/@.*?$/', '', $email);

        // Get the password from the request.
        $password = (isset($_GET['password']) AND ! empty($_GET['password']))
            ? $_GET['password'] : ((isset($_POST['logonPassword']) AND ! empty($_POST['logonPassword'])) 
                ? $_POST['logonPassword'] : NULL);

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

        header('Refferer: ' . $refferer);
        header('Location: ' . $redirect);

        die;
    }

}
