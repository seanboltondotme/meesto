// tagger javasccript functions

var tagger = {

	initialize: function() {
		tgr_focus = true;
		this.activetuid = 0;
		this.obj = 0;
		var objs = $(document.body).getElements('div[id$=_tagcont]');
		for(i=0; i<objs.length; i++) {
			var obj = $(objs[i]).get('id').substr(0, $(objs[i]).get('id').lastIndexOf("_"));
			
			var newElem = new Element('div', {'id': obj+'_taggercont', 'align': 'left', 'styles': {'position': 'absolute', 'top': '0px', 'left': '0px'} });
			newElem.inject($(obj+'_tagcont'), 'top');
			
			var newElem2 = new Element('div', {'styles': {'border': '1px solid #36F', 'position': 'absolute', 'top': '0px', 'left': '0px'} });
			newElem2.inject($(obj+'_taggercont'), 'top');
			var newElem3 = new Element('div', {'styles': {'width': '50px', 'height': '50px', 'border': '1px solid #fff'} });
			newElem3.inject(newElem2, 'top');
			
			var newElem4 = new Element('div', {'id': obj+'_sugsrtcont', 'align': 'left', 'styles': {'position': 'absolute', 'top': '2px', 'left': '56px', 'border': '2px solid #C5C5C5', 'background-color': '#fff', 'padding': '6px', 'z-index': '1000', 'display': 'none'} });
			newElem4.inject(newElem2, 'after');
			var newElem5 = new Element('div', {'align': 'left', 'styles': {'margin-bottom': '4px'}, 'html': '<input type="text" id="'+obj+'_taggername" name="name" size="24" maxlength="400" autocomplete="off" onfocus="tagger.setObj(\''+obj+'\');if (trim(this.value) == \'start typing name here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'start typing name here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="start typing name here...">'});
			newElem5.inject(newElem4, 'top');
			var newElem6 = new Element('div', {'id': obj+'_taggersuggestions', 'align': 'left', 'styles': {'height': '100px', 'width': '228px', 'overflow-x': 'none', 'overflow-y': 'scroll'} });
			newElem6.inject(newElem5, 'after');
			var newElem7 = new Element('div', {'id': obj+'_loader', 'align': 'center', 'styles': {'margin-top': '4px', 'display': 'block'}, 'html': '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="http://www.meesto.com/images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table>'});
			newElem7.inject(newElem6, 'after');
		}
		
		new Request.JSON({  
				url: "http://www.meesto.com/externalfiles/photos/tagger.php",
				onSuccess: function(response){
					var sugobjs = $(document.body).getElements('div[id$=_taggersuggestions]');
					for(i=0; i<sugobjs.length; i++) {
						var sugobj = $(sugobjs[i]).get('id').substr(0, $(sugobjs[i]).get('id').lastIndexOf("_"));
						for(j=0; j<response.length; j++) {
							var newPS = new Element('div', {'id': sugobj+'_peepts_'+response[j][0], 'align': 'left', 'class': 'blockbtn-hs', 'html': response[j][2], 'onclick': 'tagger.setObj(\''+sugobj+'\');tagger.addTag('+response[j][0]+');'});
							newPS.inject($(sugobj+'_taggersuggestions'), 'bottom');
						}
						$(sugobj+'_loader').set('styles',{'display':'none'});
					}
					tagger.setValues(response);
				}
			}).send();
	},
	
	setObj: function(obj) {
		tgr_focus = true;
		if (this.obj!=obj) {
			this.activetuid = 0;
		}
		this.obj = obj;
		$(document.body).getElements('div[id$=_sugsrtcont]').set('styles',{'display':'none'});
		$(this.obj+'_sugsrtcont').set('styles',{'display':'block'});
		if ((trim($(this.obj+'_taggername').get('value'))!='start typing name here...')&&(trim($(this.obj+'_taggername').get('value'))!='')) {
			tagger.filter($(this.obj+'_taggername').value);
		}
	},

	movetag: function(event){
		clickX = ((event.clientX + window.getScroll().x) - $(this.obj+'_tagcont').getCoordinates().left) - 26;
		if (clickX<0) {
			clickX = 0;
		} else if ((clickX+50)>$(this.obj+'_tagcont').getSize().x) {
			clickX = ($(this.obj+'_tagcont').getSize().x-50);
		}
		clickY = ((event.clientY + window.getScroll().y) - $(this.obj+'_tagcont').getCoordinates().top) - 26;
		if (clickY<0) {
			clickY = 0;
		} else if ((clickY+50)>$(this.obj+'_tagcont').getSize().y) {
			clickY = ($(this.obj+'_tagcont').getSize().y-50);
		}
		$(this.obj+'_taggercont').set('styles', {'top': clickY+'px'});
		$(this.obj+'_taggercont').set('styles', {'left': clickX+'px'});
		$(this.obj+'_taggername').focus();
	},
	
	setValues: function(values){
		this.values = values;
	},
	
	filter: function(search){
		this.activetuid = 0;
		if ($(this.obj+'_srchstatelm')) { 
			$(this.obj+'_srchstatelm').destroy();
		}
		$(this.obj+'_taggersuggestions').getChildren().destroy();
		var matched = 0;
		$(this.obj+'_loader').set('styles',{'display':'block'});
		if (search.length==0) {
			var values = this.values;
			for (var i = 0; i < values.length; i++){
				var peepid = values[i][0];
					var newPS = new Element('div', {'id': this.obj+'_peepts_'+values[i][0], 'align': 'left', 'class': 'blockbtn-hs', 'html': values[i][2], 'onclick': 'tagger.addTag('+values[i][0]+');'});
					newPS.inject($(this.obj+'_taggersuggestions'), 'bottom');
				matched++;
			}
			$(this.obj+'_loader').set('styles',{'display':'none'});
		} else {
			var values = this.values, regexp = new RegExp('\\b' + search.escapeRegExp(), true ? 'i' : '');
			for (var i = 0; i < values.length; i++){
				var peepid = values[i][0];
				if (values[i][1].test(regexp)) { //if matched
						var newPS = new Element('div', {'id': this.obj+'_peepts_'+values[i][0], 'align': 'left', 'class': 'blockbtn-hs', 'html': values[i][2], 'onclick': 'tagger.addTag('+values[i][0]+');'});
						newPS.inject($(this.obj+'_taggersuggestions'), 'bottom');
					matched++;
				}
			}
			if (matched==0) {
				$(this.obj+'_loader').set('styles',{'display':'none'});
				if (!$(this.obj+'_srchstatelm')) {
					var srchStatElm = new Element('div', {'id': this.obj+'_srchstatelm', 'align': 'left', 'html': 'no matches were found'});
					srchStatElm.inject($(this.obj+'_loader'), 'after');
				}
			} else {
				$(this.obj+'_loader').set('styles',{'display':'none'});
				$(this.obj+'_taggersuggestions').getFirst().addClass('blockbtn-hsOn');
				this.activetuid = $(this.obj+'_taggersuggestions').getFirst().get('id').substr($(this.obj+'_taggersuggestions').getFirst().get('id').indexOf("_")+8, $(this.obj+'_taggersuggestions').getFirst().get('id').lastIndexOf("_"));
			}
		}
	},
	
	selectNext: function(){
		$(this.obj+'_taggername').setCaretPosition("end");
		if(this.activetuid==0) {
			$(this.obj+'_taggersuggestions').getFirst().addClass('blockbtn-hsOn');
			this.activetuid = $(this.obj+'_taggersuggestions').getFirst().get('id').substr($(this.obj+'_taggersuggestions').getFirst().get('id').indexOf("_")+8, $(this.obj+'_taggersuggestions').getFirst().get('id').lastIndexOf("_"));
		} else if (!$(this.obj+'_peepts_'+this.activetuid).getNext()) {
			$(this.obj+'_peepts_'+this.activetuid).removeClass('blockbtn-hsOn');
			this.activetuid = 0;
		} else {
			$(this.obj+'_peepts_'+this.activetuid).removeClass('blockbtn-hsOn');
			$(this.obj+'_peepts_'+this.activetuid).getNext().addClass('blockbtn-hsOn');
			this.activetuid = $(this.obj+'_peepts_'+this.activetuid).getNext().get('id').substr($(this.obj+'_taggersuggestions').getNext().get('id').indexOf("_")+8, $(this.obj+'_taggersuggestions').getNext().get('id').lastIndexOf("_"));
		}
	},
	
	selectPrevious: function(){
		$(this.obj+'_taggername').setCaretPosition("end");
		if (!$(this.obj+'_peepts_'+this.activetuid).getPrevious()) {
			$(this.obj+'_peepts_'+this.activetuid).removeClass('blockbtn-hsOn');
			this.activetuid = 0;
		} else if(this.activetuid!=0) {
			$(this.obj+'_peepts_'+this.activetuid).removeClass('blockbtn-hsOn');
			$(this.obj+'_peepts_'+this.activetuid).getPrevious().addClass('blockbtn-hsOn');
			this.activetuid = $(this.obj+'_peepts_'+this.activetuid).getPrevious().get('id').substr($(this.obj+'_taggersuggestions').getPrevious().get('id').indexOf("_")+8, $(this.obj+'_taggersuggestions').getPrevious().get('id').lastIndexOf("_"));
		} 
	},
	
	makeChoice: function(){
		if (this.activetuid!=0) {
			this.addTag(this.activetuid);
		}
	},
	
	getObj: function(){
		return this.obj;
	},
	
	addTag: function(uid){
		$(this.obj+'_taggername').set('styles',{'display':'none'});
		$(this.obj+'_taggersuggestions').set('styles',{'display':'none'});
		$(this.obj+'_loader').set('styles',{'display':'block'});
		this.activetuid = 0;
		gotopage(this.obj+'_taglist', 'http://www.meesto.com/externalfiles/photos/grabtags.php?apid='+this.obj.substr(2)+'&action=add&method=edtalbmp&uid='+uid+'&y='+$(this.obj+'_taggercont').getPosition(this.obj+'_tagcont').y+'&x='+$(this.obj+'_taggercont').getPosition(this.obj+'_tagcont').x);
		var apid = this.obj.substr(2);
		if ($(apid+'apt'+uid)) {
			$(apid+'apt'+uid).getParent().destroy();
		}
			var newTagElem = new Element('div', {'align': 'left', 'styles': {'top': $(this.obj+'_taggercont').getPosition(this.obj+'_tagcont').y+'px', 'left': $(this.obj+'_taggercont').getPosition(this.obj+'_tagcont').x+'px', 'width': '54px', 'height': '54px', 'position': 'absolute', 'z-index': '10', 'display': 'block'}, html: '<div align="left" id="'+apid+'apt'+uid+'" style="border: 1px solid #36F; display: none;"><div align="left" style="width: 50px; height: 50px; border: 1px solid #fff;"></div></div><div align="center" id="'+apid+'apt'+uid+'_name" style="top: 54px; width: 104px; position: absolute; display: none; background-color: #fff; border: 1px solid #C5C5C5; padding: 2px;">name here</div>' });
			newTagElem.inject($(this.obj+'_tagcont'), 'bottom');
		$(this.obj+'_taggername').set('styles',{'display':'block'});
		$(this.obj+'_taggersuggestions').set('styles',{'display':'block'});
		$(this.obj+'_taggername').set('value', '');
		this.filter('');
		$(this.obj+'_taggername').focus();
	}

}