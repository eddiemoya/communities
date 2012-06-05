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
                self::factory($class);
            }

            self::log('Loaded [' . implode(', ', $init_classes['init']) . '] on "init" hook.', self::INFO);
        }
    }

    public static function config($file)
    {
        if ( ! array_key_exists(self::$configs, $file))
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

    public static function log($message, $type = NULL)
    {
        if ($type === NULL)
        {
            $type = self::ERROR;
        }

        error_log(date('Y-m-d H:i:s') . ' --- ' . ucfirst($type) . ': ' . $message);
    }

}
