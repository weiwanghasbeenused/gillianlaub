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
		padding: 20px 10px;

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
		padding-right: 30px;
		padding-bottom: 10px;
	}

	#lightbox-prev-image,
	#lightbox-next-image
	{
		display: none;
	}
	.lightbox-image
	{
		flex: 1;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}
	.lightbox-image-wrapper
	{
		/*flex: 1;*/
		/*position: relative;*/
	}
	.lightbox-image img
	{
		display: block;
		object-fit: contain;
		/*position: absolute;*/
		width: 100%;
		height: 100%;
	}
	#lightbox-control-container
	{
		position: relative;
		padding: 0 2px;
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
		padding: 10px 0;
	}
	@media screen and (min-width: 376px){
		#lightbox-container
		{
			padding-top: 40px;
			padding-left: 20px;
			padding-right: 20px;
		}
		.project-name.in-lightbox
		{
			padding-right: 30px;
			padding-bottom: 40px;
		}
		.lightbox-image-wrapper
		{
			position: relative;
			flex: 1;
		}
		.lightbox-image img
		{
			position: absolute;
		}
	}
	@media screen and (min-width: 769px){
		.project-name.in-lightbox
		{
			padding-right: 10vw;
			padding-left: 10vw;
			text-align: center;
		}
	}

	
	@media screen and (min-width: 821px){
		.lightbox-caption
		{
			padding-left: 10%;
			padding-right: 10%;
			/*max-width: 80%;*/
		}

	}
	@media screen and (min-width: 821px) and (orientation: landscape){
		#lightbox-container[section="exhibition"] #lightbox-current-image .lightbox-image-wrapper,
		#lightbox-container[section="book"] #lightbox-current-image .lightbox-image-wrapper{
			position: absolute;
			width: 100vw;
			max-width: 100%;
			height: 100vh;
			top: 0;
			left: 0;
		}
		#lightbox-container[section="exhibition"] #lightbox-current-image .lightbox-image-wrapper img,
		#lightbox-container[section="book"] #lightbox-current-image .lightbox-image-wrapper img{
			object-fit: cover;
		}

	}
	#lightbox-container [section="exhibition"]
	@media screen and (min-width: 1024px){
		.lightbox-caption
		{
			padding-left: 15%;
			padding-right: 15%;
		}
		#lightbox-next-button,
		#lightbox-prev-button
		{
			width: 36px;
			height: 36px;
		}
	}
</style>
<div id="lightbox-container" section="<?= $section; ?>">
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
	<div id="lightbox-close-button" class="cross-icon"></div>
</div>
<script src="/static/js/Lightbox.js"></script>
<script>
	var slightbox_container = document.getElementById('lightbox-container');
	var sLightbox_btn = document.getElementsByClassName('lightbox-btn');
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
		// console.log('resizeee');
		// console.log(window.innerHeight);
		slightbox_container.style.height = window.innerHeight + 'px';
	});
</script>