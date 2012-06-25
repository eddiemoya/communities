<?php defined('SHCSSO_PATH') or die('Cannot be accessed directly');
/**
 * Shcsso_Service_Profile
 *
 * @package Shcsso
 * @category Service
 * @version $id$
 */
class Shcsso_Service_Profile {

    protected $_endpoint= 'http://toad.ecom.sears.com:8180/universalservices/v3/';
    protected $_site_id = 41;
    protected $_error;
    protected $_query = array();
    protected $_post = array();
    protected $_options = array();
    protected $_action;
    protected $_method = 'GET';

    public function __construct($environment = 'production')
    {
        $config = Shcsso_Plugin::config('profile');

        if (isset($config[$environment]))
        {
            foreach ($config[$environment] as $key => $value)
            {
                $this->$key($value);
            }
        }

        $settings = Shcsso_Plugin::option('settings');

        $this->site_id( (int) $settings['profile_site_id'])
            ->key($settings['profile_key']);

        unset($settings, $config);
    }

    public function digital_signature()
    {
        $now = date('c', time());

        $encrypt = 'sid=' . $this->site_id() . 'ts=' . $now;

        return hash_hmac('sha1', $encrypt, $this->key());
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

    public function key($key = NULL)
    {
        if ($key === NULL)
        {
            return $this->_key;
        }

        $this->_key = $key;

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

    public function error($error = NULL)
    {
        if ($error === NULL)
        {
            return $this->_error;
        }

        $this->_error = $error;

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

    public function method($method = NULL)
    {
        if ($method === NULL)
        {
            return $this->_method;
        }

        $this->_method = $method;

        return $this;
    }

    public function execute()
    {
        $url = $this->endpoint(NULL, $this->action());

        $options = array(
            CURLOPT_RETURNTRANSFER  => TRUE,
            CURLOPT_HEADER          => FALSE,
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_SSL_VERIFYPEER  => 0,
            CURLOPT_USERAGENT       => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_CUSTOMREQUEST   => $this->method(),
        );

        if ($this->post())
        {
            $this->post('sid', $this->digital_signature())
                ->post('ts', date('c'));

            $options[CURLOPT_POSTFIELDS] = $this->post();
        }

        if ($this->query())
        {
            $this->query('sid', $this->digital_signature())
                ->query('ts', date('c'));

            $url .= '?'.http_build_query($this->query(), NULL, '&');
        }

        $options[CURLOPT_URL] = $url;
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

        $xml = new \SimpleXmlElement($response);

        if (isset($xml->{error-response}))
        {
            $error = $xml->{error-response}->message;
            $this->error($error);
            throw new \Exception('Error calling CIS with the error ["'.$error.'"]');
        }

        return $xml;
    }

    public function get($id = NULL)
    {
        return $this->query('id', $id)
            ->method('GET')
            ->action('user')
            ->execute();
    }

    public function update($id, $user)
    {
        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><user></user>");

        $data = array(
            'id'        => (int) $id,
            'status'    => (isset($user['status'])) ? $user['status'] : 'active',
            'name'      => array(
                'first'     => (isset($user['first_name'])) ? $user['first_name'] : NULL,
                'last'      => (isset($user['last_name'])) ? $user['last_name'] : NULL,
                'middle'    => (isset($user['middle_name'])) ? $user['middle_name'] : NULL,
            ),
            'screen-names'  => array(
                array(
                    'screen-name'   => array(
                        'name'      => (isset($user['screen_name'])) ? $user['screen_name'] : NULL,
                        'site-id'   => 'global',
                    ),
                ),
            ),
            'zipcode'   => (isset($user['zipcode'])) ? $user['zipcode'] : NULL,
            'birthdate' => (isset($user['birthdate'])) ? $user['birthdate'] : NULL,
            'associate-number'   => (isset($user['associate-number'])) ? $user['associate-number'] : NULL,
            'emails'    => array(
                array(
                    'email' => (isset($user['email'])) ? $user['email'] : NULL,
                ),
            ),
        );

        $this->_toXML($data, $xml);
        $this->_post = $xml->asXML();
        $this->method('PUT')
            ->action('user');

        try
        {
            $this->execute();
        }
        catch(\Exception $e)
        {
            return FALSE;
        }

        return TRUE;
    }

    public function reset_password($logon, $url)
    {
        $this->method('POST')
            ->post('logon', $logon)
            ->post('url', $url);

        try
        {
            $this->execute();
        }
        catch(\Exception $e)
        {
            return FALSE;
        }

        return TRUE;
    }

    private function _toXML(array $data, $xml)
    {
        foreach ($data as $key => $value)
        {
            if (is_array($value))
            {
                if ( ! is_numeric($key))
                {
                    $sub = $xml->addChild($key);
                    $this->_toXML($value, $sub);
                }
                else
                {
                    $this->_toXML($value, $xml);
                }
            }
            else
            {
                $xml->addChild($key, $value);
            }
        }
    }

}
