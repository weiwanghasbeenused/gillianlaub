<?
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/static/php/editFunctions.php');
require_once(__ROOT__.'/models/WhatYouSee.php');

if($item['id'] == 0)
	die();

$project_item = $item;
$form_url = $admin_path."static/php/updateHandler.php";
if(!empty($section)) $form_url .= '?section=' . $section;
$sections = $oo->children($project_item['id']);
$nav_items = $sections;
array_unshift($nav_items, array('name1' => 'Main', 'url' => ''));

?><section id="nav-container"><?
foreach($nav_items as $n)
{
	$isActive = $section == $n['url'];
	$class = $isActive ? "nav-item active" : "nav-item inactive";
	$this_url = implode('/', $uri);
	if(!empty($n['url']))
		$this_url .= '?section=' . $n['url'];
	?><a class="<?= $class; ?>" href="<?= $this_url; ?>"><?= $n['name1']; ?></a><?
}?><a class="nav-item" href="<?= $general_urls['add']; ?>">&plus;</a>
</section><?

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
			'slug' => 'thumbnail',
			'var' => 'address2',
			'type' => 'image'
		),
		'external' => array(
			'displayName' => 'Sections',
			'slug' => 'sections',
			'var' => 'external',
			'type' => 'order'
		),
		'rank' => array(
			'displayName' => 'Rank',
			'slug' => 'rank',
			'var' => 'rank',
			'type' => 'hidden'
		),
		'rank' => array(
			'displayName' => 'Url',
			'slug' => 'url',
			'var' => 'url',
			'type' => 'text'
		)
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
		'address2' => array(
			'displayName' => 'Section Thumbnail',
			'slug' => 'thumbnail',
			'var' => 'address2',
			'type' => 'image'
		),
		'body' => array(
			'displayName' => 'Body',
			'slug' => 'body',
			'var' => 'body',
			'type' => 'wysiwyg'
		),
		'rank' => array(
			'displayName' => 'Rank',
			'slug' => 'rank',
			'var' => 'rank',
			'type' => 'hidden'
		),
		'rank' => array(
			'displayName' => 'Url',
			'slug' => 'url',
			'var' => 'url',
			'type' => 'text'
		)
	),
);
$checkbox_options = array(
	'layout' => array(
		'grid' => array(
			'displayName' => 'Grid',
			'slug' => 'grid',
			'checked' => true
		),
		'scroll' => array(
			'displayName' => 'Scroll',
			'slug' => 'scroll',
			'checked' => false
		)
		
	),
);
$select_options = array();

$class_prefix = 'gillianlaub';
$current_fields = empty($section) ? $fields['main'] : $fields['section'];
$wysiwyg_section_opening_pattern = '/^\[wysiwygsection wysiwygtag=\"(.*?)\"\](.*)/';
$wysiwyg_section_ending_pattern = '[/wysiwygsection]';

?><main id="body-container">
	<?
	$a_url = $admin_path."browse";

	// get existing image data
	$medias = $oo->media($current_item['id']);
	$num_medias = count($medias);

	for($i = 0; $i < $num_medias; $i++)
	{
		$m_padded = "".m_pad($medias[$i]['id']);
		$medias[$i]["fileNoPath"] = '/media/'.$m_padded.".".$medias[$i]["type"];
		$medias[$i]["file"] = $media_path.$m_padded.".".$medias[$i]["type"];
		if ($medias[$i]["type"] == "pdf")
			$medias[$i]["display"] = $admin_path."media/pdf.png";
		else if ($medias[$i]["type"] == "mp4")
			$medias[$i]["display"] = $admin_path."media/mp4.png";
		else if ($medias[$i]["type"] == "mp3")
			$medias[$i]["display"] = $admin_path."media/mp3.png";
		else
			$medias[$i]["display"] = $medias[$i]["file"];
	}

	$ws = new WhatYouSee($medias);

// object contents
?>
<script src="<?= $admin_path; ?>static/js/WhatYouSee.js"></script>
<script>
	var toid = <?= $current_item['id']; ?>;
	var medias = <?= json_encode($medias, true); ?>;
	var media_path = '<?= $media_path; ?>';
	
