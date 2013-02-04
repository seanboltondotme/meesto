<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($htid)) {
	$htid = escape_data($_GET['htid']);
}

$htinfo = mysql_fetch_array (mysql_query("SELECT ht_id, msg, a_id FROM help_threads WHERE ht_id='$htid' LIMIT 1"), MYSQL_ASSOC);

echo '<div align="left" class="p24" style="margin-top: 24px; margin-bottom: 12px;">
	Q: '.$htinfo['msg'].'</div>
</div>';
if ($htinfo['a_id']!='') {
	$aid = $htinfo['a_id'];
	$ainfo = mysql_fetch_array (mysql_query("SELECT htm_id, msg FROM help_threads WHERE ht_id='$aid' LIMIT 1"), MYSQL_ASSOC);
	echo '<div align="left" class="p24" style="margin-top: 4px; margin-bottom: 12px;">
		A: '.$ainfo['msg'].'</div>
	</div>';
}

echo '<div id="maincontent" style="margin-top: 18px; margin-left: 32px;">';

$msgcmts = mysql_query ("SELECT m.htm_id, m.u_id, m.msg, u.defaultimg_url, DATE_FORMAT(m.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM help_thread_msgs m INNER JOIN users u ON u.user_id=m.u_id WHERE m.ht_id='$htid' ORDER BY m.time_stamp ASC");
while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
	echo '<div align="left" style="padding-top: 12px;" id="cpcmtcid'.$msgcmt['cmcc_id'].'"';
			if (($msgcmt['u_id']==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
				echo ' onmouseover="$(\'btndeletemsg'.$msgcmt['cmcc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletemsg'.$msgcmt['cmcc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.''.substr($msgcmt['defaultimg_url'], 0, -4).'m'.substr($msgcmt['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="397px" style="padding-left: 12px;">
			<div align="left" class="p18">'.nl2br($msgcmt['msg']).'</div>
			<div class="subtext">by '; loadpersonname($msgcmt['u_id']); echo' on '.$msgcmt['time'].'</div>
		</td></tr></table>
	</div>';
}

//writer
echo '<div align="left" style="margin-top: 12px; width: 400px; " class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'answrwrite'.$htid.'cont\', \'align\': \'left\'});newElem.inject($(\'maincontent\'), \'bottom\');gotopage(\'answrwrite'.$htid.'cont\', \''.$baseincpat.'externalfiles/help/answer.php?id='.$htid.'\');">
		click here to answer this.
</div>';

echo '</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>