<?php
	class ActionJacksonAdmin {
		public $views;
		
		public function __construct() {
			$this->views = new ActionJacksonViews();
			
			add_action('admin_menu', array(&$this, 'initialize'));
//			add_action('admin_init', array(&$this, 'setOptions'));
		}
		
		public function initialize() {
            $pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-datepicker', $pluginfolder . '/jquery.ui.datepicker.min.js', array('jquery', 'jquery-ui-core') );
            wp_enqueue_style('jquery.ui.theme', $pluginfolder . '/smoothness/jquery-ui-1.8.12.custom.css');

            add_menu_page('User Action :: Home', 'User Actions', 5, 'home', array(&$this, 'showLanding'));
            add_submenu_page('home', 'User Actions :: Questions', 'Questions', 5, 'questions');
		}
		
        public function showLanding() {
            $this->views->openView('form');
        }
	}