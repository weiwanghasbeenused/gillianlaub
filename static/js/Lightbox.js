class Lightbox{
	constructor(btns, container){
		this.btns = btns;
		this.container = container;
		this.currentBox = this.container.querySelector('#lightbox-current-image');
		this.prevBox = this.container.querySelector('#lightbox-prev-image');
		this.nextBox = this.container.querySelector('#lightbox-next-image');
		// this.prevBtn = this.container.querySelector('#lightbox-prev-button');
		// this.nextBtn = this.container.querySelector('#lightbox-next-button');
		this.currentIndex = 0;
	}
	init(i){
		this.currentIndex = i;
		this.loadImg('current');
		this.loadImg('next');
		this.loadImg('prev');
		document.body.classList.add('viewing-lightbox');
	}
	next(){
		this.currentIndex++;
		if(this.currentIndex > this.btns.length - 1)
			this.currentIndex = 0;
		this.loadImg('current');
		this.loadImg('next');
		this.loadImg('prev');
	}
	prev(){
		this.currentIndex--;
		if(this.currentIndex < 0)
			this.currentIndex = this.btns.length - 1;
		this.loadImg('current');
		this.loadImg('next');
		this.loadImg('prev');
	}
	loadImg(boxName){
		var thisBox, thisIdx;
		if(boxName == 'prev'){
			thisBox = this.prevBox;
			thisIdx = this.currentIndex-1;
			if(thisIdx < 0)
				thisIdx = this.btns.length - 1;

		}
		else if(boxName == 'next'){
			thisBox = this.nextBox;
			thisIdx = this.currentIndex+1;
			if(thisIdx > this.btns.length - 1)
				thisIdx = 0;
		}
		else if(boxName == 'current'){
			thisBox = this.currentBox;
			thisIdx = this.currentIndex;
		}
		console.log(boxName, thisIdx);
		console.log(this.btns[thisIdx]);
		this.src = this.btns[thisIdx].src;
		this.caption = this.btns[thisIdx].getAttribute('alt');
		thisBox.querySelector('img').src = this.btns[thisIdx].src;
		thisBox.querySelector('.lightbox-caption').innerHTML = this.btns[thisIdx].getAttribute('alt');
	}
	exit(){
		document.body.classList.remove('viewing-lightbox');
	}
}