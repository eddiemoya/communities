<?php
/**
 * External/shared functions
 */
require_once( 'functions.php' );

class WP_CDN_Rewrite {
	/**
	 * The name of the plugin
	 */
	const NAME = 'CDN Rewrite';
	
	/**
	 * The slug to use for plugin URLs
	 */
	const SLUG = 'wpcdnrewrite';
	
	/**
	 * Required user capability
	 */
	const REQUIRED_CAPABILITY = 'manage_options';
	
	/**
	 * Version of the plugin
	 */
    const VERSION = '1.1';
    
    /**
     * wp_options key for the plugin version
     */
	const VERSION_KEY = 'wpcdnrewrite-version';
	
	/**
	 * wp_options key for rules
	 */
	const RULES_KEY = 'wpcdnrewrite-rules';
	
	/**
	 * wp_options key for domains to rewrite URLs for
	 */
	const WHITELIST_KEY = 'wpcdnrewrite-whitelist';
	
	/**
	 * Constant to signify that a rule is only for the host portion of the url
	 */
	const REWRITE_TYPE_HOST_ONLY = 1;
	
	/**
	 * Constant to signify that a rule is for the full URL up to the file
	 */
	const REWRITE_TYPE_FULL_URL = 2;
	

	/**
	 * Creates a new WP_CDN_Rewrite object
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @return    object  A new WP_CDN_Rewrite object
	 */
	public function __construct() {
        // Only register the admin call backs if we're in the admin app
        if ( is_admin() ) {
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'include_admin_javascript' ) );
        }

        register_uninstall_hook( __FILE__, array( 'WP_CDN_Rewrite', 'uninstall' ) );
        
        // Add filters to run our rewrite code on
        if ( function_exists( 'is_multisite' ) && is_multisite() ) {
        	add_filter( 'muplugins_loaded', array( $this, 'startup' ), 5 );
        } else {
        	add_filter( 'plugins_loaded', array( $this, 'startup' ), 5 );
        }
        add_filter( 'shutdown', array( $this, 'shutdown' ), 20 );
	}
	
	/**
	 * Filter to start buffering at the start of WordPress' work
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @return    void
	 */
	public function startup() {
		
		if ( ! is_admin() ) {
			$ret = ob_start( 'wpcdn_rewrite_content' ); 
		}
	}
	
	/**
	 * Filter to end buffering/flush any remaining buffer at the end of WordPress' work
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @return void
	 */
	public function shutdown() {
		if ( ! is_admin() ) {
			@ob_end_flush();
		}
	}

    /**
     * The admin_init hook runs as soon as the admin initializes and we use it
     * to add our settings to the whitelist of allowed options
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @return    void
     */
    public function admin_init() {
        register_setting( 'wpcdnrewrite', self::RULES_KEY, array( $this, 'sanitize_rules' ) );
        register_setting( 'wpcdnrewrite', self::WHITELIST_KEY, array( $this, 'sanitize_whitelist' ) );
    }
	
	/**
     * Adds a link to our settings page under the Settings menu
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @return    void
     */
    public function admin_menu() {
		add_options_page( self::NAME, self::NAME, self::REQUIRED_CAPABILITY, self::SLUG, array( $this, 'show_config' ) );
	}

    /**
     * adds the necessary wordpress options for the plugin to use later. Only runs on activation
     *
     * @return    void
     */
    public function activate() {
        $host = parse_url( network_site_url(), PHP_URL_HOST );
        // add_option only runs if the option doesn't exist
        add_option( self::VERSION_KEY, self::VERSION );
        add_option( self::RULES_KEY, array() );
        add_option( self::WHITELIST_KEY, array( $host ) );
    }

    /**
     * Adds admin.js to the <head>
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @return    void
     */
    public function include_admin_javascript() {
        wp_enqueue_script( 'admin.js', plugins_url( 'html/admin.js', __FILE__ ), array( 'jquery' ) );
    }
	
	/**
     * Shows the configuration page within the settings
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @return    void
     */
    public function show_config() {
		if ( ! current_user_can( self::REQUIRED_CAPABILITY ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		require_once( 'html/config.php' );
	}
	
	/**
     * Rewrites the specified content per specified rules
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @param     string  $content         The text to rewrite
	 * @return    string  The new content with appropriate URLs rewritten
     */
    public function rewrite_content( $content ) {
    	// Grab the version number we're working with
		$version = get_option( self::VERSION_KEY );
		
		if (( strcmp( $version, '1.0' ) >= 0 ) && strlen($content)) {
			// Pull the rules and whitelist arrays from the database
			$rules = get_option( self::RULES_KEY );
			$whitelist = get_option( self::WHITELIST_KEY );
			
			// Get a DOM object for this content that we can manipulate
			$dom = new DOMDocument();
			@$dom->loadHTML( $content );
			$dom->formatOutput = true;
			
			// Rewrite URLs
			$this->do_rewrite( $dom, $rules, $whitelist, 'a', 'href' );
			$this->do_rewrite( $dom, $rules, $whitelist, 'img', 'src' );
			$this->do_rewrite( $dom, $rules, $whitelist, 'script', 'src' );
			$this->do_rewrite( $dom, $rules, $whitelist, 'link', 'href' );
			
			// Grab the modified HTML
			$newContent = $dom->saveHTML();
			
			return $newContent;
		}
		
		return $content;
	}

    /**
     * Deletes all of the stuff we put into the database so that we don't leave anything behind to corrupt future installs
     * 
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @return    void
     */
    public static function uninstall() {
        delete_option( self::VERSION_KEY );
        delete_option( self::RULES_KEY );
        delete_option( self::WHITELIST_KEY );
    }
    
    /**
     * Does the actual URL rewriting for a given DOMDocument object
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @param     DOMDocument  $dom        The DOM to rewrite URLs in
	 * @param     array        $rules      Rewrite rules
	 * @param     array        $whiteList  Array of server names to rewrite links for
	 * @param     string       $tag        The tag type to rewrite for
	 * @param     string       $attribute  The attribute to rewrite for on the specified tag
	 * @return    void
     */
    protected function do_rewrite( $dom, $rules = array(), $whitelist = array(), $tag = 'a', $attribute = 'href' ) {
    	// Make sure we got a valid DOM
    	if ( NULL == $dom ) {
    		wp_die( "Invalid DOM passed to WP CDN Rewrite's do_rewrite()" );
    	}
    	
    	// Go through all of the tags of the type specified…
    	$tags = $dom->getElementsByTagName( $tag );
		if ( ! is_null( $tags ) ) {
			foreach ( $tags as $tag ) {
				// …and look for ones that have the requested attribute
				if ( $tag->hasAttribute( $attribute ) ) {
					$url = $tag->getAttribute( $attribute );
					
					/*if ( $this->starts_with( $url, '/' ) ) {
						$base = network_site_url();
						if ( ! $this->starts_with( $base, '/' ) ) {
							$base = $base . '/';
						}
						$url = $base . $url;
					}*/
					$parsed = parse_url( $url );
					
					if ( FALSE !== $parsed ) {
						$host = $parsed['host'];
						if ( in_array( $host, $whitelist ) ) {
							// The target is on a whitelisted domain, so
							// we want to rewrite the url
							
							$matchedRule = NULL;
							foreach ( $rules as $rule ) {
								$path = $parsed['path'];
								
								if ( $this->ends_with( $path, $rule['match'] ) ) {
									// Found a rule to rewrite for
									$matchedRule = $rule;
									break;
								}
							}
							
							$tag->setAttribute( $attribute, $this->rewrite_url( $url, $matchedRule ) );
						}
					}
				}
			}
		}
    }
    
    /**
     * Rewrites one URL per the specified rule
     * 
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @param     string  $url             The URL
	 * @param     array   $rule            Rewrite rule
	 * @return    string  The rewritten URL
     */
    protected function rewrite_url( $url, $rule ) {
    	if ( NULL == $rule ) {
    		return $url;
    	}
    	
    	$ret = $url;
    	
		if ( self::REWRITE_TYPE_HOST_ONLY == $rule['type'] ) {
			$host = parse_url( $ret, PHP_URL_HOST );
			
			// Set the scheme and host if we have an absolute path
			if ( FALSE === $host ) {
				$host = network_site_url();
			}
			
			// Find the stuff to the left and right of the host
			$oldHostLen = strlen( $host );
			$leftLen = strpos( $ret, $host );
			$rightLen = strlen( $ret ) - ( $leftLen + $oldHostLen );
			
			$left = substr( $ret, 0, $leftLen );
			$right = substr( $ret, $leftLen + $oldHostLen );
			
			// Build a new URL with our replacement host
			$ret = $left . $rule['rule'] . $right;
			
		}
		else if ( self::REWRITE_TYPE_FULL_URL == $rule['type'] ) {
			$filename = pathinfo( parse_url( $ret, PHP_URL_PATH ), PATHINFO_BASENAME );
			$ret = $rule['rule'];
			
			// Make sure we have a / on the end
			if ( ! $this->ends_with( $ret, '/' ) ) {
				$ret = $ret . '/';
			}
			
			$ret = $ret . $filename;
			
			// Add in the scheme and host for an absolute path
			if ( $this->starts_with( $ret, '/' ) ) {
				$base = network_site_url();
				if ( ! $this->ends_with( $base, '/' ) ) {
					$base = $base . '/';
				}
				
				$ret = $base . $ret;
			}
		}
		
		return $ret;
    }


    /**
     * Sanitize the array of rules
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @param     array   $ruleArray       Array of rules
	 * @return    array   Array of sanitized rules
     */
    public function sanitize_rules( array $ruleArray ) {
        $allowedTypes = array(
            self::REWRITE_TYPE_FULL_URL,
            self::REWRITE_TYPE_HOST_ONLY,
        );

        foreach ( $ruleArray as $key => $rule ) {
            if ( ! in_array( $rule['type'], $allowedTypes ) ) {
                unset( $ruleArray[$key] );
                add_settings_error( self::RULES_KEY, self::RULES_KEY, 'Invalid rule type entered' );
                continue;
            }

            $rule['match'] = preg_replace( '/\W/', '', $rule['match'] );
            if ( trim( $rule['match'] ) == '' ) {
                unset( $ruleArray[$key] );
                continue;
            }

            $validRule = true;
            if ( $rule['type'] == self::REWRITE_TYPE_FULL_URL ) {
                $rule['rule'] = filter_var( $rule['rule'], FILTER_SANITIZE_URL );
                $validRule = filter_var( $rule['rule'], FILTER_VALIDATE_URL );
            } elseif ( $rule['type'] == self::REWRITE_TYPE_HOST_ONLY ) {
                $rule['rule'] = preg_replace( '/[http|https]:\/\//', '', $rule['rule'] );
                $validRule = self::validate_domain_name( $rule['rule'] );
            }

            if ( ! $validRule ) {
                unset( $ruleArray[$key] );
                add_settings_error( self::RULES_KEY, self::RULES_KEY, 'Invalid rewrite URL entered' );
            } else {
                $ruleArray[$key] = $rule;
            }
        }

        // Make sure all the indexes are contiguous
        $ruleArray = array_values( $ruleArray );
        return $ruleArray;
    }

    /**
     * Sanitize the array of domains
     * 
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @param     array   $valueArray      Array of whitelist rules
	 * @return    array   Array of sanitized whitelist rules
     */
    public function sanitize_whitelist( array $valueArray ) {
        foreach ( $valueArray as $key => $value ) {
            $value = trim( $value );

            if ( $value == '' ) {
                unset( $valueArray[$key] );
            } else {
                // Strip http, https, and ://
                $value = preg_replace( '/[http|https]:\/\//', '', $value );

                $validDomain = self::validate_domain_name( $value );
                if ( false == $validDomain ) {
                    add_settings_error( self::WHITELIST_KEY, self::WHITELIST_KEY, 'Invalid domain name "{$value}" entered' );
                } else {
                    $valueArray[$key] = $value;
                }
            }
        }

        // Make sure all the indexes are contiguous
        $valueArray = array_values( $valueArray );
        return $valueArray;
    }

    /**
	 * Tests whether a text starts with the given string or not
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @param     string  $haystack        The text to search
	 * @param     string  $needle          The string to search for
	 * @return    bool    True if the text starts with a given string, else false
	 */
	protected function starts_with( $haystack, $needle ) {
    	$needleLen = strlen( $needle );
    	return substr( $haystack, 0, $needleLen ) === $needle;
    }
    
    /**
	 * Tests whether a text ends with the given string or not
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * @source  http://www.jonasjohn.de/snippets/php/ends-with.htm
	 * 
	 * @param     int     $count           The rule number
	 * @param     string  $haystack        The text to search
	 * @param     string  $needle          The string to look for
	 * @return    bool    True if the text ends with a given string, else false
	 */
	protected function ends_with( $haystack, $needle ){
	    return strrpos( $haystack, $needle ) === strlen( $haystack ) - strlen( $needle );
	}


    /**
     * Used to check and see if the domains that are posted are valid
	 *
	 * @package WP CDN Rewrite
	 * @since 1.0
	 * 
	 * @param     string  $domainName      The domain name to validate
	 * @return    bool    True if the domain name is valid, else false
     */
    protected function validate_domain_name( $domainName ) {
        $pieces = explode( '.', $domainName );
        foreach ( $pieces as $piece ) {
            if ( ! preg_match( '/^[a-z\d][a-z\d-]{0,62}$/i', $piece ) || preg_match( '/-$/', $piece ) ) {
                return false;
            }
        }
        return true;
    }
}

//this is technically the activation hook but WP 3.3.1 doesn't run those anymore apparently...
// so this is kind of a hack
add_action('wp_loaded', array('WP_CDN_Rewrite', 'activate'));
new WP_CDN_Rewrite();