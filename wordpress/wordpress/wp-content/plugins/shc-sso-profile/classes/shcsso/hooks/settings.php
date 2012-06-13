<?php defined('SHCSSO_PATH') or die('Cannot be accessed directly');
/**
 * Hooks_Settings makes all the needs hooks for Settings page
 * in the admin section of Wordpress.
 *
 * @package Shcsso
 * @category Core
 * @version $id$
 */
class Shcsso_Hooks_Settings {

    public static $controller;

    public static function execute()
    {
        add_action('admin_menu', array(__CLASS__, 'menu'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
    }

    public static function menu()
    {
        add_options_page('Shc SSO and Profile Settings', 'SSO Settings', 'manage_options', 'shcsso-settings', array(self::controller(), 'menu'));
    }

    public static function register_settings()
    {
        register_setting(SHCSSO_OPTION_PREFIX . 'settings', SHCSSO_OPTION_PREFIX . 'settings');
        add_settings_section(SHCSSO_OPTION_PREFIX . 'main_settings', __('Main Settings'), array(self::controller(), 'main_section'), 'shcsso-settings');
        add_settings_field('environment', __('Environment'), array(self::controller(), 'environment'), 'shcsso-settings', SHCSSO_OPTION_PREFIX . 'main_settings');
        add_settings_section(SHCSSO_OPTION_PREFIX . 'sso_settings', __('SSO Settings'), array(self::controller(), 'sso_section'), 'shcsso-settings');
        add_settings_field('sso_site_id', __('Source Site ID'), array(self::controller(), 'sso_site_id'), 'shcsso-settings', SHCSSO_OPTION_PREFIX . 'sso_settings');
        add_settings_field('sso_role', __('User Role'), array(self::controller(), 'sso_role'), 'shcsso-settings', SHCSSO_OPTION_PREFIX . 'sso_settings');
        add_settings_section(SHCSSO_OPTION_PREFIX . 'profile_settings', __('Profile Settings'), array(self::controller(), 'profile_section'), 'shcsso-settings');
        add_settings_field('profile_site_id', __('Profile Site ID'), array(self::controller(), 'profile_site_id'), 'shcsso-settings', SHCSSO_OPTION_PREFIX . 'profile_settings');
        add_settings_field('profile_key', __('Profile Key'), array(self::controller(), 'profile_key'), 'shcsso-settings', SHCSSO_OPTION_PREFIX . 'profile_settings');
    }

    public static function controller()
    {
        if (self::$controller === NULL)
        {
            self::$controller = new Shcsso_Controller_Admin_Settings();
        }

        return self::$controller;
    }

}
