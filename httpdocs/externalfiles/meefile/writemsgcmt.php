<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');

$tid = escape_data($_GET['id']);

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {
	
	$pdrjs = '$(\'msg\').focus();';
	$ifname = 'convomsgwritecmt'.$tid;
	include ('../../../externals/header/header-iframe.php');
	
	if (isset($_POST['send'])) {
	//save
		
		$errors = NULL;
		
		if (isset($_POST['msg']) && ($_POST['msg'] != 'type here to reply to this message...')) {
			$msg = escape_form_data($_POST['msg']);
		} else {
			$errors[] = 'no msg content';
		}
		
		if (empty($errors)) {
			$createthread = mysql_query("INSERT INTO msgs (t_id, u_id, msg, time_stamp) VALUES ('$tid', '$id', '$msg', NOW())");
			$mid = mysql_insert_id();
			
			//make notifs
			$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM msg_owners WHERE t_id='$tid' AND u_id!='$id'");
			while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
				$mcuid = $msgcmt['u_id'];
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$mcuid', 'msgcmt', '$id', '$tid', '$mid', NOW())");
				$notifid = mysql_insert_id();
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND msg='y' LIMIT 1"), 0)>0) {				
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
											
						//params
						$subject = returnpersonnameasid($id, $mcuid).' replied to your message';
						$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> replied to <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">your message</a>.<br /><br />"'.$msg.'"';
											
						include('../../../externals/general/emailer.php');
					}
			}
			
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'msgcmts'.$tid.'\', \''.$baseincpat.'externalfiles/meefile/grabmsgcmts.php?id='.$tid.'\');", \'0\');
				</script>';
		} else {
			echo '<script type="text/javascript">
					setTimeout("parent.gotopage(\'msgcmts'.$tid.'\', \''.$baseincpat.'externalfiles/meefile/grabmsgcmts.php?id='.$tid.'\');", \'3200\');
				</script>';
			echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
			reporterror('meefile/writemsgcmt.php', 'writting msg', $errors);
		}
		
	} else {
	
	$myinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
	
	echo '<form action="'.$baseincpat.'externalfiles/meefile/writemsgcmt.php?action=iframe&id='.$tid.'" method="post">
		
		<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.substr($myinfo['defaultimg_url'], 0, -4).'m'.substr($myinfo['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="397px" style="padding-left: 12px;">
				<textarea name="msg" id="msg" cols="42" rows="2" onfocus="if (trim(this.value) == \'type here to reply to this message...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type here to reply to this message...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'msgovertxtalrt\');" class="inputplaceholder">type here to reply to this message...</textarea>
				<div id="msgovertxtalrt" align="left" class="palert"></div>
			</td><td align="left" valign="bottom" width="110px" style="padding-left: 16px;">
				<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
				<div id="btngrp" align="left">
					<div id="btnsbmt" style="margin-top: 12px;"><input type="submit" id="submit" value="send" name="send" onclick="$(\'btngrp\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/></div>
				</div>
			</td></tr></table>
		</div>
	
	</form>';
	}
	
	include ('../../../externals/header/footer-iframe.php');

} else {
	echo '<iframe width="100%" height="100px" align="center" id="convomsgwritecmt'.$tid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/meefile/writemsgcmt.php?action=iframe&id='.$tid.'"></iframe>';
}
?>