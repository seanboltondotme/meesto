<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$aid = escape_data($_GET['aid']);	
$apid = escape_data($_GET['apid']);

$uid = mysql_result(mysql_query("SELECT pa.u_id FROM photo_albums pa INNER JOIN album_photos ap ON ap.pa_id=pa.pa_id AND ap.ap_id='$apid' LIMIT 1"), 0);

if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='strm' AND ps.stream=apv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='chan' AND apv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) { //allow vis if tagged !important

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {

	$ifname = 'pa'.$aid.'cmtwrite'.$apid;
	$pdrjs = '$(\'msg\').focus();';
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
			$createthread = mysql_query("INSERT INTO ap_cmts (ap_id, u_id, msg, time_stamp) VALUES ('$apid', '$id', '$msg', NOW())");
			$apcid = mysql_insert_id();
			
			//notification
				$painfo = mysql_fetch_array(mysql_query ("SELECT name FROM photo_albums WHERE pa_id='$aid' LIMIT 1"), MYSQL_ASSOC);
			if ($uid!=$id) {
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$uid', 'apcmt', '$id', '$apid', '$apcid', NOW())");
				$notifid = mysql_insert_id();
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$uid' AND cmt_myphoto='y' LIMIT 1"), 0)>0) {				
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$uid' LIMIT 1"), 0);
									
						//params
						$subject = returnpersonnameasid($id, $uid).' commented on your album photo';
						$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $uid).'</a> wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a photo in your album "'.$painfo['name'].'"</a>.';
									
						include('../../../externals/general/emailer.php');
					}
			}
			
			//notify all other commenters
			$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM ap_cmts WHERE ap_id='$apid' AND u_id!='$uid' AND u_id!='$id'");
			while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
				$mcuid = $msgcmt['u_id'];
				//need to test to see if still visible to this person !important
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$mcuid', 'apcmtx', '$id', '$apid', '$apcid', NOW())");
				$notifid = mysql_insert_id();
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND cmt_onphoto='y' LIMIT 1"), 0)>0) {				
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
									
						//params
						$subject = returnpersonnameasid($id, $mcuid).' also commented on a album photo you commented on';
						$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> also wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a photo in the album "'.$painfo['name'].'"</a>.';
									
						include('../../../externals/general/emailer.php');
					}
			}
		
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'apcmts'.$apid.'\', \''.$baseincpat.'externalfiles/photos/grabpacmts.php?aid='.$aid.'&apid='.$apid.'\');", \'0\');
				</script>';
		} else {
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'apcmts'.$apid.'\', \''.$baseincpat.'externalfiles/photos/grabpacmts.php?aid='.$aid.'&apid='.$apid.'\');", \'3200\');
				</script>';
			echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
			reporterror('photos/writemtcmt.php', 'writting msg', $errors);
		}
		
	} else {
	
	$myinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
	
	echo '<form action="'.$baseincpat.'externalfiles/photos/writepacmt.php?action=iframe&aid='.$aid.'&apid='.$apid.'" method="post">
		
		<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.substr($myinfo['defaultimg_url'], 0, -4).'m'.substr($myinfo['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="500px" style="padding-left: 12px;">
				<textarea id="msg" name="msg" cols="56" rows="2" onfocus="if (trim(this.value) == \'type here to comment on this...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type here to comment on this...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'cmtovertxtalrt\');" class="inputplaceholder">type here to comment on this...</textarea>
				<div id="cmtovertxtalrt" align="left" class="palert"></div>
			</td><td align="left" valign="bottom" width="110px" style="padding-left: 16px;">
				<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
				<div id="btngrp" align="left">
					<div id="btnsbmt" style="margin-top: 12px;"><input type="submit" id="submit" value="post" name="send" onclick="$(\'btngrp\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/></div>
				</div>
			</td></tr></table>
		</div>
	
	</form>';
	}
	
	include ('../../../externals/header/footer-iframe.php');

} else {
	echo '<iframe width="100%" height="100px" align="center" id="pa'.$aid.'cmtwrite'.$apid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/photos/writepacmt.php?action=iframe&aid='.$aid.'&apid='.$apid.'"></iframe>';
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