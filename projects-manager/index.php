<?
$request = $_SERVER['REQUEST_URI'];
$requestclean = strtok($request,"?");
$uri = explode('/', $requestclean);

require_once("views/head.php");
if(!$uri[2])
	require_once("views/welcome.php");
else if($uri[2] == 'browse')
	require_once("views/browse.php");
else if($uri[2] == 'edit')
	require_once("views/edit.php");
require_once("views/foot.php");

?>
