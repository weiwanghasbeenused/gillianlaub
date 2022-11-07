<?
$request = $_SERVER['REQUEST_URI'];
$requestclean = strtok($request,"?");
$uri = explode('/', $requestclean);

require_once("views/head.php");
if (!$uri[1])
    require_once("views/home.php");
else if($uri[1] == 'projects')
    require_once("views/projects.php");
else if ($uri[1] == 'about')
    require_once("views/about.php");
else 
    require_once("views/main.php");
// require_once("views/badge.php");
require_once("views/foot.php");
?>
