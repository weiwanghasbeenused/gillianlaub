<? 
	
?>

<div id="body-container">
	<? require_once('cats.php'); 
	$children = $Cat->getActiveCatChildren();
	?>
	<main id="children-container"><?
		if(!isset($_GET['action']))
		{
			foreach($children as $key => $child){
				if(count($children) > 99 )
					$idx = str_pad($key + 1, 3, 0, STR_PAD_LEFT);
				else
					$idx = str_pad($key + 1, 2, 0, STR_PAD_LEFT);
				?><a class="child" href="<?= $child['editUrl']; ?>"><?= $idx . ' ' . $child['name1']; ?></a><?
			};
			?><div class="button-container"><a class="btn on-grey" href="<?= $admin_path . 'browse/' . $uri[3] . '?action=edit'; ?>">Reorder projects</a></div><?
		}
		else if(isset($_GET['action']) && $_GET['action'] == 'edit')
		{
			?>
			<script src="<?= $admin_path . 'static/js/edit.js'; ?>"></script>
			<script src="<?= $admin_path . 'static/js/WhatYouSee.js'; ?>"></script>
			<form id="reorder-form" method="post" enctype="multipart/form-data" action="<?= $admin_path; ?>static/php/editHandler.php">
				<input type='hidden' name='successUrl' value='<?= $admin_path . 'browse/' . $uri[3]; ?>' >
				<input type='hidden' name='errorUrl' value='<?= $admin_path . 'browse/' . $uri[3]; ?>' >
				<input type="hidden" name="section-order">
				<div class="order-container"><?
			$item_num = count($children);
			foreach($children as $key => $child){
				$select_html = '<select class="order-select" section="'.$child['id'].'" currentValue="'.($key + 1).'" onchange="reorder(this);">';
				for($i = 0; $i < $item_num; $i++)
				{
					if($i == $key) $select_html .= '<option value = "' .($i + 1). '" selected>' . ($i + 1) . '</option>';
					else $select_html .= '<option value = "' .($i + 1). '" >' . ($i + 1) . '</option>';
				}
				$select_html .= '</select>';

				if(count($children) > 99 )
					$idx = str_pad($key + 1, 3, 0, STR_PAD_LEFT);
				else
					$idx = str_pad($key + 1, 2, 0, STR_PAD_LEFT);
				?><div class="child float-container order-item" ><div class="child-name"><?= $idx . ' ' . $child['name1']; ?></div><div class="order-control"><?= $select_html; ?></div></div><?
			};
			?></div><div class="button-container"><a class="btn on-grey" href="<?= $admin_path . 'browse/' . $uri[3]; ?>">Cancel</a><span class="btn on-grey" onclick="wsForm.submit();" >Update</span></div>
			</form>
			<script>
				var wsForm = new WhatYouSee(document.getElementById("reorder-form"));
			</script>
			<?
		}
		?>
		
	</main>
</div>
<style>
	.float-container > .child-name
	{
		float:left;
	}
	
</style>
<script>
	var sChildren_page = document.getElementsByClassName('children-page');
	if(sChildren_page.length != 0){
		var page_index = 0;
		var page_num = sChildren_page.length;
		var sChildren_index = document.querySelectorAll('.children-index');

		function next_page(){
			sChildren_page[page_index].style.display = 'none';
			deactivate_folio();
			page_index ++;
			if (page_index == page_num)
				page_index = 0;
			sChildren_page[page_index].style.display = 'block';
			activate_folio(page_index);
		}
		function prev_page(){
			sChildren_page[page_index].style.display = 'none';
			deactivate_folio();
			page_index --;
			if (page_index == -1)
				page_index = page_num-1;
			sChildren_page[page_index].style.display = 'block';
			activate_folio(page_index);
		}
		function jump_to_page(index){
			sChildren_page[page_index].style.display = 'none';
			deactivate_folio();
			page_index = index;
			sChildren_page[page_index].style.display = 'block';
			activate_folio(page_index);
		}

		function activate_folio(index){
			Array.prototype.forEach.call(sChildren_index, function(el, i){
				var folio_to_activate = el.getElementsByClassName('folio')[index];
				folio_to_activate.classList.add('active');
			});
			window.scrollTo(0,0);
		}
		function deactivate_folio(){
			var active_folio = document.querySelectorAll('.folio.active');
			Array.prototype.forEach.call(active_folio, function(el, i){
				el.classList.remove('active');
			});
		}


		Array.prototype.forEach.call(sChildren_index, function(el, i){
			var this_prev = el.getElementsByClassName('children-prev')[0];
			var this_next = el.getElementsByClassName('children-next')[0];
			var this_folio = el.getElementsByClassName('folio');
			this_prev.addEventListener('click', function(){
				prev_page();
			});
			this_next.addEventListener('click', function(){
				next_page();
			});
			Array.prototype.forEach.call(this_folio, function(ell, ii){
				ell.addEventListener('click', function(){
					jump_to_page(ii);
				});
			});
		});

		// add active to page 1
		Array.prototype.forEach.call(sChildren_index, function(el, i){
			var folio_to_activate = el.getElementsByClassName('folio')[page_index];
			folio_to_activate.classList.add('active');
		});
	}
</script>
