<?
$config_dir = __DIR__."/../../open-records-generator/config/";
require_once($config_dir."config.php");
$admin_path = $host . "projects-manager/";
$user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : $_SERVER['REDIRECT_REMOTE_USER'];
$db = db_connect($user);
$oo = new Objects();
$mm = new Media();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Projects Manager</title>
		<meta charset="utf-8">
		<meta name="description" content="anglophile">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="<? echo $admin_path;?>media/icon.png">
		<link rel="apple-touch-icon-precomposed" href="<? echo $admin_path;?>media/icon.png">
		<link rel="stylesheet" href="<? echo $admin_path; ?>static/css/main.css">
		<link rel="stylesheet" href="<? echo $admin_path; ?>static/css/form.css">
	</head>
	<body>
<? 
if(empty($_POST) || $_POST['action'] != 'upload') // display form
{
?>
<script src="<?= $admin_path . 'static/js/edit.js'; ?>"></script>
<form id="media-form" method="post" enctype='multipart/form-data'>
	<input form="media-form" name="action" value="upload" type="hidden">
	<input form="media-form" name="toid" value="" type="hidden">
	<div id="upload-container"><label class="btn on-grey" for="uploads">Select files</label><input form="media-form" id="uploads" type="file" name="uploads[]" multiple="multiple" onchange="callLoader(this);"><button id="media-form-submit" type="submit" class="btn on-grey">Upload</button></div>
	<div id="preview-container"></div>
</form>
<script>
	window.addEventListener( "message", function (e) { 
	    document.querySelector('input[name="toid"]').value = e.data;
	},
	false);
</script>
<style>
	header,
	footer
	{
		display: none;
	}
	#media-form-submit
	{
		position: fixed;
		right: 10px;
		top: 10px;
		z-index: 10;
	}
	input[name="uploads[]"]
	{
		display: none;
	}
	label[for="uploads"]
	{
		position: fixed;
		left: 50%;
		transform: translate(-50%, 0);
		top: 10px;
		z-index: 10;
	}
	
	#preview-container
	{
		display: flex;
		flex-wrap: wrap;
		overflow: scroll;
	}
	.preview-cell
	{
		flex: 0 0 50%;
		height: 150px;
		padding: 20px;
		position:relative;
	}
	.preview-cell:nth-child(2n)
	{
		margin-right:0;
	}
	.preview-cell > img
	{
		width:100%;
		height:100%;
		object-fit: contain;
	}
	.preview-cell:hover > img
	{
		opacity: 0.25;
	}
	.preview-removethisbtn
	{
		position: absolute;
		left:0;
		top:0;
		font-size: 20px;
		padding: 2px 6px;
		cursor: pointer;
		z-index: 10;
	}
	.preview-cell:hover,
	.preview-cell:hover .preview-removethisbtn
	{
		background-color: #fff;
	}
	
	.preview-msg
	{
		display: none;
		position: absolute;
		z-index: 10;
	}
	.preview-cell:hover .preview-msg
	{
		display: block;
		
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
	}
	#media-form
	{
		padding-top: 80px;
	}
</style>
</div>
	</body>
</html>
<? 
	// require_once('foot.php');
}
else // respond with uploaded media srcs
{
	$message = [];
	$toid = $_POST['toid'];
	foreach($_FILES["uploads"]["error"] as $key => $error)
	{
	    if($error == UPLOAD_ERR_OK)
        {
            $tmp_name = $_FILES["uploads"]["tmp_name"][$key];
			$m_name = $_FILES["uploads"]["name"][$key];
            $temp = explode(".", $m_name);
			$m_type = strtolower(end($temp));

			$m_arr["type"] = "'".$m_type."'";
			$m_arr["object"] = "'".$toid."'";
			$m_arr["caption"] = "''";
			
			$insert_id = $mm->insert($m_arr);
			// $m_rows++;

			$m_file = m_pad($insert_id).".".$m_type;
			$m_dest = $media_root;
			$m_dest .= $m_file;
			$message[] = array(
				'id' =>  $insert_id,
				'caption' => '',
				'type' => $m_type,
				'file' => '/media/'.$m_file,
				'filename' => $m_file
			);
			if(move_uploaded_file($tmp_name, $m_dest)) {
				if($resize)
					resize($m_dest, $media_root.$m_file, $resize_scale);
			}
			else {
				// $m_rows--;
				$mm->deactivate($insert_id);
			}
	    }
	}
	$media = $oo->media($toid);
	foreach($media as &$m)
	{
		$m['file'] = '/media/' . m_pad($m['id']) . '.' . $m['type'];
	}
	$message = json_encode($media);
	?>
	<div id="msg-success">
		File upload successes!
	</div>
	<script>
		var message = '<?= $message; ?>';
		top.postMessage(message, '*');
	</script>
	<style>
		header,
		footer
		{
			display: none;
		}
		#msg-success
		{
			position: fixed;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
		}
	</style>
	</div>
	</body>
</html>
	<?
	// require_once('foot.php');
}
