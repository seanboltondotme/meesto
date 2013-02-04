<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$htid = escape_data($_GET['id']);

if ($id>0) {//test vis

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {

	$ifname = 'answrwrite'.$htid;
	$pdrjs = '$(\'msg\').focus();';
	include ('../../../externals/header/header-iframe.php');
	
	if (isset($_POST['send'])) {
	//save
		
		$errors = NULL;
		
		if (isset($_POST['msg']) && ($_POST['msg'] != 'type here to answer this...')) {
			$msg = escape_form_data($_POST['msg']);
		} else {
			$errors[] = 'no msg content';
		}
		
		if (empty($errors)) {
			$createmsg = mysql_query("INSERT INTO help_thread_msgs (ht_id, u_id, msg, time_stamp) VALUES ('$htid', '$id', '$msg', NOW())");
			$htmid = mysql_insert_id();
			
			//notification
			$uid = mysql_result(mysql_query ("SELECT u_id FROM help_threads WHERE ht_id='$htid' LIMIT 1"), 0);
			if ($uid!=$id) {
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$uid', 'helprep', '$id', '$htid', NOW())");
				
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$uid' AND meesto_help_resp='y' LIMIT 1"), 0)>0) {
						
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$uid' LIMIT 1"), 0);
									
						//params
						$subject = 'Your help question was responded to';
						$emailercontent = '<a href="'.$baseincpat.'help.php?htid='.$htid.'">Your help question</a> was responded to.';
									
						include('../../../externals/general/emailer.php');
					}
			}
		
			echo '<script type="text/javascript">
					setTimeout("parent.location.reload();", \'0\');
				</script>';
		} else {
			echo '<script type="text/javascript">
					setTimeout("parent.location.reload();", \'3200\');
				</script>';
			echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
			reporterror('help/answer.php', 'writting msg', $errors);
		}
		
	} else {
	
	$myinfo = mysql_fetch_array (mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
	
	echo '<form action="'.$baseincpat.'externalfiles/help/answer.php?action=iframe&id='.$htid.'" method="post">
		
		<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.substr($myinfo['defaultimg_url'], 0, -4).'m'.substr($myinfo['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="397px" style="padding-left: 12px;">
				<textarea id="msg" name="msg" cols="44" rows="3" onfocus="if (trim(this.value) == \'type here to answer this...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type here to answer this...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'cmtovertxtalrt\');" class="inputplaceholder">type here to answer this...</textarea>
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
	echo '<iframe width="100%" height="100px" align="center" id="answrwrite'.$htid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/help/answer.php?action=iframe&id='.$htid.'"></iframe>';
}

} else { //if not event admin
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must login to use this feature.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>