<?
if(!empty($_POST)){

	require_once('editFunctions.php');
	$function = $_POST['function'];
	if(isset($_POST['function']) && isset($_POST['params']) && function_exists($function))
	{
		$params = json_decode($_POST['params'], true);
		echo $function(...$params);
	}
	else
		echo 'false';

}
else
	echo 'false';

