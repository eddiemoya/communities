<?php

class autoptimizeHTML extends autoptimizeBase
{
	private $keepcomments = false;
	
	//Does nothing
	public function read($options)
	{
		//Remove the HTML comments?
		$this->keepcomments = (bool) $options['keepcomments'];
		
		//Nothing to read for HTML
		return true;
	}
	
	//Joins and optimizes CSS
	public function minify()
	{
		if(class_exists('Minify_HTML'))
		{
			// Minify html
			// but don't remove comment-blocks needed by WP Super Cache (& W3 Total Cache)
			if ( ($this->keepcomments===false) && (preg_match( '/<!--mclude|<!--mfunc|<!--dynamic-cached-content-->/', $this->content ))) { 
				$this->content = preg_replace('#(<!--mclude .*<!--/mclude-->)#ise','\'%%MFUNC%%\'.base64_encode("$0").\'%%MFUNC%%\'', $this->content);
				$this->content = preg_replace('#(<!--mfunc.*<!--/mfunc-->)#ise','\'%%MFUNC%%\'.base64_encode("$1").\'%%MFUNC%%\'', $this->content);
				$this->content = preg_replace('#(<!--dynamic-cached-content-->.*<!--/dynamic-cached-content-->)#ise','\'%%MFUNC%%\'.base64_encode("$0").\'%%MFUNC%%\'', $this->content);
				$restore_mfuncs=true;
			}
			
			$options = array('keepComments' => $this->keepcomments);
			$this->content = Minify_HTML::minify($this->content,$options);
			

			if ($restore_mfuncs) {
				$this->content = preg_replace('#%%MFUNC%%(.*)%%MFUNC%%#sie','stripslashes(base64_decode("$1"))',$this->content);
				}
			
			return true;
		}
		
		//Didn't minify :(
		return false;
	}
	
	//Does nothing
	public function cache()
	{
		//No cache for HTML
		return true;
	}
	
	//Returns the content
	public function getcontent()
	{
		return $this->content;
	}
}
