// notification javasccript functions

var notifs = {
	initialize: function() {
		soundManager.onready(function(oStatus) {
		  if (oStatus.success) {
			mySound_Notif = soundManager.createSound({
			  id: 'mySound_Notif',
			  url: baseincpat+'externalfiles/notifications/notifs-new.mp3',
			  volume: 80
			});
		  } else {
			// SM2 could not start. Show an error, etc.?
		  }
		});
		this.openONR = false;
		this.observeNotifs();
	},
	observeNotifs: function() {
		if (this.timeout) {
			this.observeNotifs.delay(8000, this);
			return
		}
		this.observe();
		this.observeNotifs.delay(8000, this);
	},
	observeNotifsTimeout: function() {
		if (this.timeout) this.timeout = $clear(this.timeout);
		else this.timeout = this.observeNotifsTimeout.delay(8000, this);
	},
	observe: function() {
		
		if (this.openONR) {
			return
		}
		
		new Request.JSON({  
			url: baseincpat+"externalfiles/notifications/observe.php", 
			onRequest: function() {
				this.openONR = true;
			},  
			onSuccess: function(response){ 
				var oldonlnct = $('po_badge').get('html');
				if(oldonlnct!=response){
					$('po_badge').set('html', response);
					if (response>0) {
						$('po_badge').set('styles',{'display':'block'});
						if (response>oldonlnct) {
							mySound_Notif.play();
							notifs.loadlist();
						}
					} else {
						$('po_badge').set('styles',{'display':'none'});
					}
				}
				this.openONR = false;
			}
		}).send();
	},
	loadlist: function() {
		loadcont('po_maincont', ''+baseincpat+'externalfiles/notifications/grab.php');
	},
	setRead: function() {
		if ($('ponotifcont').getFirst()) {
			goto(''+baseincpat+'externalfiles/notifications/setread.php?snid='+$('ponotifcont').getFirst().get('id').substr(5));
		} else {
			goto(''+baseincpat+'externalfiles/notifications/setread.php?');
		}
		this.observeNotifs();
	}
}