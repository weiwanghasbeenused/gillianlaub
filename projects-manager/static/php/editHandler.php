<?
require_once('../../../open-records-generator/config/config.php');
require_once('../../../open-records-generator/views/head.php');
$urlIsValid = false;
$objectId = $_POST['objectId'];
$current_item = $oo->get($objectId);
function update_object(&$old, &$new, $siblings, $vars)
{
	global $oo;
	global $urlIsValid;

	// set default name if no name given
	if(!$new['name1'])
		$new['name1'] = "untitled";

	// add a sort of url break statement for urls that are already in existence
	// (and potentially violate our new rules?)
    // urldecode() is for query strings, ' ' -> '+'
    // rawurldecode() is for urls, ' ' -> '%20'
	$url_updated = rawurldecode($old['url']) != $new['url'];

	if($url_updated)
	{
		// slug-ify url
		if($new['url'])
			$new['url'] = slug($new['url']);

		// if the slugified url is empty,
		// or the original url field is empty,
		// slugify the name of the object
		if(empty($new['url']))
			$new['url'] = slug($new['name1']);

		// make sure url doesn't clash with urls of siblings
		$s_urls = array();
		foreach($siblings as $s_id)
			$s_urls[] = $oo->get($s_id)['url'];

		$urlIsValid = validate_url($new['url'], $s_urls);
		if( !$urlIsValid )
			$new['url'] = valid_url($new['url'], strval($old['id']), $s_urls);
	}
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

	// check for differences
	$arr = array();
	foreach($vars as $v)
		if($old[$v] != $new[$v])
			$arr[$v] = $new[$v] ?  "'".$new[$v]."'" : "null";

	$updated = false;
	if(!empty($arr))
	{
		$updated = $oo->update($old['id'], $arr);
	}

	return $updated;
}

if(isset($_POST['section-order']) && !empty($_POST['section-order']))
{
	$section_arr = json_decode($_POST['section-order'], true);
	foreach($section_arr as $s)
	{
		$query = 'UPDATE objects SET `rank` = "'.$s['rank'].'" WHERE id="'.$s['id'].'"';
		$db->query($query);
	}
}
$new = array();
// objects
foreach($vars as $var)
{
	if(is_array($rr->$var))
	{
		if(empty($rr->$var))
			$rr->$var = '';
		else
		{
			if($current_fields[$var]['type'] == 'checkbox')
				$rr->$var = implode(',', $rr->$var);
		}
	}
	
	if(!empty($rr->$var))
		$new[$var] = addslashes($rr->$var);
	else
		$new[$var] = $rr->$var;

	if(isset($current_item[$var]))
		$current_item[$var] = addslashes($current_item[$var]);
	else
		$current_item[$var] = '';
}
$siblings = $oo->siblings($objectId);
$updated = update_object($current_item, $new, $siblings, $vars);
if($updated)
{
	header("Location: " . substr($_POST['successUrl'], 0, strripos($_POST['successUrl'], '/')) . '/' . $new['url'] . '?action=edit');
	exit();
}
else
{
	header("Location: " . substr($_POST['errorUrl'], 0, strripos($_POST['errorUrl'], '/')) . '/' . $new['url'] . '?action=edit');
	exit();
}

