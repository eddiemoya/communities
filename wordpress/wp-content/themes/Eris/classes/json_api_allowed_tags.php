<?php
/**
 * 
 * JSON_Api_Allowed_Tags: Makes it possible to filter out html elements from post content diplayed
 *  in JSON_Api response. It 'listens' for the following QS: allowed_html, allowed_html_group, and remove_html.
 *  
 *  allowed_html: a csv of the html elements you explicity want to display in post content.
 *  allowed_html_group: the name of the pre-defined group of allowed tags that you want to display. These are
 *  	stored in the $_groups property.
 *  remove_html: a csv of html elements that you DO NOT want to display. Display every whitelisted element as defined
 *  	in $allowedposttags (wp-includes/kses.php)
 *  
 * @author Dan Crimmins
 * @version 1.0
 * @since 11/26/2013
 *
 */
class JSON_Api_Allowed_Tags {
	
	/**
	 * An array of predefined html elements (csv). Add aditional predefined groups here.
	 * @var array
	 * @access protected
	 */
	protected $_groups = array('pdp' => 'p,b,i,ul,li,ol,h1,h2,h3,a');
	
	/**
	 * HTML element attributes that you do not want to be used.
	 * @var array 
	 * @access protected
	 */
	protected $_disallow_attrs = array('class', 'id', 'style');
	
	/**
	 * Contains all HTML elements and their attributes that you want to allow.
	 * @var array
	 * @access protected 
	 * @see wp-includes/kses.php $allowedposttags for structure.
	 */
	protected $_tags;
	
	/**
	 * 
	 * Query vars used for filtering.
	 * @var array 
	 * @access private
	 */
	private $_query_vars = array('allowed_html', 'allowed_html_group', 'remove_html');
	
	
	/**
	 * The query var from $_query_vars that is being used.
	 * @var string
	 * @access protected
	 */
	protected $_query_var;
	
	/**
	 * Holds the values from $allowedposttags (wp-inlcudes/kses.php)
	 * @var array
	 * @access private
	 * @see $allowedposttags in wp-includes/kses.php
	 */
	private $_allowedposttags;
	
	
	/**
	 * Constructor
	 * @access public
	 */
	public function __construct() {
		
		global $allowedposttags;
		
		$this->_allowedposttags = $allowedposttags;
		
		if($this->_get_var()) {
			
			$this->_get_tags();
		}
		
		$this->_add_filter();
		
	}
	
	/**
	 * Factory method
	 * 
	 * @param void
	 * @return object - instance of this class
	 */
	public static function init() {
		
		return new JSON_Api_Allowed_Tags;
	}
	
	/**
	 * Takes $content (response object from JSON API plugin) and allows/excludes html 
	 * 
	 * @param object $content
	 * @uses wp_kses
	 */
	public function content($content) {
		
		//Single post
		if(isset($content['post'])) {
			
			$content['post']->content = wp_kses( html_entity_decode( do_shortcode( $content['post']->content ) ), $this->_tags );
		}
		
		//Multiple posts
		if(isset($content['posts'])) {
			
			foreach($content['posts'] as $post) {
				
				$post->content = wp_kses( html_entity_decode( do_shortcode( $post->content ) ), $this->_tags );
			}
			
		}
		
		return $content;
	}

	/**
	 * Searches for any query vars defined in $_query_vars and sets $_query_var if found.
	 * @param void
	 * @return bool
	 * @access protected
	 */
	protected function _get_var() {
		
		foreach($this->_query_vars as $var) {
			
			if(isset($_GET[$var])) {
				$this->_query_var = $var;
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Grabs tags from query var used and processes them to set $_tags property (array)
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function _get_tags() {
		
		if($tags = $_GET[$this->_query_var]) {
	
			switch($this->_query_var) {
				
				case 'allowed_html':
					
					$this->_process_tags( urldecode($tags) );
					
					break;
					
				case 'allowed_html_group':
					
					if(isset($this->_groups[$tags]))
						$this->_process_tags( $this->_groups[$tags] );
					
					break;
					
				case 'remove_html':
					
					$tags = explode(',', preg_replace('/\s/', '', urldecode($tags)));
					$this->_remove_disallowed_attrs($this->_allowedposttags);
					$this->_tags = $this->_allowedposttags;
					$this->_remove_excluded_html($tags, $this->_tags);
			
					break;
			}
			
			return true;
		
		} else {
			
			return false;
		}
		
	}
	
	/**
	 * Takes the tags received and whitelists them against $allowedposttags, filters out any tag attributes defined 
	 * in $_disallow_attrs. Sets the $_tags property
	 * 
	 * @param string $tags
	 * @return void
	 */
	protected function _process_tags($tags) {
		
		//Return array of html elements that are allowed based on $allowedposttags from the tags requested ($tags)
		$whitelisted = array_intersect_key( array_flip( explode(',', preg_replace('/\s/', '', $tags) ) ), $this->_allowedposttags );
		
		//array of allowed attributes for each element in $whitelisted
		$allowed_attrs = array_map( array($this, '_get_attrs'), $whitelisted, array_keys( $whitelisted ) );
		
		//Remove any attributes in $_disallow_attrs from $allowed_attrs
		$this->_remove_disallowed_attrs($allowed_attrs);
		
		//Create array for each whitlisted element with an array of allowed attrs for each as its value
		//and assign to $_tags property
		$this->_tags = array_combine(array_keys($whitelisted), $allowed_attrs);
		
	}
	
	/**
	 * Returns array of allowed attributes for a given element as defined in $allowedposttags
	 * @param array $val
	 * @param atring $key
	 * @return array
	 */
	private function _get_attrs($val, $key) {
		
		return $this->_allowedposttags[$key];
		
	}
	
	/**
	 * Removes disallowed attributes for a given element as defined in $_disallow_attrs
	 * @param array $attr_array
	 * @return void
	 */
	private function _remove_disallowed_attrs(&$attr_array) {
		
		foreach($this->_disallow_attrs as $attr) {
			
			foreach($attr_array as $key=>&$attrs) {
				
				if(array_key_exists($attr, $attrs)) {
					
					unset($attrs[$attr]);
				}
			}
		}
		
	}
	
	/**
	 * Removes any tags defined in $remove_tags from $allowed_tags
	 * 
	 * @param array $remove_tags
	 * @param array $allowed_tags
	 */
	private function _remove_excluded_html($remove_tags, &$allowed_tags) {
		
		foreach($remove_tags as $tag) {
			
			if(isset($allowed_tags[$tag])) unset($allowed_tags[$tag]);
		}
	}
	
	/**
	 * Assigns content() method to the JSON API plugin's json_api_encode filter.
	 * 
	 * @param void
	 * @return void
	 */
	protected function _add_filter() {
		
		add_filter('json_api_encode', array($this, 'content'));
	}
	
}