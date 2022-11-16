<?
$generate_url = implode("/", $uu->urls);
$g = $host.$generate_url;
			?>
			<footer id="main-footer" class="centre">
				<a class="btn" href="<? echo $g; ?>" target="_blank">GENERATE</a>
				<?php if ($user != 'guest'): ?>
					<a class="btn" href="<? echo $admin_path; ?>settings">SETTINGS</a>
				<?php endif; ?>
				<a class="btn" href="<? echo $admin_path; ?>logout" style="float: right;">LOG OUT</a>
			</footer>
		</div>
	</body>
</html><?
$db-> close();
?>
