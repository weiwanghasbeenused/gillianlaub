<?
if(!empty($_POST)){

	require_once('editFunctions.php');
	require_once('../../../open-records-generator/config/config.php');
	$function = $_POST['function'];
	if(isset($_POST['function']) && isset($_POST['params']) && function_exists($function))
	{
		$params = json_decode($_POST['params'], true);
		if(isset($params['media']))
			$params['media'] = json_decode($params['media'], true);
		echo $function(...$params);
	}
	else
		echo 'false';

}
else
	echo 'false';

