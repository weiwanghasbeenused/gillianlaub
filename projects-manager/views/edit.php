<?
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/static/php/editFunctions.php');

$current_section = isset($_GET['section']) ? $_GET['section'] : '';

$project_item = $item;
if(empty($current_section))
	$current_item = $project_item;
else
{
	$temp = $oo->urls_to_ids(array($uri[3], $uri[4], $current_section));
	$current_item = $oo->get(end($temp));
}

$sections = $oo->children($project_item['id']);
$nav_items = $sections;
array_unshift($nav_items, array('name1' => 'Main', 'url' => ''));

?><section id="nav-container"><?
foreach($nav_items as $n)
{
	$isActive = $current_section == $n['url'];
	$class = $isActive ? "nav-item active" : "nav-item inactive";
	$url = implode('/', $uri);
	if(!$isActive) $url .= '?section=' . $n['url'];
	?><a class="<?= $class; ?>" href="<?= $url; ?>"><?= $n['name1']; ?></a><?
}
?></section><?

$browse_url = $admin_path.'browse/'.$uu->urls();

$urlIsValid = true;

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
		// 'body' => array(
		// 	'displayName' => 'Default Section',
		// 	'slug' => 'default-section',
		// 	'var' => 'body',
		// 	'type' => 'select'
		// ),
		'address2' => array(
			'displayName' => 'Project Thumbnail',
			'slug' => 'project-thumbnail',
			'var' => 'address2',
			'type' => 'image'
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
		// 'media' => array(
		// 	'displayName' => 'Section Thumbnail',
		// 	'slug' => 'section-thumbnail',
		// 	'var' => 'media',
		// 	'type' => 'upload'
		// ),
	),
);
$class_prefix = 'gillianlaub';
$current_fields = empty($current_section) ? $fields['main'] : $fields['section'];
// $wysiwyg_section_pattern = '/\[wysiwygsection wysiwygtag=\"(.*?)\"\]((:?.|\n|\r)*)\[\/wysiwygsection\]/';
$wysiwyg_section_opening_pattern = '/^\[wysiwygsection wysiwygtag=\"(.*?)\"\](.*)/';
$wysiwyg_section_ending_pattern = '[/wysiwygsection]';
// var_dump($wysiwyg_section_pattern);
$figure_pattern = '/\<figure class="'.$class_prefix .'\-wysiwyg\-figure"\>(.*?)\<\/figure\>/';
$video_pattern = '/\<video class="'.$class_prefix .'\-wysiwyg\-video"\>(.*?)\<\/video\>/';
$paragraph_pattern = '/\<p class="'.$class_prefix .'\-wysiwyg\-paragraph"\>(.*?)\<\/p\>/';

$img_pattern = '/\<img class=\"wysiwygimg\" src="(.*)"\>/';
$figcaption_pattern = '/\<figcaption class=\"wysiwygfigcaption\"\s*\>(.*?)\<\/figcaption\>/';
// return false if object not updated,
// else, return true
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

?><div id="body-container">
	<div><?
	$a_url = $admin_path."browse";

