<?php defined('SHCSSO_PATH') or die('Cannot be accessed directly');
/**
 * Shcsso_Plugin class facilitates the rest of the plugins by providing
 * things like a factory method for easy class object instantiation,
 * Initializes the plugin using the init hook,
 * Config file loader, etc.
 *
 * @package Shcsso
 * @category Core
 * @version $id$
 */
class Shcsso_Plugin {

    const ERROR = 'error';
    const NOTICE = 'notice';
    const INFO = 'information';

    public static $configs = array();

    public static function factory($class)
    {
        return new $class();
    }

    public static function load($file)
    {
        return include $file;
    }

    public static function init()
    {
        $init_classes = self::config('loader');

        if (isset($init_classes['init']) and is_array($init_classes['init']))
        {
            foreach ($init_classes['init'] as $class)
            {
                call_user_func(array($class, 'execute'));
            }

            self::log('Loaded [' . implode(', ', $init_classes['init']) . '] on "init" hook.', self::INFO);
        }
    }

    public static function config($file)
    {
        if ( ! array_key_exists($file, self::$configs))
        {
            $path = SHCSSO_PATH.'config/' . $file . '.php';

            if (is_file($path))
            {
                self::$configs[$file] = self::load($path);
            }
            else
            {
                throw new \Exception('Config file "' . $file . '" does not exist for Shcsso Plugin.');
            }
        }

        return self::$configs[$file];
    }

    public static function option($name, array $value = NULL)
    {
        // First let's see if a config file of the same name
        // has any content or exists
        try
        {
            $config = self::config($name);
        }
        catch(\Exception $e)
        {
            $config = FALSE;
        }

        if ($value === NULL)
        {
            // Trying to get a value from the options table.
            $option = get_option(SHCSSO_OPTION_PREFIX . $name);

            if ($config !== FALSE AND is_array($option))
            {
                $option = array_merge($config, $option);
            }
            elseif ($config !== FALSE AND $option === FALSE)
            {
                $option = $config;
            }

            return $option;
        }
        else
        {
            if ($config !== FALSE AND is_array($value))
            {
                $value = array_merge($config, $value);
            }

            return update_option(SHCSSO_OPTION_PREFIX . $name, $value);
        }
    }

    public static function log($message, $type = NULL, array $params = NULL)
    {
        if ($type === NULL)
        {
            $type = self::ERROR;
        }

        if ( ! empty($params))
        {
            $message = str_replace(array_keys($params), $params, $message);
        }

        error_log(date('Y-m-d H:i:s') . ' --- ' . ucfirst($type) . ': ' . $message);
    }

    public static function install()
    {
        // First get the current version that's installed for this plugin.
        $current_version = self::option('version');

        if ($current_version != '' AND $current_version != SHCSSO_VERSION)
        {
            Shcsso_Migration::upgrade($current_version, SHCSSO_VERSION);

            self::log('Upgrade Shc Sso and Profile plugin from version ":current" to ":version".', self::INFO, array(
                ':current'  => $current_version,
                ':version'  => SHCSSO_VERSION,
            ));
        }
        else
        {
            Shcsso_Migration::up();

            self::log('Installed Shc Sso and Profile plugin.', self::INFO);
        }

        self::option('version', SHCSSO_VERSION);
    }

    public static function uninstall()
    {
        Shcsso_Migration::down();

        foreach (array('version', 'settings') as $option)
        {
            delete_option($option);
        }

        self::log('Uninstalled Shc Sso and Profile plugin.', self::INFO);
    }

    public static function view($view, array $params = array())
    {
        // Import the view variables to local namespace
        extract($params, EXTR_SKIP);

        // Capture the view output
        ob_start();

        try
        {
            $file = SHCSSO_PATH . 'views/' . $view . '.php';

            // Load the view within the current scope
            include $file;
        }
        catch (Exception $e)
        {
            // Delete the output buffer
            ob_end_clean();

            // Re-throw the exception
            throw $e;
        }

        // Get the captured output and close the buffer
        return ob_get_clean();
    }

}
