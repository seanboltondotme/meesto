<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$mtsid = escape_data($_GET['id']);
$uid = mysql_result(mysql_query ("SELECT mt.u_id FROM meefile_tab_sec mts INNER JOIN meefile_tab mt ON mt.mt_id=mts.mt_id WHERE mts.mts_id='$mtsid' LIMIT 1"), 0);

//test vis
if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$mtsid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$mtsid'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$mtsid'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$mtsid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {

	$ifname = 'mtswritecmt'.$mtsid;
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
			$createthread = mysql_query("INSERT INTO meefile_tab_cmts (mts_id, u_id, msg, time_stamp) VALUES ('$mtsid', '$id', '$msg', NOW())");
			$mtcid = mysql_insert_id();
			
			//notification
				$mtsinfo = mysql_fetch_array(mysql_query ("SELECT mt.name, mts.title FROM meefile_tab_sec mts INNER JOIN meefile_tab mt ON mt.mt_id=mts.mt_id WHERE mts.mts_id='$mtsid' LIMIT 1"), MYSQL_ASSOC);
			if ($uid!=$id) {
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$uid', 'mtscmt', '$id', '$mtsid', '$mtcid', NOW())");
				$notifid = mysql_insert_id();
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$uid' AND cmt_mymtsec='y' LIMIT 1"), 0)>0) {				
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$uid' LIMIT 1"), 0);
									
						//params
						$subject = returnpersonnameasid($id, $uid).' commented on "'.$mtsinfo['title'].'"';
						$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $uid).'</a> wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">"'.$mtsinfo['title'].'" in your "'.$mtsinfo['name'].'" Meefile tab</a>.';
									
						include('../../../externals/general/emailer.php');
					}
			}
			
			//notify all other commenters
			$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM meefile_tab_cmts WHERE mts_id='$mtsid' AND u_id!='$uid' AND u_id!='$id'");
			while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
				$mcuid = $msgcmt['u_id'];
				//need to test to see if still visible to this person !important
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$mcuid', 'mtscmtx', '$id', '$mtsid', '$mtcid', NOW())");
				$notifid = mysql_insert_id();
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND cmt_onmtsec='y' LIMIT 1"), 0)>0) {				
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
									
						//params
						$subject = returnpersonnameasid($id, $mcuid).' also commented on "'.$mtsinfo['title'].'"';
						$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> also wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">"'.$mtsinfo['title'].'" in '.returnpersonnameasid($uid, $mcuid).'\'s Meefile tab "'.$mtsinfo['name'].'"</a>.';
									
						include('../../../externals/general/emailer.php');
					}
			}
		
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'msgcmts'.$mtsid.'\', \''.$baseincpat.'externalfiles/meefile/grabmtcmts.php?id='.$mtsid.'\');", \'0\');
				</script>';
		} else {
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'msgcmts'.$mtsid.'\', \''.$baseincpat.'externalfiles/meefile/grabmtcmts.php?id='.$mtsid.'\');", \'3200\');
				</script>';
			echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
			reporterror('meefile/writemtcmt.php', 'writting msg', $errors);
		}
		
	} else {
	
	$myinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
	
	echo '<form action="'.$baseincpat.'externalfiles/meefile/writemtcmt.php?action=iframe&id='.$mtsid.'" method="post">
		
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
	echo '<iframe width="100%" height="100px" align="center" id="mtswritecmt'.$mtsid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/meefile/writemtcmt.php?action=iframe&id='.$mtsid.'"></iframe>';
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