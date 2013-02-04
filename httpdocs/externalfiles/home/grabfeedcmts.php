<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses2 = true;
}

if (!isset($fid)) {
	$fid = escape_data($_GET['id']);	
}

$isvis = false;

$feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id, type, ref_id, ref_type FROM feed WHERE f_id='$fid' LIMIT 1"), MYSQL_ASSOC);
$uid = $feedinfo['u_id'];
$type = $feedinfo['type'];
$ref_id = $feedinfo['ref_id'];
$ref_type = $feedinfo['ref_type'];

if ($type=='actvapt') {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$uid' AND dvapt.type='strm' AND ps.stream=dvapt.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$uid' AND dvapt.type='chan' AND dvapt.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvap') {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE pa_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='strm' AND ps.stream=pav.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='chan' AND pav.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mt')) {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mts')) {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvcev') {
	if (($uid==$id) || (mysql_result (mysql_query("SELECT COUNT(*) FROM events WHERE e_id='$ref_id' AND vis='pub' LIMIT 1"), 0)>0) || (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0)) {
		$isvis = true;
	}
} else {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='strm' AND ps.stream=fv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='chan' AND fv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
}

if ($isvis==true) {

$msgcmts = mysql_query ("SELECT fc.fc_id, fc.u_id, fc.msg, u.defaultimg_url, DATE_FORMAT(fc.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM feed_cmt fc INNER JOIN users u ON u.user_id=fc.u_id WHERE fc.f_id='$fid' ORDER BY fc.time_stamp ASC");
while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
	$fcid = $msgcmt['fc_id'];
	$cmtuid = $msgcmt['u_id'];
	if (($cmtuid==$id) || ($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_cmt_vis WHERE fc_id='$fcid' LIMIT 1"), 0)==0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_cmt_vis WHERE fc_id='$fcid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN feed_cmt_vis fv ON (fv.fc_id='$fcid' AND fv.type='strm' AND ps.stream=fv.sub_type) WHERE ps.u_id='$cmtuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN feed_cmt_vis fv ON (fv.fc_id='$fcid' AND fv.type='chan' AND fv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_cmt_vis WHERE fc_id='$fcid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		echo '<div align="left" id="fcmtcid'.$msgcmt['fc_id'].'" style="padding-top: 12px;"';
				if (($uid==$id) || ($msgcmt['u_id']==$id)) {
					echo ' onmouseover="$(\'btndeletemsg'.$msgcmt['fc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletemsg'.$msgcmt['fc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
				}
				echo '>
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.''.substr($msgcmt['defaultimg_url'], 0, -4).'m'.substr($msgcmt['defaultimg_url'], -4).'" /></a></td><td align="left" valign="top" width="451px" style="padding-left: 12px;">
				<div align="left" class="p18">'.nl2br($msgcmt['msg']).'</div>
				<div class="subtext">by '; loadpersonname($msgcmt['u_id']); echo' on '.$msgcmt['time'].'</div>
			</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
				if (($uid==$id) || ($msgcmt['u_id']==$id)) {
					echo '<div id="btndeletemsg'.$msgcmt['fc_id'].'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/deletefeedcmt.php?id='.$msgcmt['fc_id'].'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
				}
			echo '</td></tr></table>
		</div>';
	}
}

//writer
echo '<div align="left" style="margin-top: 12px; width: 400px; " class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'msgwrite'.$fid.'\', \'align\': \'left\'});newElem.inject($(\'feedcmts'.$fid.'\'), \'bottom\');gotopage(\'msgwrite'.$fid.'\', \''.$baseincpat.'externalfiles/home/postfeedcmt.php?id='.$fid.'\');">
		click here to comment on this.
</div>';

} else { //if not vis
	echo '<div align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

if (isset($minses2)) {
	session_write_close();
	exit();	
}
?>