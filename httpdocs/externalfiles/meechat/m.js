// meechat javasccript functions

var meechat = {
	initialize: function(plsoundstat, plocid) {
		soundManager.onready(function(oStatus) {
		  if (oStatus.success) {
			mySound_MCR = soundManager.createSound({
			  id: 'mySound_MCR',
			  url: 'http://www.meesto.com/externalfiles/meechat/MeeChat-Receive.mp3',
			  volume: 80
			});
			mySound_MCS = soundManager.createSound({
			  id: 'mySound_MCS',
			  url: 'http://www.meesto.com/externalfiles/meechat/MeeChat-Send.mp3',
			  volume: 30
			});
		  } else {
			// SM2 could not start. Show an error, etc.?
		  }
		});
		if (plocid!=0) {
			opencid = plocid;
			$('chat_thread'+opencid).scrollTo(0, $('chat_thread'+opencid).getScrollSize().y);
			$('chat_chatter'+opencid).addEvent('keydown', this.enterSubmit);
			$('chat_chatter'+opencid).focus();
		} else {
			opencid = 0;
		}
		if (plsoundstat=='y') {
			soundstat = true;
		} else {
			soundstat = false;
		}
		this.openOCR = false;
		this.observeMeechat();
	},
	observeMeechat: function() {
		if (this.timeout) {
			this.observeMeechat.delay(2000, this);
			return
		}
		this.observeList();
		this.observeChats();
		this.observeMeechat.delay(2000, this);
	},
	observeMeechatTimeout: function() {
		if (this.timeout) this.timeout = $clear(this.timeout);
		else this.timeout = this.observeMeechatTimeout.delay(2000, this);
	},
	enterSubmit: function(event) {
		if ((event.key=="enter")&&(opencid>0)) {
			if ((trim($('chat_chatter'+opencid).get('value'))!='type chatter here')&&(trim($('chat_chatter'+opencid).get('value'))!='')) { 
				meechat.newMsg(opencid, encodeURIComponent($('chat_chatter'+opencid).value) );
				$('chat_chatter'+opencid).set('value', '');
			}
		}
	},
	observeList: function() {
		new Request.JSON({  
			url: "http://www.meesto.com/externalfiles/meechat/observelist.php",  
			onSuccess: function(response){ 
				var oldonlnct = $('mc_onlinect').get('html');
				$('mc_onlinect').set('html', response);
				if((oldonlnct!=response)&&($('chat_content').getStyles('visibility').visibility=='visible')){
					meechat.loadlist();
				}
			}
		}).send();
	},
	loadlist: function() {
		loadcont('chat_main', 'http://www.meesto.com/externalfiles/meechat/grablist.php');
	},
	openChat: function(cid) {
		if (opencid!=0) {
			$('chat_convocont'+opencid).set('tween', {duration: 'short'}).fade('hide');
			$('chat_chatter'+opencid).removeEvent('keydown', this.enterSubmit);
		}
		opencid = cid;
		$('chat_convocont'+cid).set('tween', {duration: 'short'}).fade('show');
		$('chat_badge'+cid).set('tween', {duration: 'short'}).fade('hide');
		goto('http://www.meesto.com/externalfiles/meechat/showchat.php?cid='+cid);
		$('chat_thread'+cid).scrollTo(0, $('chat_thread'+cid).getScrollSize().y);
		meechat.loadNewMsgs(cid);
		$('chat_chatter'+opencid).addEvent('keydown', this.enterSubmit);
		$('chat_chatter'+opencid).focus();
	},
	hideChat: function(cid) {
		opencid = 0;
		goto('http://www.meesto.com/externalfiles/meechat/hidechat.php?cid='+cid);
		$('chat_convocont'+cid).set('tween', {duration: 'short'}).fade('hide');
		$('chat_chatter'+opencid).removeEvent('keydown', this.enterSubmit);
	},
	closeChat: function(cid) {
		opencid = 0;
		goto('http://www.meesto.com/externalfiles/meechat/closechat.php?cid='+cid);
		$('chat_chatter'+cid).removeEvent('keydown', this.enterSubmit);
		$('chat_pers'+cid).destroy();
	},
	observeChats: function() {
		
		if (this.openOCR) {
			return
		}
		
		new Request.JSON({  
			url: "http://www.meesto.com/externalfiles/meechat/observechats.php", 
			onRequest: function() {
				this.openOCR = true;
			},
			onSuccess: function(response){ 
				for(i=0; i<response.length; i++) {
					var cid = response[i];
					meechat.observeChat(cid);
				}
				this.openOCR = false;
			}
		}).send();
		
	},
	observeChat: function(cid) {
		if ($('chat_pers'+cid)) {
			//alert('hi'+cid);
			new Request.JSON({  
				url: "http://www.meesto.com/externalfiles/meechat/observechat.php", 
				onRequest: function() {
					//alert('onRequest'+cid);
				},
				onComplete: function() {
					//alert('onComplete'+cid);
				},
				onSuccess: function(response){ 
					//alert('onSuccess '+cid+'; response.action  '+response.action+'; last mcmid '+$('chat_thread'+cid).getLast().get('mcmid'));
					//alert('opencid '+opencid);
					//alert('response.action  '+response.action);
					//if(response.action == 'none'){
					if(response.action.substr(0, 3) == 'new'){
						if (cid == opencid) {
							//alert('active chat load new'+cid);
							if (soundstat==true) {
								mySound_MCR.play();
							}
							meechat.loadNewMsgs(cid);
						} else {
							if ($('chat_badge'+cid).get('html')!=response.action.substr(3)) {
								if (soundstat==true) {
									mySound_MCR.play();
								}
							}
							$('chat_badge'+cid).set('tween', {duration: 'short'}).fade('show');
							//$('chat_badge'+cid).set('html', '1');
							$('chat_badge'+cid).set('html', response.action.substr(3));
						}
					}
				}
			}).get({'cid': cid, 'mcmid': $('chat_thread'+cid).getLast().get('mcmid')});
		} else {
			meechat.newChatEx(cid, 'b1');
		}
		
	},
	loadNewMsgs: function(cid) { //had to make fix here by not using loadcont() because scrollto would fire before new content was loaded and thus would not scroll to the bottom
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
					document.getElementById('chat_thread'+cid).innerHTML = echo;
					$('chat_thread'+cid).scrollTo(0, $('chat_thread'+cid).getScrollSize().y);
				}
			}
			xhr.open("GET", 'http://www.meesto.com/externalfiles/meechat/grabmsgs.php?cid='+cid, true);
			xhr.send(null);
		}
		else {
			document.getElementById('chat_thread'+cid).innerHTML = "An error occured.";
		}
	},
	newChat: function(cid, a) {
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
					} else {
						var echo = "There was a problem with the request " + xhr.status;
					}
					var bodyWrapVar = $('chat_tray');
					var newElementVar = new Element('div', {
						'html': echo,
						'id': 'chat_pers'+cid,
						'align': 'center',
						'styles': {
							'float': 'right',
							'width': '134px',
							'height': '30px',
							'border-left': '2px solid #fff'
						},
					});
					newElementVar.inject(bodyWrapVar, 'bottom');
					meechat.openChat(cid);
					}
				}
			xhr.open("GET", 'http://www.meesto.com/externalfiles/meechat/newchat.php?cid='+cid+'&a='+a, true);
			xhr.send();
		} else {
			alert("An error occured – sorry about that. Let us know if this continues.");
		}
	},
	newChatEx: function(cid) {
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
					} else {
						var echo = "There was a problem with the request " + xhr.status;
					}
					var bodyWrapVar = $('chat_tray');
					var newElementVar = new Element('div', {
						'html': echo,
						'id': 'chat_pers'+cid,
						'align': 'center',
						'styles': {
							'float': 'right',
							'width': '134px',
							'height': '30px',
							'border-left': '2px solid #fff'
						},
					});
					newElementVar.inject(bodyWrapVar, 'bottom');
					meechat.observeChat(cid);
					}
				}
			xhr.open("GET", 'http://www.meesto.com/externalfiles/meechat/newchat.php?cid='+cid+'&ns=true', true);
			xhr.send();
		} else {
			alert("An error occured – sorry about that. Let us know if this continues.");
		}
	},
	newMsg: function(cid, msg) { //had to make fix here by not using loadcont() because scrollto would fire before new content was loaded and thus would not scroll to the bottom
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
					document.getElementById('chat_thread'+cid).innerHTML = echo;
					$('chat_thread'+cid).scrollTo(0, $('chat_thread'+cid).getScrollSize().y);
				}
			}
			xhr.open("GET", 'http://www.meesto.com/externalfiles/meechat/newmsg.php?cid='+cid+'&msg='+msg, true);
			xhr.send(null);
		}
		else {
			document.getElementById('chat_thread'+cid).innerHTML = "An error occured.";
		}
		if (soundstat==true) {
			mySound_MCS.play();
		}
		$('chat_thread'+cid).scrollTo(0, $('chat_thread'+cid).getScrollSize().y);
	},
	injectLoader: function(target) {
		$(target).set('html', '<div style="padding-top: 2px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="http://www.meesto.com/images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>');
	},
	toggleSound: function(sndtgl) {
		if (sndtgl=='on') {
			soundstat = true;
			$('mc_sndOn').set('styles',{'font-size': '16px', 'color': '#000'});
			$('mc_sndOff').set('styles',{'font-size': '13px', 'color': '#808080'});
		} else {
			soundstat = false;
			$('mc_sndOff').set('styles',{'font-size': '16px', 'color': '#000'});
			$('mc_sndOn').set('styles',{'font-size': '13px', 'color': '#808080'});
		}
		goto('http://www.meesto.com/externalfiles/meechat/togglesound.php?t='+sndtgl);	
	},
	toggleStat_OffChk: function() {
		var sctns = $('chat_main').getChildren();
		var On_ct = 0;
		for(i=0; i<sctns.length; i++) {
			if (sctns[i].getFirst().get('class')=='chatgrpOn') {
				On_ct++;
			}
		}
		if (On_ct>0) {
			return false;	
		} else {
			return true;	
		}
	}
}