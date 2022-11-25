<?

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