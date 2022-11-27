function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function expireCookie(name) {
	if (getCookie(name) != "")
	{
		document.cookie = name+"=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
		return true;
	} 
	else
		return false;
}

function getCookie(name) {
	var cname = name + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++)
	{
		var c = ca[i];
		while (c.charAt(0)==' ')
			c = c.substring(1);
		if (c.indexOf(cname) != -1) 
			return c.substring(cname.length,c.length);
	}
	return "";
}

function checkCookie(name) {
	if (getCookie(name) != "")
		return true;
	else
		return false;
}

function centerHashlinkTargets(targets, topDev=0, bottomDev=0){
	
	let wH = window.innerHeight;
	let viewportH = wH - topDev - bottomDev;
	[].forEach.call(targets, function(el, i){
		let padding = 'calc(' + viewportH / 2 + 'px - ' + 50 * el.getAttribute('ratio') + '% + '+topDev+'px)';
		let margin = 'calc(' + 50 * el.getAttribute('ratio') + '% - ' + viewportH / 2 + 'px - '+topDev+'px)';
		el.style.paddingTop = padding;
		el.style.marginTop = margin;
		console.log(el);
	});
}

function centerHashlinkTarget(target, topDev=0, bottomDev=0){
	let wH = window.innerHeight;
	let viewportH = wH - topDev - bottomDev;
	// let padding = 'calc(' + viewportH / 2 + 'px - ' + 50 * el.getAttribute('ratio') + '% + '+topDev+'px)';
	let padding = (viewportH - target.offsetHeight) / 2 + 'px';
	// let margin = 'calc(' + 50 * el.getAttribute('ratio') + '% - ' + viewportH / 2 + 'px - '+topDev+'px)';
	let margin = (target.offsetHeight - viewportH) / 2 + 'px';
	target.parentNode.style.paddingTop = padding;
	target.parentNode.style.marginTop = margin;
}
