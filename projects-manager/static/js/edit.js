// var default_editor_mode = '<?= $default_editor_mode; ?>';
function link(name) {
		var linkURL = prompt('Enter a URL:', 'http://');
		if (linkURL === null || linkURL === "") {
			return;
		}

		document.execCommand('createlink', false, linkURL);
}

// function addListeners(name) {
// 	document.getElementById(name + '-html').addEventListener('click', function(e) {resignImageContainer(name);}, false);
// 	document.getElementById(name + '-bold').addEventListener('click', function(e) {resignImageContainer(name);}, false);
// 	document.getElementById(name + '-italic').addEventListener('click', function(e) {resignImageContainer(name);}, false);
// 	document.getElementById(name + '-link').addEventListener('click', function(e) {resignImageContainer(name);}, false);
// 	document.getElementById(name + '-indent').addEventListener('click', function(e) {resignImageContainer(name);}, false);
// 	document.getElementById(name + '-reset').addEventListener('click', function(e) {resignImageContainer(name);}, false);
// 
function resignImageContainer(name) {
	var imagecontainer = document.getElementById(name + '-imagecontainer');
	if (imagecontainer.style.display === 'block') {
		imagecontainer.style.display = 'none';
	}
}
function image(name) {
	var imagecontainer = document.getElementById(name + '-imagecontainer');
	var imagebox = document.getElementById(name + '-imagebox');
	// toggle image box
	if (imagecontainer.style.display !== 'block') {
		imagecontainer.style.display = 'block';
	} else {
		imagecontainer.style.display = 'none';
	}
}

function showToolBar(name) {
	hideToolBars();
	var tb = document.getElementById(name + '-toolbar');
	tb.style.display = 'block';
}

function hideToolBars() {
	var tbs = document.getElementsByClassName('toolbar');
	Array.prototype.forEach.call(tbs, function(tb) { tb.style.display = 'none'});

	var ics = document.getElementsByClassName('imagecontainer');
	Array.prototype.forEach.call(ics, function(ic) { ic.style.display = 'none'});
}

function commitAll() {
// 	var names = <?
// 		$textnames = [];
// 		foreach($vars as $var) {
// 			if($var_info["input-type"][$var] == "textarea") {
// 				$textnames[] = $var;
// 			}
// 		}
// 		echo '["' . implode('", "', $textnames) . '"]'
// 		?>;

// 	for (var i = 0; i < names.length; i++) {
// 		commit(names[i]);
// 	}
}
function commit(name) {
	var editable = document.getElementById(name + '-editable');
	var textarea = document.getElementById(name + '-textarea');
	if (editable.style.display === 'block') {
		var html = editable.innerHTML;
		textarea.value = html;    // update textarea for form submit
	} else {
		var html = textarea.value;
		editable.innerHTML = html;    // update editable
		textarea.value = editable.innerHTML;
	}
}

function showrich(name) {
	var bold = document.getElementById(name + '-bold');
	var italic = document.getElementById(name + '-italic');
	var link = document.getElementById(name + '-link');
	var indent = document.getElementById(name + '-indent');
	var reset = document.getElementById(name + '-reset');
	var image = document.getElementById(name + '-image');
	var imagecontainer = document.getElementById(name + '-imagecontainer');
	var html = document.getElementById(name + '-html');
	var txt = document.getElementById(name + '-txt');
	var editable = document.getElementById(name + '-editable');
	var textarea = document.getElementById(name + '-textarea');

	textarea.style.display = 'none';
	editable.style.display = 'block';

	html.style.display = 'block';
	txt.style.display = 'none';

	bold.style.visibility = 'visible';
	italic.style.visibility = 'visible';
	indent.style.visibility = 'visible';
	reset.style.visibility = 'visible';
	link.style.visibility = 'visible';
	image.style.visibility = 'visible';

	var html = textarea.value;
	editable.innerHTML = html;    // update editable
}

