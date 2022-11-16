<?php
// namespace controllers;

class CategoryController
{
	public function __construct(array $catUrls = array(), string $currentCatUrl = '')
    {
    	global $oo;
    	global $admin_path;
    	$this->oo = $oo;
    	$this->admin_path = $admin_path;
        $this->categoryUrls = $catUrls;
        $this->currentCategory = $currentCatUrl;
        $catItems = array();
        $activeCatItem = array();
        foreach($catUrls as $urlArr)
		{

			$item = array();
			if(is_array($urlArr))
			{
				$ids = $oo->urls_to_ids($urlArr);
				if(count($ids) == count($urlArr)){
					$item = $oo->get(end($ids));
					if( !empty($currentCatUrl) && empty($activeCatItem) && end($urlArr) == $currentCatUrl)
						$activeCatItem = $item;
				}
			}
			$catItems[] = $item;
		}
		$this->catItems = $catItems;
		$this->activeCatItem = $activeCatItem;
    }
	function getCatItems(){
		return $this->catItems;
	}
	function getActiveCatItem(string $field = ''){
		if(!empty($field))
		{
			if(isset($this->activeCatItem[$field]))
				return $this->activeCatItem[$field];
			else
				return false;
		}
		else
			return $this->activeCatItem;
		
	}
	function getActiveCatChildren(){
		$activeCatItem = $this->getActiveCatItem();
		if(!empty($activeCatItem))
		{
			$children = $this->oo->children($activeCatItem['id']);
			foreach($children as $key => &$child)
			{
				$child['browseUrl'] = $this->admin_path . 'browse/' . $activeCatItem['url'] . '/' . $child['url'];
				$child['editUrl'] = $this->admin_path . 'edit/' . $activeCatItem['url'] . '/' . $child['url'];
				$child['deleteUrl'] = $this->admin_path . 'delete/' . $activeCatItem['url'] . '/' . $child['url'];
			}
			unset($child);
		} else $children = array();
		
		return $children;
	}
} 	
?>