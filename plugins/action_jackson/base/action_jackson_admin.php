<?php
	class FitStudioAdminActions {
		public $views;
		
		public function __construct() {
			$this->views = new FitStudioViewActions();
			
			add_action('admin_menu', array(&$this, 'initializeSideMenu'));
			add_action('admin_init', array(&$this, 'setOptions'));
		}
		
		public function initializeSideMenu() {
			add_options_page('UserActions', 'User Actions', 'manage_options', 'manage-fitstudio-hf', array(&$this, 'createForm'));
		}
		
		public function createForm() {
			$data = array('me' => 'sebastian');
			
			$this->views->openView('form', $data);
		}
		
		public function setOptions() {
  			register_setting('fitStudioOptions', 'fitStudioOptions-apiKey');
  			register_setting('fitStudioOptions', 'fitStudioOptions-visibility');
  			register_setting('fitStudioOptions', 'fitStudioOptions-apiUrl');
  			register_setting('fitStudioOptions', 'fitStudioOptions-siteSection');
  			
	  		add_settings_section('infoSection', 'Use this plugin to turn on the header/footer for FitStudio', array(&$this,'printVisibility'), 'manage-fitstudio-hf');
			add_settings_field('apiUrl', '', array(&$this,'printApiUrl'), 'manage-fitstudio-hf', 'infoSection');
			add_settings_field('apiKey', '', array(&$this,'printApiKey'), 'manage-fitstudio-hf', 'infoSection');
			add_settings_field('toggleVisibility', '', array(&$this,'printVisibilityToggle'), 'manage-fitstudio-hf', 'infoSection');
			add_settings_field('siteSection', '', array(&$this,'printSiteSection'), 'manage-fitstudio-hf', 'infoSection');
		}
		
		public function getOption($name) {
			return get_option($name);
		}
		
		public function printVisibility() {
			$this->views->openField();
		}
		
		public function printApiKey() {
			$data = array('value' => $this->getOption('fitStudioOptions-apiKey'));
			
			$this->views->openField('fields/keys', $data);
		}
		
		public function printVisibilityToggle() {
			$data = array('value' => $this->getOption('fitStudioOptions-visibility'));
			
			$this->views->openField('fields/visibility', $data);
		}
		
		public function printApiUrl() {
			$data = array('value' => $this->getOption('fitStudioOptions-apiUrl'));
			
			$this->views->openField('fields/url', $data);
		}
		
		public function printSiteSection() {
			$data = array('value' => $this->getOption('fitStudioOptions-siteSection'));
			
			$this->views->openField('fields/section', $data);
		}		
	}
?>