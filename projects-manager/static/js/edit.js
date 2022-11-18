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
// 	commitAll();
// 	var names = <?
// 		$textnames = [];
// 		foreach($vars as $var) {
// 			if($var_info["input-type"][$var] == "textarea") {
// 				$textnames[] = $var;
// 			}
// 		}
// 		echo '["' . implode('", "', $textnames) . '"]'
// 		?>;

	
// 	if(editorMode == 'regular')
// 	{
// 		for (var i = 0; i < names.length; i++) {
// 			if (!(name && name === names[i]))
// 				showrich(names[i]);
// 		}
// 	}
// 	else if(editorMode == 'html')
// 	{
// 		for (var i = 0; i < names.length; i++) {
// 			if (!(name && name === names[i]))
// 				sethtml(names[i], default_editor_mode);
// 		}
// 	}
		
	
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

function displayToolbar(target)
{
	target.parentNode.classList.toggle('viewing-toolbar');
}

function toggleSectionOptions(target)
{
	target.parentNode.parentNode.classList.toggle('viewing-sectionOptions');
}

function useThisImage(target)
{
	let src = target.src;
	let wrapper = target.parentNode.parentNode.parentNode;
	let img = wrapper.querySelector('img');
	wrapper.classList.remove('viewing-toolbar');
	img.src = src;
}
function addSectionHere(target, name, type){
	let request_url = '/projects-manager/static/php/editFunctions_ajax.php';
	let request = new XMLHttpRequest();
	let functionName = '';
	let functionParams = '';
	let postParams = '';
	var thisWysiwygSection = target.parentNode;
	while( !thisWysiwygSection.classList.contains('wysiwyg-section') && thisWysiwygSection != document.body)
	{
		thisWysiwygSection = thisWysiwygSection.parentNode;
	}
	request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = this.responseText;
            if(response !== '')
            {
            	thisWysiwygSection.innerHTML = response;
            	// thisWysiwygSection.classList.remove('viewing-sectionOptions');
            }
            
        }
    };
    
	if(type == 'text')
	{
		functionName = 'renderWysiwygText';
		functionParams = '{ "var":"' + name + '", "content": "", "acceptEmptyContent": true }';
		postParams = 'function=' + functionName + '&params=' + functionParams;
	}
	request.open("POST", request_url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(postParams);
}