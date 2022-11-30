<?
$now = time();
$now_date = date('Y-m-d', time());
$current_html = '';
$past_html = '';
$sql_current = 'SELECT objects.* FROM objects, wires WHERE wires.active = "1" AND objects.active = "1" AND wires.toid = objects.id AND wires.fromid = "'.$item['id'].'" AND DATE(objects.begin) >= "'.$now_date.'" ORDER BY objects.begin ASC, objects.end ASC';
$result = $db->query($sql_current);
while($e = $result->fetch_assoc()){
	$current_html .= renderEventItem($e, 'current-event-item');
}
$sql_past = 'SELECT objects.* FROM objects, wires WHERE wires.active = "1" AND objects.active = "1" AND wires.toid = objects.id AND wires.fromid = "'.$item['id'].'" AND DATE(objects.begin) < "'.$now_date.'" ORDER BY objects.begin DESC, objects.end DESC';
$result = $db->query($sql_past);
while($e = $result->fetch_assoc()){
	$past_html .= renderEventItem($e, 'past-event-item');
}
$current_html = '<ul>' . $current_html . '</ul>';
$past_html = '<ul>' . $past_html . '</ul>';

function renderEventItem($event, $class=''){
	$now_y = date('Y', time());
	$date = date('M d', strtotime($event['begin']));
	if( $now_y != date('Y', strtotime($event['begin'])) )
		$date .= ',<br>' . date('Y', strtotime($event['begin']));
	$time = date('h:m A', strtotime($event['begin']));
	$output = '<li class="'.$class.' event-info flex-container" breakpoint="1024"><div class="event-datetime flex-item"><span class="event-date">'.$date . '</span><span class=""event-time>' . $time .'</span></div><div class="event-name-location flex-item"><p class="middle event-name">'.$event['name1'].'</p><div class="small event-location">'.$event['notes'].'</div></div></li>';
	return $output;
}
?>
<main id="event-container" class="main-container padding-wrapper">
	<div class="flex-container" breakpoint="700">
		<div id="current-events-container" class="about-section flex-item">
			<h3 class="large about-section-title">Current News & Events</h3>
			<?= $current_html; ?>
		</div>
		<div id="past-events-container" class="about-section flex-item">
			<h3 class="large about-section-title">Past News & Events</h3>
			<?= $past_html; ?>
		</div>
	</div>
</main>
<style>
	
	@media screen and (min-width: 376px)
	{
		#about-container
		{
			padding-top: 70px;
		}
	}
	
	@media screen and (min-width: 700px) {
		#current-events-container,
		#past-events-container
		{
			flex: 1;
		}
	}
	@media screen and (min-width: 700px) and (orientation: landscape)
	{
		
	}
	@media screen and (min-width: 821px) {

	}
	@media screen and (min-width: 1024px) {
		
	}
</style>