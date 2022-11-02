<style>
	#lightbox-container
	{
		position: fixed;
		top: 0;
		left: 0;
		width: 100vw;
		max-width: 100%;
		height: 100vh;
		background-color: #fff;
		pointer-events: none;
		opacity: 0;
	}
	body.viewing-lightbox #lightbox-container
	{
		opacity: 1;
		pointer-events: auto;
		transition: opacity .5s;
	}
	body.viewing-lightbox
	{
		height: 100vh;
		overflow: hidden;
	}
</style>
<div id="lightbox-container">
	<h1 id="project-name"><?= $item['name1'];?></h1>
	<figure id="lightbox-prev-image" class="lightbox-image">
		<img src="">
		<figcaption></figcaption>
	</figure>
	<figure id="lightbox-current-image" class="lightbox-image">
		<img src="">
		<figcaption></figcaption>
	</figure>
	<figure id="lightbox-next-image" class="lightbox-image">
		<img src="">
		<figcaption></figcaption>
	</figure>
	<div id="lightbox-next-button"></div>
	<div id="lightbox-prev-button"></div>
	<div id="lightbox-close-button"></div>
</div>
<script>
	var sLightbox_btn = document.getElementsByClassName('lightbox-btn');
	[].forEach.call(sLightbox_btn, function(el, i){
		el.addEventListener('click', function(){
			console.log(el.parentNode);
			init(el.parentNode);
		});
	});
	function init(figure){
		body.classList.add('viewing-lightbox');
		let this_src = figure.querySelector('IMG').src;
		let this_caption = figure.querySelector('figcaption').innerHTML;
		document.querySelector('#lightbox-current-image img').src = this_src;
		document.querySelector('#lightbox-current-image figcaption').innerHTML = this_caption;
	}
</script>