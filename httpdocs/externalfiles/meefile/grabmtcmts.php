<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses3 = true;
}

if (!isset($mtsid)) {
	$mtsid = escape_data($_GET['id']);
	$uid = mysql_result(mysql_query ("SELECT mt.u_id FROM meefile_tab_sec mts INNER JOIN meefile_tab mt ON mt.mt_id=mts.mt_id WHERE mts.mts_id='$mtsid' LIMIT 1"), 0);
}

if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$mtsid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$mtsid'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$mtsid'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$mtsid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {

$msgcmts = mysql_query ("SELECT m.mtc_id, m.u_id, m.msg, u.defaultimg_url, DATE_FORMAT(m.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM meefile_tab_cmts m INNER JOIN users u ON u.user_id=m.u_id WHERE m.mts_id='$mtsid' ORDER BY m.time_stamp ASC");
while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
	echo '<div align="left" id="mtcid'.$msgcmt['mtc_id'].'" style="padding-top: 12px;"';
			if (($uid==$id) || ($msgcmt['u_id']==$id)) {
				echo ' onmouseover="$(\'btndeletecmt'.$msgcmt['mtc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletecmt'.$msgcmt['mtc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><a href="'.$baseincpat.'meefile.php?id='.$msgcmt['u_id'].'"><img src="'.$baseincpat.''.substr($msgcmt['defaultimg_url'], 0, -4).'m'.substr($msgcmt['defaultimg_url'], -4).'" /></a></td><td align="left" valign="top" width="500px" style="padding-left: 12px;">
			<div align="left" class="p18">'.nl2br($msgcmt['msg']).'</div>
			<div class="subtext">by '; loadpersonname($msgcmt['u_id']); echo' on '.$msgcmt['time'].'</div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
			if (($uid==$id) || ($msgcmt['u_id']==$id)) {
				echo '<div id="btndeletecmt'.$msgcmt['mtc_id'].'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletemtcmt.php?id='.$msgcmt['mtc_id'].'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
			}
		echo '</td></tr></table>
	</div>';
}

//writer
echo '<div align="left" style="margin-top: 12px; width: 400px; " class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'msgwrite'.$mtsid.'\', \'align\': \'left\'});newElem.inject($(\'msgcmts'.$mtsid.'\'), \'bottom\');gotopage(\'msgwrite'.$mtsid.'\', \''.$baseincpat.'externalfiles/meefile/writemtcmt.php?id='.$mtsid.'\');">
		click here to comment on this.
</div>';

} else { //if not vis
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

if (isset($minses3)) {
	session_write_close();
	exit();	
}
?>