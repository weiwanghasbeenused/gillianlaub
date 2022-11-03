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
		display: flex;
		flex-direction: column;
		padding: 10px 0 20px 0;

	}
	body.viewing-lightbox #lightbox-container
	{
		opacity: 1;
		pointer-events: auto;
		transition: opacity .5s;
		z-index: 1005;
	}
	body.viewing-lightbox
	{
		height: 100vh;
		overflow: hidden;
	}

	.project-name.in-lightbox
	{
		text-align: center;
		padding-bottom: 10px;
	}

	#lightbox-prev-image,
	#lightbox-next-image
	{
		display: none;
	}
	.lightbox-image
	{
		/*height: 100%;*/
		/*flex-basis: auto;*/
		/*flex-grow: 1;*/
		flex: 1;
		
		display: flex;
		flex-direction: column;
	}
	.lightbox-image-wrapper
	{
		flex: 1;
		position: relative;
	}
	.lightbox-image img
	{
		/*height: 100%;*/
		display: block;
		object-fit: contain;
		/*flex: 1;*/
		position: absolute;
		width: 100%;
		height: 100%;
	}
	#lightbox-control-container
	{
		position: relative;
		padding: 0 10px;
	}
	#lightbox-close-button
	{
		position: absolute;
		top: 10px;
		right: 8px;
		width: 27px;
		height: 27px;
		cursor: pointer;
	}
	#lightbox-close-button:before,
	#lightbox-close-button:after
	{
		content: "";
		position: absolute;
		border-top: 3px solid #000;
		width: 100%;
		top: 50%;
		left: 50%;
		
	}
	#lightbox-close-button:before
	{
		transform: translate(-50%, -50%) rotate(45deg);
	}
	#lightbox-close-button:after
	{
		transform: translate(-50%, -50%) rotate(-45deg);
	}
	#lightbox-next-button,
	#lightbox-prev-button
	{
		width: 24px;
		height: 24px;
		position: relative;
		cursor: pointer;
	}
	#lightbox-next-button
	{
		float: right;
	}
	#lightbox-prev-button
	{
		float: left;
	}
	#lightbox-next-button:after,
	#lightbox-prev-button:after
	{
		content: "";
		display: block;
		position: absolute;
		border-right: 3px solid #000;
		border-bottom: 3px solid #000;
		width: 70%;
		height: 70%;
		top: 50%;
		left: 50%;		
	}
	#lightbox-next-button:before,
	#lightbox-prev-button:before
	{
		content: "";
		display: block;
		position: absolute;
		border-top: 3px solid #000;
		width: 100%;
		/*left: 50%;*/
		top: 50%;
		transform: translate(-50%, -50%);
	}
	#lightbox-next-button:after
	{
		transform: translate(-50%, -50%) rotate(-45deg);
	}
	#lightbox-prev-button:after
	{
		transform: translate(-50%, -50%) rotate(135deg);
	}
	#lightbox-next-button:before
	{
		left: 50%;
	}
	#lightbox-prev-button:before
	{
		left: 50%;
	}
	.lightbox-caption
	{
		text-align: center;
		padding: 20px 0;
	}
	@media screen and (min-width: 821px){
		.lightbox-caption
		{
			padding-left: 10%;
			padding-right: 10%;
			/*max-width: 80%;*/
		}
	}
	@media screen and (min-width: 1024px){
		.lightbox-caption
		{
			padding-left: 15%;
			padding-right: 15%;
		}
	}
</style>
<div id="lightbox-container">
	<h1 class="project-name large in-lightbox"><?= $item['name1'];?></h1>
	<!-- <div id="lightbox-image-container"> -->
		<div id="lightbox-prev-image" class="lightbox-image">
			<div class="lightbox-image-wrapper">
				<img src="">
			</div>
			<p class="lightbox-caption caption"></p>
		</div>
		<div id="lightbox-current-image" class="lightbox-image">
			<div class="lightbox-image-wrapper">
				<img src="">
			</div>
			<p class="lightbox-caption caption"> </p>
		</div>
		<div id="lightbox-next-image" class="lightbox-image">
			<div class="lightbox-image-wrapper">
				<img src="">
			</div>
			<p class="lightbox-caption caption"></p>
		</div>
	<!-- </div> -->
	<div id="lightbox-control-container" class="float-container">
		<div id="lightbox-next-button"></div>
		<div id="lightbox-prev-button"></div>
	</div>
	<div id="lightbox-close-button" onclick="exit()"></div>
</div>
<script src="/static/js/Lightbox.js"></script>
<script>
	var slightbox_container = document.getElementById('lightbox-container');
	var sLightbox_btn = document.getElementsByClassName('lightbox-btn');
	// var sLightbox_current_box = document.getElementById('lightbox-current-image');
	// var sLightbox_prev_box = document.getElementById('lightbox-prev-image');
	// var sLightbox_next_box = document.getElementById('lightbox-next-image');
	var sLightbox_prev_button = document.getElementById('lightbox-prev-button');
	var sLightbox_next_button = document.getElementById('lightbox-next-button');

	var sLightbox_close_button = document.getElementById('lightbox-close-button');
	var lightbox = new Lightbox(sLightbox_btn, slightbox_container);
	
	[].forEach.call(sLightbox_btn, function(el, i){
		el.addEventListener('click', function(){
			lightbox.init(i);
		});
	});
	sLightbox_close_button.addEventListener('click', function(){
		lightbox.exit();
	});
	sLightbox_prev_button.addEventListener('click', function(){
		lightbox.prev();
	});
	sLightbox_next_button.addEventListener('click', function(){
		lightbox.next();
	});
	
	window.addEventListener('resize', function(){
		console.log('resize');
		console.log(window.innerHeight);
		slightbox_container.height = window.innerHeight + 'px';
	});
</script>