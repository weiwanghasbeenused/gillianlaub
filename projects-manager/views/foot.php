<?
// $generate_url = implode("/", $uu->urls);
// $g = $host.$generate_url;
			?>
			<footer id="main-footer" class="centre">
				<? if($uri[2] == 'edit'){
					?><a class="btn" href="<? echo $general_urls['generate']; ?>" target="_blank">GENERATE</a><?
					?><a class="btn" href="<? echo $general_urls['delete']; ?>" >DELETE</a><?
				} else if($uri[2] == 'browse' && count($uri) == 4)
				{
					?><a class="btn" href="<? echo $general_urls['add']; ?>">ADD</a><?
				} ?>				
				<a class="btn" href="<? echo $general_urls['logout']; ?>" style="float: right;">LOG OUT</a>
			</footer>
		</div>
	</body>
</html><?
$db-> close();
?>
