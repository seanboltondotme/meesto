<?php
require_once('../externals/sessions/db_sessions.inc.php');
$id = $_SESSION['user_id'];
require_once ('../externals/general/includepaths.php');
require_once ('../externals/general/functions.php');

if ($_SESSION['user_id'] == NULL) {
	echo '<script type="text/javascript">
		window.location.href = \''.$baseincpat.'login.php?rel=\'+encodeURIComponent(window.location.pathname+window.location.search+window.location.hash);
	</script>
	<div align="left" valign="top" style="padding: 24px;">
		We were unable to redirect you. <form action="'.$baseincpat.'login.php?"><input type="submit" value="click here to login"/></form>
	</div>';
	exit();
}

$nid = escape_data(substr($_GET['id'], 1));
$type = escape_data(substr($_GET['id'], 0, 1));


if (mysql_result(mysql_query("SELECT COUNT(*) FROM notifications WHERE n_id='$nid' AND u_id='$id' LIMIT 1"), 0)>0) { //test for owner

	$notif = mysql_fetch_array (mysql_query ("SELECT type, s_id, sub, params, ref_id, xref_id FROM notifications WHERE n_id='$nid'"), MYSQL_ASSOC);
	$sid = $notif['s_id'];
	$type = $notif['type'];
	$sub = $notif['sub'];
	$params = $notif['params'];
	$refid = $notif['ref_id'];
	$xrefid = $notif['xref_id'];
	
	if ($type=='feedcmt') {
		$url = $baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid;
	} elseif ($type=='feedcmtx') {
		$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		$n_feeduid = $n_feedinfo['u_id'];
		$url = $baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid;
	} elseif ($type=='feedeml') {
		$url = $baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid;
	} elseif ($type=='feedemlx') {
		$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		$n_feeduid = $n_feedinfo['u_id'];
		$url = $baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid;
	} elseif ($type=='feedemd') {
		$url = $baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid;
	} elseif ($type=='feedemdx') {
		$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		$n_feeduid = $n_feedinfo['u_id'];
		$url = $baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid;
	} elseif ($type=='msg') {
		$url = $baseincpat.'meefile.php?id='.$id.'&#&vid='.$refid;
	} elseif ($type=='msgcmt') {
		$url = $baseincpat.'meefile.php?id='.$id.'&#&vid='.$refid;
	} elseif ($type=='apt') {
		$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id, u_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		$url = $baseincpat.'meefile.php?id='.$n_apinfo['u_id'].'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid;
	} elseif ($type=='apcmt') {
		$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		$url = $baseincpat.'meefile.php?id='.$id.'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid;
	} elseif ($type=='apcmtx') {
		$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id, u_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		$url = $baseincpat.'meefile.php?id='.$n_apinfo['u_id'].'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid;
	} elseif ($type=='uicmt') {
		$url = $baseincpat.'meefile.php?id='.$id.'&t=photos&view=meepic&#uiid='.$refid;
	} elseif ($type=='uicmtx') {
		$n_uiinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM user_imgs WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		$url = $baseincpat.'meefile.php?id='.$n_uiinfo['u_id'].'&t=photos&view=meepic&#uiid='.$refid;
	} elseif ($type=='mtscmt') {
		$n_mtsinfo = mysql_fetch_array (mysql_query ("SELECT title, mt_id FROM meefile_tab_sec WHERE mts_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		$url = $baseincpat.'meefile.php?id='.$id.'&t='.$n_mtsinfo['mt_id'].'&vid='.$refid;
	} elseif ($type=='mtscmtx') {
		$n_mtsinfo = mysql_fetch_array (mysql_query ("SELECT mts.title, mts.mt_id, mt.u_id FROM meefile_tab_sec mts INNER JOIN meefile_tab mt ON mt.mt_id=mts.mt_id WHERE mts.mts_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		$url = $baseincpat.'meefile.php?id='.$n_mtsinfo['u_id'].'&t='.$n_mtsinfo['mt_id'].'&vid='.$refid;
	} elseif ($type=='fdbkcmt') {
		$url = $baseincpat.'community.php?#f=fdbk&vid='.$refid;
	} elseif ($type=='fdbkcmtx') {
		$url = $baseincpat.'community.php?#f=fdbk&vid='.$refid;
	} elseif ($type=='evntmcmt') {
		$url = $baseincpat.'event.php?id='.$refid.'&vid='.$xrefid;
	} elseif ($type=='evntcmt') {
		$url = $baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'&vt=x';
	} elseif ($type=='evntcmtx') {
		$url = $baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'&vt=x';
	} elseif ($type=='cprjmcmt') {
		$url = $baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid;
	} elseif ($type=='cprjcmt') {
		$url = $baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'&vt=x';
	} elseif ($type=='cprjcmtx') {
		$url = $baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'&vt=x';
	}
	
	header("Location: $url");
	session_write_close();
	exit();
}

$title = 'Email Notification Unavailable';
include ('../externals/header/header.php');

//main content
echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="686px" style="padding-left: 76px; padding-right: 8px;">
	This notification is no longer available.
</td></tr></table>';

include ('../externals/header/footer.php');
?>