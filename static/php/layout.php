<?
function renderGrid($items, $colNumber, $id='', $itemType='link'){
	$items = array_values($items);
	if($colNumber == 2)
		return render2ColsGrid($items, $id, $itemType);
	else if($colNumber == 3)
		return render3ColsGrid($items, $id, $itemType);
	else if($colNumber == '2-3')
		return render2to3ColsGrid($items, $id, $itemType);
}
function render2ColsGrid($items, $id='', $itemType='link'){
	$output = '<div id="'.$id.'" class="two-cols-grid-container grid-container">';
	
	$common_class = 'two-cols-grid-item';
	foreach($items as $key => $item){
		if( ($key + 1) % 2 == 1)
			$class = $common_class . ' first-col-grid-item';
		else if(($key + 1) % 2 == 0)
			$class = $common_class . ' second-col-grid-item last-col-grid-item';
		if( $itemType == 'link' ) $output .= renderLinkItem($item, $class);
		else if ($itemType == 'gallery') $output .= renderGalleryItem($item, $class);
	}
	$output .= '</div>';
	return $output;
}
function render2to3ColsGrid($items, $id='', $itemType='link'){
	$output = '<div id="'.$id.'" class="two-to-three-cols-grid-container grid-container">';
	$common_class = 'two-to-three-cols-grid-item';
	foreach($items as $key => $item){
		$class = '';
		// if( ($key + 1) % 2 == 1)
		// 	$class = $common_class . ' first-col-grid-item';
		// else if(($key + 1) % 2 == 0)
		// 	$class = $common_class . ' second-col-grid-item last-col-grid-item';
		if( $itemType == 'link' ) $output .= renderLinkItem($item, $class);
		else if ($itemType == 'gallery') $output .= renderGalleryItem($item, $class);
	}
	$output .= '</div>';
	return $output;
}
function render3ColsGrid($items, $id='', $itemType='link'){
	$output = '<div id="'.$id.'" class="three-cols-grid-container grid-container">';
	$common_class = 'three-cols-grid-item';
	foreach($items as $key => $item){
		if( ($key + 1) % 3 == 1)
			$class = $common_class . ' first-col-grid-item';
		else if(($key + 1) % 3 == 2)
			$class = $common_class . ' second-col-grid-item';
		else if(($key + 1) % 3 == 0)
			$class = $common_class . ' third-col-grid-item last-col-grid-item';
		if( $itemType == 'link' ) $output .= renderLinkItem($item, $class);
		else if ($itemType == 'gallery') $output .= renderGalleryItem($item, $class);
	}
	$output .= '</div>';
	return $output;
}
function renderLinkItem($item, $class=''){
	global $oo;
	$media = $oo->media($item['id']);
	if(!empty($item['address2']))
		$thumbnail = '<div class="grid-item-thumbnail-container"><img class="grid-item-thumbnail" src="'. processSrc($item['address2']) .'"></div>';
	else if(!empty($media))
		$thumbnail = '<div class="grid-item-thumbnail-container"><img class="grid-item-thumbnail" src="'. m_url($media[0]) .'"></div>';
	else
		$thumbnail = '';
	$displayName = isset($item['displayName']) ? $item['displayName'] : $item['name1'];

	$output = '<a href="' . $item['fullUrl'] . '" class="grid-item '.$class.'">'.$thumbnail.'<h2 class="grid-item-title middle">'.$displayName.'</h2></a>';
	return $output;
}
function renderGalleryItem($item, $class=''){
	$output = '<div class="grid-item '.$class.'"><div class="grid-item-thumbnail-container"><img loading="lazy" alt="'.$item['caption'].'" class="grid-item-thumbnail lightbox-btn" src="'.m_url($item).'"></div></div>';
	return $output;
}

if(!$uri[1])
	$col_number = 3; 

if(!$uri[1]){
	$temp = $oo->urls_to_ids(array('projects'));
	$projects = $oo->children(end($temp));
	foreach($projects as &$p)
	{
		$fullUrl = '/projects/' . $p['url'];
		$p['fullUrl'] = $fullUrl;
	}
	unset($p);

	$temp = $oo->urls_to_ids(array('commissions'));
	$commissions = $oo->children(end($temp));
	foreach($commissions as &$c)
	{
		$fullUrl = '/commissions/' . $c['url'];
		$c['fullUrl'] = $fullUrl;
	}
	unset($c);
	// $items = $projects;
}

?>