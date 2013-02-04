<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($eid)) {
	$eid = escape_data($_GET['id']);	
}

//test vis
$einfo = mysql_fetch_array (mysql_query ("SELECT vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
if (($einfo['vis']=='pub')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0)) {

	if (isset($_GET['vid'])&&is_numeric($_GET['vid'])) {
		$vid = escape_data($_GET['vid']);
		$viewsingle = true;
		echo '<div align="left" style="padding-bottom: 22px;">
			<input type="button" value="view all" onclick="window.location.href=\''.$baseincpat.'event.php?id='.$eid.'\';"/>
		</div>';
		if (isset($_GET['vt'])&&($_GET['vt']=='x')) {
			$vectid = mysql_result (mysql_query("SELECT ect_id FROM eventcmt_cmts WHERE ecc_id='$vid' LIMIT 1"), 0);
			$msgs = mysql_query ("SELECT ect_id, u_id, msg, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM eventcmt_threads WHERE ect_id='$vectid' LIMIT 1");
		} else {
			$msgs = mysql_query ("SELECT ect_id, u_id, msg, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM eventcmt_threads WHERE ect_id='$vid' LIMIT 1");
		}
	} else {
		$viewsingle = false;
		echo '<div align="left">
			<iframe width="100%" height="200px" align="center" id="cmtwritecmt'.$eid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/event/writecmt.php?id='.$eid.'"></iframe>
		</div>';
		$msgs = mysql_query ("SELECT ect_id, u_id, msg, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM eventcmt_threads WHERE e_id='$eid' ORDER BY time_stamp DESC");
	}

while ($msg = mysql_fetch_array ($msgs, MYSQL_ASSOC)) {
	$ectid = $msg['ect_id'];
	$msgsid = $msg['u_id'];
	echo '<div align="left"';
			if (($msgsid==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
				echo ' onmouseover="$(\'btndeletethrd'.$ectid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletethrd'.$ectid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><a href="'.$baseincpat.'meefile.php?id='.$msgsid.'"><img src="'.$baseincpat.''.mysql_result (mysql_query("SELECT defaultimg_url FROM users WHERE user_id='$msgsid' LIMIT 1"), 0).'" /></a></td><td align="left" valign="top" width="458px" style="padding-left: 12px;">
			<div align="left" class="p24">'.nl2br($msg['msg']).'</div>
			<div class="subtext"><table cellpadding="0" cellspacing="0"><tr><td align="left">by '; loadpersonname($msgsid); echo' on '.$msg['time'].'</td><td align="left">';
			//test for messages in thread
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM eventcmt_cmts WHERE ect_id='$ectid' LIMIT 1"), 0)<1) {
				echo '<div align="left" class="postoptlink" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'msgwrite'.$ectid.'\', \'align\': \'left\'});newElem.inject($(\'msgcmts'.$ectid.'\'), \'bottom\');gotopage(\'msgwrite'.$ectid.'\', \''.$baseincpat.'externalfiles/event/writecmtcmt.php?id='.$ectid.'\');"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" class="subtext" style="padding-left: 6px; padding-right: 6px;">|</td><td align="left" valign="center"><div align="left" class="postoptlinkmrkr"></div></td><td align="left" valign="center" style="padding-left: 4px;">comment</td></tr></table></div>';
			}
			echo '</td></tr></table></div></td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
			if (($msgsid==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
				echo '<div id="btndeletethrd'.$ectid.'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/deletecmt.php?id='.$ectid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
			}
		echo '</td></tr></table>
	</div><div align="left" style="padding-left: 62px; padding-bottom: 24px;"><div align="left" id="msgcmts'.$ectid.'" style="padding-left: 12px; border-left: 1px solid #C5C5C5;">';
		//test for messages in thread
		if (mysql_result (mysql_query("SELECT COUNT(*) FROM eventcmt_cmts WHERE ect_id='$ectid' LIMIT 1"), 0)>0) {
			include('grabcmtcmts.php');	
		}
	echo '</div></div>';
}

} else { //unable to view private event
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		This is a private event. You must be invited to be able to view it.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>