<?
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/static/php/editFunctions.php');
require_once(__ROOT__.'/models/WhatYouSee.php');

if($item['id'] == 0)
	die();

$sections = $oo->children($project_id);
$nav_items = $sections;
array_unshift($nav_items, array('name1' => 'Main', 'url' => ''));
?><section id="nav-container"><?
foreach($nav_items as $n)
{
	$isActive = $section == $n['url'];
	$class = $isActive ? "nav-item active" : "nav-item inactive";
	$this_url = '/projects-manager/edit/' . $uri[3] . '/' . $uri[4];
	if(!empty($n['url']))
		$this_url .= '/' . $n['url'];
	?><a class="<?= $class; ?>" href="<?= $this_url; ?>"><?= $n['name1']; ?></a><?
}?><a class="nav-item" href="<?= $general_urls['add']; ?>">&plus;</a>
</section><?

$action = isset($_GET['action']) ? $_GET['action'] : 'edit';

if($action == 'add')
{
	if($uri[2] == 'success')
		$msg = 'Record created successfully. Please <a href="'.$general_urls['edit'].'">click here</a> to start editing it.';
	else if($uri[2] == 'error')
		$msg = 'Record not created, please try again.';
}
else if($action == 'edit')
{
	if($uri[2] == 'success')
		$msg = 'Record successfully updated.';
	else if($uri[2] == 'error')
		$msg = 'Nothing was edited, therefore update not required.';
}


?><main id="body-container">
	<p class="callback-msg"><?= $msg; ?></p>
</main>