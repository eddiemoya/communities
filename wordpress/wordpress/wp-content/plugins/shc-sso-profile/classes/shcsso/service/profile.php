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
    protected $_query = array();
    protected $_post = array();
    protected $_options = array();
    protected $_action;

    public function __construct()
    {
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

    public function site_id($site_id = NULL)
    {
        if ($site_id === NULL)
        {
            return $this->_site_id;
        }

        $this->_site_id = (int) $site_id;

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

}