if ($rr->action != "update" && $uu->id)
{
	// get existing image data
	$medias = $oo->media($uu->id);
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
	$form_url = $admin_path."edit/".$uu->urls();
// object contents
?><div id="form-container">
			<div class="form">
				<script src="<?= $admin_path . 'static/js/edit.js' ?>"></script>
				<?php
				// show object data
				foreach($current_fields as $field)
				{
					$var = $field['var'];
					$fieldType = $field['type'];
					$displayName = $field['displayName'];
					$fieldSlug = $field['slug'];
				?><div class="field">
					<div class="field-name"><? echo $displayName; ?></div>
					<div class="field-body">
					<? if($fieldType == "wysiwyg") { ?>
                        <textarea name='<? echo $var; ?>' class='large dontdisplay' id='<? echo $var; ?>-textarea' onclick="showToolBar('<? echo $var; ?>'); resetViews('<? echo $var; ?>', default_editor_mode);" onblur="" style="display: none;" form="edit-form"><?
                            if($current_item[$var])
                                echo htmlentities($current_item[$var]);
                        ?></textarea>
                        <? if(!empty($current_item[$var])){
                        	$body = trim($current_item[$var]);
                        	$body = str_replace("\r", '', $body);
                        	$body = str_replace("\n", '', $body);
                        	$body_arr = explode($wysiwyg_section_ending_pattern, $body);
                        	$field_html = '';
                        	var_dump($body_arr);
                        	if(!empty($body_arr))
                        	{
                        		foreach($body_arr as $key => $section)
                        		{
                        			// $thisType = '';
                        			$section = trim($section);
                        			if(!empty($section))
                        			{
                        				preg_match($wysiwyg_section_opening_pattern, $section, $match);
	                        			if(!empty($match) && !empty(trim($match[2]))){
	                        				$thisType = $match[1];
	                        				$thisContent = trim($match[2]);
	                        				if($thisType == 'figure')
	                        				{
                        						$field_html .= '<div class="wysiwyg-section">' . renderWysiwygFigure($var, $key, $thisContent, $medias) . '</div>';
                        						$field_html .= '<div class="wysiwyg-section">' .renderWysiwygAdd($var) . '</div>';
	                        				}
	                        				else if($thisType == 'p')
	                        				{	
	                        					$field_html .= '<div class="wysiwyg-section">' . renderWysiwygText($var, $thisContent) . '</div>';
	                        					$field_html .= '<div class="wysiwyg-section">' .renderWysiwygAdd($var) . '</div>';
	                        				}
	                        			}
                        			}                        			
                        		}
                        		echo $field_html;
                        	}
                        	else
                        	{
                        		echo '<div class="wysiwyg-section">' .renderWysiwygAdd($var) . '</div>';
                        	}
                        }
                        else
                        {
                        	echo '<div class="wysiwyg-section">' .renderWysiwygAdd($var) . '</div>';
                        }
                                
                        ?>

						<script>
							<? 
							if($user == 'admin' && $default_editor_mode == 'html') { ?>
								sethtml('<? echo $var; ?>', default_editor_mode);
							<? } ?>
						</script>
						<?
						// ** end minimal wysiwig toolbar **
						}
						else if($fieldType == "image")
						{
							echo renderImageBlock($var, '', $medias, $fieldSlug);
						}
						else if($fieldType == "checkbox")
						{

						?><?
						}
						else if($fieldType == "select")
						{
							$options = array();
							if($field['slug'] == 'default-section')
								$options = $sections;
							if(!empty($options))
							{
								echo renderSelect($var, $current_item[$var], $options);
							}
						?><?
						}
						else if($fieldType == "text")
						{

						?><input name="<?= $var; ?>" type="text" value="<?= $current_item[$var]; ?>" onclick="hideToolBars(); resetViews('', default_editor_mode);" form="edit-form"><?
						}
					?></div>
				</div><?
				}
				// show existing images
				
				// upload new images
				
					for($j = 0; $j < $max_uploads; $j++)
					{
						$im = str_pad(++$i, 2, "0", STR_PAD_LEFT);
					?><div class="image-upload">
						<span class="field-name">Image <? echo $im; ?></span>
						<span>
							<input type="file" name="uploads[]" form="edit-form">
						</span>
						<!--textarea name="captions[]"><?php
								echo $medias[$i]["caption"];
						?></textarea-->
					</div><?php
					}
				?>
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
						onClick="<? echo $js_back; ?>"
						form="edit-form"
					>
					<input
						type='submit'
						name='submit'
						value='Update Object'
						onclick='commitAll();'
						form="edit-form"
						<?php if ($user == 'guest'): ?>
							disabled = "disabled"
						<?php endif; ?>
					>
				</div>
			</div>
		<!-- </form> -->
		<form
			method="post"
			enctype="multipart/form-data"
			action="<? echo $form_url; ?>"
			id="edit-form"
		>
		</form>
	</div>
<?php
}
// THIS CODE NEEDS TO BE FACTORED OUT SO HARD
// basically the same as what is happening in add.php
else
{
	$new = array();
	// objects
	foreach($vars as $var)
	{
		$new[$var] = addslashes($rr->$var);
		if(isset($current_item[$var]))
			$current_item[$var] = addslashes($current_item[$var]);
		else
			$current_item[$var] = '';
	}
	$siblings = $oo->siblings($uu->id);
	$updated = update_object($item, $new, $siblings, $vars);

	// process new media
	$updated = (process_media($uu->id) || $updated);

	// delete media
	// check to see if $rr->deletes exists (isset)
	// because if checkbox is unchecked that variable "doesn't exist"
	// although the expected behaviour is for it to exist but be null.
	if(isset($rr->deletes))
	{
		foreach($rr->deletes as $key => $value)
		{
			$m = $rr->medias[$key];
			$mm->deactivate($m);
			$updated = true;
		}
	}

	// update caption, weight, rank
    if (is_array($rr->captions)) {
	    $num_captions = sizeof($rr->captions);
	    if (sizeof($rr->medias) < $num_captions)
		    $num_captions = sizeof($rr->medias);
		for ($i = 0; $i < $num_captions; $i++)
		{
			unset($m_arr);
			$m_id = $rr->medias[$i];
			$caption = addslashes($rr->captions[$i]);
			$rank = addslashes($rr->ranks[$i]);

			$m = $mm->get($m_id);
			if($m["caption"] != $caption)
				$m_arr["caption"] = "'".$caption."'";
			if($m["rank"] != $rank)
				$m_arr["rank"] = "'".$rank."'";

			if(isset($m_arr))
			{
				$arr["modified"] = "'".date("Y-m-d H:i:s")."'";
				$updated = $mm->update($m_id, $m_arr);
			}
		}
    }

	?><div class="self-container"><?
		// should change this url to reflect updated url
		$urls = array_slice($uu->urls, 0, count($uu->urls)-1);
		$u = implode("/", $urls);
		$url = $admin_path."browse/";
		if(!empty($u))
			$url.= $u."/";
		$url.= $new['url'];
		?><p><a href="<? echo $url; ?>"><?php echo $new['name1']; ?></a></p><?
	// Job well done?
	if($updated)
	{
	?><p>Record successfully updated.</p><?
		if(!$urlIsValid)
		{
		?><p>*** The url of this record has been set to '<?= $new['url']; ?>' because of a conflict with another record. ***</p><?
		}
	}
	else
	{
	?><p>Nothing was edited, therefore update not required.</p><?
	}
	?></div><?
}
?></div>
</div>
<style>
	.wysiwyg-section
	{
/*		padding:20px;*/
		background-color:#dedede;
/*		margin-bottom:5px;*/
	}
	.wysiwyg-edit-img
	{
		display: block;
		cursor: pointer;
	}
	.wysiwyg-section textarea
	{
		background-color: #dedede;
		cursor:pointer;
		border:none;
		resize: vertical;
		padding: 5px;
		display: block;
	}
	.wysiwyg-section textarea:focus
	{
		background-color: #fff;
		outline:none;
		cursor:text;
	}

	.wysiwyg-section textarea.wysiwyg-edit-figcaption
	{
		text-align: center;
		padding-left: 30px;
		padding-right: 30px;
		margin-top:px;
	}
	
	.wysiwyg-add-section {
		cursor: pointer;
		text-align: center;
		color: #fff;
		font-size: 12px;
	}
	.wysiwyg-add-toggle:hover,
	.section-option:hover
	{
		background-color: #fff;
		color: #000;
	}
	.wysiwyg-add-section .msg
	{
		display: inline-block;
	}
	.inline-icon
	{
/*		display: inline-block;*/
		margin-right:5px;
		font-size: 16px;

	}
	.toolbar
	{
		width: auto;
	}
	.msg
	{
/*		font-family: ;*/
	}
	.add-options-container,
	.viewing-sectionOptions .add-prompt-container
	{
		display: none;
	}
	.viewing-sectionOptions .add-options-container
	{
		display: block;
	}
	.wysiwyg-add-toggle,
	.section-option
	{
		padding: 5px;
		background-color:#000;
	}
	.section-option
	{
		margin-top:2px;
	}
</style>