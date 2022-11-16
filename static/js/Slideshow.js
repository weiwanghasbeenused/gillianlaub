class Slideshow{
	constructor(imgData, container){
		this.imgData = imgData;
		this.container = container;
		this.slideContainers = this.container.querySelectorAll('.slide-container');
		this.captionContainer = this.container.querySelector('#slideshow-caption-container');
		this.currentIndex = 0;
		this.currentLoadingIndex = 0;
		this.transitionDuration = 250;
		
	}
	next(){
		// this.container.classList.add('transition')
		this.currentIndex++;
		if(this.currentIndex > this.imgData.length - 1)
			this.currentIndex = 0;
		if(this.currentIndex < this.imgData.length - 3)
			this.currentLoadingIndex = this.currentIndex + 3;
		else
			this.currentLoadingIndex = (this.currentIndex + 3) % this.imgData.length;
		this.updateCaption(this.imgData[this.currentIndex].caption);
		[].forEach.call(this.slideContainers, function(el, i){
			let newOrder = parseInt(el.getAttribute('order'));
			if(newOrder == -3){
				el.querySelector('.slide').src = this.imgData[this.currentLoadingIndex].src;
				newOrder = 3;
			}
			else
				newOrder = newOrder - 1;
			el.setAttribute('order', newOrder);
		}.bind(this));
	}
	prev(){
		this.currentIndex--;
		if(this.currentIndex < 0)
			this.currentIndex = this.imgData.length - 1;
		if(this.currentIndex > 2)
			this.currentLoadingIndex = this.currentIndex - 3;
		else
			this.currentLoadingIndex = this.imgData.length + this.currentIndex - 3;
		this.updateCaption(this.imgData[this.currentIndex].caption);
		[].forEach.call(this.slideContainers, function(el, i){
			let newOrder = parseInt(el.getAttribute('order'));
			if(newOrder == 3){
				el.querySelector('.slide').src = this.imgData[this.currentLoadingIndex].src;
				newOrder = -3;
			}
			else
				newOrder = newOrder + 1;
			el.setAttribute('order', newOrder);
		}.bind(this));
	}
	updateCaption(str){
		this.captionContainer.innerHTML = str;
	}
}