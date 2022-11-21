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
// }

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

function displayMediaToolbar(target, media_arr = false)
{
	let thisWrapper = target.parentNode;
	while(!thisWrapper.classList.contains('image-container') && thisWrapper != document.body)
		thisWrapper = thisWrapper.parentNode;
	target.parentNode.classList.toggle('viewing-toolbar');
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
	}
}
function toggleSectionOptions(target)
{
	target.parentNode.parentNode.classList.toggle('viewing-sectionOptions');
}

function useThisImage(target)
{
	let src = target.src;
	let wrapper = target.parentNode.parentNode.parentNode;
	let img = wrapper.querySelector('.display-image');
	if(img){
		img.src = src;
	}
	else
		console.log('missing .display-image');
	wrapper.classList.remove('viewing-toolbar');
	
}

function reqeustEditFunctions(functionName, param = '', onComplete){
	let request_url = '/projects-manager/static/php/editFunctions_ajax.php';
	let request = new XMLHttpRequest();
	let postParams = 'function=' + functionName + '&params=' + param;
	request.onreadystatechange = function() {
        if (this.readyState == 4) {
        	if(this.status == 200)
        	{
        		var response = this.responseText;
        		var isAddSection = functionName == 'renderWysiwygAdd';
            	onComplete(response, isAddSection);
        	}
        }
    };
    request.open("POST", request_url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(postParams);
}

function addSectionHere(target, name, type){
	let functionName = '';
	let params = '';
	let postParams = '';
	var thisWysiwygSection = target.parentNode;
	while( !thisWysiwygSection.classList.contains('wysiwyg-section') && thisWysiwygSection != document.body)
	{
		thisWysiwygSection = thisWysiwygSection.parentNode;
	}
    
	if(type == 'text')
	{
		functionName = 'renderWysiwygText';
		params = '{ "var":"' + name + '", "content": "", "acceptEmptyContent": true }';
		
	}
	else if(type == 'image')
	{
		functionName = 'renderWysiwygFigure';
		params = '{ "var":"' + name + '", "content": ""}';
		// postParams = 'function=' + functionName + '&params=' + functionParams;
	}

	var requestedElementHTML = false;
	var addSectionHTML = false;

	function postRequest(html, isAddSection = false){
		if(!isAddSection)
			requestedElementHTML = html;
		else
			addSectionHTML = html;
		if(requestedElementHTML && addSectionHTML)
		{

			let temp = document.createElement('DIV');
			temp.className = 'wysiwyg-section';
			temp.innerHTML = addSectionHTML;
			thisWysiwygSection.innerHTML = requestedElementHTML;
			thisWysiwygSection.parentNode.insertBefore(temp.cloneNode(true), thisWysiwygSection);
			if(thisWysiwygSection.nextSibling)
				thisWysiwygSection.parentNode.insertBefore(temp.cloneNode(true), thisWysiwygSection.nextSibling);
			else
				thisWysiwygSection.parentNode.appendChild(temp.cloneNode(true));
		}
	}


	reqeustEditFunctions(functionName, params, postRequest);
	reqeustEditFunctions('renderWysiwygAdd', '{ "var":"' + name + '" }', postRequest);
}

function renderPreviewCell(imgs, filenames){
	var container = document.getElementById('preview-container');
	container.innerHTML = '';
	document.querySelector('label[for="uploads"]').innerText = 'Re-select files';
	[].forEach.call(imgs, function(el, i){
		let thisCell = document.createElement('DIV');
		thisCell.className = 'preview-cell';
		// thisCell.innerHTML = '<div class="preview-removethisbtn icon-cross" onclick="removePreviewCell(this, '+i+');">&times;</div><div class="preview-msg"><div class="preview-filename">'+filenames[i]+'</div></div>';
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
