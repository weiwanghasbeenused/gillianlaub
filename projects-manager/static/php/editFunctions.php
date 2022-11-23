<?

$wysiwygPattern = array(
	'img' => '/\<img class=\"wysiwygimg\" src=\".*?\" filename=\"(.*?)\"\>/',
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
function renderImageBlock($var, $value, $class="", $media=array(), $attr = array()){
	global $media_path;
	$attr_str = '';
	if(!empty($attr))
	{
		foreach($attr as $key => $val)
			$attr_str .= ' ' . $key . '="' . $val . '"';
	}
	$output = '<div class="image-container '.$class.'"'.$attr_str.'>'.renderMediaToolbar($media);
	if(empty($value)){
		$src = 'null';
		$filename = 'null';
	}
	else {
		$src = $media_path . $value; 
		$filename = $value;
	}
	$output .= '<img class="display-image" onclick="displayMediaToolbar(this, medias);" src="'. $src .'" filename = "'.$filename.'">';
	$output .= '<div class="msg-container"><span class="msg">Replace the image</span></div></div>';
	return $output;
}
function renderMediaToolbar($media, $id = ''){
	$toolbar_html = '<div class="media-toolbar-toggle" onclick="displayMediaToolbar(this, medias)"></div>';
	$toolbar_html .= empty($media) ? '<div id="'.$id.'" class="toolbar media-toolbar empty">' : '<div id="'.$id.'" class="toolbar media-toolbar">';
	$toolbar_html .= '<div class="empty-msg">Please upload media first.</div>';
	foreach($media as $m)
	{
		$filename = $m['filename'];
		$toolbar_html .= '<div class="toolbar-image-container" ><img onclick="useThisImage(this);" src="'.m_rel($m).'" filename="'.$filename.'"></div>';
	}
	$toolbar_html .= '</div>';
	return $toolbar_html;
}
function renderWysiwygElement(){

}
function renderWysiwygAdd($var){
	$output = '<div class="wysiwyg-add-section" fieldname="'.$var.'"><div class="add-prompt-container"><div class="wysiwyg-add-toggle" onclick="toggleSectionOptions(this);"><div class="msg"><span class="inline-icon">&plus;</span>Add a section here</div></div></div><div class="add-options-container"><div class="wysiwyg-add-toggle" onclick="toggleSectionOptions(this);"><div class="msg"><span class="inline-icon">&times;</span>Cancel</div></div><div class="section-option" onclick="addSectionHere(this, \''.$var.'\', \'text\');">Paragraph</div><div class="section-option" onclick="addSectionHere(this, \''.$var.'\', \'image\');">Image</div><div class="section-option" onclick="addSectionHere(this, \''.$var.'\', \'video\');">Video</div></div></div>';
	return $output;
}

function renderWysiwygFigure($var, $content, $media=array(), $imageBlockClass=''){
	global $wysiwygPattern;
	$output = '';
	preg_match($wysiwygPattern['img'], $content, $img_match);
	if(!empty($img_match)){
		$filename = $img_match[1];
	}
	else
		$filename = '';

	$caption = '';
	if(strpos($content, '<figcaption class="wysiwygfigcaption">') !== false){
		preg_match($wysiwygPattern['figcaption'], $content, $figcaption_match);
		if(!empty($figcaption_match))
			$caption = $figcaption_match[1];
	}
	$imageBlockClass .= ' wysiwyg-edit-img-wrapper';
	$media_html = renderImageBlock($var, $filename, $imageBlockClass, $media);
	$output .= '<div class="wysiwyg-edit-container wysiwyg-edit-figure" fieldname="'.$var.'" type="figure">'.$media_html.'<textarea class="wysiwyg-edit-figcaption" placeholder="click to add caption . . ." rows="2">' . $caption . '</textarea></div>'; 
	return $output;
}

function renderWysiwygText($var, $content, $acceptEmptyContent = false){
	global $wysiwygPattern;
	// $output = '';
	$output = str_replace('<br>', "\n\r", $content);
	$output = trim($output);
	if(!empty($output) || $acceptEmptyContent){
		$output = '<textarea class="wysiwyg-edit-container wysiwyg-edit-p" type="p" fieldname="'.$var.'" rows="7" placeholder="Click to start writing...">'.$output.'</textarea>'; 
	}
	return $output;
}
function m_rel($m){
	return '/media/'.m_pad($m['id']).".".$m['type'];
}