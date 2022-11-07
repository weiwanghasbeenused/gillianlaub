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
	$section = false;
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
		$col_number = '2-3';
	$project_description = empty($item['deck']) ? false : $item['deck'];
}

$pattern_projectSite = '/\[project\-site\]\((.*?)\)/';
preg_match($pattern_projectSite, $item['notes'], $temp);

if(!empty($temp))
	$project_site_url = $temp[1];
else
	$project_site_url = false;

?>
<main id="project-container" class="main-container padding-wrapper">
	<h1 class="project-name in-main large"><?= $item['name1']; ?></h1>
    <?
    	if($project_site_url)
			echo '<a class="small project-site-link in-main" href="' . $project_site_url . '" target="_blank">PROJECT SITE<span class="top-right-arrow"></span></a>';
    	if($pageType == 'detail')
		{
			if($project_description)
			{
				?><div id="project-description-container" class="large"><h1 class="project-name large in-description"><?= $item['name1']; ?></h1><?= $project_description ; ?></div>
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
				?><div id="gallery-container" class="scroll-gallery-container large"><? 
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
							$replacement = '<figure><img class="'.$image_class.'" src="'.$src.'" loading="lazy" alt="'.$this_m['caption'].'"><figcaption class="gallery-caption caption">'.$this_m['caption'].'</figcaption></figure>';
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
	<h1 class="project-name in-footer large"><?= $item['name1'];?></h1>
	<div class="align-right">
	<? if($pageType == 'detail'){
		if($section)
		{
			function renderSectionLinks($sections, $section, $isDropdown = false, $class=''){
				$baseClass = 'project-sections-link';
				if($isDropdown)
					$class .= ' dropdown';
				else
					$class .= ' not-dropdown';
				$output = '<div class="project-sections-link-container '.$class.'"><div class="project-sections-select-wrapper">';
				if($isDropdown)
				{
					$active_btn_html = '';
					$other_btn_html = '';
					foreach($sections as $key => $s)
					{
						if($section == $s['url']){
							$active_btn_html .= '<span class="project-sections-dummy small">' . strtoupper($s['displayName']) . '</span>';
						}
						else{
							$other_btn_html .= '<a class="project-sections-link small" href="' . $s['fullUrl'] . '">' . strtoupper($s['displayName']) . '</a>';
						}
					}
					$output .= $active_btn_html . $other_btn_html;
					$output .= '<div id="project-sections-toucharea"></div>';
				}
				else
				{
					foreach($sections as $key => $s)
					{
						if($section == $s['url']){
							$output .= '<span class="project-sections-dummy small">' . strtoupper($s['displayName']) . '</span>';
						}
						else{
							$output .= '<a class="project-sections-link small" href="' . $s['fullUrl'] . '">' . strtoupper($s['displayName']) . '</a>';
						}
					}
				}
				$output .= '</div></div>';
				return $output;
			}
			echo renderSectionLinks($sections, $section, true);
			echo renderSectionLinks($sections, $section);
			
		}
		if($project_site_url)
			echo '<a class="project-sections-link small project-site-link in-footer" href="' . $project_site_url . '" target="_blank">PROJECT SITE<span class="top-right-arrow"></span></a>';
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
			<!-- <div id="close-project-description-btn" class="icon-btn"></div> -->
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
    if(window.innerWidth < 821)
    {
    	var sProject_sections_toucharea = document.getElementById('project-sections-toucharea');
    	// var sProject_sections_dummy = document.querySelector('.project-sections-dummy');
    	if(sProject_sections_toucharea)
    	{
    		sProject_sections_toucharea.addEventListener('click', function(){
	    		body.classList.toggle('viewing-dropdown');
	    	});
    	}
    	
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
#project-sections-toucharea
{
	position: absolute;
	top: 0;
	right: 0;
	width: 100%;
	height: 100%;
}
.viewing-dropdown #project-sections-toucharea
{
	width: 26px;
	height: 26px;
}
.project-sections-link-container
{
    width: 140px;
    height: 26px;
    display: inline-block;
    /*overflow: hidden;*/
    border-bottom: 3px solid #000;
    /*margin-right: 10px;*/
    position: relative;
    float: left;
    text-align: left;
}
.project-sections-link-container.not-dropdown
{
	display: none;
}
.project-sections-select-wrapper
{
    position: absolute;
    width: 100%;
    bottom: 0;
    left: 0;
    max-height: 26px;
    overflow: hidden;
    background-color: #fff;
}
.project-sections-select-wrapper:after
{
    content: "";
    display: block;
    position: absolute;
    right: 5px;
    top: 4px;
    width: 0px;
    height: 0px;
    /*background-color: ;*/
    border-right: 9px solid transparent;
    border-left: 9px solid transparent;
    border-bottom: 16px solid #000;
    pointer-events: none;
}
.viewing-dropdown .project-sections-select-wrapper:after
{
    border-bottom: none;
    border-top: 16px solid #000;
}
.project-sections-link
{
    position: relative;
    /*transform: translate(0, 100%);*/
}
.viewing-dropdown .project-sections-select-wrapper
{
    transition: max-height .5s;
    /*transform: translate(0, -100%);*/
    max-height: 200px;
}
.viewing-dropdown .project-sections-link
{
    transition: transform .25s;
    /*transform: translate(0, 0);*/
}
.project-sections-dummy,
.project-sections-link
{
    margin-right: 10px;
    font-weight: 600;
    display: block;
    padding:5px;
}
.icon-btn
{
    width: 27px;
    height: 27px;
    display: inline-block;
    margin-right: 10px;
}
.icon-btn.grid,
.icon-btn.scroll
{
    position: relative;
    border: 3px solid #BBB;
}
.icon-btn.grid:before
{
    content: "";
    display: block;
    position: absolute;
    width: 100%;
    height: 3px;
    top: 50%;
    left: 0;
    background-color: #BBB;
    transform: translate(0,-50%);
}
.icon-btn.grid:after
{
    content: "";
    display: block;
    position: absolute;
    width: 3px;
    height: 100%;
    left: 50%;
    top: 0;
    background-color: #BBB;
    transform: translate(-50%,0);
}
#description-btn
{
    /*border-top: 3px solid #BBB;*/
    /*border-bottom: 3px solid #BBB;*/
    position: relative;
    cursor: pointer;
    margin-right: 0;
}
#description-btn:before,
#description-btn:after
{
    content: "";
    border-top: 3px solid #BBB;
    position: absolute;
    
    left: 0;
    width: 100%;
    transition: transform .35s, top .35s, bottom .35s, left .35s, width .35s;
}
#description-btn:before
{
    top: 0;
}
#description-btn:after
{
    bottom: 0;
    
}
.description-btn-bar
{
    width: 100%;
    height: 3px;
    background-color: #BBB;
    position: absolute;
    left: 0;
    transform: translate(0,-50%);
    
}
.description-btn-bar:first-child
{
    top: 7.5px;
}
.description-btn-bar:nth-child(2)
{
    top: 50%;
}
.description-btn-bar:last-child
{
    top: 19.5px;
}
.viewing-description .description-btn-bar
{
    opacity: 0;
}
.viewing-description #description-btn:before,
.viewing-description #description-btn:after
{
    border-color: #000;
    left: 50%;
    width: 120%;
}
.viewing-description #description-btn:before
{
    top: 50%;
    transform: translate(-50%, -50%) rotate(45deg);
}
.viewing-description #description-btn:after
{
    bottom: 50%;
    transform: translate(-50%, 50%) rotate(-45deg);
}
.icon-btn.active
{
    border-color: #000;
}
.icon-btn.active:before,
.icon-btn.active:after,
.icon-btn.active .description-btn-bar
{
    background-color: #000;
}

.noTouchScreen .icon-btn:hover,
.noTouchScreen #description-btn:hover,
.noTouchScreen #description-btn:hover:after,
.noTouchScreen #description-btn:hover:before
{
    border-color: #000;
}
.noTouchScreen .icon-btn:hover:before,
.noTouchScreen .icon-btn:hover:after,
.noTouchScreen .icon-btn:hover .description-btn-bar
{
    background-color: #000;
}
#project-description-container
{
    position: fixed;
    width: 100vw;
    max-width: 100%;
    height: 100vh;
    background-color: #fff;
    z-index: 1000;
    opacity: 0;
    left: -10000px;
    top: 0;
    overflow: scroll;
    padding: 20px 10px 60px 10px;
    line-height: 1.3em;
}
.viewing-description #project-description-container
{
    opacity: 1;
    transition: opacity .5s;
    left: 0;
}
.viewing-description
{
    height: 100vh;
    overflow: hidden;
}
.viewing-description .project-sections-link-container,
.viewing-description .layout-option-btn
{
    opacity: 0;
    pointer-events: none;
}
.align-right
{
    text-align: right;
}
#project-footer
{
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100vw;
    max-width: 100%;
    padding-bottom: 10px;
    padding-top: 15px;
    background-color: #fff;
    z-index: 1002;
}
.project-name.in-footer
{
    display: none;
}
.project-name.in-description
{
	margin-bottom: 1em;
}
.top-right-arrow
{
	position: relative;
	display: inline-block;
	width: 12px;
	height: 12px;
	margin-left: 5px;
	/*top: 2px;*/
}
.top-right-arrow:before
{
	content: "";
	position: absolute;
	border-top: 3px solid;
	border-right: 3px solid;
	top: 0;
	right: 0;
	width: 100%;
	height: 100%;
	box-sizing: border-box;
	border-color: inherit;

}
.top-right-arrow:after
{
	content: "";
	border-top: 3px solid;
	position: absolute;
	top: 0;
	right: 1.5px;
	transform-origin: center right;
	width: 130%;
	/*transform: translate(-50%, -50%);*/
	transform: rotate(-45deg);
	border-color: inherit;
}
.project-site-link.in-main
{
	font-weight: 700;
	margin-bottom: 20px;
	text-align: center;
	display: block;
}
.project-site-link.in-footer
{
	display: none;
}
#gallery-container
{
	line-height: 1.3em;
}
@media screen and (min-width: 376px) {
    .project-sections-select-wrapper
    {
        max-height: 32px;
    }
    .project-sections-link-container
    {
        height: 32px;
    }
    .project-sections-select-wrapper:after
    {
        top: 7px;
    }
    #project-description-container
    {
        padding: 20px;
    }
    .project-sections-dummy, .project-sections-link
    {
        padding: 8px;
    }
    #project-footer
    {
        padding-top: 20px;
        padding-bottom: 15px;
    }
    #project-description-container
	{
		padding-bottom: 70px;
		padding-left: 10vw;
		padding-right: 10vw;
		padding-top: 40px;
	}
	
}
@media screen and (min-width: 769px) {
	.viewing-dropdown #project-sections-toucharea
	{
		width: 28px;
		height: 28px;
	}
	#project-description-container
	{
		/*padding-left: 5vw;*/
		padding-left: 10vw;
		padding-right: 10vw;
	}

}
@media screen and (min-width: 821px) {
	.project-sections-link-container
    {
        width: auto;
        height: auto;
        float: none;
        border-bottom: none;
    }
    .project-sections-select-wrapper
    {
        position: static;
        overflow: visible;
        max-height: none;
    }
    .project-sections-select-wrapper:after
    {
        display: none;
    }
    .project-sections-dummy,
    .project-sections-link
    {
        display: inline-block;
        padding: 0;
    }
    .align-right
    {
        flex-grow: 1;
    }
    #project-footer
    {
        display: flex;
    }
    .project-name.in-footer
    {
        display: block;
        float: left;
        margin-top: 7px;
    }
    .project-name.in-description
    {
    	display: none;
    }
    #project-sections-toucharea
	{
		display: none;
	}
	.project-sections-link-container.dropdown
	{
		display: none;
	}
	.project-sections-link-container.not-dropdown
	{
		display: inline-block;
	}
	.project-site-link.in-main
	{
		display: none;
	}
	.project-site-link.in-footer
	{
		display: inline-block;
	}
	.noTouchScreen .top-right-arrow:after,
	.noTouchScreen .top-right-arrow:before
	{
		border-color: ;
	}
}
@media screen and (min-width: 1024px) {
	.icon-btn
    {
        width: 36px;
        height: 36px;
    }
    #about-btn
    {
        margin-right: 25px;
    }
    .project-name.in-footer
    {
        margin-top: 0px;
    }
    .description-btn-bar:first-child
    {
        top: 9.75px;
    }
    .description-btn-bar:last-child
    {
        top: 26.25px;
    }
    #project-description-container
	{
		padding-right: 15vw;
		padding-left: 15vw;
		padding-bottom: 80px;
	}
}

</style>