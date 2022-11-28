<?
require_once('../../../open-records-generator/config/config.php');
require_once('../../../open-records-generator/views/head.php');
$urlIsValid = false;
$parentId = $_POST['parentId'];
$isSection = $_POST['isSection'] == '1';
$newUrl = '';

function insert_object(&$new, $siblings)
{
	global $oo;
	global $urlIsValid;

	// set default name if no name given
	if(!$new['name1'])
		$new['name1'] = 'untitled';

	// slug-ify url
	if($new['url'])
		$new['url'] = slug($new['url']);

	if(empty($new['url']))
		$new['url'] = slug($new['name1']);

	// make sure url doesn't clash with urls of siblings
	$s_urls = array();
	foreach($siblings as $s_id)
		$s_urls[] = $oo->get($s_id)['url'];

	// deal with dates
	if(!empty($new['begin']))
	{
		$dt = strtotime($new['begin']);
		$new['begin'] = date($oo::MYSQL_DATE_FMT, $dt);
	}

	if(!empty($new['end']))
	{
		$dt = strtotime($new['end']);
		$new['end'] = date($oo::MYSQL_DATE_FMT, $dt);
	}

	// make mysql happy with nulls and such
	foreach($new as $key => $value)
	{
		if($value)
			$new[$key] = "'".$value."'";
		else
			$new[$key] = "null";
	}

	$id = $oo->insert($new);

	// need to strip out the quotes that were added to appease sql
	$u = str_replace("'", "", $new['url']);
	$urlIsValid = validate_url($u, $s_urls);
	if( !$urlIsValid )
	{
		$url = valid_url($u, strval($id), $s_urls);
		$new['url'] = "'".$url."'";
		$oo->update($id, $new);
	}

	return $id;
}

$f = array();
$f_section = array();
// objects
foreach($vars as $var){
	if($var == 'name1' && !$isSection)
		$f[$var] = empty($rr->$var) ? '' : '.' . addslashes($rr->$var);
	else
		$f[$var] = empty($rr->$var) ? '' : addslashes($rr->$var);
	if(!$isSection)
	{
		if($var == 'name1' || $var == 'url')
			$f_section[$var] = 'photograph';
		else
			$f_section[$var] = '';
	}
	
}
if(!empty($f['name1']) && $f['name1'] != 'undefined')
{
	$siblings = $oo->children_ids($parentId);
	$toid = insert_object($f, $siblings);
	if($toid)
	{
		// wires
		$ww->create_wire($parentId, $toid);
		$newUrl = $oo->get($toid)['url'];
		if(!$isSection)
		{
			// default section
			$toid_section = insert_object($f_section, array());
			if($toid_section)
				$ww->create_wire($toid, $toid_section);
		}
	}
}

if($toid)
{
	$redirect_url = $_POST['successUrl'];
}
else
{
	$redirect_url = $_POST['successUrl'];
}
if(!$isSection)
	$redirect_url = $redirect_url . '/' . $newUrl . '?action=add';
else
	$redirect_url = $redirect_url . '?section=' . $newUrl . '&action=add';

header("Location: " . $redirect_url);
exit();
