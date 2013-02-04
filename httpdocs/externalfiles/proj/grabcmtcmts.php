<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses2 = true;
}

if (!isset($cmctid)) {
	$cmctid = escape_data($_GET['id']);
	$cpid = mysql_result(mysql_query("SELECT cp_id FROM commprojcmt_threads WHERE cmct_id='$cmctid' LIMIT 1"), 0);
}

if ($id>0) {//test vis

$msgcmts = mysql_query ("SELECT m.cmcc_id, m.u_id, m.msg, u.defaultimg_url, DATE_FORMAT(m.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM commprojcmt_cmts m INNER JOIN users u ON u.user_id=m.u_id WHERE m.cmct_id='$cmctid' ORDER BY m.time_stamp ASC");
while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
	echo '<div align="left" style="padding-top: 12px;" id="cpcmtcid'.$msgcmt['cmcc_id'].'"';
			if (($msgcmt['u_id']==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
				echo ' onmouseover="$(\'btndeletemsg'.$msgcmt['cmcc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletemsg'.$msgcmt['cmcc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.''.substr($msgcmt['defaultimg_url'], 0, -4).'m'.substr($msgcmt['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="397px" style="padding-left: 12px;">
			<div align="left" class="p18">'.nl2br($msgcmt['msg']).'</div>
			<div class="subtext">by '; loadpersonname($msgcmt['u_id']); echo' on '.$msgcmt['time'].'</div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
			if (($msgcmt['u_id']==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
				echo '<div id="btndeletemsg'.$msgcmt['cmcc_id'].'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/proj/deletecmtcmt.php?id='.$msgcmt['cmcc_id'].'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
			}
		echo '</td></tr></table>
	</div>';
}

//writer
echo '<div align="left" style="margin-top: 12px; width: 400px; " class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'msgwrite'.$cmctid.'\', \'align\': \'left\'});newElem.inject($(\'msgcmts'.$cmctid.'\'), \'bottom\');gotopage(\'msgwrite'.$cmctid.'\', \''.$baseincpat.'externalfiles/proj/writecmtcmt.php?id='.$cmctid.'\');">
		click here to comment on this.
</div>';

} else { //unable to view
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must login to use this feature.
	</div>';
}

if (isset($minses2)) {
	session_write_close();
	exit();	
}
?>