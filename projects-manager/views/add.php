<?
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/static/php/editFunctions.php');
require_once(__ROOT__.'/models/WhatYouSee.php');

$browse_url = $admin_path.'browse/'.$uu->urls();

$urlIsValid = true;
// for use on add.php
// return false if process fails
// (siblings must not have same url slug as object)
// return id of new object on success
$nav_items = array();
array_unshift($nav_items, array('name1' => 'Main', 'url' => ''));

?><?
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

$fields = array(
	'main' => array(
		'name1' => array(
			'displayName' => 'Project Name',
			'slug' => 'project-name',
			'var' => 'name1',
			'type' => 'text'
		),
		'deck' => array(
			'displayName' => 'Project Description',
			'slug' => 'project-description',
			'var' => 'deck',
			'type' => 'wysiwyg'
		),
		'address2' => array(
			'displayName' => 'Project Thumbnail',
			'slug' => 'project-thumbnail',
			'var' => 'address2',
			'type' => 'image'
		),
		'external' => array(
			'displayName' => 'Sections',
			'slug' => 'sections',
			'var' => 'external',
			'type' => 'order'
		),
	),
	'section' => array(
		'name1' => array(
			'displayName' => 'Section Name',
			'slug' => 'section-name',
			'var' => 'name1',
			'type' => 'text'
		),
		'address1' => array(
			'displayName' => 'Layout',
			'slug' => 'layout',
			'var' => 'address1',
			'type' => 'checkbox'
		),
		'body' => array(
			'displayName' => 'Body',
			'slug' => 'body',
			'var' => 'body',
			'type' => 'wysiwyg'
		),
	),
);
$current_fields = count($uri) == 4 ? $fields['main'] : $fields['section'];
$ws = new WhatYouSee(array());
?><main id="body-container"><?
	if($rr->action != "add")
	{
		$form_url = $admin_path."add";
		if($uu->urls())
			$form_url.="/".$uu->urls();
		$msg = 'You are adding a new ';
		$msg .= count($uri) == 4 ? 'project' : 'section';
	?><div class="description"><?= $msg; ?></div>
	<form
		enctype="multipart/form-data"
		action="<? echo $form_url; ?>"
		method="post"
	>
		<div class="form"><?
		// object data
		$var = 'name1';
		$displayName = $current_fields[$var]['displayName'];
		?><div class="field-name"><? echo $displayName; ?></div>
			<div class="field-body"><input name="<?= $var; ?>" type="text" value=""></div><?
		?></div>
		<div class="button-container">
			<input
				type='hidden'
				name='action'
				value='add'
			>
			<input
				type='button'
				name='cancel'
				value='Cancel'
				class="btn on-grey"
				onClick="<? echo $js_back; ?>"
			>
			<input
				type='submit'
				name='submit'
				class="btn on-grey"
				value='Add Object'
			>
		</div>
	</form><?
	}
	// process form
	else
	{
		$f = array();
		$f_section = array();
		// objects
		foreach($vars as $var){
			if($var == 'name1')
				$f[$var] = empty($rr->$var) ? '' : '.' . addslashes($rr->$var);
			else
				$f[$var] = empty($rr->$var) ? '' : addslashes($rr->$var);
			if($var == 'name1' || $var == 'url')
				$f_section[$var] = 'photograph';
			else
				$f_section[$var] = '';
		}
		if(!empty($f['name1']) && $f['name1'] != 'undefined')
		{
			$siblings = $oo->children_ids($uu->id);
			$toid = insert_object($f, $siblings);
			if($toid)
			{
				// wires
				$ww->create_wire($uu->id, $toid);
				// default section
				$toid_section = insert_object($f_section, array());
				if($toid_section)
					$ww->create_wire($toid, $toid_section);
				else echo 'pp';
				$url = $oo->get($toid)['url'];
				$redirect_url = "/projects-manager/edit/".$uri[3]."/" . $url;
				?><script>
					window.location.href="<?= $redirect_url; ?>";
				</script><?
			}
			else
				$msg = 'Record not created, please <a href="<? echo $js_back; ?>">try again.';
		}
		else
			$msg = 'Please enter the name of the project';
		?><div><?= $msg; ?></div><?
	}
?>
</main>
