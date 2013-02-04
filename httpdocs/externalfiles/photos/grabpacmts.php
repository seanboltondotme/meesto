<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses3 = true;
}

if (!isset($aid)) {
	$aid = escape_data($_GET['aid']);	
	$apid = escape_data($_GET['apid']);
	$uid = mysql_result(mysql_query("SELECT pa.u_id FROM photo_albums pa INNER JOIN album_photos ap ON ap.pa_id=pa.pa_id AND ap.ap_id='$apid' LIMIT 1"), 0);
}

if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='strm' AND ps.stream=apv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='chan' AND apv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) { //allow vis if tagged !important

$msgcmts = mysql_query ("SELECT apc.apc_id, apc.u_id, apc.msg, u.defaultimg_url, DATE_FORMAT(apc.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM ap_cmts apc INNER JOIN users u ON u.user_id=apc.u_id WHERE apc.ap_id='$apid' ORDER BY apc.apc_id ASC");
while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
	echo '<div align="left" id="apcid'.$msgcmt['apc_id'].'" style="padding-top: 12px;"';
			if (($uid==$id) || ($msgcmt['u_id']==$id)) {
				echo ' onmouseover="$(\'btndeletecmt'.$msgcmt['apc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletecmt'.$msgcmt['apc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.''.substr($msgcmt['defaultimg_url'], 0, -4).'m'.substr($msgcmt['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="500px" style="padding-left: 12px;">
			<div align="left" class="p18">'.nl2br($msgcmt['msg']).'</div>
			<div class="subtext">by '; loadpersonname($msgcmt['u_id']); echo' on '.$msgcmt['time'].'</div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
			if (($uid==$id) || ($msgcmt['u_id']==$id)) {
				echo '<div id="btndeletecmt'.$msgcmt['apc_id'].'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/deleteapcmt.php?id='.$msgcmt['apc_id'].'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
			}
		echo '</td></tr></table>
	</div>';
}

//writer
echo '<div align="left" style="margin-top: 12px; width: 500px; " class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'pa'.$aid.'cmtwrite'.$apid.'\', \'align\': \'left\'});newElem.inject($(\'apcmts'.$apid.'\'), \'bottom\');gotopage(\'pa'.$aid.'cmtwrite'.$apid.'\', \''.$baseincpat.'externalfiles/photos/writepacmt.php?aid='.$aid.'&apid='.$apid.'\');">
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