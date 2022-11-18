<?

$wysiwygPattern = array(
	'img' => '/\<img class=\"wysiwygimg\" src="(.*)"\>/',
	'figcaption' => '/\<figcaption class=\"wysiwygfigcaption\"\s*\>(.*?)\<\/figcaption\>/'
);

function renderWysiwygElement(){

}

function renderWysiwygFigure($var, $idx, $content){
	global $wysiwygPattern;
	$output = '';
	preg_match($wysiwygPattern['img'], $content, $img_match);
	if(!empty($img_match)){
		$src = $img_match[1];
		$caption = '';
		if(strpos($content, '<figcaption class="wysiwygfigcaption">') !== false){
			preg_match($wysiwygPattern['figcaption'], $content, $figcaption_match);
			if(!empty($figcaption_match))
				$caption = $figcaption_match[1];
		}
		$output .= '<div class="wysiwyg-edit-container wysiwyg-edit-figure" fieldname="'.$var.'"><div class=""><img src="'.$src.'"></div>'; 
		if(!empty($caption))
			$output .= '<div class="">' . $caption . '</div><input id="'.$var . '-checkbox_showCaption-' . $idx . '" class="checkbox_showCaption" type="checkbox" checked><label for="'.$var . '-checkbox_showCaption-' . $idx . '">display caption</label></div>';
		else
			$output .= '<input id="'.$var . '-checkbox_showCaption-' . $idx . '" class="checkbox_showCaption" type="checkbox"><label for="'.$var . '-checkbox_showCaption-' . $idx . '">display caption</label></div>';
	}
	return $output;
}
