<?
include_once('static/php/layout.php');
$children = $oo->children($item['id']);
$slideshow_html = '<section id="slideshow-container" class="about-section"><div id="slides-container">';
$downloads_html = '<section id="downloads-container" class="about-section padding-wrapper">';
$bio_html = '<section id="bio-container" class="about-section padding-wrapper"><h3 class="large about-section-title">About</h3><p class="middle">';
$news_and_events_html = '<section id="news-and-events-container" class="about-section padding-wrapper"><h3 class="large about-section-title">News & Events</h3><ul>';
$contact_and_commissions_html = '<section id="contact-and-commissions-container" class="about-section padding-wrapper"><h3 class="large about-section-title">Contact & Commissions</h3>';
$books_html = '<section id="books-container" class="about-section padding-wrapper"><h3 class="large about-section-title">Books</h3>';
$exhibitions_html = '<section id="books-container" class="about-section padding-wrapper"><h3 class="large about-section-title">Exhibitions</h3>';
foreach($children as $child)
{
	if($child['url'] == 'slideshow')
	{
		$media = $oo->media($child['id']);
		foreach($media as $m)
		{
			$slideshow_html .= '<figure class="slide-container"><img alt="'.$m['caption'].'" class="slide" src="'. m_url($m) .'"><figcaption class="slide-caption caption">'.$m['caption'].'</figcaption></figure>';
		}
		$slideshow_html .= '</div></section>';

	}
	else if($child['url'] == 'downloads')
	{
		$media = $oo->media($child['id']);
		foreach($media as $m)
		{
			$downloads_html .= '<a class="about-download-btn large" download href="'.m_url($m).'">' . $m['caption'] . '<span class="download-icon"></span></a>';
		}
		$downloads_html .= '</section>';
	}
	else if($child['url'] == 'bio')
	{
		$bio_html .= $child['deck'] . '</p><div class="more-button">MORE</div></section>';
	}
	else if($child['url'] == 'news-events')
	{
		$sql = 'SELECT objects.* FROM objects, wires WHERE wires.active = "1" AND objects.active = "1" AND objects.id = wires.toid AND wires.fromid = "'.$child['id'].'" AND objects.begin >= NOW() ORDER BY objects.begin ASC LIMIT 2';
		$res = $db->query($sql);

		$selected_events = array();
		while ($obj = $res->fetch_assoc())
			$selected_events[] = $obj;
		$res->close();
		if(count($selected_events) == 0)
		{
			$sql = 'SELECT objects.* FROM objects, wires WHERE wires.active = "1" AND objects.active = "1" AND objects.id = wires.toid AND wires.fromid = "'.$child['id'].'" ORDER BY objects.begin LIMIT 2';
			$res = $db->query($sql);
			while ($obj = $res->fetch_assoc())
				$selected_events[] = $obj;
			$res->close();
			// $selected_events = array_reverse($selected_events);
		}
		
		foreach($selected_events as $e)
		{
			$date = date('M d', strtotime($e['begin']));
			$time = date('h:m A', strtotime($e['begin']));
			$news_and_events_html .= '<li class="event-info"><div class="event-datetime"><span class="event-date">'.$date . '</span><span class=""event-time>' . $time .'</span></div><div class="event-name-location"><p class="middle event-name">'.$e['name1'].'</p><div class="small event-location">'.$e['notes'].'</div></div></li>';
		}
		
		$news_and_events_html .= '</ul><div class="more-button">MORE & PAST EVENTS</div></section>';
	}
	else if($child['url'] == 'contact-commissions')
	{
		$contact_and_commissions_html .= '<div id="contact-container" class="small"><div class="">CONTACT</div>' . $child['deck'] . '</div><div id="commissions-container" class="small"><div >COMMISSIONS</div>' . $child['body'] . '</div></section>';
	}
	else if($child['url'] == 'books')
	{
		$pattern = '/\[(.*?)\]/';
		preg_match_all($pattern, $child['body'], $matches);
		$books = array();
		if(!empty($matches[1]))
		{
			foreach($matches[1] as $name){
				$slug = slug($name);
				$temp = $oo->urls_to_ids(array('projects', $slug, 'book'));
				if(count($temp) == 3)
				{
					$this_book = $oo->get(end($temp));
					$this_book['fullUrl'] = '/projects/' . $slug . '?section=book';
					$this_book['displayName'] = $name;
					$books[] = $this_book;
				}
			}
		}
		$books_html .= render2to3ColsGrid($books) . '</section>';
	}
	else if($child['url'] == 'exhibitions')
	{
		$pattern = '/\[(.*?)\]/';
		preg_match_all($pattern, $child['body'], $matches);
		$exhibits = array();
		if(!empty($matches[1]))
		{
			foreach($matches[1] as $name){
				$slug = slug($name);
				$temp = $oo->urls_to_ids(array('projects', $slug, 'exhibition'));
				// var_dump($temp);
				if(count($temp) == 3)
				{
					$this_exhibit = $oo->get(end($temp));
					$this_exhibit['fullUrl'] = '/projects/' . $slug . '?section=exhibition';
					$this_exhibit['displayName'] = $name;
					$exhibits[] = $this_exhibit;
				}
			}
		}
		$exhibitions_html .= render2to3ColsGrid($exhibits) . '</section>';
	}

}
?>
<main id="about-container" class="main-container padding-wrapper">
	<?= $slideshow_html; ?>
	<?= $bio_html; ?>
	<?= $downloads_html; ?>
	<?= $news_and_events_html; ?>
	<?= $contact_and_commissions_html; ?>
	<?= $books_html; ?>
	<?= $exhibitions_html; ?>
