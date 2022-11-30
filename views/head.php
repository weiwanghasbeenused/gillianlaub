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

$wg = new WhatYouGet($uri);

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
else
{
	$body_class .= ' ' . $uri[1];
	$category = $uri[1];
}

require_once('static/php/function.php');
require_once('projects-manager/lib/lib.php');

$css_arr = array('suisse', 'main');

if($uri[1] == 'about')
	$css_arr[] = 'about';

?><!DOCTYPE html>
<html lang="en">
	<head>
		<title><? echo $site; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="google" content="notranslate">
		<meta http-equiv="Content-Language" content="en">
		<? foreach($css_arr as $css){
			echo '<link rel="stylesheet" href="/static/css/'.$css.'.css">';
		} ?>
		<link rel="apple-touch-icon" href="/media/png/touchicon.png" />
		<script src="/static/js/global.js"></script>
	</head>
	<body class="<?= $body_class; ?>" <?= isset($category) ? 'category="'.$category.'"' : '' ?>>
	<script>
		var wW = window.innerWidth;
		var wH = window.innerHeight;
		var body = document.body;
	</script>
	<script src="/static/js/_sniffing.js"></script>
	<header id= "main-header" class="float-container padding-wrapper">
		<a id="site-name" href="/"><img src = "/media/svg/title.svg"></a>
		<div class="about-btn-container"><a href="/about" class="about-btn in-header middle">ABOUT</a></div>
		<div id="cat-toggle-btn-container" class="float-container <?= $uri[1] ? 'inactive' : ''; ?>">
			<? if(!$uri[1]){ ?>
			<p id="cat-projects" class="cat-name middle">PROJECTS</p>
			<div id="cat-toggle-btn"></div>
			<p id="cat-commissions" class="cat-name middle">COMMISSIONS</p>
			<? } else { ?>
			<a id="cat-projects" class="cat-name middle" href="/?category=projects">PROJECTS</a>
			<div id="cat-toggle-btn"></div>
			<a id="cat-commissions" class="cat-name middle" href="/?category=commissions">COMMISSIONS</a>
			<? } ?>
		</div>
		
	</header>
	<? if($uri[1] == 'about') { 
		if(count($uri) == 2)
			$url = '/';
		else
			$url = '/about';
	?><a id="btn-close-news-events" class="cross-icon" href="<?= $url; ?>"></a>		
	<? } ?>
