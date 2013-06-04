// main javasccript functions

function resize_iframe(iframename) {
	parent.document.getElementById(iframename).style.height = Math.floor($(iframename+'content').getScrollSize().y)+"px";
};
	
	function loadcont(target, url) {
		var xhr = false;
		
		if (window.XMLHttpRequest) {
			xhr = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			try {
					xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e1) {
				try {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e2) {}
			}
		}
		
		if (xhr) {
			xhr.onreadystatechange = function () {
				if (xhr.readyState == 4) {
					if (xhr.status == 200) {
						var echo = xhr.responseText;
					}
					else {
						var echo = "There was a problem with the request " + xhr.status;
					}
					document.getElementById(target).innerHTML = echo;
				}
			}
			xhr.open("GET", url, true);
			xhr.send(null);
		}
		else {
			document.getElementById(target).innerHTML = "An error occured.";
		}
	}
	
	function gotopage(target, url) {
		
		var height = $(target).getSize().y;
		
		if (height==0) {
			height = 24;	
		}
		
		$(target).set('html', '<div style="padding-top: 2px; height: '+height+'px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'+baseincpat+'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>');
		
		var xhr = false;
		
		if (window.XMLHttpRequest) {
			xhr = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			try {
					xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e1) {
				try {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e2) {}
			}
		}
		
		if (xhr) {
			xhr.onreadystatechange = function () {
				if (xhr.readyState == 4) {
					if (xhr.status == 200) {
						var echo = xhr.responseText;
					}
					else {
						var echo = "There was a problem with the request " + xhr.status;
					}
					$(target).set('html', echo);
				}
			}
			xhr.open("GET", url, true);
			xhr.send(null);
		}
		else {
			$(target).set('html', 'An error occured.');
		}
	}
	
	function goto(url) {
		var xhr = false;
		
		if (window.XMLHttpRequest) {
			xhr = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			try {
					xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e1) {
				try {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e2) {}
			}
		}
		
		if (xhr) {
			xhr.onreadystatechange = function () {
				if (xhr.readyState == 4) {
					if (xhr.status == 200) {
						return true;
					}
				}
			}
			xhr.open("GET", url, true);
			xhr.send(null);
		}
		return false;
	}
	
	function trim(str, charlist) {
	 
		var whitespace, l = 0;
		
		if (!charlist) {
			whitespace = ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
		} else {
			whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
		}
		
		l = str.length;
		for (var i = 0; i < l; i++) {
			if (whitespace.indexOf(str.charAt(i)) === -1) {
				str = str.substring(i);
				break;
			}
		}
		
		l = str.length;
		for (i = l - 1; i >= 0; i--) {
			if (whitespace.indexOf(str.charAt(i)) === -1) {
				str = str.substring(0, i + 1);
				break;
			}
		}
		
		return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
	}
	
	function testtextlength(elem, maxlen, errorelm) {
		var curlen = $(elem).value.length ;
		if (curlen > maxlen) {
			var currdiff = curlen-maxlen;
			$(errorelm).set('styles', {'display': 'block'});	
			if (currdiff==1) {
				$(errorelm).set('html', 'You are 1 character over this form\'s limit! (Characters over this form\'s limit will not be saved.)');	
			} else {
				$(errorelm).set('html', 'You are '+currdiff+' characters over this form\'s limit! (Characters over this form\'s limit will not be saved.)');		
			}
		} else if (curlen == maxlen) {
			$(errorelm).set('styles', {'display': 'block'});	
			$(errorelm).set('html', 'You have reached this form\'s character limit!');
		} else if (curlen > (maxlen-50)) {
			var currdiff = maxlen-curlen;
			$(errorelm).set('styles', {'display': 'block'});	
			if (currdiff==1) {
				$(errorelm).set('html', 'This form can only hold 1 more character!');
			} else {
				$(errorelm).set('html', 'This form can only hold '+currdiff+' more characters!');
			}
		} else {
			$(errorelm).set('styles', {'display': 'none'});	
			$(errorelm).set('html', '');
		}
		//for testing, to be deleted in final !important
		//$(errorelm).set('styles', {'display': 'block'});	
		//$(errorelm).set('html', 'test! '+maxlen);
	}