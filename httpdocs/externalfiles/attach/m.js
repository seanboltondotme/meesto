// attachment javasccript functions

var getlinkdata = {
	get: function(url) {
		requestToGetSiteData = new Request.JSON({  
			url: baseincpat+"externalfiles/attach/getsiteinfo.php", 
			onRequest: function() {
				$('btnattach').set('styles',{'display':'none'});
				$('btnsbmt').set('styles',{'display':'none'});
				$('loader').set('styles',{'display':'block'});
				var newElem0 = new Element('div', {'id': 'link_loader', 'align': 'left', 'styles': {'background': '#fff', 'border-bottom': '2px solid #C5C5C5', 'padding-bottom': '12px', 'margin-top': '6px'}, 'html': '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top"><img src="'+baseincpat+'images/spinner.gif"/></td><td align="left" valign"center" style="padding-left: 2px;">getting link information...<br /><span class="subtext" style="font-size: 14px;">this might be a minute or so</span></td></tr></table></td><td align="left" valign="top" id="loadercncl_btn" style="padding-left: 12px;"><input type="button" value="cancel" onclick="getlinkdata.stopRequest();"/></td></tr></table>'});
				newElem0.inject($('attachments'), 'top');
				parent.PopBox.close();
			},
			onSuccess: function(response){
				if (response['type']=='image') { //if link is an image
					
					var newElem = new Element('div', {'id': 'atchmnt', 'align': 'left', 'styles': {'background': '#fff', 'border-bottom': '2px solid #C5C5C5', 'padding-bottom': '12px', 'margin-top': '6px'}, 'html': '<div align="left"><table cellpadding="0" cellspacing="0" width="444px"><tr><td align="left" valign="top" style="width: 90px; height: 80px;"><div id="thumbnailviewer" style="width: 90px; height: 80px; overflow: hidden; position: relative;"><div id="thumbnaillist" style="position: absolute; top: 0px; left: 0px;"><table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" width="90px"><div align="center" valign="top" style="width: 90px; height: 80px;"><img src="'+response['thumbnails'][0][0]+'"  width="'+response['thumbnails'][0][1]+'px" height="'+response['thumbnails'][0][2]+'px"/></div></td></tr></table></div></div></td></td><td align="left" valign="top" style="padding-left: 24px;"><input type="button" value="remove" onclick="$(\'atchmnt\').destroy();$(\'btnattach\').set(\'styles\',{\'display\':\'block\'});"/></td></table></div><div align="left"><input type="hidden" id="thmubdisplaynum" name="thmubdisplaynum" value="1"/><input type="hidden" id="thumbsexist" name="thumbsexist" value="y"/><input type="hidden" id="type" name="type" value="'+response['type']+'"/><input type="hidden" id="url" name="url" value="'+response['url']+'"/><input type="hidden" id="host" name="host" value="'+response['host']+'"/></div>'});
					
				} else { //if link is anything besides an image
					var tncount = response['thumbnails'].length;
					if (tncount>1) {
							//geta all images as string
							var tnstr = '';
							for(i=0; i<tncount; i++) {
								tnstr += '<td align="center" valign="top" width="90px"><div align="center" valign="top" style="width: 90px; height: 80px;"><img id="ui-img'+i+'" src="'+response['thumbnails'][i][0]+'"  width="'+response['thumbnails'][i][1]+'px" height="'+response['thumbnails'][i][2]+'px"/></div></td>';
							}
						var newElem = new Element('div', {'id': 'atchmnt', 'align': 'left', 'styles': {'background': '#fff', 'border-bottom': '2px solid #C5C5C5', 'padding-bottom': '12px', 'margin-top': '6px'}, 'html': '<div align="left"><table cellpadding="0" cellspacing="0" width="444px"><tr><td align="left" valign="top" style="width: 90px; height: 80px;"><div id="thumbnailviewer" style="width: 90px; height: 80px; overflow: hidden; position: relative;"><div id="thumbnaillist" style="position: absolute; top: 0px; left: 0px;"><table cellpadding="0" cellspacing="0"><tr>'+tnstr+'</tr></table></div></div></td><td align="left" valign="top" width="334px" style="padding-left: 12px;"><table cellpadding="0" cellspacing="0"><tr><td align="left"><input type="text" id="title" name="title" size="40" maxlength="400" autocomplete="off" onfocus="if (trim(this.value) == \'type title here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type title here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="if (trim(this.value) != \'\') {$(\'submit\').set(\'styles\',{\'display\':\'block\'});} else if (trim(this.value) == \'\') {$(\'submit\').set(\'styles\',{\'display\':\'none\'});}" value="'+response['title']+'"></td></tr><tr><td align="left" class="subtext" style="font-size: 14px;">'+response['host']+'</td></tr><tr><td align="left" style="padding-left: 12px; padding-top: 4px;"><textarea name="details" id="details" cols="40" rows="2" onfocus="if (trim(this.value) == \'type description here (optional)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type description here (optional)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}"'+response['description']+'</textarea></td></tr><tr><td align="left" id="descritp" style="padding-left: 10px; padding-top: 6px;"><table cellpadding="0" cellspacing="0" id="thumbpicker"><tr><td align="right" style="cursor: pointer;"><img src="'+baseincpat+'images/attachlink/btnprevOff.png" id="btnprev" onclick="if(parseFloat($(\'thmubdisplaynum\').value)>1){$(\'tnurl\').set(\'value\', $(\'ui-img\'+(parseFloat($(\'thmubdisplaynum\').value)-2)).getProperty(\'src\') );$(\'thmubdisplaynum\').set(\'value\', parseFloat($(\'thmubdisplaynum\').value)-1);$(\'thumbnaillist\').set(\'tween\', { duration: 200 }).tween(\'left\', (-90*parseFloat($(\'thmubdisplaynum\').value)+90));$(\'showthmubdisplaynum\').innerHTML=$(\'thmubdisplaynum\').value;$(\'btnnext\').set(\'src\', \''+baseincpat+'images/attachlink/btnnext.png\'); if(parseFloat($(\'thmubdisplaynum\').value)==1){this.set(\'src\', \''+baseincpat+'images/attachlink/btnprevOff.png\');}}"/></td><td align="left" style="cursor: pointer;"><img src="'+baseincpat+'images/attachlink/btnnext.png" id="btnnext" onclick="if(parseFloat($(\'thmubdisplaynum\').value)<'+tncount+'){$(\'tnurl\').set(\'value\', $(\'ui-img\'+parseFloat($(\'thmubdisplaynum\').value)).getProperty(\'src\') );$(\'thmubdisplaynum\').set(\'value\', parseFloat($(\'thmubdisplaynum\').value)+1);$(\'thumbnaillist\').set(\'tween\', { duration: 200 }).tween(\'left\', (-90*parseFloat($(\'thmubdisplaynum\').value)+90));$(\'showthmubdisplaynum\').innerHTML=$(\'thmubdisplaynum\').value;$(\'btnprev\').set(\'src\', \''+baseincpat+'images/attachlink/btnprev.png\'); if(parseFloat($(\'thmubdisplaynum\').value)=='+tncount+'){this.set(\'src\', \''+baseincpat+'images/attachlink/btnnextOff.png\');}}"/></td><td align="left" style="padding-left: 4px; padding-right: 2px;">choose thumbnail<td align="right" id="showthmubdisplaynum">1</td><td align="left" style="padding-left: 2px;">of '+tncount+'</td></tr></table></td></tr><tr><td align="left" class="subtext" style="padding-left: 14px; padding-top: 4px;"><table cellpadding="0" cellspacing="0" onclick="if($(\'ui-thumbsexist\').get(\'checked\') == false){$(\'ui-thumbsexist\').set(\'checked\', true);$(\'thumbnailviewer\').set(\'styles\',{\'display\':\'none\'});$(\'thumbpicker\').set(\'styles\',{\'display\':\'none\'});$(\'thumbsexist\').set(\'value\', \'n\');}else{$(\'ui-thumbsexist\').set(\'checked\', false);$(\'thumbnailviewer\').set(\'styles\',{\'display\':\'block\'});$(\'thumbpicker\').set(\'styles\',{\'display\':\'block\'});$(\'thumbsexist\').set(\'value\', \'y\');}" style="cursor: pointer;"><tr><td align="left"><input type="checkbox" name="ui-thumbsexist" id="ui-thumbsexist" value="y"  onclick="if($(\'ui-thumbsexist\').get(\'checked\') == false){$(\'ui-thumbsexist\').set(\'checked\', true);$(\'thumbnailviewer\').set(\'styles\',{\'display\':\'none\'});$(\'thumbpicker\').set(\'styles\',{\'display\':\'none\'});$(\'thumbsexist\').set(\'value\', \'n\');}else{$(\'ui-thumbsexist\').set(\'checked\', false);$(\'thumbnailviewer\').set(\'styles\',{\'display\':\'block\'});$(\'thumbpicker\').set(\'styles\',{\'display\':\'block\'});$(\'thumbsexist\').set(\'value\', \'y\');}"/></td><td align="left" style="padding-left: 4px;">don\'t show thumbnail</td></tr></table></td></tr></table></td><td align="left" valign="top" style="padding-left: 24px;"><input type="button" value="remove" onclick="$(\'atchmnt\').destroy();$(\'btnattach\').set(\'styles\',{\'display\':\'block\'});"/></td></tr></table></div><div align="left"><input type="hidden" id="thmubdisplaynum" name="thmubdisplaynum" value="1"/><input type="hidden" id="thumbsexist" name="thumbsexist" value="y"/><input type="hidden" id="type" name="type" value="'+response['type']+'"/><input type="hidden" id="url" name="url" value="'+response['url']+'"/><input type="hidden" id="host" name="host" value="'+response['host']+'"/><input type="hidden" id="tnurl" name="tnurl" value="'+response['thumbnails'][0][0]+'"/></div>'});	
					} else {
						var newElem = new Element('div', {'id': 'atchmnt', 'align': 'left', 'styles': {'background': '#fff', 'border-bottom': '2px solid #C5C5C5', 'padding-bottom': '12px', 'margin-top': '6px'}, 'html': '<div align="left"><table cellpadding="0" cellspacing="0" width="444px"><tr><td align="left" valign="top" style="width: 90px; height: 80px;"><div id="thumbnailviewer" style="width: 90px; height: 80px; overflow: hidden; position: relative;"><div id="thumbnaillist" style="position: absolute; top: 0px; left: 0px;"><table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" width="90px"><div align="center" valign="top" style="width: 90px; height: 80px;"><img src="'+response['thumbnails'][0][0]+'"  width="'+response['thumbnails'][0][1]+'px" height="'+response['thumbnails'][0][2]+'px"/></div></td></tr></table></div></div></td><td align="left" valign="top" width="334px" style="padding-left: 12px;"><table cellpadding="0" cellspacing="0"><tr><td align="left"><input type="text" id="title" name="title" size="40" maxlength="400" autocomplete="off" onfocus="if (trim(this.value) == \'type title here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type title here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="if (trim(this.value) != \'\') {$(\'submit\').set(\'styles\',{\'display\':\'block\'});} else if (trim(this.value) == \'\') {$(\'submit\').set(\'styles\',{\'display\':\'none\'});}" value="'+response['title']+'"></td></tr><tr><td align="left" class="subtext" style="font-size: 14px;">'+response['host']+'</td></tr><tr><td align="left" style="padding-left: 12px; padding-top: 4px;"><textarea name="details" id="details" cols="40" rows="2" onfocus="if (trim(this.value) == \'type description here (optional)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type description here (optional)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}"'+response['description']+'</textarea></td></tr><tr><td align="left" class="subtext" style="padding-left: 14px; padding-top: 4px;"><table cellpadding="0" cellspacing="0" onclick="if($(\'ui-thumbsexist\').get(\'checked\') == false){$(\'ui-thumbsexist\').set(\'checked\', true);$(\'thumbnailviewer\').set(\'styles\',{\'display\':\'none\'});$(\'thumbpicker\').set(\'styles\',{\'display\':\'none\'});$(\'thumbsexist\').set(\'value\', \'n\');}else{$(\'ui-thumbsexist\').set(\'checked\', false);$(\'thumbnailviewer\').set(\'styles\',{\'display\':\'block\'});$(\'thumbpicker\').set(\'styles\',{\'display\':\'block\'});$(\'thumbsexist\').set(\'value\', \'y\');}" style="cursor: pointer;"><tr><td align="left"><input type="checkbox" name="ui-thumbsexist" id="ui-thumbsexist" value="y"  onclick="if($(\'ui-thumbsexist\').get(\'checked\') == false){$(\'thumbsexist\').set(\'value\', \'n\');$(\'ui-thumbsexist\').set(\'checked\', true);$(\'thumbnailviewer\').set(\'styles\',{\'display\':\'none\'});$(\'thumbpicker\').set(\'styles\',{\'display\':\'none\'});}else{$(\'thumbsexist\').set(\'value\', \'y\');$(\'ui-thumbsexist\').set(\'checked\', false);$(\'thumbnailviewer\').set(\'styles\',{\'display\':\'block\'});$(\'thumbpicker\').set(\'styles\',{\'display\':\'block\'});}"/></td><td align="left" style="padding-left: 4px;">don\'t show thumbnail</td></tr></table></td></tr></table></td><td align="left" valign="top" style="padding-left: 24px;"><input type="button" value="remove" onclick="$(\'atchmnt\').destroy();$(\'btnattach\').set(\'styles\',{\'display\':\'block\'});"/></td></tr></table></div><div align="left"><input type="hidden" id="thmubdisplaynum" name="thmubdisplaynum" value="1"/><input type="hidden" id="thumbsexist" name="thumbsexist" value="y"/><input type="hidden" id="type" name="type" value="'+response['type']+'"/><input type="hidden" id="url" name="url" value="'+response['url']+'"/><input type="hidden" id="host" name="host" value="'+response['host']+'"/><input type="hidden" id="tnurl" name="tnurl" value="'+response['thumbnails'][0][0]+'"/></div>'});	
					}
					
				}
				newElem.inject($('attachments'), 'top');
				$('link_loader').destroy();
				$('loader').set('styles',{'display':'none'});
				$('btnsbmt').set('styles',{'display':'block'});
			}
		}).get({'u': url});
	},
	
	stopRequest: function() {
		requestToGetSiteData.cancel();
		$('link_loader').destroy();
		$('loader').set('styles',{'display':'none'});
		$('btnattach').set('styles',{'display':'block'});
		$('btnsbmt').set('styles',{'display':'block'});
	}
}

