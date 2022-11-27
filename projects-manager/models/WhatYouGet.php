<?
require_once('WhatYouSeeIsWhatYouGet.php');
class WhatYouGet extends WhatYouSeeIsWhatYouGet{
	public function __construct(){
		$this->uri = explode('/', strtok($_SERVER['REQUEST_URI'],"?"));
	}
	public function renderScroll($input){
		$body = trimBreaksFromSides($input);
		$body = str_replace("\r", '<br>', $body);
    	$body = str_replace("\n", '<br>', $body);
		$body_arr = explode($this->patterns['wysiwyg_section_ending_pattern'], $body);
		$output = '';
    	if(!empty($body_arr))
    	{
    		foreach($body_arr as $key => $section)
    		{
    			$section = trim($section);
    			if(!empty($section))
    			{
    				preg_match($this->patterns['wysiwyg_section_opening_pattern'], $section, $match);
        			if(!empty($match) && !empty(trim($match[2]))){

        				$thisType = $match[1];
        				$thisContent = trimBreaksFromSides($match[2]);
        				if($thisType == 'figure')
        				{
    						$output .= $this->renderFigure($thisContent);
    						
        				}
        				else if($thisType == 'p' && !empty($thisContent))
        				{
        					$output .= $this->renderText($thisContent);
        				}
        			}
    			}                        			
    		}
    	}
        echo $output;
	}
	public function prepareGridItems($input){
		$body = trimBreaksFromSides($input);
		$body = str_replace("\r", '<br>', $body);
    	$body = str_replace("\n", '<br>', $body);
		$body_arr = explode($this->patterns['wysiwyg_section_ending_pattern'], $body);
		$output = array();
    	if(!empty($body_arr))
    	{
    		foreach($body_arr as $key => $section)
    		{
    			$section = trim($section);
    			if(!empty($section))
    			{
    				preg_match($this->patterns['wysiwyg_section_opening_pattern'], $section, $match);
        			if(!empty($match) && !empty(trim($match[2]))){

        				$thisType = $match[1];
        				$thisContent = trimBreaksFromSides($match[2]);
        				if($thisType == 'figure')
        				{
        					$this_item = array(
        						'id' => '',
        						'caption' => '',
        						'fullUrl' => ''
        					);
        					preg_match($this->patterns['img'], $thisContent, $img_match);
        					if(!empty($img_match)){
        						$src = $img_match[1];
								$temp = explode('/', $src);
								$temp = end($temp);
								$media_id = intval(substr($temp, 0, strpos($temp, '.')));
								$type = substr($temp, strpos($temp, '.') + 1);
								$this_item['id'] = $media_id;
								$this_item['type'] = $type;
								$fullUrl = implode('/', $this->uri) . '?section=' . $_GET['section'] . '&layout=scroll#figure-'.$media_id;
								$this_item['fullUrl'] = $fullUrl;
        					}
        					if(strpos($input, '<figcaption class="wysiwygfigcaption">') !== false){
								preg_match($this->patterns['figcaption'], $input, $figcaption_match);
								if(!empty($figcaption_match))
									$caption = $figcaption_match[1];
								$this_item['caption'] = $caption;
							}
        					$output[] = $this_item;
        				}
        			}
    			}                        			
    		}
    	}
        return $output;
	}
	private function renderFigure($input){
		$output = '';
		$src = '';
		$caption = '';
		$media_id = '';
		$img_class = 'gallery-image lightbox-btn';
		$ratio = 0;
		preg_match($this->patterns['img'], $input, $img_match);
		if(!empty($img_match)){
			$src = $img_match[1];
			$temp = explode('/', $src);
			$temp = end($temp);
			$media_id = intval(substr($temp, 0, strpos($temp, '.')));
			$size = getimagesize(substr($src, 1));
			if($size[0] > $size[1])
				$img_class .= ' landscape';
			else if($size[0] < $size[1])
				$img_class .= ' portrait';
			else
				$img_class .= ' square';
			$ratio = $size[1] / $size[0];
			$ratio = intval($ratio * 10) / 10;
		}
		if(strpos($input, '<figcaption class="wysiwygfigcaption">') !== false){
			preg_match($this->patterns['figcaption'], $input, $figcaption_match);
			if(!empty($figcaption_match))
				$caption = $figcaption_match[1];
		}

		$output = '<figure id="figure-'.$media_id.'" class="gallery-element hashlink-target" ratio="'.$ratio.'"><img class="'.$img_class.'" src="'.$src.'" onload="centerHashlinkTarget(this);" >';
		if(!empty($caption))
			$output .= '<figcaption class="caption gallery-caption">' . $caption . '</figcaption>';
		$output .= '</figure>';
		return $output;
	}
	private function renderText($input){
		$output = str_replace('<br>', "\n\r", $input);
		$output = '<p class="gallery-element">'.$output.'</p>'; 
		return $output;
	}
}