<?
include_once('static/php/layout.php');
$children = $oo->children($item['id']);
$slideshow_html = '<section id="slideshow-container" class="about-section"><div id="slides-container">';
$bio_html = '<section id="bio-container" class="about-section padding-wrapper"><h3 class="large about-section-title">About</h3><div class="flex-container" breakpoint="700"><div id="" class="bio-content middle flex-item">';
$bio_mobile_html = '<section id="bio-mobile-container" class="about-section padding-wrapper"><h3 class="large about-section-title">About</h3><div class="flex-container" breakpoint="700"><div id="" class="bio-content middle flex-item">';
$news_and_events_html = '<section id="news-and-events-container" class="about-section padding-wrapper"><h3 class="large about-section-title">News & Events</h3><ul>';
$contact_and_commissions_html = '<section id="contact-and-commissions-container" class="about-section padding-wrapper"><h3 class="large about-section-title">Contact & Commissions</h3>';
$books_html = '<section id="books-container" class="about-section padding-wrapper"><h3 class="large about-section-title">Books</h3>';
$exhibitions_html = '<section id="exhibitions-container" class="about-section padding-wrapper"><h3 class="large about-section-title">Exhibitions</h3>';

$slideshow_srcs = array();
foreach($children as $child)
{
	if($child['url'] == 'slideshow')
	{
		$media = $oo->media($child['id']);
		$slideshow_html .= '<div order="-3" class="slide-container"><img alt="'.$media[count($media) - 3]['caption'].'" class="slide" src="'. m_url($media[count($media) - 3]) .'"></div>';
		$slideshow_html .= '<div order="-2" class="slide-container"><img alt="'.$media[count($media) - 2]['caption'].'" class="slide" src="'. m_url($media[count($media) - 2]) .'"></div>';
		$slideshow_html .= '<div order="-1" class="slide-container left"><img alt="'.end($media)['caption'].'" class="slide" src="'. m_url(end($media)) .'"></div>';
		$slideshow_html .= '<div order="0" class="slide-container"><img alt="'.$media[0]['caption'].'" class="slide" src="'. m_url($media[0]) .'"></div>';
		$slideshow_html .= '<div order="1" class="slide-container right"><img alt="'.$media[1]['caption'].'" class="slide" src="'. m_url($media[1]) .'"></div>';
		$slideshow_html .= '<div order="2" class="slide-container right"><img alt="'.$media[2]['caption'].'" class="slide" src="'. m_url($media[2]) .'"></div>';
		$slideshow_html .= '<div order="3" class="slide-container right"><img alt="'.$media[3]['caption'].'" class="slide" src="'. m_url($media[3]) .'"></div>';
		
		$slideshow_html .= '</div><p id="slideshow-caption-container" class="caption">'.$media[0]['caption'].'</p><div id="slide-control" class="float-container"><div id="slideshow-prev-button" class="left-arrow-icon"></div><div id="slideshow-next-button" class="right-arrow-icon"></div></div>';

		foreach($media as $m)
		{
			$slideshow_srcs[] = array(
				'src' => m_url($m),
				'caption' => $m['caption']
			);
		}
		
		$slideshow_html .= '</section>';

	}
	else if($child['url'] == 'bio')
	{
		$bio_html .= $child['deck'] . '<div class="more-button"><div class="plus-icon"></div>MORE</div></div><div class="downloads-container flex-item">';
		$bio_mobile_html .= $child['deck'] . '<div class="more-button"><div class="plus-icon"></div>MORE</div></div><div class="downloads-container flex-item">';

		$media = $oo->media($child['id']);
		foreach($media as $m)
		{
			$bio_html .= '<a class="about-download-btn large" download href="'.m_url($m).'">' . $m['caption'] . '<span class="download-icon"></span></a>';
			$bio_mobile_html .= '<a class="about-download-btn large" download href="'.m_url($m).'">' . $m['caption'] . '<span class="download-icon"></span></a>';
		}
		$bio_html .= '</div></div></section>';
		$bio_mobile_html .= '</div></div></section>';
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
			$news_and_events_html .= '<li class="event-info flex-container" breakpoint="1024"><div class="event-datetime flex-item"><span class="event-date">'.$date . '</span><span class=""event-time>' . $time .'</span></div><div class="event-name-location flex-item"><p class="middle event-name">'.$e['name1'].'</p><div class="small event-location">'.$e['notes'].'</div></div></li>';
		}
		
		$news_and_events_html .= '</ul><a href="/about/'.$child['url'].'" class="more-button"><div class="plus-icon"></div>MORE & PAST EVENTS</a></section>';
	}
	else if($child['url'] == 'contact-commissions')
	{
		$contact_and_commissions_html .= '<div class="flex-container" breakpoint="1024"><div id="contact-container" class="small flex-item"><div class="">CONTACT</div>' . $child['deck'] . '</div><div id="commissions-container" class="small flex-item"><div >COMMISSIONS</div>' . $child['body'] . '</div></div></section>';
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
	<?= $bio_mobile_html; ?>
	<?= $slideshow_html; ?>
	<?= $bio_html; ?>
	<?= $news_and_events_html; ?>
	<?= $contact_and_commissions_html; ?>
	<?= $books_html; ?>
	<?= $exhibitions_html; ?>
</main>
<style>

</style>
<script src="/static/js/Slideshow.js"></script>
<script>
	var slideshow_srcs = <?= json_encode($slideshow_srcs, true); ?>;
	var sSlideshow_container = document.getElementById('slideshow-container');
	var sSlideshow_prev_button = document.getElementById('slideshow-prev-button');
	var sSlideshow_next_button = document.getElementById('slideshow-next-button');
	var slideshow = new Slideshow(slideshow_srcs, sSlideshow_container);

	sSlideshow_prev_button.addEventListener('click', function(){
		slideshow.prev();
	});
	sSlideshow_next_button.addEventListener('click', function(){
		slideshow.next();
	});
	
	window.addEventListener('resize', function(){
		// console.log('resizeee');
		// console.log(window.innerHeight);
		// slightbox_container.style.height = window.innerHeight + 'px';
	});
</script>