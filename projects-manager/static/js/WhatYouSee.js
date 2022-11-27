class WhatYouSee{
	constructor(form, container=false, mediaPath)
	{
		this.mediaPath = mediaPath;
		this.form = form;
		this.patterns = {
			'figure': {
				'opening': '[wysiwygsection wysiwygtag="figure"]',
				'closing': '[/wysiwygsection]'
			}
		};
		if(!container)
			this.container = form;
		else
			this.container = container;
	}
	openingTag(type){
		return '[wysiwygsection wysiwygtag="'+type+'"]';
	}
	closingTag(){
		return '[/wysiwygsection]';
	}
	getRelativeSrc(img){
		return img.getAttribute('src');
	}
	compileFigure(editContainer){
		let output = '';
		let img = editContainer.querySelector('img.display-image');
		let src = this.getRelativeSrc(img);
		if(src != 'null')
		{
			let caption = editContainer.querySelector('textarea.wysiwyg-edit-figcaption').value;
			output = this.openingTag('figure') + '<img class="wysiwygimg" src="'+src+'">';
			if(caption !== '')
				output += '<figcaption class="wysiwygfigcaption">'+caption+'</figcaption>';
			output += this.closingTag();
		}
		return output;
	}
	compileP(editContainer){
		let output = '';
		if(editContainer.value !== '');
			output = this.openingTag('p') + editContainer.value + this.closingTag();
		return output;
	}
	compile(field){
		let output = '';
		let name = field.getAttribute('name');
		let this_editContainers = field.parentNode.querySelectorAll('.wysiwyg-edit-container[fieldname="'+name+'"]');
		[].forEach.call(this_editContainers, function(el){
			let thisType = el.getAttribute('type');
			console.log(el);
			if(thisType == 'figure'){
				console.log('ff');
				output += this.compileFigure(el);
			}
			else if(thisType == 'p')
				output += this.compileP(el);
		}.bind(this));
		return output;
	}
	submit(){
		let sWysiwyg_field = this.container.querySelectorAll('.wysiwyg-field');
		if(sWysiwyg_field.length != 0)
		{
			[].forEach.call(sWysiwyg_field, function(el){
				let updateValue = this.compile(el);
				el.value = updateValue;
			}.bind(this));
		}
		let sImage_field = this.container.querySelectorAll('.image-field');
		if(sImage_field.length != 0)
		{
			[].forEach.call(sImage_field, function(el){
				let name = el.getAttribute('fieldname');
				let img = el.querySelector('.display-image');
				let src = this.getRelativeSrc(img);
				this.container.querySelector('input[name='+name+']').value = src;
			}.bind(this));
		}
		let sCheckbox_field = this.container.querySelectorAll('.checkbox-field');
		let sOrder_select = this.container.querySelectorAll('.order-select');
		if(sOrder_select.length != 0)
		{
			let temp = [];
			[].forEach.call(sOrder_select, function(el){
				temp.push('{ "id": "'+el.getAttribute('section')+'", "rank":"' +el.value+'"}');
			});
			temp = '[' + temp.join(',') + ']';
			this.container.querySelector('input[name="section-order"]').value = temp;
		}
		this.form.submit();
	}
}