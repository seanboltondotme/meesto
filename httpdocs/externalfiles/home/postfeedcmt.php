<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$fid = escape_data($_GET['id']);

$isvis = false;

$feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id, type, ref_id, ref_type FROM feed WHERE f_id='$fid' LIMIT 1"), MYSQL_ASSOC);
$uid = $feedinfo['u_id'];
$type = $feedinfo['type'];
$ref_id = $feedinfo['ref_id'];
$ref_type = $feedinfo['ref_type'];

if ($type=='actvapt') {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$uid' AND dvapt.type='strm' AND ps.stream=dvapt.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$uid' AND dvapt.type='chan' AND dvapt.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvap') {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE pa_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='strm' AND ps.stream=pav.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='chan' AND pav.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mt')) {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mts')) {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvcev') {
	if (($uid==$id) || (mysql_result (mysql_query("SELECT COUNT(*) FROM events WHERE e_id='$ref_id' AND vis='pub' LIMIT 1"), 0)>0) || (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0)) {
		$isvis = true;
	}
} else {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='strm' AND ps.stream=fv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='chan' AND fv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
}

if ($isvis==true) {

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {
	
	$pdrjs = '$(\'msg\').focus();';
	$ifname = 'feedwritecmt'.$fid;
	include ('../../../externals/header/header-iframe.php');
	
	if (isset($_POST['send'])) {
	//save
		
		$errors = NULL;
		
		if (isset($_POST['msg']) && ($_POST['msg'] != 'type here to comment on this...')) {
			$msg = escape_form_data($_POST['msg']);
		} else {
			$errors[] = 'no msg content';
		}
		
		if (empty($errors)) {
			$createthread = mysql_query("INSERT INTO feed_cmt (f_id, u_id, msg, time_stamp) VALUES ('$fid', '$id', '$msg', NOW())");
			$fcid = mysql_insert_id();
		
			if (isset($_POST['publicvis'])) {
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed_cmt_vis WHERE fc_id='$fcid' AND type='pub' AND sub_type IS NOT NULL LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO feed_cmt_vis (fc_id, type, sub_type, time_stamp) VALUES ('$fcid', 'pub', 'y', NOW())");
				}
			}
			
			if (isset($_POST['streamvis'])) {
				foreach ($_POST['streamvis'] as $streamvis) {
					$streamvis = escape_data($streamvis);
					if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed_cmt_vis WHERE fc_id='$fcid' AND type='strm' AND sub_type='$streamvis' LIMIT 1"), 0)<1) {
						$addvis = mysql_query("INSERT INTO feed_cmt_vis (fc_id, type, sub_type, time_stamp) VALUES ('$fcid', 'strm', '$streamvis', NOW())");
					}
				}
			}
			
			if (isset($_POST['chanvis'])) {
				foreach ($_POST['chanvis'] as $chanvis) {
					$chanvis = escape_data($chanvis);
					if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed_cmt_vis WHERE fc_id='$fcid' AND type='chan' AND ref_id='$chanvis' LIMIT 1"), 0)<1) {
						$addvis = mysql_query("INSERT INTO feed_cmt_vis (fc_id, type, ref_id, time_stamp) VALUES ('$fcid', 'chan', '$chanvis', NOW())");
					}
				}
			}
			
			$peeple = explode(",", $_POST['peeplenames']);
			if (isset($_POST['peeplenames'])) {
				foreach ($peeple as $visuid) {
					$visuid = escape_data($visuid);
					if (($visuid!=0)&&(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_cmt_vis WHERE fc_id='$fcid' AND type='user' AND ref_id='$visuid' LIMIT 1"), 0)<1)) {
						$addvis = mysql_query("INSERT INTO feed_cmt_vis (fc_id, type, ref_id, time_stamp) VALUES ('$fcid', 'user', '$visuid', NOW())");
					}
				}
			}
			
			//notification
			if ($uid!=$id) {
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$uid', 'feedcmt', '$id', '$fid', '$fcid', NOW())");
				$notifid = mysql_insert_id();
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$uid' AND cmt_myfeed='y' LIMIT 1"), 0)>0) {					
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$uid' LIMIT 1"), 0);
									
						//params
						$subject = returnpersonnameasid($id, $uid).' commented on your feed post';
						$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $uid).'</a> wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on your feed post.';
									
						include('../../../externals/general/emailer.php');
					}
			}
			
			//notify all other commenters
			$notif_uids = array();
			$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feed_cmt WHERE f_id='$fid' AND u_id!='$uid' AND u_id!='$id'");
			while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
				$mcuid = $msgcmt['u_id'];
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$mcuid', 'feedcmtx', '$id', '$fid', '$fcid', NOW())");
				$notifid = mysql_insert_id();
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND cmt_onfeed='y' LIMIT 1"), 0)>0) {					
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
									
						//params
						$subject = returnpersonnameasid($id, $mcuid).' also commented on '.returnpersonnameasid($uid, $mcuid).'\'s feed post';
						$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> also wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on '.returnpersonnameasid($uid, $mcuid).'\'s feed post.';
									
						include('../../../externals/general/emailer.php');
					}
				$notif_uids[] = $mcuid;
			}
			
			//notify all emos, likes
			$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feed_emo WHERE f_id='$fid' AND type='l' AND u_id!='$uid' AND u_id!='$id'");
			while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
				$mcuid = $msgcmt['u_id'];
				if (!in_array($mcuid, $notif_uids)) {
					$notif = mysql_query("INSERT INTO notifications (u_id, type, sub, s_id, ref_id, xref_id, time_stamp) VALUES ('$mcuid', 'feedcmtx', 'emo', '$id', '$fid', '$fcid', NOW())");
					$notifid = mysql_insert_id();
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND cmt_onfeed='y' LIMIT 1"), 0)>0) {					
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
										
							//params
							$subject = returnpersonnameasid($id, $mcuid).' commented on '.returnpersonnameasid($uid, $mcuid).'\'s feed post you liked';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on '.returnpersonnameasid($uid, $mcuid).'\'s feed post you liked.';
										
							include('../../../externals/general/emailer.php');
						}
				}
				$notif_uids[] = $mcuid;
			}
			
			//notify all emos, dislikes
			$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feed_emo WHERE f_id='$fid' AND type='d' AND u_id!='$uid' AND u_id!='$id'");
			while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
				$mcuid = $msgcmt['u_id'];
				if (!in_array($mcuid, $notif_uids)) {
					$notif = mysql_query("INSERT INTO notifications (u_id, type, sub, s_id, ref_id, xref_id, time_stamp) VALUES ('$mcuid', 'feedcmtx', 'emo', '$id', '$fid', '$fcid', NOW())");
					$notifid = mysql_insert_id();
					//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND cmt_onfeed='y' LIMIT 1"), 0)>0) {					
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
										
							//params
							$subject = returnpersonnameasid($id, $mcuid).' commented on '.returnpersonnameasid($uid, $mcuid).'\'s feed post you disliked';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on '.returnpersonnameasid($uid, $mcuid).'\'s feed post you disliked.';
										
							include('../../../externals/general/emailer.php');
						}
				}
				$notif_uids[] = $mcuid;
			}
		
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'feedcmts'.$fid.'\', \''.$baseincpat.'externalfiles/home/grabfeedcmts.php?id='.$fid.'\');", \'0\');
				</script>';
		} else {
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'feedcmts'.$fid.'\', \''.$baseincpat.'externalfiles/home/grabfeedcmts.php?id='.$fid.'\');", \'3200\');
				</script>';
			echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
			reporterror('home/postfeedcmt.php', 'writting msg', $errors);
		}
		
	} else {
	
	$myinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
	
	echo '<form action="'.$baseincpat.'externalfiles/home/postfeedcmt.php?action=iframe&id='.$fid.'" method="post">
		
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.substr($myinfo['defaultimg_url'], 0, -4).'m'.substr($myinfo['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="563px" style="padding-left: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="451px">
				<div align="left"><textarea id="msg" name="msg" cols="49" rows="2" onfocus="if (trim(this.value) == \'type here to comment on this...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type here to comment on this...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'cmtovertxtalrt\');" class="inputplaceholder">type here to comment on this...</textarea></div>
				<div id="cmtovertxtalrt" align="left" class="palert"></div>
					
				<div align="left" style="padding-top: 4px; font-size: 13px;">
					<table cellpadding="0" cellspacing="0" width="447px"><tr><td align="left" valign="top">
						<div align="left" id="visall"><table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'vis[all]\').get(\'checked\') == false){$(\'vis[cust]\').set(\'checked\',false); $(\'vis[all]\').set(\'checked\',true);}else{$(\'vis[cust]\').set(\'checked\',true); $(\'vis[all]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="vis[all]" name="vis[all]" value="frnd" onclick="if($(\'vis[all]\').get(\'checked\') == false){$(\'vis[cust]\').set(\'checked\',false); $(\'vis[all]\').set(\'checked\',true);}else{$(\'vis[cust]\').set(\'checked\',true); $(\'vis[all]\').set(\'checked\',false);}" CHECKED/></td><td align="left" style="padding-left: 4px;">this is visible to all peeple who can view main post</td></tr></table></div>
						<div align="left" id="viscustom"><table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'vis[cust]\').get(\'checked\') == false){$(\'vis[all]\').set(\'checked\',false); $(\'vis[cust]\').set(\'checked\',true); parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/editpostcmtvis.php?id='.$fid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});}else{$(\'vis[all]\').set(\'checked\',true); $(\'vis[cust]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="vis[cust]" name="vis[cust]" value="frnd" onclick="if($(\'vis[cust]\').get(\'checked\') == false){$(\'vis[all]\').set(\'checked\',false); $(\'vis[cust]\').set(\'checked\',true); parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/editpostcmtvis.php?id='.$fid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});}else{$(\'vis[all]\').set(\'checked\',true); $(\'vis[cust]\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">use custom visibility</td></tr></table></div>
					</td><td align="right" valign="top" style="padding-right: 6px;">
						<input type="submit" id="submit" value="edit visibiliy" name="visibiliy" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/editpostcmtvis.php?id='.$fid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
					</td></tr></table>
				</div>
				
				<div align="left" style="display: none;">
					<input type="checkbox" id="publicvis" name="publicvis" value="y"/>
					<input type="checkbox" id="streamvis[mb]" name="streamvis[mb]" value="mb"/>
					<input type="checkbox" id="streamvis[frnd]" name="streamvis[frnd]" value="frnd"/>
					<input type="checkbox" id="streamvis[fam]" name="streamvis[fam]" value="fam"/>
					<input type="checkbox" id="streamvis[prof]" name="streamvis[prof]" value="prof"/>
					<input type="checkbox" id="streamvis[edu]" name="streamvis[edu]" value="edu"/>
					<input type="checkbox" id="streamvis[aqu]" name="streamvis[aqu]" value="aqu"/>';
					//get channels
					$channels = @mysql_query("SELECT mpc_id, name FROM my_peeple_channels WHERE u_id='$id' ORDER BY name ASC");
					while ($channel = @mysql_fetch_array ($channels, MYSQL_ASSOC)) {
						echo '<input type="checkbox" id="chanvis['.$channel['mpc_id'].']" name="chanvis['.$channel['mpc_id'].']" value="'.$channel['mpc_id'].'"'; if(in_array($channel['mpc_id'], $plchans)){echo' CHECKED';} echo'/>';
					}
					echo '<input type="text" name="peeplenames" value="" id="form_peeplenames_input"/>
				</div>
					
			</td><td align="left" valign="bottom" width="100px" style="padding-left: 2px;">
				<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
				<div id="btngrp" align="left" style="padding-bottom: 7px;">
					<div id="btnsbmt"><input type="submit" id="submit" value="share" name="send" onclick="$(\'btngrp\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/></div>
				</div>
			</td></tr></table>
		</td></tr></table>
	</div>
	
	</form>';
	}
	
	include ('../../../externals/header/footer-iframe.php');

} else {
	echo '<iframe width="100%" height="100px" align="center" id="feedwritecmt'.$fid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/home/postfeedcmt.php?action=iframe&id='.$fid.'"></iframe>';
}

} else { //if not vis
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>