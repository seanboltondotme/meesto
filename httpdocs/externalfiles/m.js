// main javasccript functions

var backcontrol = {
	initialize: function(url, hasclass, classname) {
		this.state = '';
		this.url = url;
		this.hasclass = hasclass;
		this.classname = classname;
		if (this.getState()=='') {
			this.state = '';
		}
		this.observe();
	},
	loadcontent: function(state) {
		gotopage('maincontent', this.url+state);
	},
	observe: function() {
		if (this.timeout) {
			this.observe.delay(200, this);
			return
		}
		var state = this.getState();
		if (this.state != state) {
			this.setState(state);
		} else {
			this.observe.delay(200, this);
			return
		}
	},
	observeTimeout: function() {
		if (this.timeout) this.timeout = $clear(this.timeout);
		else this.timeout = this.observeTimeout.delay(200, this);
	},
	getHash: function() {
		var href = top.location.href;
		var pos = href.indexOf('#') + 1;
		return (pos) ? href.substr(pos) : '';
	},
	getState: function() {
		var state = this.getHash();
		if (this.iframe) {
			var doc = this.iframe.contentWindow.document;
			if (doc && doc.body.id == 'state') {
				var istate = doc.body.innerText;
				if (this.state == state) return istate;
				this.istateOld = true;
			} else return this.istate;
		}
		if (window.webkit419 && history.length != this.count) {
			this.count = history.length;
			return $pick(this.states[this.count - 1], state);
		}
		return state;
	},
	setState: function(state) {
		state = $pick(state, '');
		if (this.hasclass) {
			if (state.indexOf('&') >= 0) {
				var pos = state.indexOf('&');
				var classid = state.substr(0, pos);	
			} else {
				var classid = state;	
			}
				if (classid=='') {
					$$('#filterlist div.'+this.classname+'On').set('class', this.classname);
					$('fltrelm-0').set('class', this.classname+'On');
				} else {
					$$('#filterlist div.'+this.classname+'On').set('class', this.classname);
					$('fltrelm-'+classid).set('class', this.classname+'On');
				}
		}
		if (window.webkit419) {
			if (!this.form) this.form = new Element('form', {method: 'get'}).injectInside(document.body);
			this.observeTimeout();
			this.form.setProperty('action', '#' + state).submit();
		} else { if (state!='') {top.location.hash = state || '#';}};
		if (window.ie && (this.istateOld)) {
			if (!this.iframe) {
				this.iframe = new Element('iframe', {
					src: this.options.iframeSrc,
					styles: 'visibility: hidden;'
				}).injectInside(document.body);
				this.istate = this.state;
			}
			try {
				var doc = this.iframe.contentWindow.document;
				doc.open();
				doc.write('<html><body id="state">' + state + '</body></html>');
				doc.close();
				this.istateOld = false;
			} catch(e) {};
		}
		this.state = state;
		this.loadcontent(state);
		this.observe();
	}
}

	//multiple pages
	
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
		
		$(target).set('html', '<div style="padding-top: 2px; height: '+height+'px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="http://www.meesto.com/images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>');
		
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
	
