<?php

abstract class autoptimizeBase
{
	protected $content = '';
	
	public function __construct($content)
	{
		$this->content = $content;
		//Best place to catch errors
	}
	
	//Reads the page and collects tags
	abstract public function read($justhead);
	
	//Joins and optimizes collected things
	abstract public function minify();
	
	//Caches the things
	abstract public function cache();
	
	//Returns the content
	abstract public function getcontent();
	
	//Converts an URL to a full path
	protected function getpath($url)
	{
		$siteurl = site_url();
		if ((strpos($url,'//')===false) && (strpos($url,parse_url($siteurl,PHP_URL_HOST))===false)) {
			$url = $siteurl.$url;
		}
        	$path = str_replace(WP_ROOT_URL,'',$url);
	        if(preg_match('#^(https?|ftp)://#i',$path))
        	{
                	/** External script/css (adsense, etc) */
            		return false;
            	}
        	$path = str_replace('//','/',WP_ROOT_DIR.$path);
        	return $path;
	}

	// coz I'm a crappy developer and I need easy access to whatever I want to log
	protected function ao_logger($logmsg) {
		$logfile=WP_CONTENT_DIR.'/ao_log.txt';
		$logmsg.="\n";
		file_put_contents($logfile,$logmsg,FILE_APPEND);
	}
}