function sethtml(name, editorMode = 'regular') {
	var bold = document.getElementById(name + '-bold');
	var italic = document.getElementById(name + '-italic');
	var link = document.getElementById(name + '-link');
	var indent = document.getElementById(name + '-indent');
	var reset = document.getElementById(name + '-reset');
	var image = document.getElementById(name + '-image');
	var imagecontainer = document.getElementById(name + '-imagecontainer');
	var html = document.getElementById(name + '-html');
	var txt = document.getElementById(name + '-txt');
	var editable = document.getElementById(name + '-editable');
	var textarea = document.getElementById(name + '-textarea');

	textarea.style.display = 'block';
	editable.style.display = 'none';

	html.style.display = 'none';
	txt.style.display = 'block';

	bold.style.visibility = 'hidden';
	italic.style.visibility = 'hidden';
	indent.style.visibility = 'hidden';
	reset.style.visibility = 'hidden';
	link.style.visibility = 'hidden';
	image.style.visibility = 'hidden';
	imagecontainer.style.display = 'none';

	var html = editable.innerHTML;
	textarea.value = pretty(html);    // update textarea for form submit
	if(editorMode == 'regular')
		window.scrollBy(0, textarea.getBoundingClientRect().top); // scroll to the top of the textarea
}

function resetViews(name, editorMode = 'regular') {

}

// pretifies html (barely) by adding two new lines after a </div>
function pretty(str) {
	while(str.charCodeAt(0) == '9' || str.charCodeAt(0) == '10'){
		str = str.substring(1, str.length);
	}
    // return (str + '').replace(/(?<=<\/div>)(?!\n)/gi, '\n\n');
    return str;
}

function getSelectionText() {
    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        text = document.selection.createRange().text;
    }
    return text;
}

function indent(name){
    document.execCommand('formatBlock',false,'blockquote');
}

function reset(name){
    document.execCommand('formatBlock',false,'div');
    document.execCommand('removeFormat',false,'');
}

function displayMediaToolbar(target)
{
	let thisWrapper = target.parentNode;
	while(!thisWrapper.classList.contains('image-container') && thisWrapper != document.body)
		thisWrapper = thisWrapper.parentNode;
	thisWrapper.classList.toggle('viewing-toolbar');
}
function updateMediaToolbar(media_arr = false)
{
	if(media_arr){
		let sMediaToolbars = document.getElementsByClassName('media-toolbar');
		if(sMediaToolbars.length != 0)
		{
			let html = '';
			for(idx in media_arr)
			{
				html += '<div class="toolbar-image-container"><img onclick="useThisImage(this);" src="'+media_arr[idx].file+'"></div>';
			}
			[].forEach.call(sMediaToolbars, function(el, i){
				el.innerHTML = html;
			});
		}
		let sEmptyMediaToolbarContainers = document.querySelectorAll('.media-toolbar-container.empty');
		if(sEmptyMediaToolbarContainers)
		{
			[].forEach.call(sEmptyMediaToolbarContainers, function(el, i){
				el.classList.remove('empty');
			});
		}
	}
}
function toggleSectionOptions(target)
{
	target.parentNode.parentNode.classList.toggle('viewing-sectionOptions');
}

function useThisImage(target)
{
	let src = target.getAttribute('src');
	let thisWrapper = target.parentNode;
	while(!thisWrapper.classList.contains('image-container') && thisWrapper != document.body)
		thisWrapper = thisWrapper.parentNode;
	let img = thisWrapper.querySelector('.display-image');
	if(img){
		img.src = src;
	}
	else
		console.log('missing .display-image');
	thisWrapper.classList.remove('viewing-toolbar');
	thisWrapper.classList.remove('empty');
}