var attachments = {
	ap: function(url, apid) {
		$('btnattach').set('styles',{'display':'none'});
		parent.PopBox.close();
		var newElem = new Element('div', {'id': 'atchmnt', 'align': 'left', 'styles': {'background': '#fff', 'border-bottom': '2px solid #C5C5C5', 'padding-bottom': '12px', 'margin-top': '6px'}, 'html': '<div align="left"><table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top"><img src="'+url+'"/></td><td align="center" valign="top" style="padding-left: 6px;">Album Photo</td><td align="left" valign="top" style="padding-left: 24px;"><input type="button" value="remove" onclick="$(\'atchmnt\').destroy();$(\'btnattach\').set(\'styles\',{\'display\':\'block\'});"/></td></tr></table></div><div align="left"><input type="hidden" id="type" name="type" value="ap"/><input type="hidden" id="apid" name="apid" value="'+apid+'"/></div>'});
		newElem.inject($('attachments'), 'top');
	},
	upload: function(url, atchid) {
		$('btnattach').set('styles',{'display':'none'});
		parent.PopBox.close();
		var newElem = new Element('div', {'id': 'atchmnt', 'align': 'left', 'styles': {'background': '#fff', 'border-bottom': '2px solid #C5C5C5', 'padding-bottom': '12px', 'margin-top': '6px'}, 'html': '<div align="left"><table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top"><img src="'+url+'"/></td><td align="center" valign="top" style="padding-left: 6px;">Uploaded Photo</td><td align="left" valign="top" style="padding-left: 24px;"><input type="button" value="remove" onclick="$(\'atchmnt\').destroy();$(\'btnattach\').set(\'styles\',{\'display\':\'block\'});"/></td></tr></table></div><div align="left"><input type="hidden" id="type" name="type" value="upld_p"/><input type="hidden" id="atchid" name="atchid" value="'+atchid+'"/></div>'});
		newElem.inject($('attachments'), 'top');
	},
	lnk: function(type, host, tn_url, title, description, atchid) {
		$('btnattach').set('styles',{'display':'none'});
		parent.PopBox.close();
		if (type=='img') {
			var newElem = new Element('div', {'id': 'atchmnt', 'align': 'left', 'styles': {'background': '#fff', 'border-bottom': '2px solid #C5C5C5', 'padding-bottom': '12px', 'margin-top': '6px'}, 'html': '<div align="left"><table cellpadding="0" cellspacing="0" width="444px"><tr><td align="left" valign="top" style="width: 90px; height: 80px;"><div id="thumbnailviewer" style="width: 90px; height: 80px; overflow: hidden; position: relative;"><div id="thumbnaillist" style="position: absolute; top: 0px; left: 0px;"><table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" width="90px"><div align="center" valign="top" style="width: 90px; height: 80px;"><img src="'+tn_url+'"/></div></td></tr></table></div></div></td></td><td align="left" valign="top" style="padding-left: 24px;"><input type="button" value="remove" onclick="$(\'atchmnt\').destroy();$(\'btnattach\').set(\'styles\',{\'display\':\'block\'});"/></td></table></div><div align="left"><input type="hidden" id="type" name="type" value="prevlnk_img"/><input type="hidden" id="atchid" name="atchid" value="'+atchid+'"/></div>'});
		} else {
			var newElem = new Element('div', {'id': 'atchmnt', 'align': 'left', 'styles': {'background': '#fff', 'border-bottom': '2px solid #C5C5C5', 'padding-bottom': '12px', 'margin-top': '6px'}, 'html': '<div align="left"><table cellpadding="0" cellspacing="0" width="444px"><tr><td align="left" valign="top" style="width: 90px; height: 80px;"><div id="thumbnailviewer" style="width: 90px; height: 80px; overflow: hidden; position: relative;"><div id="thumbnaillist" style="position: absolute; top: 0px; left: 0px;"><table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" width="90px"><div align="center" valign="top" style="width: 90px; height: 80px;"><img src="'+tn_url+'"/></div></td></tr></table></div></div></td><td align="left" valign="top" width="334px" style="padding-left: 12px;"><table cellpadding="0" cellspacing="0"><tr><td align="left">'+title+'</td></tr><tr><td align="left" class="subtext" style="font-size: 14px;">'+host+'</td></tr><tr><td align="left" class="subtext" style="padding-left: 12px; padding-top: 4px;">'+description+'</td></tr></table></td><td align="left" valign="top" style="padding-left: 24px;"><input type="button" value="remove" onclick="$(\'atchmnt\').destroy();$(\'btnattach\').set(\'styles\',{\'display\':\'block\'});"/></td></tr></table></div><div align="left"><input type="hidden" id="type" name="type" value="prevlnk_site"/><input type="hidden" id="atchid" name="atchid" value="'+atchid+'"/></div>'});	
		}
		newElem.inject($('attachments'), 'top');
	}
}