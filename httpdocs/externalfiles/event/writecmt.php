<?php
require_once ('../../../externals/general/functions.php');

$eid = escape_data($_GET['id']);

$ifname = 'cmtwritecmt'.$eid;
include ('../../../externals/header/header-iframe.php');

//test vis
$einfo = mysql_fetch_array (mysql_query ("SELECT vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
if (($einfo['vis']=='pub')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0)) {

if (isset($_POST['send'])) {
//save
	
	$errors = NULL;
	
	if (isset($_POST['msg']) && ($_POST['msg'] != 'type here to comment on this event...')) {
		$msg = escape_form_data($_POST['msg']);
	} else {
		$errors[] = 'no msg content';
	}
	
	if (empty($errors)) {
		$createthread = mysql_query("INSERT INTO eventcmt_threads (e_id, u_id, msg, time_stamp) VALUES ('$eid', '$id', '$msg', NOW())");
		$ectid = mysql_insert_id();
		
		//notifications (only to event owners)
			$einfo = mysql_fetch_array(mysql_query ("SELECT name FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
		$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM event_owners WHERE e_id='$eid' AND type='a' AND u_id!='$id'");
		while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
			$mcuid = $msgcmt['u_id'];
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$mcuid', 'evntmcmt', '$id', '$eid', '$ectid', NOW())");
			$notifid = mysql_insert_id();
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND admn_evntcmt='y' LIMIT 1"), 0)>0) {
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $mcuid).' commented on your event';
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on your event "<a href="'.$baseincpat.'event.php?id='.$eid.'">'.$einfo['name'].'</a>"';
								
					include('../../../externals/general/emailer.php');
				}
		}
		
		echo '<script type="text/javascript">
				setTimeout("parent.gotopage(\'cmtsmain\', \''.$baseincpat.'externalfiles/event/grabcmts.php?id='.$eid.'\');", \'0\');
			</script>';
	} else {
		echo '<script type="text/javascript">
				setTimeout("parent.gotopage(\'cmtsmain\', \''.$baseincpat.'externalfiles/event/grabcmts.php?id='.$eid.'\');", \'3200\');
			</script>';
		echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('event/writecmt.php', 'writting msg', $errors);
	}
	
} else {

$myinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);

echo '<form action="'.$baseincpat.'externalfiles/event/writecmt.php?id='.$eid.'" method="post">
	
<div align="left" style="padding-bottom: 20px;">

	<div align="left">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><img src="'.$baseincpat.''.$myinfo['defaultimg_url'].'" /></td><td align="left" valign="top" width="458px" style="padding-left: 12px;">
			<div align="left">
				<textarea name="msg" cols="50" rows="2" onfocus="if (trim(this.value) == \'type here to comment on this event...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type here to comment on this event...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'cmtovertxtalrt\');" class="inputplaceholder">type here to comment on this event...</textarea>
				<div id="cmtovertxtalrt" align="left" class="palert"></div>
			</div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">
			<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
			<div id="btngrp" align="left">
				<div id="btnsbmt" style="margin-top: 36px;"><input type="submit" id="submit" value="post" name="send" onclick="$(\'btngrp\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/></div>
			</div>
		</td></tr></table>
	</div>
</div>
</form>';
}

} else { //unable to view private event
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		This is a private event. You must be invited to be able to view it.
	</div>';
}

include ('../../../externals/header/footer-iframe.php');
?>