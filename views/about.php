<?
include_once('static/php/layout.php');
$children = $oo->children($item['id']);
$slideshow_html = '<section id="slideshow-container" class="about-section"><div id="slides-container">';
$bio_html = '<section id="bio-container" class="about-section padding-wrapper"><h3 class="large about-section-title">About</h3><div class="flex-container" breakpoint="700"><div id="bio-container" class="middle flex-item">';
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
		$bio_html .= $child['deck'] . '<div class="more-button"><div class="plus-icon"></div>MORE</div></div><div id="downloads-container" class="flex-item">';

		$media = $oo->media($child['id']);
		foreach($media as $m)
		{
			$bio_html .= '<a class="about-download-btn large" download href="'.m_url($m).'">' . $m['caption'] . '<span class="download-icon"></span></a>';
		}
		$bio_html .= '</div></div></section>';
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
		
		$news_and_events_html .= '</ul><div class="more-button"><div class="plus-icon"></div>MORE & PAST EVENTS</div></section>';
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
	<?= $slideshow_html; ?>
	<?= $bio_html; ?>
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
		padding-bottom:1px;
	}
	#bio-container
	{
		line-height:1.3em;
	}
	.padding-wrapper
	{
		/*margin-top: 10px;*/
	}
	.slide
	{
		height: 40vh;
		object-fit: contain;
	}
	#slides-container
	{
		overflow: hidden;
		position:relative;
		width:100vw;
		height: 40vh;
		padding-bottom: 40px;
		box-sizing: content-box;
	}
	.slide-container
	{
		width: 60vw;
		display: inline-block;
		padding: 0 10px;
		position: absolute;
		left: 0;
		top: 0;

	}
	.slide-container[order="-3"]
	{
		left: -130vw;
		transform: translate(-50%, 0);
	}
	.slide-container[order="-2"]
	{
		left: -70vw;
		transform: translate(-50%, 0);
		transition: left .25s;
	}
	.slide-container[order="-1"]
	{
		left: -10vw;
		transform: translate(-50%, 0);
		transition: left .25s;
	}

	.slide-container[order="0"]
	{
		left: 50vw;
		transform: translate(-50%, 0);
		transition: left .25s;
	}
	.slide-container[order="1"]
	{
		left: 110vw;
		transform: translate(-50%, 0);
		transition: left .25s;
	}
	.slide-container[order="2"]
	{
		left: 170vw;
		transform: translate(-50%, 0);
		transition: left .25s;
	}
	.slide-container[order="3"]
	{
		left: 230vw;
		transform: translate(-50%, 0);
	}
	
	.more-button{
		font-size: 16px;
    	line-height: 16px;
    	margin-top: 1em;
    	
/*    	padding-left: 18px;*/
	}
	.plus-icon
	{
		display: inline-block;
		margin-right: 8px;
		position: relative;
		width: 12px;
		height: 12px;
		vertical-align: top;
	}
	.plus-icon:before
	{
		content: "";
		position: absolute;
		width: 100%;
		height: 3px;
		background-color: #000;
		left: 0;
		top: 7px;
		transform: translate(0, -50%);
	}
	.plus-icon:after
	{
		content: "";
		position: absolute;
		height: 100%;
		width: 3px;
		background-color: #000;
		left: 6px;
		top: 1px;
		/*top: 50%;*/
		transform: translate(-50%, 0);
	}
	#downloads-container
	{
/*		margin-top: -50px;*/
		margin-top: 30px;
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
		line-height: 1.35em;
	}
	#contact-container
	{
		margin-bottom: 1em;
	}
	#slide-control
	{
		padding:20px;
	}
	#slideshow-caption-container
	{
		text-align: center;
		padding: 0px 20px;
		min-height:20px;
	}
	@media screen and (min-width: 376px)
	{
		#about-container
		{
			padding-top: 70px;
		}
	}
	
	@media screen and (min-width: 700px) {
		.flex-container[breakpoint="700"]
		{
			display: flex;
		}
		.flex-container[breakpoint="700"] > .flex-item
		{
			margin-left: 30px;
		}
		.flex-container[breakpoint="700"] > .flex-item:first-child
		{
			margin-left: 0;
		}
		.event-datetime
		{
			flex: 0 0 75px;
		}
		.event-date
		{
			display: block;
			width: auto;
		}
		.more-button
		{
			margin-top: 30px;
		}
		#downloads-container
		{
			margin-top: 0;
			flex: 0 0 120px;
		}
		#news-and-events-container,
		#contact-and-commissions-container
		{
			display: inline-block;
			width: 49.5%;
			vertical-align: top;
		}
		.more-button
		{
			font-size: 20px;
			line-heihgt: 20px;
		}
		.plus-icon
		{
			width: 16px;
			height:16px;
		}
		.plus-icon:before
		{
			content: "";
			position: absolute;
			width: 100%;
			height: 3px;
			background-color: #000;
			left: 0;
			top: 7px;
			transform: translate(0, -50%);
		}
		.plus-icon:after
		{
			content: "";
			position: absolute;
			height: 100%;
			width: 3px;
			background-color: #000;
			left: 8px;
			top: -1px;
			transform: translate(-50%, 0);
		}
	}
	@media screen and (min-width: 700px) and (orientation: landscape)
	{
		.slide-container
		{
			width: 40vw;
		}
		.slide-container[order="-3"]
		{
			left: -70vw;
			transform: translate(-50%, 0);
		}
		.slide-container[order="-2"]
		{
			left: -30vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}
		.slide-container[order="-1"]
		{
			left: 10vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}

		.slide-container[order="0"]
		{
			left: 50vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}
		.slide-container[order="1"]
		{
			left: 90vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}
		.slide-container[order="2"]
		{
			left: 130vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}
		.slide-container[order="3"]
		{
			left: 170vw;
			transform: translate(-50%, 0);
		}
	}
	@media screen and (min-width: 821px) {
		.flex-container[breakpoint="821"]
		{
			display: flex;
		}
		.flex-container[breakpoint="821"] > .flex-item
		{
			margin-left: 30px;
		}
		.flex-container[breakpoint="821"] > .flex-item:first-child
		{
			margin-left: 0;
		}
		#slideshow-caption-container
		{
			padding: 0px 10%;
		}

	}
	@media screen and (min-width: 1024px) {
		#about-container
		{
			padding-top:95px;
		}
		#downloads-container
		{
			flex-basis:20%;
		}
		.flex-container[breakpoint="1024"]
		{
			display: flex;
		}
		.flex-container[breakpoint="1024"] > .flex-item
		{
			margin-left: 30px;
		}
		.flex-container[breakpoint="1024"] > .flex-item:first-child
		{
			margin-left: 0;
		}
		.event-datetime
		{
			flex-basis: 85px;
		}
		.download-icon
		{
			width: 24px;
			height: 24px;
		}
		.download-icon:after
		{
			width: 12px;
			height: 12px;
		}
		
	}
	@media (min-aspect-ratio: 2/1) {
		.slide-container
		{
			width: 30vw;
		}
		.slide-container[order="-3"]
		{
			left: -40vw;
			transform: translate(-50%, 0);
		}
		.slide-container[order="-2"]
		{
			left: -10vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}
		.slide-container[order="-1"]
		{
			left: 20vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}

		.slide-container[order="0"]
		{
			left: 50vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}
		.slide-container[order="1"]
		{
			left: 80vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}
		.slide-container[order="2"]
		{
			left: 110vw;
			transform: translate(-50%, 0);
			transition: left .25s;
		}
		.slide-container[order="3"]
		{
			left: 140vw;
			transform: translate(-50%, 0);
		}
	}
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