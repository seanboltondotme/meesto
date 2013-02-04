// inviter javasccript functions

function showA() {
	if ($('srchstat')) {
		$('srchstat').destroy();
	}
	if ($('msrch').get('value')!='search') {
		$('msrch').set('value', 'search');
		$('msrch').className='inputplaceholder';
		PeepSearch.filter('');
	}
	
	var matched = 0;
	var peeps = $('peeparea').getChildren().get('id');
	for(i=0; i<peeps.length; i++) {
		var peepid = peeps[i].substr(1);
		var peeprsvp = peeps[i].substr(0, 1);
		if (peeprsvp=='a') {
			$(peeprsvp+peepid).set('styles',{'display':'block'});
			matched++;
		} else {
			$(peeprsvp+peepid).set('styles',{'display':'none'});
		}
	}
	if (matched==0) {
		var newElem = new Element('div', {'id': 'srchstat', 'align': 'left', 'html': 'no peeple here'});
		newElem.inject($('peeparea'), 'top');
	}
}

function showM() {
	if ($('srchstat')) {
		$('srchstat').destroy();
	}
	if ($('msrch').get('value')!='search') {
		$('msrch').set('value', 'search');
		$('msrch').className='inputplaceholder';
		PeepSearch.filter('');
	}
	
	var matched = 0;
	var peeps = $('peeparea').getChildren().get('id');
	for(i=0; i<peeps.length; i++) {
		var peepid = peeps[i].substr(1);
		var peeprsvp = peeps[i].substr(0, 1);
		if (peeprsvp=='m') {
			$(peeprsvp+peepid).set('styles',{'display':'block'});
			matched++;
		} else {
			$(peeprsvp+peepid).set('styles',{'display':'none'});
		}
	}
	if (matched==0) {
		var newElem = new Element('div', {'id': 'srchstat', 'align': 'left', 'html': 'no peeple here'});
		newElem.inject($('peeparea'), 'top');
	}
}

function showN() {
	if ($('srchstat')) {
		$('srchstat').destroy();
	}
	if ($('msrch').get('value')!='search') {
		$('msrch').set('value', 'search');
		$('msrch').className='inputplaceholder';
		PeepSearch.filter('');
	}
	
	var matched = 0;
	var peeps = $('peeparea').getChildren().get('id');
	for(i=0; i<peeps.length; i++) {
		var peepid = peeps[i].substr(1);
		var peeprsvp = peeps[i].substr(0, 1);
		if (peeprsvp=='n') {
			$(peeprsvp+peepid).set('styles',{'display':'block'});
			matched++;
		} else {
			$(peeprsvp+peepid).set('styles',{'display':'none'});
		}
	}
	if (matched==0) {
		var newElem = new Element('div', {'id': 'srchstat', 'align': 'left', 'html': 'no peeple here'});
		newElem.inject($('peeparea'), 'top');
	}
}

var PeepSearch = {
	
	setValues: function(values){
		this.values = values;
	},
	
	filter: function(search){
		if (search.length==0) {
			if ($('srchstat')) {
				$('srchstat').destroy();
			}
			$('peeparea').getChildren().set('styles',{'display':'block'});
		} else {
			var matched = 0;
			if (!$('srchstat')) {
				var newElem = new Element('div', {'id': 'srchstat', 'align': 'left', 'html': '<div style="margin: 12px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="http://www.meesto.com/images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>'});
				newElem.inject($('peeparea'), 'top');
			} else {
				$('srchstat').set('html', '<div style="margin: 12px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="http://www.meesto.com/images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>');
			}
			var values = this.values, regexp = new RegExp('\\b' + search.escapeRegExp(), true ? 'i' : '');
			for (var i = 0; i < values.length; i++){
				var peepid = values[i][0];
				var peeprsvp = values[i][2];
				if (values[i][1].test(regexp)) { //if matched
					$(peeprsvp+peepid).set('styles',{'display':'block'});
					matched++;
				} else {
					$(peeprsvp+peepid).set('styles',{'display':'none'});
				}
			}
			if (matched==0) {
				$('srchstat').set('html', 'no matches were found');
			} else {
				$('srchstat').destroy();
			}
		}
	}

}