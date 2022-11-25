<?
if(!empty($_POST)){

	require_once('../../models/WhatYouSee.php');
	require_once('../../../open-records-generator/config/config.php');
	$function = $_POST['function'];
	if(isset($_POST['media']) && isset($_POST['function']) && isset($_POST['params']))
	{

		$media = stripslashes($_POST['media']);
		$media = json_decode($media, true);
		$ws = new WhatYouSee($media);
		$params = json_decode(stripslashes($_POST['params']), true);
		if(isset($params['params']))
			$params['params'] = json_decode(stripslashes($params['params']), true);

		echo $ws->$function(...$params);
	}
	else
		echo 'false';
}
else
	echo 'false';

