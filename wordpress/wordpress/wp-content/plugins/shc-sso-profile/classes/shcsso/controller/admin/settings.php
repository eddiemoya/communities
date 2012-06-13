<?php defined('SHCSSO_PATH') or die('Cannot be accessed directly');

class Shcsso_Controller_Admin_Settings {

    public function menu()
    {
        $params = array(
            'classname' => __CLASS__,
        );

        echo Shcsso_Plugin::view('admin/settings', $params);
    }

    public function main_section()
    {
        echo '<p>' . __('Settings specifically for this plugin.') . '</p>';
    }

    public function sso_section()
    {
        echo '<p>' . __('Settings specifically for SSO.') . '</p>';
    }

    public function profile_section()
    {
        echo '<p>' . __('Settings specifically for Profile.') . '</p>';
    }

    public function sso_site_id()
    {
        $settings = Shcsso_Plugin::option('settings');

        $params = array(
            'attributes'    => array(
                'name'  => SHCSSO_OPTION_PREFIX . 'settings[sso_site_id]',
                'id'    => 'sso_site_id',
                'value' => $settings['sso_site_id'],
            )
        );

        echo Shcsso_Plugin::view('fields/text', $params);
    }

    public function profile_site_id()
    {
        $settings = Shcsso_Plugin::option('settings');

        $params = array(
            'attributes'    => array(
                'name'  => SHCSSO_OPTION_PREFIX . 'settings[profile_site_id]',
                'id'    => 'profile_site_id',
                'value' => $settings['profile_site_id'],
            )
        );

        echo Shcsso_Plugin::view('fields/text', $params);
    }

    public function profile_key()
    {
        $settings = Shcsso_Plugin::option('settings');

        $params = array(
            'attributes'    => array(
                'name'  => SHCSSO_OPTION_PREFIX . 'settings[profile_key]',
                'id'    => 'profile_key',
                'value' => $settings['profile_key'],
            )
        );

        echo Shcsso_Plugin::view('fields/text', $params);
    }

    public function environment()
    {
        $settings = Shcsso_Plugin::option('settings');

        $params = array(
            'attributes'    => array(
                'name'  => SHCSSO_OPTION_PREFIX . 'settings[environment]',
                'id'    => 'environment',
            ),
            'options'       => array(
                'integration'   => 'integration',
                'qa'            => 'qa',
                'production'    => 'production',
            ),
            'value'         => $settings['environment']
        );

        echo Shcsso_Plugin::view('fields/select', $params);
    }

    public function sso_role()
    {
        global $wp_roles;

        $settings = Shcsso_Plugin::option('settings');

        $params = array(
            'attributes'    => array(
                'name'  => SHCSSO_OPTION_PREFIX . 'settings[sso_role]',
                'id'    => 'sso_role',
            ),
            'options'       => (array) $wp_roles->get_names(),
            'value'         => $settings['sso_role']
        );

        echo Shcsso_Plugin::view('fields/select', $params);
    }

}
