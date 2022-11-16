<?
require_once __DIR__ . '/../controllers/CategoryController.php';
$category_urls_arr = array(
	array('projects'),
	array('commissions')
);
$active_cat_url = isset($uri[3]) ? $uri[3] : '';
$Cat = new CategoryController($category_urls_arr, $active_cat_url);
$cat_items = $Cat->getCatItems();
$active_cat_item = $Cat->getActiveCatItem();
?>
<section id="category-container" class="flex-container">
	<? foreach($cat_items as $cat_item){
		if(!empty($active_cat_item) && $active_cat_item['url'] == $cat_item['url'])
		{
			?><span id="category-<?= $cat_item['url']; ?>" class="dummy-link cat-item flex-item active" href="<? echo $admin_path.'browse/' . $cat_item['url']; ?>"><?= strtoupper($cat_item['name1']); ?></span><?
		}
		else{
		?><a id="category-<?= $cat_item['url']; ?>" class="cat-item flex-item inactive" href="<? echo $admin_path.'browse/' . $cat_item['url']; ?>"><?= strtoupper($cat_item['name1']); ?></a><?
		}
	} ?>
</section>
