<?

class WhatYouSeeIsWhatYouGet
{
	public function __construct(){
		$this->uri = explode('/', strtok($_SERVER['REQUEST_URI'],"?"));
	}
	public $patterns = array(
		'wysiwyg_section_opening_pattern' => '/^\[wysiwygsection wysiwygtag=\"(.*?)\"\](.*)/',
		'wysiwyg_section_ending_pattern'  => '[/wysiwygsection]',
		'img' => '/\<img class=\"wysiwygimg\" src=\"(.*?)\".*?\>/',
		'figcaption' => '/\<figcaption class=\"wysiwygfigcaption\"\s*\>(.*?)\<\/figcaption\>/'
	);

	public $media_root = '/media/';
	// public $uri = explode('/', strtok($_SERVER['REQUEST_URI'],"?"));
	// public $uri = $_SERVER['REQUEST_URI'];
}