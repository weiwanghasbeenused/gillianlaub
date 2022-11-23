<?

class WhatYouSeeIsWhatYouGet
{
	public $patterns = array(
		'wysiwyg_section_opening_pattern' => '/^\[wysiwygsection wysiwygtag=\"(.*?)\"\](.*)/',
		'wysiwyg_section_ending_pattern'  => '[/wysiwygsection]',
		'img' => '/\<img class=\"wysiwygimg\" src=\".*?\" filename=\"(.*?)\"\>/',
		'figcaption' => '/\<figcaption class=\"wysiwygfigcaption\"\s*\>(.*?)\<\/figcaption\>/'
	);

	public $media_root = '/media/';
}