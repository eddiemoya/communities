<?php
/*
Plugin Name: Sears SSO and Profile
Description: Integrates Wordpress with the Sears SSO and Profile web services.
Version: 0.1.0
Author: Brian Greenacre
*/

/**
 * Sears Holdings SSO and Profile wordpress plugin.
 *
 * This plugin integrates the Sears SSO and Profile service into Wordpress.
 *
 * @package Shcsso
 * @author Brian Greenace
 * @version $id$
 */
if ( ! defined('SHCSSO_VERSION'))
{
    define('SHCSSO_VERSION', '0.1.0');
    define('SHCSSO_PATH', WP_PLUGIN_DIR . '/shc-sso-profile/');
    define('SHCSSO_FILE', SHCSSO_PATH . pathinfo(__FILE__, PATHINFO_BASENAME));
    define('SHCSSO_OPTION_PREFIX', 'shcsso_');

    // Do not register the autoloader more then once.
    // There's potential for the bootstrap to be loaded more then once.
    if ( ! defined('SHCSSP_AUTOLOAD'))
    {
        define('SHCSSO_AUTOLOAD', TRUE);

        spl_autoload_register(function($class) {
            // Transform the class name into a path
            $file = str_replace('_', '/', strtolower($class));

            // Prepend classes path
            $path = SHCSSO_PATH . 'classes' . DIRECTORY_SEPARATOR . $file;

            if (is_file($path))
            {
                // Load the class file
                require $path;

                // Class has been found
                return TRUE;
            }

            // Class is not in the filesystem
            return FALSE;
        });
    }

    add_action('init', array('Shcsso_Plugin', 'init'));
    register_activation_hook(SHCSSO_FILE, array('Shcsso_Plugin', 'install'));
    register_deactivation_hook(SHCSSO_FILE, array('Shcsso_Plugin', 'uninstall'));
}