function reqeustWhatYouSeeFunctions(functionName, media=[], param = '', onComplete){
	let request_url = '/projects-manager/static/php/WhatYouSeeController.php';
	let request = new XMLHttpRequest();
	media = JSON.stringify(media);
	media = media.replace(/"/g, '\\"');
	let postParams = 'function=' + functionName + '&media='+media+'&params=' + param;
	request.onreadystatechange = function() {
        if (this.readyState == 4) {
        	if(this.status == 200)
        	{
        		var response = this.responseText;
            	onComplete(response);
        	}
        }
    };
    request.open("POST", request_url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(postParams);
}

function addSectionHere(target, name, type, media){
	let params = '';
	let extraParams = '';
	var thisWysiwygSection = target.parentNode;
	while( !thisWysiwygSection.classList.contains('wysiwyg-section') && thisWysiwygSection != document.body)
	{
		thisWysiwygSection = thisWysiwygSection.parentNode;
	}

	if(type == 'text' || type == 'p')
	{
		let existingElement = document.querySelector('.wysiwyg-edit-p');
		if(existingElement)
		{	
			toggleSectionOptions(target);
			let parent = existingElement.parentNode.cloneNode(true);
			let temp = parent.querySelector('.wysiwyg-edit-p');
			temp.setAttribute('fieldname', name);
			temp.value = '';
			if(thisWysiwygSection.nextElementSibling)
			{
				thisWysiwygSection.parentNode.insertBefore(thisWysiwygSection.cloneNode(true), thisWysiwygSection.nextElementSibling);
				thisWysiwygSection.parentNode.insertBefore(parent, thisWysiwygSection.nextElementSibling);
				
			}
			else
			{
				thisWysiwygSection.parentNode.appendChild(parent);
				thisWysiwygSection.parentNode.appendChild(thisWysiwygSection.cloneNode(true));
			}
			return;
		}
		extraParams = '{"acceptEmptyContent": true}';
		extraParams = extraParams.replace(/"/g, '\\"');
		params = '{ "type": "text", "var":"' + name + '", "content": "", "params": "'+extraParams+'" }';
		params = params.replace(/"/g, '\\"');
	}
	else if(type == 'image')
	{
		let existingElement = document.querySelector('.wysiwyg-edit-figure');
		if(existingElement)
		{
			toggleSectionOptions(target);
			let parent = existingElement.parentNode.cloneNode(true);
			let temp = parent.querySelector('.wysiwyg-edit-figure');
			temp.setAttribute('fieldname', name);
			temp.querySelector('.wysiwyg-edit-figcaption').value = '';
			temp.querySelector('.display-image').src = 'null';
			temp.querySelector('.image-container').classList.add('empty');
			temp.querySelector('.image-container').classList.add('viewing-toolbar');
			if(thisWysiwygSection.nextElementSibling)
			{
				thisWysiwygSection.parentNode.insertBefore(thisWysiwygSection.cloneNode(true), thisWysiwygSection.nextElementSibling);
				thisWysiwygSection.parentNode.insertBefore(parent, thisWysiwygSection.nextElementSibling);
			}
			else
			{
				thisWysiwygSection.parentNode.appendChild(parent);
				thisWysiwygSection.parentNode.appendChild(thisWysiwygSection.cloneNode(true));
			}
			return;
		}
		extraParams = '{"imageBlockClass": "viewing-toolbar"}';
		extraParams = extraParams.replace(/"/g, '\\"');
		params = '{ "type": "figure", "var":"' + name + '", "content": "null", "params": "'+extraParams+'"}';
		params = params.replace(/"/g, '\\"');
	}

	function postRequest(html){
		let temp = document.createElement('DIV');
		temp.innerHTML = html;
		if(thisWysiwygSection.nextElementSibling)
		{
			while(temp.firstChild)
				thisWysiwygSection.parentNode.insertBefore(temp.firstChild, thisWysiwygSection.nextElementSibling);
		}
		else
			while(temp.firstChild)
				thisWysiwygSection.parentNode.appendChild(temp.firstChild);
		
		toggleSectionOptions(target);
	}
	console.log(params);
	reqeustWhatYouSeeFunctions('renderBlock', media, params, postRequest);
}

function renderPreviewCell(imgs, filenames){
	var container = document.getElementById('preview-container');
	container.innerHTML = '';
	document.querySelector('label[for="uploads"]').innerText = 'Re-select files';
	[].forEach.call(imgs, function(el, i){
		let thisCell = document.createElement('DIV');
		thisCell.className = 'preview-cell';
		thisCell.innerHTML = '<div class="preview-msg"><div class="preview-filename">'+filenames[i]+'</div></div>';
		thisCell.appendChild(el);
		container.appendChild(thisCell);
	});
}
function removePreviewCell(target, idx){
	var input = document.getElementById('uploads');

}
function loadImageToImg(items, i, onComplete) {
    var onLoad = function (e) {
        e.target.removeEventListener("load", onLoad);
        onComplete(img, i);
    }
    // var img = new Image();
    var img = document.createElement('IMG');
    img.className = 'preview-image';
    img.addEventListener("load", onLoad, false);
    img.src = URL.createObjectURL(items[i]);
}

function loader(items, thingToDo, allDone) {
    if (!items) {
        // nothing to do.
        return;
    }

    if ("undefined" === items.length) {
        // convert single item to array.
        items = [items];
    }

    var count = items.length;
    var img_arr = [];
    var imgName_arr = [];

    // this callback counts down the things to do.
    var thingToDoCompleted = function (img, i) {
        count--;
        img_arr[i] = img;
        if (0 == count) {
            allDone(img_arr, imgName_arr);
        }
    };

    for (var i = 0; i < items.length; i++) {
    	let filename = items[i].name + '.' + items[i].type.substring(items[i].type.indexOf('/'));
    	imgName_arr.push(filename)
        thingToDo(items, i, thingToDoCompleted);
    }
}

function callLoader(input){
	let file_arr = input.files;
	loader(file_arr, loadImageToImg, renderPreviewCell);
}

function openMediaContainer(){
	document.body.classList.add('viewing-media-upload');
	document.body.classList.add('viewing-popup-window');
}
function closeMediaContainer(){
	document.body.classList.remove('viewing-media-upload');
	document.body.classList.remove('viewing-popup-window');
	let iframe = document.querySelector('#media-upload-container iframe');
	iframe.src = iframe.src;
}
// function encodeWysiwygFigure(editContainer){
// 	let output = '';
// 	let img = editContainer.querySelector('img.display-image');
// 	let src = img.getAttribute('src');
// 	if(src != 'null')
// 	{
// 		output = '[wysiwygsection wysiwygtag="figure"]';
// 		let caption = editContainer.querySelector('textarea.wysiwyg-edit-figcaption').value;
// 		output += '<img class="wysiwygimg" src="'+src+'">';
// 		if(caption !== '')
// 			output += '<figcaption class="wysiwygfigcaption">'+caption+'</figcaption>';
// 		output += '[/wysiwygsection]';
// 	}
	
// 	return output;
// }

// function encodeWysiwygP(editContainer){
// 	let output = '';
// 	if(editContainer.value !== '');
// 		output = '[wysiwygsection wysiwygtag="p"]' + editContainer.value + '[/wysiwygsection]';
// 	return output;
// }
// function compileWysiwygField(field){
// 	let output = '';
// 	let name = field.getAttribute('name');
// 	let this_editContainers = document.querySelectorAll('.wysiwyg-edit-container[fieldname="'+name+'"]');
// 	[].forEach.call(this_editContainers, function(el){
// 		let thisType = el.getAttribute('type');
// 		if(thisType == 'figure')
// 			output += encodeWysiwygFigure(el);
// 		else if(thisType == 'p')
// 			output += encodeWysiwygP(el);
// 	});

// 	return output;
// }

// function editSubmit(mediaPath){
// 	let sWysiwyg_field = document.getElementsByClassName('wysiwyg-field');
// 	if(sWysiwyg_field.length != 0)
// 	{
// 		[].forEach.call(sWysiwyg_field, function(el){
// 			let updateValue = compileWysiwygField(el);
// 			el.value = updateValue;
// 		});
// 	}
// 	let sImage_field = document.getElementsByClassName('image-field');
// 	if(sImage_field.length != 0)
// 	{
// 		[].forEach.call(sImage_field, function(el){
// 			let name = el.getAttribute('fieldname');
// 			let src = el.querySelector('.display-image').getAttribute('src');
// 			src = src.replace(mediaPath, '/');
// 			document.querySelector('input[name='+name+']').value = src;
// 		});
// 	}
// 	let sCheckbox_field = document.getElementsByClassName('checkbox-field');
// 	let sOrder_select = document.getElementsByClassName('order-select');
// 	if(sOrder_select.length != 0)
// 	{
// 		let temp = [];
// 		[].forEach.call(sOrder_select, function(el){
// 			temp.push('{ "id": "'+el.getAttribute('section')+'", "rank":"' +el.value+'"}');
// 		});
// 		temp = '[' + temp.join(',') + ']';
// 		document.querySelector('input[name="section-order"]').value = temp;
// 	}
	
// 	let form = document.getElementById('edit-form');
// 	form.submit();
// }

function removeWysiwygSection(target)
{
	let section = target.parentNode;
	while(!section.classList.contains('wysiwyg-section') && section != document.body)
		section = section.parentNode;
	if(section.previousElementSibling && section.previousElementSibling != section.parentNode.firstChild && section.previousElementSibling.classList.contains('add-parent'))
		section.parentNode.removeChild(section.previousElementSibling);
	section.parentNode.removeChild(section);
}
function reorder(select){
	let currentValue = select.getAttribute('currentValue');
	let newValue = select.value;
	let container = select.parentNode;
	while(!container.classList.contains('order-container') && container != document.body)
		container = container.parentNode;
	let selects = container.querySelectorAll('select');
	let replaceItem = false;
	let thisItem = false;
	if(newValue > currentValue)
	{
		// -1 for whose value <= newValue && > currentValue
		[].forEach.call(selects,function(el){
			if(el.getAttribute('currentValue') == currentValue && !thisItem){
				thisItem = el;
				while(!thisItem.classList.contains('order-item') && container != document.body)
					thisItem = thisItem.parentNode;
			}
			else if(el.getAttribute('currentValue') != currentValue && el.value > currentValue && el.value <= newValue){
				replaceItem = el;
				el.value = parseInt(el.value) - 1;
				el.setAttribute('currentValue', el.value);
			}
		});
		while(!replaceItem.classList.contains('order-item') && container != document.body)
			replaceItem = replaceItem.parentNode;
		if(replaceItem.nextElementSibling)
			thisItem.parentNode.insertBefore(thisItem, replaceItem.nextElementSibling);
		else
			thisItem.parentNode.appendChild(thisItem);
	}
	else if(newValue < currentValue)
	{	
		// +1 for whose value >= newValue && < currentValue
		[].forEach.call(selects,function(el){
			if(el.getAttribute('currentValue') == currentValue && !thisItem){
				thisItem = el;
				while(!thisItem.classList.contains('order-item') && container != document.body)
					thisItem = thisItem.parentNode;
			}
			else if(el.value < currentValue && el.value >= newValue){
				if(!replaceItem){
					replaceItem = el;
					while(!replaceItem.classList.contains('order-item') && container != document.body)
						replaceItem = replaceItem.parentNode;
				}
				el.value = parseInt(el.value) + 1;
				el.setAttribute('currentValue', el.value);
			}
		});
		thisItem.parentNode.insertBefore(thisItem, replaceItem);
	}
	select.setAttribute('currentValue', newValue);
}