</main>
<style>
	ul
	{
		margin-left: 0;
	}
	li
	{
		list-style: none;
	}
	.about-section
	{
		margin-bottom: 75px;
		line-height: 1.3em;
	}
	.about-section-title
	{
		margin-bottom: 25px;
	}
	#about-container
	{
		padding-top: 60px;
		padding-left: 0;
		padding-right: 0;
	}
	#slideshow-container
	{
		overflow: scroll;
		width: 100%;
		padding-bottom: 15px;
		text-align: center;
	}
	.padding-wrapper
	{
		/*margin-top: 10px;*/
	}
	.slide
	{
		height: 30vh;
		width: auto;
		/*object-fit: contain;*/
	}
	#slides-container
	{
		white-space: nowrap;
	}
	.slide-container
	{
		display: inline-block;
		padding: 0 10px;
	}
	.more-button{
		font-size: 16px;
    	line-height: 16px;
    	margin-top: 1em;
    	position: relative;
    	padding-left: 18px;
	}
	.more-button:before
	{
		content: "";
		position: absolute;
		width: 12px;
		height: 3px;
		background-color: #000;
		left: 0;
		top: 7px;
		transform: translate(0, -50%);
	}
	.more-button:after
	{
		content: "";
		position: absolute;
		height: 12px;
		width: 3px;
		background-color: #000;
		left: 6px;
		top: 1px;
		/*top: 50%;*/
		transform: translate(-50%, 0);
	}
	#downloads-container
	{
		margin-top: -50px;
	}
	.about-download-btn{
		display: inline-block;
	}
	.download-icon
	{
		display: inline-block;
		position: relative;
		width: 18px;
		height: 18px;
		border-bottom: 3px solid #000;
		margin-left: 5px;
	}
	.download-icon:before,
	.download-icon:after
	{
		content: "";
		position: absolute;
		border-color: inherit;
	}
	.download-icon:before
	{
		border-right: 3px solid;
		left: 50%;
		height: 100%;
		top: -1px;
		transform: translate(-50%, 0);
	}
	.download-icon:after
	{
		width: 8px;
		height: 8px;
		border-left: 3px solid;
		border-bottom: 3px solid;
		transform-origin: left bottom;
		left: 50%;
		bottom: 0;
		transform: rotate(-45deg);
	}
	.event-info + .event-info
	{
		margin-top: 30px;
	}
	.event-datetime
	{
		font-size: 14px;
		line-height: 14px;
		margin-bottom: 10px;
		/*white-space: nowrap;*/
	}
	.event-date
	{
		display: inline-block;
		width: 70px;
	}
	.event-location
	{
		margin-top: 10px;
	}
	.event-name,
	.event-location,
	.event-datetime
	{
		line-height: 1.2em;
	}
	#contact-container,
	#commissions-container
	{
		line-height: 1.2em;
	}
	#contact-container
	{
		margin-bottom: 1em;
	}
</style>