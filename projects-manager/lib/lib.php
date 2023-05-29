<?
function trimBreaksFromSides($string){
    if(!empty($string))
    {
        $output = str_replace('<br>', "\n\r", $string);
        $output = trim($output);
    }
    else $output = '';
    return $output;
}