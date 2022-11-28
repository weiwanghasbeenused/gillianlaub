<?
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/static/php/editFunctions.php');
require_once(__ROOT__.'/models/WhatYouSee.php');

$nav_items = array();
array_unshift($nav_items, array('name1' => 'Main', 'url' => ''));

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
$isSection = count($uri) == 5;
$current_fields = $isSection ? $fields['section'] : $fields['main'];
$parentId = $item['id'];

$ws = new WhatYouSee(array());
?><main id="body-container">
	<?
	$form_url = $admin_path."add";
	if($uu->urls())
		$form_url.="/".$uu->urls();
	$msg = 'You are adding a new ';
	$msg .= count($uri) == 4 ? 'project' : 'section';
	?>
	<div class="description"><?= $msg; ?></div>
	<form
		enctype="multipart/form-data"
		action="<?= $admin_path; ?>static/php/addHandler.php"
		method="post"
	>
		<div class="form"><?
		// object data
		$var = 'name1';
		$displayName = $current_fields[$var]['displayName'];
		?><div class="field-name"><? echo $displayName; ?></div>
			<div class="field-body"><input name="<?= $var; ?>" type="text" value=""></div><?
		?></div>
		<input type='hidden' name='successUrl' value='<?= $general_urls['success']; ?>'>
		<input type='hidden' name='errorUrl' value='<?= $general_urls['error']; ?>'>
		<input type="hidden" name="isSection" value="<?= $isSection; ?>">
		<input type="hidden" name="parentId" value="<?= $parentId; ?>">
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
	</form>
</main>
