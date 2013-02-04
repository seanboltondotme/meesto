<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses3 = true;
}

if (!isset($uiid)) {	
	$uiid = escape_data($_GET['uiid']);
	$uid = mysql_result(mysql_query("SELECT u_id FROM user_imgs WHERE ui_id='$uiid' LIMIT 1"), 0);
}

if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='meepic' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='meepic' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='meepic' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='meepic' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {

$msgcmts = mysql_query ("SELECT apc.mcc_id, apc.u_id, apc.msg, u.defaultimg_url, DATE_FORMAT(apc.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM meepic_cmts apc INNER JOIN users u ON u.user_id=apc.u_id WHERE apc.ui_id='$uiid' ORDER BY apc.mcc_id ASC");
while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
	echo '<div align="left" id="mccid'.$msgcmt['mcc_id'].'" style="padding-top: 12px;"';
			if (($uid==$id) || ($msgcmt['u_id']==$id)) {
				echo ' onmouseover="$(\'btndeletecmt'.$msgcmt['mcc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletecmt'.$msgcmt['mcc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.''.substr($msgcmt['defaultimg_url'], 0, -4).'m'.substr($msgcmt['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="500px" style="padding-left: 12px;">
			<div align="left" class="p18">'.nl2br($msgcmt['msg']).'</div>
			<div class="subtext">by '; loadpersonname($msgcmt['u_id']); echo' on '.$msgcmt['time'].'</div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
			if (($uid==$id) || ($msgcmt['u_id']==$id)) {
				echo '<div id="btndeletecmt'.$msgcmt['mcc_id'].'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/deletemeepiccmt.php?id='.$msgcmt['mcc_id'].'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
			}
		echo '</td></tr></table>
	</div>';
}

//writer
echo '<div align="left" style="margin-top: 12px; width: 500px; " class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'uiidcmtwrite'.$uiid.'_cont\', \'align\': \'left\'});newElem.inject($(\'uicmts'.$uiid.'\'), \'bottom\');gotopage(\'uiidcmtwrite'.$uiid.'_cont\', \''.$baseincpat.'externalfiles/photos/writemeepiccmt.php?uiid='.$uiid.'\');">
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