<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($cpid)) {
	$cpid = escape_data($_GET['id']);	
}

if ($id>0) {//test vis

	if (isset($_GET['vid'])&&is_numeric($_GET['vid'])) {
		$vid = escape_data($_GET['vid']);
		$viewsingle = true;
		echo '<div align="left" style="padding-bottom: 22px;">
			<input type="button" value="view all" onclick="window.location.href=\''.$baseincpat.'proj.php?id='.$cpid.'\';"/>
		</div>';
		if (isset($_GET['vt'])&&($_GET['vt']=='x')) {
			$vectid = mysql_result (mysql_query("SELECT cmct_id FROM commprojcmt_cmts WHERE cmcc_id='$vid' LIMIT 1"), 0);
			$msgs = mysql_query ("SELECT cmct_id, u_id, msg, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM commprojcmt_threads WHERE cmct_id='$vectid' LIMIT 1");
		} else {
			$msgs = mysql_query ("SELECT cmct_id, u_id, msg, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM commprojcmt_threads WHERE cmct_id='$vid' LIMIT 1");
		}
	} else {
		$viewsingle = false;
		echo '<div align="left">
			<iframe width="100%" height="200px" align="center" id="projcmtwritecmt'.$cpid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/proj/writecmt.php?id='.$cpid.'"></iframe>
		</div>';
		$msgs = mysql_query ("SELECT cmct_id, u_id, msg, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM commprojcmt_threads WHERE cp_id='$cpid' ORDER BY time_stamp DESC");
	}

while ($msg = mysql_fetch_array ($msgs, MYSQL_ASSOC)) {
	$cmctid = $msg['cmct_id'];
	$msgsid = $msg['u_id'];
	echo '<div align="left"';
			if (($msgsid==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
				echo ' onmouseover="$(\'btndeletethrd'.$cmctid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletethrd'.$cmctid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><a href="'.$baseincpat.'meefile.php?id='.$msgsid.'"><img src="'.$baseincpat.''.mysql_result (mysql_query("SELECT defaultimg_url FROM users WHERE user_id='$msgsid' LIMIT 1"), 0).'" /></a></td><td align="left" valign="top" width="458px" style="padding-left: 12px;">
			<div align="left" class="p24">'.nl2br($msg['msg']).'</div>
			<div class="subtext"><table cellpadding="0" cellspacing="0"><tr><td align="left">by '; loadpersonname($msgsid); echo' on '.$msg['time'].'</td><td align="left">';
			//test for messages in thread
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM commprojcmt_cmts WHERE cmct_id='$cmctid' LIMIT 1"), 0)<1) {
				echo '<div align="left" class="postoptlink" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'msgwrite'.$cmctid.'\', \'align\': \'left\'});newElem.inject($(\'msgcmts'.$cmctid.'\'), \'bottom\');gotopage(\'msgwrite'.$cmctid.'\', \''.$baseincpat.'externalfiles/proj/writecmtcmt.php?id='.$cmctid.'\');"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" class="subtext" style="padding-left: 6px; padding-right: 6px;">|</td><td align="left" valign="center"><div align="left" class="postoptlinkmrkr"></div></td><td align="left" valign="center" style="padding-left: 4px;">comment</td></tr></table></div>';
			}
			echo '</td></tr></table></div></td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
			if (($msgsid==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
				echo '<div id="btndeletethrd'.$cmctid.'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/proj/deletecmt.php?id='.$cmctid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
			}
		echo '</td></tr></table>
	</div><div align="left" style="padding-left: 62px; padding-bottom: 24px;"><div align="left" id="msgcmts'.$cmctid.'" style="padding-left: 12px; border-left: 1px solid #C5C5C5;">';
		//test for messages in thread
		if (mysql_result (mysql_query("SELECT COUNT(*) FROM commprojcmt_cmts WHERE cmct_id='$cmctid' LIMIT 1"), 0)>0) {
			include('grabcmtcmts.php');	
		}
	echo '</div></div>';
}

} else { //unable to view
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must login to use this feature.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>