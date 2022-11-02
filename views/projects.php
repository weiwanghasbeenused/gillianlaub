<?
require_once('static/php/layout.php');

$name = $item['name1'];
$deck = $item['deck'];
$body = $item['body'];
$notes = $item['notes'];
$date = $item['begin'];
$find = '/<div><br><\/div>/';
$replace = '';
// $body = preg_replace($find, $replace, $body);


$children = $oo->children($item['id']);
$children = removeHiddenChildren($children);
if(count($children) == 1){
	$pageType = 'detail';
	$about = $item['deck'];
	$detail_item = $children[0];
}
else{
	$section = isset($_GET['section']) ? $_GET['section'] : false;
	$sections = $children;
	foreach($sections as &$s)
	{
		$fullUrl = implode('/', $uri) . '?section=' . $s['url'];
		$s['fullUrl'] = $fullUrl;
		$s['displayName'] = ucfirst($s['name1']);
	}
	unset($s);
	if(!$section){
		$pageType = 'list';
		$col_number = 2;
	}
	else{
		$temp = $oo->urls_to_ids(array('projects', $uri[2], $section));
		$pageType = 'detail';
		$detail_item = $oo->get(end($temp));

	}
}

if($pageType == 'detail')
{
	$layout_options = empty($detail_item['address1']) ? false : preg_split('/\,\s?/', $detail_item['address1']);
	if(isset($_GET['layout']))
		$current_layout = $_GET['layout'];
	else if($layout_options)
		$current_layout = $layout_options[0];
	else
		$current_layout = 'grid';	
	
	if($current_layout == 'grid')
		$col_number = 3;
	$project_description = empty($item['deck']) ? false : $item['deck'];
}

// $container_class = 'main-container';

?>
<main id="project-container" class="main-container">
    <?
    	if($pageType == 'detail')
		{
			if($project_description)
			{
				?><div id="project-description-container" ><?= $project_description; ?></div>
				<?
			}
			$media = $oo->media($detail_item['id']);
			$media_key_as_id = array();
			foreach($media as $m)
			{
				$media_key_as_id[$m['id']] = $m;
			}
			if($current_layout == 'grid')
			{
				
				echo renderGrid($media, $col_number, 'gallery-container', 'gallery');
			}
			else if($current_layout == 'scroll')
			{
				?><div id="gallery-container" class="scroll-gallery-container"><? 
					$body = $detail_item['body'];
					$img_tag_pattern = '/\<img\ssrc\=\"(.*?)\">/';
					$media_id_pattern = '/\/media\/(.*?)\./';

					preg_match_all($img_tag_pattern, $body, $temp);
					if(!empty($temp) && !empty($temp[1]))
					{
						foreach($temp[1] as $key => $src)
						{
							$find = $temp[0][$key];
							$image_class = 'gallery-image lightbox-btn';
							$size = getimagesize(substr($src, 1, strlen($src)));
							if($size[0] < $size[1])
								$image_class .= ' portrait';
							else if($size[0] > $size[1])
								$image_class .= ' landscape';
							else
								$image_class .= ' square';

							preg_match($media_id_pattern, $src, $media_id);
							$media_id = intval($media_id[1]);
							$this_m = $media_key_as_id[$media_id];
							// $caption = $this_m['caption'];
							$replacement = '<figure><img class="'.$image_class.'" src="'.$src.'" loading="lazy" alt="'.$this_m['caption'].'"><figcaption class="gallery-caption">'.$this_m['caption'].'</figcaption></figure>';
							$body = str_replace($find, $replacement, $body);
						}
					}
					echo $body;
				 ?></div><?
			}

		}
    	else if($pageType == 'list')
    	{
    		echo renderGrid($sections, $col_number, 'sections-grid-container');
    	}
    ?>
    
</main>
<footer id="project-footer" class="padding-wrapper float-container">
	<h1 id="project-name"><?= $item['name1'];?></h1>
	<div class="align-right">
	<? if($pageType == 'detail'){
		if($section)
		{
			// $baseUrl = implode('/')
			$baseClass = 'project-sections-link';
			?><div id="project-sections-link-container"><?
			foreach($sections as $key => $s)
			{
				if($section == $s['url']){
					?><span class="project-sections-dummy"><?= strtoupper($s['displayName']); ?></span><?
				}
				else{
					?><a class="project-sections-link" href="<?= $s['fullUrl']?>"><?= strtoupper($s['displayName']); ?></a><?
				}
			}
			?></div><?
			
		}
		if($layout_options)
		{
			$baseClass = 'icon-btn layout-option-btn';
			if(empty($_GET))
				$baseUrl = $request . '?layout=';
			else
			{
				$temp = $_GET;
				$temp['layout'] = '';

				$baseUrl = $requestclean . '?';
				foreach($temp as $key => &$value)
					$value = $key . '=' . $value;
				unset($value);
				$baseUrl .= implode('&', $temp);
			}
			foreach($layout_options as $key => $option)
			{
				$class = $baseClass . ' ' . $option;
				if( ($current_layout && $current_layout == $option) || (!$current_layout && $key == 0)) {
					$class .= ' active';
					?><div class="<?= $class; ?>"></div><?
				}
				else
				{
					?><a href = '<?= $baseUrl . $option; ?>' class="<?= $class; ?>"></a><?
				}
				
			}
		}
		if($project_description)
		{
			?><div id="description-btn" class="icon-btn">
				<div class="description-btn-bar"></div>
				<div class="description-btn-bar"></div>
				<div class="description-btn-bar"></div>
			</div>
			<div id="close-project-description-btn" class="icon-btn"></div>
			<?
		}
	}
	?>
	</div>
</footer>
<script>
    var sDescription_btn = document.getElementById('description-btn');
    if(sDescription_btn){
    	sDescription_btn.addEventListener('click', function(){
    		body.classList.toggle('viewing-description');
    	});
    		
    }
    var sClose_project_description_btn = document.getElementById('close-project-description-btn');
    console.log(sClose_project_description_btn);
    if(sClose_project_description_btn){
    	sClose_project_description_btn.addEventListener('click', function(){
    		// console.log('click');
    		body.classList.remove('viewing-description');
    	});
    	// sClose_project_description_btn.onClick = function(){ body.classList.remove('viewing-description'); };
    }
</script>
<? require_once('views/lightbox.php'); ?>
<style>
	.project-sections-link
	{
		color: #BBBBBB;
	}
	.noTouchScreen .project-sections-link:hover
	{
		color: #000;
	}
	#project-name
	{
		
	}
</style>