var headerSearch = {
	
	loadValues: function(){
		new Request.JSON({  
			url: "http://www.meesto.com/externalfiles/autocompleter/grabmypeeple-header.php", 
			onRequest: function() {
				$('msrch').set('styles',{'background': '#fff url(\'http://www.meesto.com/images/spinner.gif\') no-repeat right center'});
			},
			onSuccess: function(response){
				headerSearch.setValues(response);
				$('msrch').set('styles',{'background': '#fff'});
			}
		}).send();
	},
	
	setValues: function(values){
		this.values = values;
	},
	
	filter: function(search){
		this.activepsid = 0;
		$('msrch').set('styles',{'background': '#fff url(\'http://www.meesto.com/images/spinner.gif\') no-repeat right center'});
		if (!$('msrch_resultscont')) {
			var newElem = new Element('div', {'id': 'msrch_resultscont', 'align': 'left', 'styles': {'background': '#fff', 'border': '2px solid #C5C5C5', 'padding': '4px', 'z-index': '100000'}});
			newElem.inject($('msrch_inputcont'), 'after');
		} else {
			$('msrch_resultscont').getChildren().destroy();	
		}
		if (search.length==0) {
			$('msrch_resultscont').destroy();
		} else {
			var matched = 0;
			var values = this.values, regexp = new RegExp('\\b' + search.escapeRegExp(), true ? 'i' : '');
			if (values!=null) {
				for (var i = 0; i < values.length; i++){
					var peepid = values[i][0];
					if (values[i][1].test(regexp)) { //if matched
						if (matched<10) {
							var newHsPs = new Element('div', {'id': 'peephsps'+peepid, 'align': 'left', 'class': 'blockbtn-hs', 'html': values[i][2], 'onclick': 'window.location.href=\'http://www.meesto.com/meefile.php?id='+peepid+'\''});
							newHsPs.inject($('msrch_resultscont'), 'bottom');
						}
						matched++;
					}
				}
			}
			if (matched==0) {
				if (!$('hsps_srchstatelm')) {
					var goToSearchPg = new Element('div', {'id': 'peephspsSrch', 'align': 'left', 'class': 'blockbtn-hs', 'html': 'No matches were found,<br/>search all of Meesto.', 'onclick': 'window.location.href=\'http://www.meesto.com/search.php?q='+encodeURIComponent($('msrch').value)+'\''});
					goToSearchPg.inject($('msrch_resultscont'), 'bottom');
				}
			} else {
				var goToSearchPg = new Element('div', {'id': 'peephspsSrch', 'align': 'left', 'class': 'blockbtn-hs', 'html': 'search all of Meesto...', 'onclick': 'window.location.href=\'http://www.meesto.com/search.php?q='+encodeURIComponent($('msrch').value)+'\''});
				goToSearchPg.inject($('msrch_resultscont'), 'bottom');
			}
			$('msrch_resultscont').getFirst().addClass('blockbtn-hsOn');
			this.activepsid = $('msrch_resultscont').getFirst().get('id').substr(8);
		}
		$('msrch').set('styles',{'background': '#fff'});
	},
	
	selectNext: function(){
		$('msrch').setCaretPosition("end");
		if(this.activepsid==0) {
			$('msrch_resultscont').getFirst().addClass('blockbtn-hsOn');
			this.activepsid = $('msrch_resultscont').getFirst().get('id').substr(8);
		} else if (!$('peephsps'+this.activepsid).getNext()) {
			$('peephsps'+this.activepsid).removeClass('blockbtn-hsOn');
			this.activepsid = 0;
		} else {
			$('peephsps'+this.activepsid).removeClass('blockbtn-hsOn');
			$('peephsps'+this.activepsid).getNext().addClass('blockbtn-hsOn');
			this.activepsid = $('peephsps'+this.activepsid).getNext().get('id').substr(8);
		}
	},
	
	selectPrevious: function(){
		$('msrch').setCaretPosition("end");
		if (!$('peephsps'+this.activepsid).getPrevious()) {
			$('peephsps'+this.activepsid).removeClass('blockbtn-hsOn');
			this.activepsid = 0;
		} else if(this.activepsid!=0) {
			$('peephsps'+this.activepsid).removeClass('blockbtn-hsOn');
			$('peephsps'+this.activepsid).getPrevious().addClass('blockbtn-hsOn');
			this.activepsid = $('peephsps'+this.activepsid).getPrevious().get('id').substr(8);
		} 
	},
	
	makeChoice: function(){
		if ($('peephsps'+this.activepsid).get('id').substr(8)=='Srch') {
			window.location.href='http://www.meesto.com/search.php?q='+encodeURIComponent($('msrch').value);
		} else {
			if (this.activepsid!=0) {
				window.location.href='http://www.meesto.com/meefile.php?id='+$('peephsps'+this.activepsid).get('id').substr(8);
			}
		}
	}
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
	}