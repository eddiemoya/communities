<?php

/**
 * Master theme class
 * 
 * @package Bolts
 * @since 1.0
 */
class CCT_Controller_Plugin_Settings {

    protected $menu_title = 'Comment Types';
    protected $page_title = 'Custom Comment Types';
    protected $page_slug = 'cct-options';
    protected $option_name = 'cct_options';
    
    private $sections;
    private $checkboxes;
    private $settings;

    /**
     * Construct
     *
     * @since 1.0
     */
    public function __construct() {

        // This will keep track of the checkbox options for the validate_settings function.
        $options = get_option('cct_options');
        $this->checkboxes = array();
        $this->settings = array();
        $this->get_settings();
        $this->sections['general'] = __('General Settings');
        $this->sections['labels'] = __('Labels');

        for ($n = 1; $n < cct_option('comment_type_count') + 1; $n++) {
            $cct_name = cct_option('cct_name_singular_' . $n);
            $cct_name_plural = cct_option('cct_name_plural_' . $n);

            if (!empty($cct_name) && !empty($cct_name_plural)) {

                $section_slug = str_replace(' ', '_', strtolower($cct_name));

                $this->sections[$section_slug] = $cct_name_plural;
            }
        }

        add_action('admin_menu', array(&$this, 'add_pages'));
        add_action('admin_init', array(&$this, 'register_settings'));

        if (!get_option('cct_options'))
            $this->initialize_settings();
    }

    /**
     * Add options page
     *
     * @since 1.0
     */
    public function add_pages() {

        $admin_page = add_options_page(__($this->page_slug), __($this->menu_title), 'manage_options', $this->page_slug, array(&$this, 'display_page'));

        add_action('admin_print_scripts-' . $admin_page, array(&$this, 'scripts'));
        add_action('admin_print_styles-' . $admin_page, array(&$this, 'styles'));
    }

    /**
     * Create settings field
     *
     * @since 1.0
     */
    public function create_setting($args = array()) {

        $defaults = array(
            'id' => 'cct_options',
            'title' => __('Custom Comment Options'),
            'desc' => __('Custom Comment Options.'),
            'std' => '',
            'type' => 'text',
            'section' => 'general',
            'choices' => array(),
            'class' => ''
        );

        extract(wp_parse_args($args, $defaults));

        $field_args = array(
            'type' => $type,
            'id' => $id,
            'desc' => $desc,
            'std' => $std,
            'choices' => $choices,
            'label_for' => $id,
            'class' => $class
        );

        if ($type == 'checkbox')
            $this->checkboxes[] = $id;

        add_settings_field($id, $title, array($this, 'display_setting'), 'cct-options', $section, $field_args);
    }

    /**
     * Display options page
     *
     * @since 1.0
     */
    public function display_page() {
      include (CCT_VIEWS . 'cct-view-options-panel.php');
    }

    /**
     * Description for section
     *
     * @since 1.0
     */
    public function display_section() {
        // code
    }

    /**
     * Description for About section
     *
     * @since 1.0
     */
    public function display_about_section() {

        // This displays on the "About" tab. Echo regular HTML here, like so:
        // echo '<p>Copyright 2011 me@example.com</p>';
    }

    /**
     * HTML output for text field
     *
     * @since 1.0
     */
    public function display_setting($args = array()) {

        extract($args);

        $options = get_option('cct_options');

        if (!isset($options[$id]) && $type != 'checkbox')
            $options[$id] = $std;
        elseif (!isset($options[$id]))
            $options[$id] = 0;

        $field_class = '';
        if ($class != '')
            $field_class = ' ' . $class;

        switch ($type) {

            case 'heading':
                echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
                break;

            case 'checkbox':

                echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="cct_options[' . $id . ']" value="1" ' . checked($options[$id], 1, false) . ' /> <label for="' . $id . '">' . $desc . '</label>';

                break;

            case 'select':
                echo '<select class="select' . $field_class . '" name="cct_options[' . $id . ']">';

                foreach ($choices as $value => $label)
                    echo '<option value="' . esc_attr($value) . '"' . selected($options[$id], $value, false) . '>' . $label . '</option>';

                echo '</select>';

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'radio':
                $i = 0;
                foreach ($choices as $value => $label) {
                    echo '<input class="radio' . $field_class . '" type="radio" name="cct_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr($value) . '" ' . checked($options[$id], $value, false) . '> <label for="' . $id . $i . '">' . $label . '</label>';
                    if ($i < count($choices) - 1)
                        echo '<br />';
                    $i++;
                }

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'textarea':
                echo '<textarea class="' . $field_class . '" id="' . $id . '" name="cct_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre($options[$id]) . '</textarea>';

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'password':
                echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="cct_options[' . $id . ']" value="' . esc_attr($options[$id]) . '" />';

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'text':
            default:
                echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="cct_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr($options[$id]) . '" />';

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;
        }
    }

