<?
function removeHiddenChildren($children, $symbol = array('.'))
{
	$output = $children;
	foreach($output as $key => $child)
	{
		if(in_array( substr($child['name1'], 0, 1), $symbol))
			unset($output[$key]);
	}
	unset($child);
	$output = array_values($output);
	return $output;
}
function processSrc($input){
	global $host;
	// return '/media/' . $filename;
	return $host . substr($input, 1);
}
?>