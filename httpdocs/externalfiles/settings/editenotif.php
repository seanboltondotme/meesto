<?php
include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit Email Notification Settings</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to edit which types of emails you will receive from Meesto.</div>';

if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (isset($_POST['req_cnct']) && ($_POST['req_cnct']=='t')) {
		$req_cnct = 'y';
	} else {
		$req_cnct = '';
	}
	
	if (isset($_POST['req_eventi']) && ($_POST['req_eventi']=='t')) {
		$req_eventi = 'y';
	} else {
		$req_eventi = '';
	}
	
	if (isset($_POST['req_proji']) && ($_POST['req_proji']=='t')) {
		$req_proji = 'y';
	} else {
		$req_proji = '';
	}
	
	if (isset($_POST['req_rltnshp']) && ($_POST['req_rltnshp']=='t')) {
		$req_rltnshp = 'y';
	} else {
		$req_rltnshp = '';
	}
	
	if (isset($_POST['reqa_cnct']) && ($_POST['reqa_cnct']=='t')) {
		$reqa_cnct = 'y';
	} else {
		$reqa_cnct = '';
	}
	
	if (isset($_POST['reqa_eventi']) && ($_POST['reqa_eventi']=='t')) {
		$reqa_eventi = 'y';
	} else {
		$reqa_eventi = '';
	}
	
	if (isset($_POST['reqa_proji']) && ($_POST['reqa_proji']=='t')) {
		$reqa_proji = 'y';
	} else {
		$reqa_proji = '';
	}
	
	if (isset($_POST['reqa_rltnshp']) && ($_POST['reqa_rltnshp']=='t')) {
		$reqa_rltnshp = 'y';
	} else {
		$reqa_rltnshp = '';
	}
	
	if (isset($_POST['mkadmin_event']) && ($_POST['mkadmin_event']=='t')) {
		$mkadmin_event = 'y';
	} else {
		$mkadmin_event = '';
	}
	
	if (isset($_POST['mkadmin_proj']) && ($_POST['mkadmin_proj']=='t')) {
		$mkadmin_proj = 'y';
	} else {
		$mkadmin_proj = '';
	}
	
	if (isset($_POST['tag_photo']) && ($_POST['tag_photo']=='t')) {
		$tag_photo = 'y';
	} else {
		$tag_photo = '';
	}
	
	if (isset($_POST['msg']) && ($_POST['msg']=='t')) {
		$msg = 'y';
	} else {
		$msg = '';
	}
	
	if (isset($_POST['emo_myfeed']) && ($_POST['emo_myfeed']=='t')) {
		$emo_myfeed = 'y';
	} else {
		$emo_myfeed = '';
	}
	
	if (isset($_POST['emo_onfeed']) && ($_POST['emo_onfeed']=='t')) {
		$emo_onfeed = 'y';
	} else {
		$emo_onfeed = '';
	}
	
	if (isset($_POST['cmt_myfeed']) && ($_POST['cmt_myfeed']=='t')) {
		$cmt_myfeed = 'y';
	} else {
		$cmt_myfeed = '';
	}
	
	if (isset($_POST['cmt_onfeed']) && ($_POST['cmt_onfeed']=='t')) {
		$cmt_onfeed = 'y';
	} else {
		$cmt_onfeed = '';
	}
	
	if (isset($_POST['cmt_myphoto']) && ($_POST['cmt_myphoto']=='t')) {
		$cmt_myphoto = 'y';
	} else {
		$cmt_myphoto = '';
	}
	
	if (isset($_POST['cmt_onphoto']) && ($_POST['cmt_onphoto']=='t')) {
		$cmt_onphoto = 'y';
	} else {
		$cmt_onphoto = '';
	}
	
	if (isset($_POST['cmt_mymtsec']) && ($_POST['cmt_mymtsec']=='t')) {
		$cmt_mymtsec = 'y';
	} else {
		$cmt_mymtsec = '';
	}
	
	if (isset($_POST['cmt_onmtsec']) && ($_POST['cmt_onmtsec']=='t')) {
		$cmt_onmtsec = 'y';
	} else {
		$cmt_onmtsec = '';
	}
	
	if (isset($_POST['cmt_myevntcmt']) && ($_POST['cmt_myevntcmt']=='t')) {
		$cmt_myevntcmt = 'y';
	} else {
		$cmt_myevntcmt = '';
	}
	
	if (isset($_POST['cmt_onevntcmt']) && ($_POST['cmt_onevntcmt']=='t')) {
		$cmt_onevntcmt = 'y';
	} else {
		$cmt_onevntcmt = '';
	}
	
	if (isset($_POST['cmt_myprojcmt']) && ($_POST['cmt_myprojcmt']=='t')) {
		$cmt_myprojcmt = 'y';
	} else {
		$cmt_myprojcmt = '';
	}
	
	if (isset($_POST['cmt_onprojcmt']) && ($_POST['cmt_onprojcmt']=='t')) {
		$cmt_onprojcmt = 'y';
	} else {
		$cmt_onprojcmt = '';
	}
	
	if (isset($_POST['cmt_myfdbk']) && ($_POST['cmt_myfdbk']=='t')) {
		$cmt_myfdbk = 'y';
	} else {
		$cmt_myfdbk = '';
	}
	
	if (isset($_POST['cmt_onfdbk']) && ($_POST['cmt_onfdbk']=='t')) {
		$cmt_onfdbk = 'y';
	} else {
		$cmt_onfdbk = '';
	}
	
	if (isset($_POST['admn_evntcmt']) && ($_POST['admn_evntcmt']=='t')) {
		$admn_evntcmt = 'y';
	} else {
		$admn_evntcmt = '';
	}
	
	if (isset($_POST['admn_projcmt']) && ($_POST['admn_projcmt']=='t')) {
		$admn_projcmt = 'y';
	} else {
		$admn_projcmt = '';
	}
	
	if (isset($_POST['meesto_news']) && ($_POST['meesto_news']=='t')) {
		$meesto_news = 'y';
	} else {
		$meesto_news = '';
	}
	
	if (isset($_POST['meesto_blog']) && ($_POST['meesto_blog']=='t')) {
		$meesto_blog = 'y';
	} else {
		$meesto_blog = '';
	}
	
	if (isset($_POST['meesto_help_resp']) && ($_POST['meesto_help_resp']=='t')) {
		$meesto_help_resp = 'y';
	} else {
		$meesto_help_resp = '';
	}
	
	if (empty($errors)) {
		$update = mysql_query("UPDATE user_e_notif SET req_cnct='$req_cnct', req_eventi='$req_eventi', req_proji='$req_proji', req_rltnshp='$req_rltnshp', reqa_cnct='$reqa_cnct', reqa_eventi='$reqa_eventi', reqa_proji='$reqa_proji', reqa_rltnshp='$reqa_rltnshp', mkadmin_event='$mkadmin_event', mkadmin_proj='$mkadmin_proj', tag_photo='$tag_photo', msg='$msg', emo_myfeed='$emo_myfeed', emo_onfeed='$emo_onfeed', cmt_myfeed='$cmt_myfeed', cmt_onfeed='$cmt_onfeed', cmt_myphoto='$cmt_myphoto', cmt_onphoto='$cmt_onphoto', cmt_mymtsec='$cmt_mymtsec', cmt_onmtsec='$cmt_onmtsec', cmt_myevntcmt='$cmt_myevntcmt', cmt_onevntcmt='$cmt_onevntcmt', cmt_myprojcmt='$cmt_myprojcmt', cmt_onprojcmt='$cmt_onprojcmt', cmt_myfdbk='$cmt_myfdbk', cmt_onfdbk='$cmt_onfdbk', admn_evntcmt='$admn_evntcmt', admn_projcmt='$admn_projcmt', meesto_news='$meesto_news', meesto_blog='$meesto_blog', meesto_help_resp='$meesto_help_resp' WHERE u_id='$id'");
		echo '<div align="center" class="p18">Your email notification settings have been saved.</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}

	$uinfo = mysql_fetch_array(mysql_query("SELECT req_cnct, req_eventi, req_proji, req_rltnshp, reqa_cnct, reqa_eventi, reqa_proji, reqa_rltnshp, mkadmin_event, mkadmin_proj, tag_photo, msg, emo_myfeed, emo_onfeed, cmt_myfeed, cmt_onfeed, cmt_myphoto, cmt_onphoto, cmt_mymtsec, cmt_onmtsec, cmt_myevntcmt, cmt_onevntcmt, cmt_myprojcmt, cmt_onprojcmt, cmt_myfdbk, cmt_onfdbk, admn_evntcmt, admn_projcmt, meesto_news, meesto_blog, meesto_help_resp FROM user_e_notif WHERE u_id='$id' LIMIT 1"), MYSQL_ASSOC);

	
	echo '<form action="'.$baseincpat.'externalfiles/settings/editenotif.php" method="post">
		<div align="left" style="padding-left: 20px;">
			
		<div align="left" class="p18" style="width: 630px; padding-bottom: 6px; border-bottom: 2px solid #C5C5C5;">
			<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px">notfication type</td><td align="right" width="106px">
				<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">yes</td><td align="center" width="53px">no</td></tr></table>
			</td></tr></table>
		</div><div align="left" style="padding-top: 6px;">
			<div id="maintable" style="overflow-y: scroll; overflow-x: hidden; height: 244px; width: 626px;">
			
				<div align="left" class="p24" style="padding-top: 6px; border-bottom: 1px solid #C5C5C5;">Tags</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">when tagged in a photo</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="tag_photo" value="t"'; if($uinfo['tag_photo']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="tag_photo" value="f"'; if($uinfo['tag_photo']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					
				<div align="left" class="p24" style="padding-top: 24px; border-bottom: 1px solid #C5C5C5;">Messages</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">when someone sends me a message</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="msg" value="t"'; if($uinfo['msg']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="msg" value="f"'; if($uinfo['msg']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
				
				<div align="left" class="p24" style="padding-top: 24px; border-bottom: 1px solid #C5C5C5;">meeLikes/meeDislikes</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">my feed post</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="emo_myfeed" value="t"'; if($uinfo['emo_myfeed']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="emo_myfeed" value="f"'; if($uinfo['emo_myfeed']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">a feed post I liked/disliked or commented on</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="emo_onfeed" value="t"'; if($uinfo['emo_onfeed']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="emo_onfeed" value="f"'; if($uinfo['emo_onfeed']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					
				<div align="left" class="p24" style="padding-top: 24px; border-bottom: 1px solid #C5C5C5;">Comments</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">my feed post</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_myfeed" value="t"'; if($uinfo['cmt_myfeed']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_myfeed" value="f"'; if($uinfo['cmt_myfeed']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">a feed post I liked/disliked or commented on</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_onfeed" value="t"'; if($uinfo['cmt_onfeed']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_onfeed" value="f"'; if($uinfo['cmt_onfeed']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">my MeePics/album photos</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_myphoto" value="t"'; if($uinfo['cmt_myphoto']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_myphoto" value="f"'; if($uinfo['cmt_myphoto']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">a MeePics/album photos I commented on</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_onphoto" value="t"'; if($uinfo['cmt_onphoto']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_onphoto" value="f"'; if($uinfo['cmt_onphoto']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">my custom Meefile sections</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_mymtsec" value="t"'; if($uinfo['cmt_mymtsec']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_mymtsec" value="f"'; if($uinfo['cmt_mymtsec']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">a custom Meefile section I commented on</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_onmtsec" value="t"'; if($uinfo['cmt_onmtsec']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_onmtsec" value="f"'; if($uinfo['cmt_onmtsec']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">events I am an admin of</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="admn_evntcmt" value="t"'; if($uinfo['admn_evntcmt']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="admn_evntcmt" value="f"'; if($uinfo['admn_evntcmt']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">my event comments</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_myevntcmt" value="t"'; if($uinfo['cmt_myevntcmt']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_myevntcmt" value="f"'; if($uinfo['cmt_myevntcmt']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">event comments I comment on</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_onevntcmt" value="t"'; if($uinfo['cmt_onevntcmt']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_onevntcmt" value="f"'; if($uinfo['cmt_onevntcmt']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">meesto community projects/bug I am a team member of</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="admn_projcmt" value="t"'; if($uinfo['admn_projcmt']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="admn_projcmt" value="f"'; if($uinfo['admn_projcmt']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">my meesto community projects/bug comments</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_myprojcmt" value="t"'; if($uinfo['cmt_myprojcmt']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_myprojcmt" value="f"'; if($uinfo['cmt_myprojcmt']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">meesto community projects/bug comments I comment on</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_onprojcmt" value="t"'; if($uinfo['cmt_onprojcmt']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_onprojcmt" value="f"'; if($uinfo['cmt_onprojcmt']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">my feedback</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_myfdbk" value="t"'; if($uinfo['cmt_myfdbk']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_myfdbk" value="f"'; if($uinfo['cmt_myfdbk']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">feedback I comment on</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="cmt_onfdbk" value="t"'; if($uinfo['cmt_onfdbk']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="cmt_onfdbk" value="f"'; if($uinfo['cmt_onfdbk']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
				
				<div align="left" class="p24" style="padding-top: 24px; border-bottom: 1px solid #C5C5C5;">Requests/Invites</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">requests to connect</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="req_cnct" value="t"'; if($uinfo['req_cnct']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="req_cnct" value="f"'; if($uinfo['req_cnct']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">answers to my connection requests</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="reqa_cnct" value="t"'; if($uinfo['reqa_cnct']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="reqa_cnct" value="f"'; if($uinfo['reqa_cnct']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">event invites</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="req_eventi" value="t"'; if($uinfo['req_eventi']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="req_eventi" value="f"'; if($uinfo['req_eventi']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">answers to my event invites</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="reqa_eventi" value="t"'; if($uinfo['reqa_eventi']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="reqa_eventi" value="f"'; if($uinfo['reqa_eventi']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">meesto community project/bug invites</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="req_proji" value="t"'; if($uinfo['req_proji']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="req_proji" value="f"'; if($uinfo['req_proji']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">answers to my meesto community projects/bug invites</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="reqa_proji" value="t"'; if($uinfo['reqa_proji']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="reqa_proji" value="f"'; if($uinfo['reqa_proji']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">relationship status confirmation requests</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="req_rltnshp" value="t"'; if($uinfo['req_rltnshp']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="req_rltnshp" value="f"'; if($uinfo['req_rltnshp']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">answers to my relationship status confirmation requests</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="reqa_rltnshp" value="t"'; if($uinfo['reqa_rltnshp']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="reqa_rltnshp" value="f"'; if($uinfo['reqa_rltnshp']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					
				<div align="left" class="p24" style="padding-top: 24px; border-bottom: 1px solid #C5C5C5;">Ownership Changes</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">changes to my event admin ability</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="mkadmin_event" value="t"'; if($uinfo['mkadmin_event']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="mkadmin_event" value="f"'; if($uinfo['mkadmin_event']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">changes to my meesto community projects/bug team membership</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="mkadmin_proj" value="t"'; if($uinfo['mkadmin_proj']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="mkadmin_proj" value="f"'; if($uinfo['mkadmin_proj']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					
				<div align="left" class="p24" style="padding-top: 24px; border-bottom: 1px solid #C5C5C5;">Meesto Emails</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">when something important is happening with Meesto<br /><span class="subtext" style="font-size: 14px;">we highly recommend you do not turn this off</span></td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="meesto_news" value="t"'; if($uinfo['meesto_news']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="meesto_news" value="f"'; if($uinfo['meesto_news']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">when a post is made in the Meesto Blog</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="meesto_blog" value="t"'; if($uinfo['meesto_blog']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="meesto_blog" value="f"'; if($uinfo['meesto_blog']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					
				<div align="left" class="p24" style="padding-top: 24px; border-bottom: 1px solid #C5C5C5;">Support Emails</div>
					<div align="left" style="padding-bottom: 6px; padding-top: 6px; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0" width="600px"><tr><td align="left" width="494px" style="padding-left: 6px;">answers to my hep questions</td><td align="right" width="106px">
							<table cellpadding="0" cellspacing="0"><tr><td align="center" width="53px">
								<input type="radio" name="meesto_help_resp" value="t"'; if($uinfo['meesto_help_resp']=='y'){echo' CHECKED';} echo'/>
							</td><td align="center" width="53px">
								<input type="radio" name="meesto_help_resp" value="f"'; if($uinfo['meesto_help_resp']!='y'){echo' CHECKED';} echo'/>
							</td></tr></table>
						</td></tr></table>
					</div>
					
			</div>
		</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

include ('../../../externals/header/footer-pb.php');
?>