<?
require_once('static/php/layout.php');

if($item)
{
	$name = $item['name1'];
	$deck = $item['deck'];
	$body = $item['body'];
	$notes = $item['notes'];
	$date = $item['begin'];
	$find = '/<div><br><\/div>/';
	$replace = '';
	$body = preg_replace($find, $replace, $body); 
}
else
{
	$name = '';
	$deck = '';
	$body = '';
	$notes = '';
	$date = '';
}
$col_number = 3; 

$temp = $oo->urls_to_ids(array('projects'));
$projects = removeHiddenChildren($oo->children(end($temp)));
foreach($projects as &$p)
{
	$fullUrl = '/projects/' . $p['url'];
	$p['fullUrl'] = $fullUrl;
}
unset($p);
$temp = $oo->urls_to_ids(array('commissions'));
$commissions = removeHiddenChildren($oo->children(end($temp)));
foreach($commissions as &$c)
{
	$fullUrl = '/commissions/' . $c['url'];
	$c['fullUrl'] = $fullUrl;
}
unset($c);

?>
<main id="home-container" class="main-container padding-wrapper">
    <?
    	echo renderGrid($projects, $col_number, 'projects-grid-container');
		echo renderGrid($commissions, $col_number, 'commissions-grid-container');
    ?>
</main>
<div id="main-footer" class="footer padding-wrapper">
	<a href="/about" class="about-btn in-footer middle">ABOUT</a>
</div>
<script>
    
</script>
