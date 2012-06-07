<?php
	class FitStudioViewActions {
		public function __construct() {
			
		}
		
		public function openView($view='', $data=null) {
			if(isset($view) && !empty($view)) {
				$this->_open(FITSTUDIO_VIEWS_DIR.$view.'.view.php', $data);
			}
		}
		
		public function openField($field='', $data=null) {
			if(isset($field) && !empty($field)) {
				$this->_open(FITSTUDIO_VIEWS_DIR.$field.'.view.php', $data);
			}
		}
		
		private function _open($file, $data=null) {
			if(file_exists($file)) {
				if(is_array($data) && !empty($data)) {
					// Make the data array available to the view. - Thanks, Brian Greenacre				
					extract($data, EXTR_SKIP);
				}
				
				include($file);
			}
		}
	}
?>