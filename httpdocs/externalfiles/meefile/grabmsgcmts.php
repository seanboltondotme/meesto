<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses3 = true;
}

if (!isset($tid)) {
	$tid = escape_data($_GET['id']);	
}

$msgcmts = mysql_query ("SELECT m.m_id, m.u_id, m.msg, u.defaultimg_url, DATE_FORMAT(m.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM msgs m INNER JOIN users u ON u.user_id=m.u_id WHERE m.t_id='$tid' ORDER BY m.time_stamp ASC");
while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
	echo '<div align="left" style="padding-top: 12px;"';
			/*if ($msgcmt['u_id']==$id) {
				echo ' onmouseover="$(\'btndeletemsg'.$msgcmt['m_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletemsg'.$msgcmt['m_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}*/
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><a href="'.$baseincpat.'meefile.php?id='.$msgcmt['u_id'].'"><img src="'.$baseincpat.''.substr($msgcmt['defaultimg_url'], 0, -4).'m'.substr($msgcmt['defaultimg_url'], -4).'" /></a></td><td align="left" valign="top" width="397px" style="padding-left: 12px;">
			<div align="left" class="p18">'.nl2br($msgcmt['msg']).'</div>
			<div class="subtext">by '; loadpersonname($msgcmt['u_id']); echo' on '.$msgcmt['time'].'</div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
			/*if ($msgcmt['u_id']==$id) {
				echo '<div id="btndeletemsg'.$msgcmt['m_id'].'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick=""/></div>';
			}*/
		echo '</td></tr></table>
	</div>';
}

//writer
echo '<div align="left" style="margin-top: 12px; width: 400px; " class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'msgwrite'.$tid.'\', \'align\': \'left\'});newElem.inject($(\'msgcmts'.$tid.'\'), \'bottom\');gotopage(\'msgwrite'.$tid.'\', \''.$baseincpat.'externalfiles/meefile/writemsgcmt.php?id='.$tid.'\');">
		click here to reply to this.
</div>';

if (isset($minses3)) {
	session_write_close();
	exit();	
}
?>