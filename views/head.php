<?
// open-records-generator
require_once('open-records-generator/config/config.php');
require_once('open-records-generator/config/url.php');

// site
require_once('static/php/config.php');

$db = db_connect("guest");
$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();

if($uu->id)
	$item = $oo->get($uu->id);
else
	$item = $oo->get(0);

$name = isset($item) ? ltrim(strip_tags($item["name1"]), ".") : '';
$nav = $oo->nav($uu->ids);
$show_menu = false;
if($uu->id) {
	$is_leaf = empty($oo->children_ids($uu->id));
	$internal = isset($_SERVER['HTTP_REFERER']) && (substr($_SERVER['HTTP_REFERER'], 0, strlen($host)) === $host);	
	if(!$is_leaf && $internal)
		$show_menu = true;
} else  
    if ($uri[1])  
        $uu->id = -1; 

$body_class = '';
if(!$uri[1]){
	$category = isset($_GET['category']) ? $_GET['category'] : 'projects';
	$body_class .= ' home';
}

require_once('static/php/function.php');

?><!DOCTYPE html>
<html lang="en">
	<head>
		<title><? echo $site; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="/static/css/suisse.css">
		<link rel="stylesheet" href="/static/css/main.css">
		<link rel="apple-touch-icon" href="/media/png/touchicon.png" />

	</head>
	<body class="<?= $body_class; ?>" <?= isset($category) ? 'category="'.$category.'"' : '' ?>>
	<script src="/static/js/_sniffing.js"></script>
	<header id= "main-header" class="float-container padding-wrapper">
		<a id="site-name" href="/"><img src = "/media/svg/title.svg"></a>
		<div id="main-header-btn-container" class="float-container">
			<a href="/about" class="about-btn in-header middle">ABOUT</a>
			<div id="cat-toggle-btn-container" class="float-container <?= $uri[1] ? 'inactive' : ''; ?>">
				<p id="cat-projects" class="cat-name middle">PROJECTS</p>
				<div id="cat-toggle-btn"></div>
				<p id="cat-commissions" class="cat-name middle">COMMISSIONS</p>
			</div>
		</div>
	</header>
