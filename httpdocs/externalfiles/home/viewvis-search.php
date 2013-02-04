<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$fid = escape_data($_GET['id']);

$isvis = false;

$feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id, type, ref_id, ref_type FROM feed WHERE f_id='$fid' LIMIT 1"), MYSQL_ASSOC);
$fuid = $feedinfo['u_id'];
$type = $feedinfo['type'];
$ref_id = $feedinfo['ref_id'];
$ref_type = $feedinfo['ref_type'];

if ($type=='actvapt') {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$fuid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$fuid' AND dvapt.type='strm' AND ps.stream=dvapt.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$fuid' AND dvapt.type='chan' AND dvapt.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$fuid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvap') {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE pa_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='strm' AND ps.stream=pav.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='chan' AND pav.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mt')) {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mts')) {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvcev') {
	if (($fuid==$id) || (mysql_result (mysql_query("SELECT COUNT(*) FROM events WHERE e_id='$ref_id' AND vis='pub' LIMIT 1"), 0)>0) || (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0)) {
		$isvis = true;
	}
} else {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='strm' AND ps.stream=fv.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='chan' AND fv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
}

if ($isvis==true) {

if ($type=='actvapt') {
				$peeple = mysql_query ("SELECT DISTINCT mp.p_id FROM my_peeple mp INNER JOIN defvis_apt dvapt ON dvapt.u_id='$fuid' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND dvapt.type='strm' AND dvapt.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND dvapt.type='chan' AND dvapt.ref_id=mpc.mpc_id LEFT JOIN defvis_apt dvapt2 ON dvapt2.u_id='$fuid' AND dvapt2.type='user' AND dvapt2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (dvapt2.aptvis_id IS NULL)");
			} elseif ($type=='actvap') {
				$peeple = mysql_query ("(SELECT DISTINCT mp.p_id FROM my_peeple mp INNER JOIN photo_album_vis pav ON pav.pa_id='$ref_id' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND pav.type='strm' AND pav.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND pav.type='chan' AND pav.ref_id=mpc.mpc_id LEFT JOIN photo_album_vis pav2 ON pav2.pa_id='$ref_id' AND pav2.type='user' AND pav2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (pav2.pavis_id IS NULL)) UNION DISTINCT (SELECT DISTINCT apt.u_id p_id FROM ap_tags apt INNER JOIN users u ON apt.u_id=u.user_id WHERE apt.pa_id='$ref_id' AND apt.u_id!='$id')");
			} elseif (($type=='actvmt')&&($ref_type=='mt')) {
				$peeple = mysql_query ("SELECT DISTINCT mp.p_id FROM my_peeple mp INNER JOIN meefile_tab_vis mtv ON mtv.mt_id='$ref_id' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND mtv.type='strm' AND mtv.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND mtv.type='chan' AND mtv.ref_id=mpc.mpc_id LEFT JOIN meefile_tab_vis mtv2 ON mtv2.mt_id='$ref_id' AND mtv2.type='user' AND mtv2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (mtv2.mtvis_id IS NULL)");
			} elseif (($type=='actvmt')&&($ref_type=='mts')) {
				$peeple = mysql_query ("SELECT DISTINCT mp.p_id FROM my_peeple mp INNER JOIN meefile_tab_sec_vis mtsv ON mtsv.mts_id='$ref_id' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND mtsv.type='strm' AND mtsv.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND mtsv.type='chan' AND mtsv.ref_id=mpc.mpc_id LEFT JOIN meefile_tab_sec_vis mtsv2 ON mtsv2.mts_id='$ref_id' AND mtsv2.type='user' AND mtsv2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (mtsv2.mtsvis_id IS NULL)");
			} elseif ($type=='actvcev') {
				$peeple = mysql_query ("SELECT DISTINCT eo.u_id p_id FROM event_owners eo INNER JOIN users u ON eo.u_id=u.user_id WHERE eo.e_id='$ref_id' AND eo.u_id!='$id'");
			} else {
				$peeple = mysql_query ("SELECT DISTINCT mp.p_id FROM my_peeple mp INNER JOIN feed_vis fv ON fv.f_id='$fid' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND fv.type='strm' AND fv.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND fv.type='chan' AND fv.ref_id=mpc.mpc_id LEFT JOIN feed_vis fv2 ON fv2.f_id='$fid' AND fv2.type='user' AND fv2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (fv2.fvis_id IS NULL)");
			}
while ($person = @mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
	$uid = $person['p_id'];
	$name = returnpersonname($uid).' '.returncleanrealname($uid);
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0){
		$response[] = array($uid, $name, 'm');
	} else {
		$response[] = array($uid, $name, 'a');
	}
}

} else { //if not able to view
	$response[] = array();	
}

header('Content-type: application/json');
echo json_encode($response);

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>