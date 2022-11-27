<?
require_once('WhatYouSeeIsWhatYouGet.php');

class WhatYouSee extends WhatYouSeeIsWhatYouGet{
	public function __construct(array $medias){
		$this->medias = $medias;
	}

	public function updateMedia($new){
		if(!empty($new))
			array_merge($this->$media, $new);
	}
	public function render($var, $input){
		$output = '';
		$isEmpty = false;
		if(!empty($input) || ( isset($params['acceptEmptyContent']) && $params['acceptEmptyContent']))
		{
			$temp_arr = explode($this->patterns['wysiwyg_section_ending_pattern'], $input);
			if( count($temp_arr) > 1)
			{
				$output .= '<div class="wysiwyg-section add-parent">' . $this->renderAdd($var) . '</div>';
				foreach($temp_arr as $block_temp)
                {
                	preg_match($this->patterns['wysiwyg_section_opening_pattern'], $block_temp, $match);
        			if(!empty($match) && !empty(trim($match[2]))){
        				$thisType = $match[1];
        				$thisContent = trimBreaksFromSides($match[2]);             
        				$output .= '<div class="wysiwyg-section">';
						$output .= $this->renderRemove();

						if($thisType == 'figure')
							$output .= $this->renderFigure($var, $thisContent);
						else if($thisType == 'p' || $thisType == 'text' )
							$output .= $this->renderText($var, $thisContent);
						$output .= '</div>';
						$output .= '<div class="wysiwyg-section add-parent">' . $this->renderAdd($var) . '</div>';
        			}
                }
				
			}
			else
				$isEmpty = true;
		}
		else
			$isEmpty = true;
		if($isEmpty)
			return '<div class="wysiwyg-section add-parent">' . $this->renderAdd($var) . '</div>';
		return $output;
		
	}
	public function renderBlock($type, $var, $content, $params = array()){
		// $output = '';
		
		$output = '<div class="wysiwyg-section">';
		$output .= $this->renderRemove();
		if($type == 'figure')
			$output .= $this->renderFigure($var, $content, ...$params);
		else if($type == 'p' || $type == 'text' )
			$output .= $this->renderText($var, $content, ...$params);
		$output .= '</div>';
		$output .= '<div class="wysiwyg-section add-parent">' . $this->renderAdd($var) . '</div>';
		
		return $output;
	}
	
	public function renderText($var, $content, $acceptEmptyContent = false){
		$output = str_replace('<br>', "\n\r", $content);
		$output = trim($output);
		if(!empty($output) || $acceptEmptyContent){
			$output = '<textarea class="wysiwyg-edit-container wysiwyg-edit-p" type="p" fieldname="'.$var.'" rows="7" placeholder="Click to start writing...">'.$output.'</textarea>'; 
		}
		return $output;
	}

	public function renderImageBlock($var, $value, $class="", $attr = array()){
		$attr_str = '';
		if(!empty($attr))
		{
			foreach($attr as $key => $val)
				$attr_str .= ' ' . $key . '="' . $val . '"';
		}
		
		if(empty($value) || $value == 'null'){
			$src = 'null';
			$class .= ' empty';
		}
		else{
			$src = $value; 
		}
		$output = '<div class="image-container '.$class.'"'.$attr_str.'>' . $this->renderMediaToolbar();
		$output .= '<img class="display-image" onclick="displayMediaToolbar(this);" src="'. $src .'">';
		$output .= '<div class="msg-container" onclick="displayMediaToolbar(this);"><span class="msg"></span></div></div>';
		return $output;
	}

	private function renderMediaToolbar($id = ''){
		$class = "media-toolbar-container toolbar-container";
		if(empty($this->medias)) $class .= ' empty'; 
		$toolbar_html = '<div class="'.$class.'"><div class="media-toolbar-toggle" onclick="displayMediaToolbar(this);"></div>';
		$toolbar_html .= '<div id="'.$id.'" class="toolbar media-toolbar">';
		foreach($this->medias as $m)
			$toolbar_html .= '<div class="toolbar-image-container" ><img onclick="useThisImage(this);" src="' . $this->m_rel($m) . '"></div>';
		$toolbar_html .= '</div><div class="msg-container"><div class="msg"></div></div></div>';
		return $toolbar_html;
	}
	public function renderFigure($var, $content, $imageBlockClass=''){
		$output = '';
		preg_match($this->patterns['img'], $content, $img_match);
		if(!empty($img_match))
			$src = $img_match[1];
		else
			$src = 'null';
		$caption = '';
		if(strpos($content, '<figcaption class="wysiwygfigcaption">') !== false){
			preg_match($this->patterns['figcaption'], $content, $figcaption_match);
			if(!empty($figcaption_match))
				$caption = $figcaption_match[1];
		}
		$imageBlockClass .= ' wysiwyg-edit-img-wrapper';
		$media_html = $this->renderImageBlock($var, $src, $imageBlockClass);
		$output .= '<div class="wysiwyg-edit-container wysiwyg-edit-figure" fieldname="'.$var.'" type="figure">'.$media_html.'<textarea class="wysiwyg-edit-figcaption" placeholder="click to add caption . . ." rows="2">' . $caption . '</textarea></div>'; 
		return $output;
	}
	public function renderAdd($var){
		$output = '<div class="wysiwyg-add-section" fieldname="'.$var.'"><div class="add-prompt-container"><div class="wysiwyg-add-toggle" onclick="toggleSectionOptions(this);"><div class="icon">&plus;</div><div class="msg"><span class="inline-icon">&plus;</span>Add a block here</div></div></div><div class="add-options-container"><div class="wysiwyg-add-toggle" onclick="toggleSectionOptions(this);"><div class="msg"><span class="inline-icon">&times;</span>Cancel</div></div><div class="section-option" onclick="addSectionHere(this, \''.$var.'\', \'text\', medias);">Paragraph</div><div class="section-option" onclick="addSectionHere(this, \''.$var.'\', \'image\', medias);">Image</div><div class="section-option" onclick="addSectionHere(this, \''.$var.'\', \'video\', medias);">Video</div></div></div>';
		return $output;
	}
	private function renderRemove(){
		$output = '<div class="btn-remove-wysiwyg-section" onclick="removeWysiwygSection(this);">&times;</div><div class="msg-container msg-container-remove"><span class="msg">Remove this block</span></div>';
		return $output;
	}

	private function m_rel($m){
		return $this->media_root . str_pad($m['id'], 5, "0", STR_PAD_LEFT) . '.' . $m['type'];
	}
}