</script>
	<div class="form">
		<script src="<?= $admin_path . 'static/js/edit.js'; ?>"></script>
		<?php
		// show object data
		foreach($current_fields as $field)
		{
			$var = $field['var'];
			$fieldType = $field['type'];
			$displayName = $field['displayName'];
			$fieldSlug = $field['slug'];
		if($fieldType == "hidden"){
			?><input type="hidden" name="<?= $var; ?>" value="<?= $current_item[$var]; ?>" form="edit-form"><?
		}
		else
		{
		?><div id="field-<?= $var; ?>" class="field">
			<div class="field-name"><? echo $displayName; ?></div>
			<div id="field-body-<?= $var; ?>" class="field-body">
			<? if($fieldType == "wysiwyg") { ?>
                <textarea name='<? echo $var; ?>' class='large dontdisplay wysiwyg-field' id='<? echo $var; ?>-textarea' onblur="" style="display: none;" form="edit-form"><?
                    if($current_item[$var])
                        echo htmlentities($current_item[$var]);
                ?></textarea>
                <? 
                echo $ws->render($var, trimBreaksFromSides($current_item[$var]));
				}
				else if($fieldType == "image")
				{
					?><input name="<?= $var; ?>" type="hidden" form="edit-form"><?
					// echo $ws->render($thisType, $var, $thisContent);
					// $class = empty($current_item[$var]) ? 'viewing-toolbar' : '';
					$class =  'image-field ' . $fieldSlug;
					// echo $class;
					echo $ws->renderImageBlock($var, $current_item[$var], $class, array('fieldname' => $var));
				}
				else if($fieldType == "checkbox")
				{
					$options = $checkbox_options[$fieldSlug];
					$value = empty($current_item[$var]) ? '' : trim($current_item[$var]);
					$saved = $value ? explode(',', $value) : array();

					foreach($options as $option)
					{
						$checked = (empty($saved) && $option['checked']) || in_array($option['slug'], $saved);
						$this_id = $var . '-' .$option['slug'];
						?><input id="<?= $this_id; ?>" name="<?= $var; ?>[]" type="checkbox" form="edit-form" required value="<?= $option['slug']; ?>" <?= $checked ? 'checked' : ''; ?>><label for="<?= $this_id; ?>"><?= $option['displayName']; ?></label><?
					}
				?><?
				}
				else if($fieldType == "select")
				{
					
				?><?
				}
				else if($fieldType == "text")
				{
				?><input name="<?= $var; ?>" type="text" value="<?= $current_item[$var]; ?>" form="edit-form"><?
				}
				else if($fieldType == "order" && $fieldSlug == 'sections')
				{
					?><input name="section-order" type="hidden" form="edit-form"><div class="order-container"><?
					$section_num = count($sections);
					foreach($sections as $key => $s)
					{

						$select_html = '<select class="order-select" section="'.$s['id'].'" currentValue="'.($key + 1).'" onchange="reorder(this);">';
						for($i = 0; $i < $section_num; $i++)
						{
							if($i == $key) $select_html .= '<option value = "' .($i + 1). '" selected>' . ($i + 1) . '</option>';
							else $select_html .= '<option value = "' .($i + 1). '" >' . ($i + 1) . '</option>';
						}
						$select_html .= '</select>';
						?><div class="order-item float-container"><?= $s['name1']; ?> <div class="order-control"><?= $select_html; ?></div></div><?
					}
					?></div><?
				}
			?></div><?
		}
		?>
		</div><?
		}
		?>
		<input type='hidden' name='successUrl' value='<?= $general_urls['success']; ?>' form="edit-form" >
		<input type='hidden' name='errorUrl' value='<?= $general_urls['error']; ?>' form="edit-form" >
		<input type='hidden' name='objectId' value='<?= $current_item['id']; ?>' form="edit-form" >
		<div id="btn-openMediaContainer" class="btn on-grey" onclick="openMediaContainer();">Upload media</div>
		<div id="media-upload-container"><div id="btn-closeMediaContainer" class="icon-cross" onclick="closeMediaContainer();">&times;</div><iframe src="/projects-manager/views/mediaForm.php"></iframe></div>
		<script>
			var media_path = '<?= $media_path; ?>';
			var iframe = document.querySelector('#media-upload-container > iframe').contentWindow;
			var temp = document.getElementById('btn-openMediaContainer');
			temp.addEventListener('click', function(){
				iframe.postMessage(toid, location.origin);
			});
			window.addEventListener("message", (event) => {
				let response = JSON.parse(event.data);
				medias = response;
				updateMediaToolbar(medias);
			}, false);
		</script>
		
		<div class="button-container">
			<input
				type='hidden'
				name='action'
				value='update'
				form="edit-form"
			>
			<input
				type='button'
				name='cancel'
				value='Cancel'
				class='btn on-grey'
				onClick="<? echo $js_back; ?>"
				form="edit-form"
			>
			<button
				onclick='wsForm.submit();'
				class='btn on-grey'
			>Update Object</button>
		</div>
	<!-- </form> -->
	<form
		method="post"
		enctype="multipart/form-data"
		action="<?= $admin_path; ?>static/php/editHandler.php"
		id="edit-form"
	>
	</form>
	<script>
		var wsForm = new WhatYouSee(document.getElementById("edit-form"), document.querySelector('.form'), media_path);
	</script>
</div>

</main>