<?

class WhatYouGet extends WhatYouSeeIsWhatYouGet{
	public function render($input){
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
	private function renderFigure($input){
		$output = '';
		// $filename = '';
		$src = '';
		$caption = '';
		$img_class = 'gallery-image';
		preg_match($this->patterns['img'], $input, $img_match);
		if(!empty($img_match)){
			$src = $img_match[1];
			// $src = $this->media_root . $src;
			// $src = $src;
			var_dump($src);
			$size = getimagesize(substr($src, 1));
			if($size[0] > $size[1])
				$img_class .= ' landscape';
			else if($size[0] < $size[1])
				$img_class .= ' portrait';
			else
				$img_class .= ' square';
		}
		if(strpos($input, '<figcaption class="wysiwygfigcaption">') !== false){
			preg_match($this->patterns['figcaption'], $input, $figcaption_match);
			if(!empty($figcaption_match))
				$caption = $figcaption_match[1];
		}

		$output = '<figure class="gallery-element"><img class="'.$img_class.'" src="'.$src.'">';
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