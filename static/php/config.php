<?
/*
    site-specific config
*/

$head = 'Hello';
$site = 'Gillian Laub';
$home = $head . ", " . $site;

$wysiwyg_models_root = $root . "projects-manager/models/";
require_once($wysiwyg_models_root . 'WhatYouSeeIsWhatYouGet.php');
require_once($wysiwyg_models_root . 'WhatYouGet.php');
?>
