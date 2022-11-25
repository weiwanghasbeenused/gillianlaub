<? 
	
?>

<div id="body-container">
	<? require_once('cats.php'); 
	$children = $Cat->getActiveCatChildren();
	?>
	<main id="children-container"><?
		foreach($children as $key => $child){
			if(count($children) > 99 )
				$idx = str_pad($key + 1, 3, 0, STR_PAD_LEFT);
			else
				$idx = str_pad($key + 1, 2, 0, STR_PAD_LEFT);
			?><a class="child" href="<?= $child['editUrl']; ?>"><?= $idx . ' ' . $child['name1']; ?></a><?
		}; ?>
	</main>
</div>
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