    /**
     * Settings and defaults
     * 
     * @since 1.0
     */
    public function get_settings() {

        /* General Settings
          =========================================== */
        
        $this->settings['general_options_header'] = array(
            'section' => 'general',
            'desc' => __('Setup'),
            'title' => '',
            'type' => 'heading',
        );
        
        $this->settings['comment_type_count'] = array(
            'section' => 'general',
            'title' => __('Number of Custom Comment Types'),
            'desc' => __('Built-in Comment Types (comment, trackback, pingbacks) <strong>not</strong> included.'),
            'type' => 'text',
            'class' => 'small-text',
            'std' => '0'
        );

        //foreach


        /* Custom Comment Types
          =========================================== */

        
        for ($n = 1; $n < cct_option('comment_type_count')+1; $n++) {
            
            
            $cct_name_singular_slug = 'cct_name_singular_' . $n;
            $cct_name_plural_slug = 'cct_name_plural_' . $n;
            
            $this->settings['custom_comment_type_names_header_' .$n] = array(
                'section' => 'labels',
                'desc' => __('Custom Comment Type #' . $n),
                'title' => '',
                'type' => 'heading',
            );
            
            $this->settings[$cct_name_singular_slug] = array(
                'section' => 'labels',
                'title' => __('Singular Name'),
                'desc' => __('ex: "Comments", "Answers", etc..'),
                'type' => 'text',
                'std' => ''
            );
            
            $this->settings[$cct_name_plural_slug] = array(
                'section' => 'labels',
                'title' => __('Plural Name'),
                'desc' => __('ex: "Comment", "Answer", etc..'),
                'type' => 'text',
                'std' => ''
            );
            
            $cct_name = cct_option($cct_name_singular_slug);
            $cct_name_plural = cct_option($cct_name_plural_slug);
   
            $section = str_replace(' ', '_' ,strtolower($cct_name));
            
            if (!empty($cct_name)) {
                $this->settings['cct_panels_menus_heading_' . $section] = array(
                    'section' => $section,
                    'title' => '',
                    'desc' => __('Admin Panels and Menus'),
                    'type' => 'heading',
                    'std' => ''
                );
                
                $this->settings['cct_moderation_panel_' . $section] = array(
                    'section' => $section,
                    'title' => __('Custom Moderation Panel'),
                    'desc' => __('Give ' . $cct_name_plural . ' their own distinct moderation queue.'),
                    'type' => 'checkbox',
                    'std' => 'on'
                );
                
                $this->settings['cct_menu_position_' . $section] = array(
                    'section' => $section,
                    'title' => __('Menu Position'),
                    'desc' => __(''),
                    'class' => 'small-text',
                    'type' => 'text',
                    'std' => 1
                );
                
                $this->settings['cct_icon_url_' . $section] = array(
                    'section' => $section,
                    'title' => __('Custom Icon'),
                    'desc' => __('Icon that will appear in the menu'),
                    'type' => 'text',
                    'std' => 'http://'
                );
                
                $post_types = get_post_types(
                        array(
                            'public' => true
                        ),
                        'objects'
                       );
                
                $this->settings['cct_post_types_heading_' . $section] = array(
                    'section' => $section,
                    'title' => '',
                    'desc' => __('Post Types'),
                    'type' => 'heading',
                    'std' => ''
                );
                        
                foreach($post_types as $post_type ){
                $this->settings['cct_post_type_' . $section . '_' . $post_type->name] = array(
                    'section' => $section,
                    'title' => $post_type->labels->name,
                    'desc' => __(''),
                    'type' => 'checkbox',
                    'std' => ''
                );
                }
            }
        }
    }

    /**
     * Initialize settings to their default values
     * 
     * @since 1.0
     */
    public function initialize_settings() {

        $default_settings = array();
        foreach ($this->settings as $id => $setting) {
            if ($setting['type'] != 'heading')
                $default_settings[$id] = $setting['std'];
        }

        update_option('cct_options', $default_settings);
    }

    /**
     * Register settings
     *
     * @since 1.0
     */
    public function register_settings() {

        register_setting('cct_options', 'cct_options', array(&$this, 'validate_settings'));

        foreach ($this->sections as $slug => $title) {
            if ($slug == 'about')
                add_settings_section($slug, $title, array(&$this, 'display_about_section'), 'cct-options');
            else
                add_settings_section($slug, $title, array(&$this, 'display_section'), 'cct-options');
        }

        $this->get_settings();

        foreach ($this->settings as $id => $setting) {
            $setting['id'] = $id;
            $this->create_setting($setting);
        }
    }

    /**
     * jQuery Tabs
     *
     * @since 1.0
     */
    public function scripts() {

        wp_print_scripts('jquery-ui-tabs');
    }

    /**
     * Styling for the theme options page
     *
     * @since 1.0
     */
    public function styles() {

        wp_register_style('cct-admin-options', plugins_url('assets/css/cct-admin-options.css', dirname(__FILE__)));
        wp_enqueue_style('cct-admin-options');
    }

    /**
     * Validate settings
     *
     * @since 1.0
     */
    public function validate_settings($input) {

        if (!isset($input['reset_theme'])) {
            $options = get_option('cct_options');

            foreach ($this->checkboxes as $id) {
                if (isset($options[$id]) && !isset($input[$id]))
                    unset($options[$id]);
            }

            return $input;
        }
        return false;
    }
}


function cct_option($option) {
    $options = get_option('cct_options');
    if (isset($options[$option]))
        return $options[$option];
    else
        return false;
}

