<?php defined('SHCSSO_PATH') or die('Cannot be accessed directly');
/**
 * Shcsso_Service_Auth - Performs interactions with the SSO
 * web service.
 *
 * @package Shcsso
 * @category Service
 * @version $id$
 */
class Shcsso_Service_Auth {

    protected $_default_callback;
    protected $_callback;
    protected $_error;
    protected $_endpoint= 'https://sso.shld.net/';
    protected $_site_id = 41;
    protected $_role = 'subscriber';
    protected $_query = array();
    protected $_post = array();
    protected $_options = array();
    protected $_action;

    public function __construct($environment = 'production')
    {
        $this->_default_callback = 'http://'.$_SERVER['HTTP_HOST'].'/';

        $config = Shcsso_Plugin::config('sso');

        if (isset($config[$environment]))
        {
            foreach ($config[$environment]) as $key => $value)
            {
                $this->$key($value);
            }
        }

        $settings = Shcsso_Plugin::option('settings');

        $this->site_id( (int) $settings['sso_site_id'])
            ->role($settings['sso_role']);

        unset($settings, $config);
    }

    public function query($query = NULL, $value = NULL)
    {
        if ($query === NULL)
        {
            return $this->_query;
        }

        if (is_array($query))
        {
            $this->_query = $query;
        }
        elseif ($value === NULL)
        {
            return (array_key_exists($query, $this->_query)) ? $this->_query[$query] : NULL;
        }
        else
        {
            $this->_query[$query] = $value;
        }

        return $this;
    }

    public function post($post = NULL, $value = NULL)
    {
        if ($post === NULL)
        {
            return $this->_post;
        }

        if (is_array($post))
        {
            $this->_post = $post;
        }
        elseif ($value === NULL)
        {
            return (array_key_exists($post, $this->_post)) ? $this->_post[$post] : NULL;
        }
        else
        {
            $this->_post[$post] = $value;
        }

        return $this;
    }

    public function action($action = NULL)
    {
        if ($action === NULL)
        {
            return $this->_action;
        }

        $this->_action = $action;

        return $this;
    }

    public function error($error = NULL)
    {
        if ($error === NULL)
        {
            return $this->_error;
        }

        $this->_error = $error;

        return $this;
    }

    public function site_id($site_id = NULL)
    {
        if ($site_id === NULL)
        {
            return $this->_site_id;
        }

        $this->_site_id = (int) $site_id;

        return $this;
    }

    public function role($role = NULL)
    {
        if ($role === NULL)
        {
            return $this->_role;
        }

        $this->_role = (string) $role;

        return $this;
    }

    public function endpoint($endpoint = NULL, $action = NULL)
    {
        if ($action === NULL)
        {
            $action = $this->action();
        }

        if ($endpoint === NULL)
        {
            return rtrim($this->_endpoint, '/').'/'.(($action) ? 'shccas/'.$action : NULL);
        }

        $this->_endpoint = $endpoint;

        return $this;
    }

    public function callback($callback = NULL)
    {
        if ($this->_callback === NULL)
        {
            $this->_callback = $this->_default_callback;
        }

        if ($callback === NULL)
        {
            return $this->_callback;
        }

        $this->_callback = $callback;

        return $this;
    }

    public function login($username, $password, $callback = NULL)
    {
        $this->callback($callback);

        $this->query(array(
            'loginId'       => $username,
            'logonPassword' => $password,
            'service'       => $this->callback() . '?password=' . urlencode($password),
            'renew'         => 'true',
            'sourceSiteid'  => $this->site_id(),
        ))->action('shcLogin');

        return $this;
    }

    public function logout($username, $password, $zipcode = NULL, $callback = NULL)
    {
        $this->callback($callback);

        $this->query(array(
            'service'       => $this->callback(),
        ))->action('logout');

        return $this;
    }

    public function register($username, $password, $zipcode = NULL, $callback = NULL)
    {
        $this->callback($callback);

        $this->query(array(
            'sourceSiteid'  => $this->site_id(),
            'service'       => $this->callback(),
            'loginId'       => $username,
            'logonPassword' => $password,
            'zipcode'       => $zipcode,
        ))->action('shcRegistration');

        return $this;
    }

    public function redirect($refferer = NULL)
    {
        if ($refferer === NULL)
        {
            $refferer = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'];
        }

        $url = $this->endpoint(NULL, $this->action());
        $url .= ($this->query()) ? '?' . http_build_query($this->query(), '', '&') : '';

        header('Location: ' . $url);
        header('Refferer: ' . $refferer);

        exit;
    }

    public function execute()
    {
        $url = $this->endpoint(NULL, $this->action());

        if ($this->query())
        {
            $url .= '?'.http_build_query($this->query(), NULL, '&');
        }

        $options = array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => TRUE,
            CURLOPT_HEADER          => FALSE,
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_SSL_VERIFYPEER  => 0,
            CURLOPT_USERAGENT       => $_SERVER['HTTP_USER_AGENT'],
        );

        if ($this->post())
        {
            $options[CURLOPT_POSTFIELDS] = $this->post();
        }

        $options += $this->_options;

        $ch = curl_init();

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        // Get the response information
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ( ! $response)
        {
            $error = curl_error($ch);
        }

        curl_close($ch);

        if (isset($error))
        {
            throw new \Exception('Error fetching remote '.$url.' [ status '.$code.' ] '.$error);
        }

        return $response;
    }

    public function receive($ticket = NULL)
    {
        $xml = $this->action('serviceValidate')
            ->query(array(
                'service'   => $this->callback(),
                'ticket'    => $ticket,
            ))
            ->execute();

        if ($xml) {
            $xml = "<?xml version='1.0'?>\n" . trim($xml);
            $xml = preg_replace('~\s*(<([^>]*)>[^<\s]*</\2>|<[^>]*>)\s*~', '$1', $xml);
            $xml = preg_replace_callback('~<([A-Za-z\s]*)>~', create_function('$matches', 'return "<".strtolower($matches[1]).">";'), $xml);
            $xml = preg_replace_callback('~</([A-Za-z\s]*)>~', create_function('$matches', 'return "</".strtolower($matches[1]).">";'), $xml);
            $xml = new \SimpleXmlElement($xml);
            $user = $xml->children('http://www.yale.edu/tp/cas');

            if (isset($user->authenticationSuccess) AND ! isset($_POST['errorCode'])) {
                return $user;
            }
            else {
                $error = Shcsso_Plugin::config('errors')[$_POST['errorCode']];
                $this->error($error);
                throw new \Exception('Failed to validate ticket ["'.$ticket.'"] with the error ["'.$error.'"]');
            }
        }
        else {
            throw new \Exception('Invalid or no response given for SSO validate request. ['.$error.']');
        }
    }

}
