<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$fdbkid = escape_data($_GET['id']);

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {

	$ifname = 'projconvocmtwritecmt'.$fdbkid;
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
			$createthread = mysql_query("INSERT INTO feedback_cmts (fdbk_id, u_id, msg, time_stamp) VALUES ('$fdbkid', '$id', '$msg', NOW())");
			$fdbkcid = mysql_insert_id();
			
			//notifications
			$uid = mysql_result(mysql_query ("SELECT u_id FROM feedback WHERE fdbk_id='$fdbkid' LIMIT 1"), 0);
			if ($uid!=$id) {
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$uid', 'fdbkcmt', '$id', '$fdbkid', '$fdbkcid', NOW())");
				$notifid = mysql_insert_id();
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$uid' AND cmt_myfdbk='y' LIMIT 1"), 0)>0) {				
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$uid' LIMIT 1"), 0);
									
						//params
						$subject = returnpersonnameasid($id, $uid).' commented on your feedback';
						$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $uid).'</a> wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on your <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">feedback</a>.';
									
						include('../../../externals/general/emailer.php');
					}
			}
			
				//notify all other commenters
				$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feedback_cmts WHERE fdbk_id='$fdbkid' AND u_id!='$uid' AND u_id!='$id'");
				while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
					$mcuid = $msgcmt['u_id'];
					$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$mcuid', 'fdbkcmtx', '$id', '$fdbkid', '$fdbkcid', NOW())");
					$notifid = mysql_insert_id();
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND cmt_onfdbk='y' LIMIT 1"), 0)>0) {				
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
										
							//params
							$subject = returnpersonnameasid($id, $mcuid).' also commented on feedback you commented on';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> also wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">feedback you commented on</a>.';
										
							include('../../../externals/general/emailer.php');
						}
				}
			
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'fdbkcmts'.$fdbkid.'\', \''.$baseincpat.'externalfiles/community/grabfdbkcmts.php?id='.$fdbkid.'\');", \'0\');
				</script>';
		} else {
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'fdbkcmts'.$fdbkid.'\', \''.$baseincpat.'externalfiles/community/grabfdbkcmts.php?id='.$fdbkid.'\');", \'3200\');
				</script>';
			echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
			reporterror('community/writefdbkcmt.php', 'writting msg', $errors);
		}
		
	} else {
	
	$myinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
	
	echo '<form action="'.$baseincpat.'externalfiles/community/writefdbkcmt.php?action=iframe&id='.$fdbkid.'" method="post">
		
		<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.substr($myinfo['defaultimg_url'], 0, -4).'m'.substr($myinfo['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="321px" style="padding-left: 12px;">
				<div align="left" class="subtext" style="padding-bottom: 2px;">This is visible to the community &mdash; it\'s public.</div>
				<div align="left"><textarea id="msg" name="msg" cols="34" rows="2" onfocus="if (trim(this.value) == \'type here to comment on this...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type here to comment on this...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'cmtovertxtalrt\');" class="inputplaceholder">type here to comment on this...</textarea></div>
				<div id="cmtovertxtalrt" align="left" class="palert"></div>
			</td><td align="left" valign="bottom" width="90px" style="padding-left: 16px;">
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
	echo '<iframe width="100%" height="100px" align="center" id="projconvocmtwritecmt'.$fdbkid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/community/writefdbkcmt.php?action=iframe&id='.$fdbkid.'"></iframe>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>