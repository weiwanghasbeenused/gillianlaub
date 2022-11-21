<?

$wysiwygPattern = array(
	'img' => '/\<img class=\"wysiwygimg\" src="(.*)"\>/',
	'figcaption' => '/\<figcaption class=\"wysiwygfigcaption\"\s*\>(.*?)\<\/figcaption\>/'
);
function renderSelect($var, $value, $options){
	$output = '';
	if(!empty($options))
	{
		foreach($options as $option){
			$displayName = isset($option['displayName']) ? $option['displayName'] : $option['name1'];
			$output .= '<option>' . $displayName . '</option>';
		};
		$output = '<select name="'.$var.'" value="'.$value.'">' . $output . '</select>';
	}
	return $output; 
}
function renderImageBlock($var, $value, $class="", $media=array()){
	$output = '<div class="image-container '.$class.'">'.renderMediaToolbar($media);
	if(empty($value))
		$value = 'null';
	$output .= '<img class="display-image" onclick="displayMediaToolbar(this, medias);" src="'. $value .'">';
	$output .= '</div>';
	return $output;
}
function renderMediaToolbar($media, $id = ''){
	$toolbar_html = '<div class="media-toolbar-toggle" onclick="displayMediaToolbar(this, medias)"></div>';
	$toolbar_html .= empty($media) ? '<div id="'.$id.'" class="toolbar media-toolbar empty">' : '<div id="'.$id.'" class="toolbar media-toolbar">';
	$toolbar_html .= '<div class="empty-msg">Please upload media first.</div>';
	foreach($media as $m)
	{
		$toolbar_html .= '<div class="toolbar-image-container" ><img onclick="useThisImage(this);" src="'.m_url($m).'"></div>';
	}
	$toolbar_html .= '</div>';
	return $toolbar_html;
}
function renderWysiwygElement(){

}
function renderWysiwygAdd($var){
	$output = '<div class="wysiwyg-add-section" fieldname="'.$var.'"><div class="add-prompt-container"><div class="wysiwyg-add-toggle" onclick="toggleSectionOptions(this);"><div class="msg"><span class="inline-icon">&plus;</span>Add a section here</div></div></div><div class="add-options-container"><div class="wysiwyg-add-toggle" onclick="toggleSectionOptions(this);"><div class="msg"><span class="inline-icon">&times;</span>Cancel</div></div><div class="section-option" onclick="addSectionHere(this, \''.$var.'\', \'text\');">Text</div><div class="section-option" onclick="addSectionHere(this, \''.$var.'\', \'image\');">Image</div><div class="section-option" onclick="addSectionHere(this, \''.$var.'\', \'video\');">Video</div></div></div>';
	return $output;
}

function renderWysiwygFigure($var, $content, $media=array()){
	global $wysiwygPattern;
	$output = '';
	preg_match($wysiwygPattern['img'], $content, $img_match);
	if(!empty($img_match)){
		$src = $img_match[1];
	}
	else
		$src = 'null';

	$caption = '';
	if(strpos($content, '<figcaption class="wysiwygfigcaption">') !== false){
		preg_match($wysiwygPattern['figcaption'], $content, $figcaption_match);
		if(!empty($figcaption_match))
			$caption = $figcaption_match[1];
	}
	$media_html = renderImageBlock($var, $src, 'wysiwyg-edit-img-wrapper', $media);
	$output .= '<div class="wysiwyg-edit-container wysiwyg-edit-figure" fieldname="'.$var.'">'.$media_html.'<textarea class="wysiwyg-edit-figcaption" placeholder="click to add caption . . ." rows="2">' . $caption . '</textarea></div>'; 
	return $output;
}

function renderWysiwygText($var, $content, $acceptEmptyContent = false){
	global $wysiwygPattern;
	// $output = '';
	$output = str_replace('<br>', "\n\r", $content);
	$output = trim($output);
	if(!empty($output) || $acceptEmptyContent){
		$output = '<textarea class="wysiwyg-edit-container wysiwyg-edit-p" fieldname="'.$var.'" rows="7" placeholder="Click to start writing...">'.$output.'</textarea>'; 
	}
	return $output;
}

function trimBreaksFromSides($string){
	$output = str_replace('<br>', "\n\r", $string);
	return trim($output);
}