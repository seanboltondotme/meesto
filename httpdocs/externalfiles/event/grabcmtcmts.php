<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses2 = true;
}

if (!isset($ectid)) {
	$ectid = escape_data($_GET['id']);	
	$ecinfo = mysql_fetch_array (mysql_query ("SELECT e_id FROM eventcmt_threads WHERE ect_id='$ectid' LIMIT 1"), MYSQL_ASSOC);
	$eid = $ecinfo['e_id'];	
}

//test vis
$einfo = mysql_fetch_array (mysql_query ("SELECT vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
if (($einfo['vis']=='pub')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0)) {

$msgcmts = mysql_query ("SELECT m.ecc_id, m.u_id, m.msg, u.defaultimg_url, DATE_FORMAT(m.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM eventcmt_cmts m INNER JOIN users u ON u.user_id=m.u_id WHERE m.ect_id='$ectid' ORDER BY m.time_stamp ASC");
while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
	echo '<div align="left" id="ecmtcid'.$msgcmt['ecc_id'].'" style="padding-top: 12px;"';
			if (($msgcmt['u_id']==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
				echo ' onmouseover="$(\'btndeletemsg'.$msgcmt['ecc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletemsg'.$msgcmt['ecc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.''.substr($msgcmt['defaultimg_url'], 0, -4).'m'.substr($msgcmt['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="397px" style="padding-left: 12px;">
			<div align="left" class="p18">'.nl2br($msgcmt['msg']).'</div>
			<div class="subtext">by '; loadpersonname($msgcmt['u_id']); echo' on '.$msgcmt['time'].'</div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
			if (($msgcmt['u_id']==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
				echo '<div id="btndeletemsg'.$msgcmt['ecc_id'].'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/deletecmtcmt.php?id='.$msgcmt['ecc_id'].'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
			}
		echo '</td></tr></table>
	</div>';
}

//writer
echo '<div align="left" style="margin-top: 12px; width: 400px; " class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'msgwrite'.$ectid.'\', \'align\': \'left\'});newElem.inject($(\'msgcmts'.$ectid.'\'), \'bottom\');gotopage(\'msgwrite'.$ectid.'\', \''.$baseincpat.'externalfiles/event/writecmtcmt.php?id='.$ectid.'\');">
		click here to comment on this.
</div>';

} else { //unable to view private event
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		This is a private event. You must be invited to be able to view it.
	</div>';
}

if (isset($minses2)) {
	session_write_close();
	exit();	
}
?>