<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($uid)) {
	$uid = escape_data($_GET['id']);
}

if (isset($_GET['s'])&&is_numeric($_GET['s'])) {
	$s = escape_data($_GET['s']);
} else {
	$s = 0;	
}

if (isset($_GET['fstfid'])) {
	$fst_fid = escape_data($_GET['fstfid']);
} else {
	$fst_fid = 0;	
}

if (isset($_GET['f'])) {
	$f = escape_data($_GET['f']);
} else {
	$f = false;	
}
if (isset($_GET['vid'])&&is_numeric($_GET['vid'])) {
	$vid = escape_data($_GET['vid']);
	$viewsingle = true;
		$isvis = false;

		$feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id, type, ref_id, ref_type FROM feed WHERE f_id='$vid' LIMIT 1"), MYSQL_ASSOC);
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
			if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$vid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN feed_vis fv ON (fv.f_id='$vid' AND fv.type='strm' AND ps.stream=fv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN feed_vis fv ON (fv.f_id='$vid' AND fv.type='chan' AND fv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$vid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
				$isvis = true;
			}
		}
		
		if ($isvis==true) {
			$feeds = mysql_query ("SELECT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f WHERE f.f_id='$vid' LIMIT 1");
		} else {
			$feeds = NULL;
		}
		
} else {
	$viewsingle = false;
	if (($f)&&($uid==$id)) {
		if ($fst_fid>0) {
			$feeds = mysql_query ("SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN feed_vis fv ON f.f_id=fv.f_id AND fv.type='strm' AND fv.sub_type='$f' WHERE f.u_id='$uid' AND f.f_id<='$fst_fid' ORDER BY time_stamp DESC LIMIT $s, 14");
		} else {
			$feeds = mysql_query ("SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN feed_vis fv ON f.f_id=fv.f_id AND fv.type='strm' AND fv.sub_type='$f' WHERE f.u_id='$uid' ORDER BY time_stamp DESC LIMIT $s, 14");
		}
	} else {
		if ($uid==$id) {
			if ($fst_fid>0) {
				$feeds = mysql_query ("SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f WHERE f.u_id='$uid' AND f.f_id<='$fst_fid' ORDER BY f_id DESC LIMIT $s, 14");
			} else {
				$feeds = mysql_query ("SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f WHERE f.u_id='$uid' ORDER BY f_id DESC LIMIT $s, 14");
			}
		} else {
			if ($fst_fid>0) {
				$feeds = mysql_query ("(SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN feed_vis fv ON f.f_id=fv.f_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=mp.u_id AND ps.p_id='$id' LEFT JOIN mpc_mems mpc ON ps.p_id=mp.p_id=f.u_id=mpc.p_id LEFT JOIN feed_vis fv2 ON f.f_id=fv2.f_id AND fv2.type='user' AND fv2.ref_id='$id' WHERE f.u_id='$uid' AND f.type='stndrd' AND f.f_id<='$fst_fid' AND ((fv.type='pub' AND fv.sub_type='y') OR (((fv.type='strm' AND fv.sub_type=ps.stream) OR (fv.type='chan' AND fv.ref_id=mpc.mpc_id)) AND (fv2.fvis_id IS NULL)))) 
				UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN photo_album_vis pav ON f.ref_id=pav.pa_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=f.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN photo_album_vis pav2 ON f.ref_id=pav2.pa_id AND pav2.type='user' AND pav2.ref_id='$id' WHERE f.f_id<='$fst_fid' AND f.u_id='$uid' AND f.type='actvap' AND ((pav.type='pub' AND pav.sub_type='y') OR (((pav.type='strm' AND pav.sub_type=ps.stream) OR (pav.type='chan' AND pav.ref_id=mpc.mpc_id)) AND (pav2.pavis_id IS NULL)))) 
				UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN ap_tags apt ON f.ref_id=apt.pa_id AND apt.u_id='$id' WHERE f.f_id<='$fst_fid' AND f.u_id='$uid' AND f.type='actvap') 
				UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN defvis_apt dvapt ON f.u_id=dvapt.u_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=f.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN defvis_apt dvapt2 ON f.u_id=dvapt2.u_id AND dvapt2.type='user' AND dvapt2.ref_id='$id' WHERE f.f_id<='$fst_fid' AND f.u_id='$uid' AND f.type='actvapt' AND ((dvapt.type='pub' AND dvapt.sub_type='y') OR (((dvapt.type='strm' AND dvapt.sub_type=ps.stream) OR (dvapt.type='chan' AND dvapt.ref_id=mpc.mpc_id)) AND (dvapt2.aptvis_id IS NULL)))) 
				UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN meefile_tab_sec_vis mtsv ON f.ref_id=mtsv.mts_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=f.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN meefile_tab_sec_vis mtsv2 ON f.ref_id=mtsv2.mts_id AND mtsv2.type='user' AND mtsv2.ref_id='$id' WHERE f.f_id<='$fst_fid' AND f.u_id='$uid' AND f.type='actvmt' AND f.ref_type='mts' AND ((mtsv.type='pub' AND mtsv.sub_type='y') OR (((mtsv.type='strm' AND mtsv.sub_type=ps.stream) OR (mtsv.type='chan' AND mtsv.ref_id=mpc.mpc_id)) AND (mtsv2.mtsvis_id IS NULL)))) 
				UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN meefile_tab_vis mtv ON f.ref_id=mtv.mt_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=f.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN meefile_tab_vis mtv2 ON f.ref_id=mtv2.mt_id AND mtv2.type='user' AND mtv2.ref_id='$id' WHERE f.f_id<='$fst_fid' AND f.u_id='$uid' AND f.type='actvmt' AND f.ref_type='mt' AND ((mtv.type='pub' AND mtv.sub_type='y') OR (((mtv.type='strm' AND mtv.sub_type=ps.stream) OR (mtv.type='chan' AND mtv.ref_id=mpc.mpc_id)) AND (mtv2.mtvis_id IS NULL)))) 
				UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN events e ON f.ref_id=e.e_id LEFT JOIN event_owners eo ON f.ref_id=eo.e_id AND eo.u_id='$id' WHERE f.f_id<='$fst_fid' AND f.u_id='$uid' AND f.type='actvcev' AND (e.vis='pub' OR eo.eo_id IS NOT NULL)) 
				ORDER BY f_id DESC LIMIT $s, 14");
			} else {
				$feeds = mysql_query ("(SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN feed_vis fv ON f.f_id=fv.f_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=mp.u_id AND ps.p_id='$id' LEFT JOIN mpc_mems mpc ON ps.p_id=mp.p_id=f.u_id=mpc.p_id LEFT JOIN feed_vis fv2 ON f.f_id=fv2.f_id AND fv2.type='user' AND fv2.ref_id='$id' WHERE f.u_id='$uid' AND f.type='stndrd' AND ((fv.type='pub' AND fv.sub_type='y') OR (((fv.type='strm' AND fv.sub_type=ps.stream) OR (fv.type='chan' AND fv.ref_id=mpc.mpc_id)) AND (fv2.fvis_id IS NULL)))) 
			UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN photo_album_vis pav ON f.ref_id=pav.pa_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=f.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN photo_album_vis pav2 ON f.ref_id=pav2.pa_id AND pav2.type='user' AND pav2.ref_id='$id' WHERE f.u_id='$uid' AND f.type='actvap' AND ((pav.type='pub' AND pav.sub_type='y') OR (((pav.type='strm' AND pav.sub_type=ps.stream) OR (pav.type='chan' AND pav.ref_id=mpc.mpc_id)) AND (pav2.pavis_id IS NULL)))) 
			UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN ap_tags apt ON f.ref_id=apt.pa_id AND apt.u_id='$id' WHERE f.u_id='$uid' AND f.type='actvap') 
			UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN defvis_apt dvapt ON f.u_id=dvapt.u_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=f.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN defvis_apt dvapt2 ON f.u_id=dvapt2.u_id AND dvapt2.type='user' AND dvapt2.ref_id='$id' WHERE f.u_id='$uid' AND f.type='actvapt' AND ((dvapt.type='pub' AND dvapt.sub_type='y') OR (((dvapt.type='strm' AND dvapt.sub_type=ps.stream) OR (dvapt.type='chan' AND dvapt.ref_id=mpc.mpc_id)) AND (dvapt2.aptvis_id IS NULL)))) 
			UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN meefile_tab_sec_vis mtsv ON f.ref_id=mtsv.mts_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=f.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN meefile_tab_sec_vis mtsv2 ON f.ref_id=mtsv2.mts_id AND mtsv2.type='user' AND mtsv2.ref_id='$id' WHERE f.u_id='$uid' AND f.type='actvmt' AND f.ref_type='mts' AND ((mtsv.type='pub' AND mtsv.sub_type='y') OR (((mtsv.type='strm' AND mtsv.sub_type=ps.stream) OR (mtsv.type='chan' AND mtsv.ref_id=mpc.mpc_id)) AND (mtsv2.mtsvis_id IS NULL)))) 
			UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN meefile_tab_vis mtv ON f.ref_id=mtv.mt_id INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN peep_streams ps ON ps.u_id=f.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN meefile_tab_vis mtv2 ON f.ref_id=mtv2.mt_id AND mtv2.type='user' AND mtv2.ref_id='$id' WHERE f.u_id='$uid' AND f.type='actvmt' AND f.ref_type='mt' AND ((mtv.type='pub' AND mtv.sub_type='y') OR (((mtv.type='strm' AND mtv.sub_type=ps.stream) OR (mtv.type='chan' AND mtv.ref_id=mpc.mpc_id)) AND (mtv2.mtvis_id IS NULL)))) 
			UNION DISTINCT (SELECT DISTINCT f.f_id, f.u_id, f.type, f.msg, f.ref_id, f.ref_type, DATE_FORMAT(f.time_stamp, '%b %D, %Y at %l:%i%p') AS time, f.time_stamp FROM feed f INNER JOIN my_peeple mp ON mp.u_id=f.u_id AND mp.p_id='$id' INNER JOIN events e ON f.ref_id=e.e_id LEFT JOIN event_owners eo ON f.ref_id=eo.e_id AND eo.u_id='$id' WHERE f.u_id='$uid' AND f.type='actvcev' AND (e.vis='pub' OR eo.eo_id IS NOT NULL)) 
				ORDER BY f_id DESC LIMIT $s, 14");
			}
		}
	}
}
$f_ct = 0;
while ($feed = mysql_fetch_array ($feeds, MYSQL_ASSOC)) {
	$fid = $feed['f_id'];
	$fuid = $feed['u_id'];
	$type = $feed['type'];
	$ref_id = $feed['ref_id'];
	$ref_type = $feed['ref_type'];
	if ($fst_fid==0) {
		$fst_fid = $fid;
	}
	echo '<div align="left" id="fid'.$fid.'" onmouseover="$(\'fpoptbtns'.$fid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'fpoptbtns'.$fid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px" style="padding-top: 2px;"><a href="'.$baseincpat.'meefile.php?id='.$fuid.'"><img src="'.$baseincpat.''.mysql_result (mysql_query("SELECT defaultimg_url FROM users WHERE user_id='$fuid' LIMIT 1"), 0).'" /></a></td><td align="left" valign="top" width="530px" style="padding-left: 12px;">
			<div align="left" class="p18">'; loadpersonname($fuid); echo'</div>';
				if ($type=='actvapt') {
					$painfo = mysql_fetch_array(mysql_query ("SELECT name, u_id FROM photo_albums WHERE pa_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
					$apuid = $painfo['u_id'];
								if ($apuid==$id) {
									$photos_ct = mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id WHERE apt.u_id='$fuid' AND apt.pa_id='$ref_id'"), 0);
									$photos = mysql_query ("SELECT apt.apt_id, ap.url FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id WHERE apt.u_id='$fuid' AND apt.pa_id='$ref_id' ORDER BY RAND() LIMIT 4");
								} else {
									$photos_ctA = mysql_result(mysql_query ("SELECT DISTINCT COUNT(apt.apt_id) FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN album_photos_vis apv ON ap.ap_id=apv.ap_id LEFT JOIN my_peeple mp ON mp.u_id='$fuid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$fuid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN album_photos_vis apv2 ON ap.ap_id=apv2.ap_id AND apv2.type='user' AND apv2.ref_id='$id' WHERE apt.u_id='$fuid' AND apt.pa_id='$ref_id' AND ((apv.type='pub' AND apv.sub_type='y') OR (((apv.type='strm' AND apv.sub_type=ps.stream) OR (apv.type='chan' AND apv.ref_id=mpc.mpc_id)) AND (apv2.apvis_id IS NULL)))"), 0);
									$photos_ctB = mysql_result(mysql_query ("SELECT DISTINCT COUNT(apt.apt_id) FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN ap_tags apt2 ON ap.ap_id=apt2.ap_id AND apt2.u_id='$id' WHERE apt.u_id='$fuid'"), 0);
									$photos_ct = $photos_ctA+$photos_ctB; //fix to show count of tags in visible photos
									$photos = mysql_query ("(SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN album_photos_vis apv ON ap.ap_id=apv.ap_id LEFT JOIN my_peeple mp ON mp.u_id='$fuid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$fuid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN album_photos_vis apv2 ON ap.ap_id=apv2.ap_id AND apv2.type='user' AND apv2.ref_id='$id' WHERE apt.u_id='$fuid' AND apt.pa_id='$ref_id' AND ((apv.type='pub' AND apv.sub_type='y') OR (((apv.type='strm' AND apv.sub_type=ps.stream) OR (apv.type='chan' AND apv.ref_id=mpc.mpc_id)) AND (apv2.apvis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN ap_tags apt2 ON ap.ap_id=apt2.ap_id AND apt2.u_id='$id' WHERE apt.u_id='$fuid') ORDER BY RAND() LIMIT 4");
								}
						echo '<div align="left" class="p24" style="padding-top: 2px; padding-bottom: 2px;">was tagged in '.$photos_ct.' photo'; if($photos_ct>1){echo's';} echo' in the "<a href="'.$baseincpat.'meefile.php?id='.$fuid.'&t=photos&aid='.$ref_id.'">'.$painfo['name'].'</a>" photo album</div>
						<div align="left" style="margin-top: 2px; margin-bottom: 8px;">
							<table cellpadding="0" cellspacing="0"><tr>';
								while ($photo = @mysql_fetch_array ($photos, MYSQL_ASSOC)) {
									echo '<td align="left" valign="top" style="padding-left: 8px;">
										<a href="'.$baseincpat.'meefile.php?id='.$apuid.'&t=photos&view=taggedpic&#aptid='.$photo['apt_id'].'"><img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn"/></a>
									</td>';
								}
							
							echo '</tr></table>
						</div>';
				} elseif ($type=='actvap') {
					$painfo = mysql_fetch_array(mysql_query ("SELECT name, cover_url FROM photo_albums WHERE pa_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
						echo '<div align="left" class="p24" style="padding-top: 4px; padding-bottom: 4px;">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
								created the "<a href="'.$baseincpat.'meefile.php?id='.$fuid.'&t=photos&aid='.$ref_id.'">'.$painfo['name'].'</a>" photo album
							</td><td align="left" valign="top" style="padding-left: 8px;">
								<a href="'.$baseincpat.'meefile.php?id='.$fuid.'&t=photos&aid='.$ref_id.'"><img src="'.$baseincpat.$painfo['cover_url'].'" class="pictn"/></a>
							</td></tr></table>
						</div>';
				} elseif ($type=='actvmt') {
					if ($ref_type=='mt') {
						$mtinfo = mysql_fetch_array(mysql_query ("SELECT name FROM meefile_tab WHERE mt_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
							echo '<div align="left" class="p24" style="padding-top: 2px; padding-bottom: 2px;">created the "<a href="'.$baseincpat.'meefile.php?id='.$fuid.'&t='.$ref_id.'">'.$mtinfo['name'].'</a>" Meefile tab</div>';
					} else {
						$mtinfo = mysql_fetch_array(mysql_query ("SELECT mts.title, mt.name, mt.mt_id FROM meefile_tab_sec mts INNER JOIN meefile_tab mt ON mts.mt_id=mt.mt_id WHERE mts_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
							echo '<div align="left" class="p24" style="padding-top: 2px; padding-bottom: 2px;">added "<a href="'.$baseincpat.'meefile.php?id='.$fuid.'&t='.$mtinfo['mt_id'].'#vid='.$ref_id.'">'.$mtinfo['title'].'</a>" to '; if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$fuid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} echo' "<a href="'.$baseincpat.'meefile.php?id='.$fuid.'&t='.$mtinfo['mt_id'].'">'.$mtinfo['name'].'</a>"</div>';
					}
				} elseif ($type=='actvcev') {
					$einfo = mysql_fetch_array(mysql_query ("SELECT name, defaultimg_url FROM events WHERE e_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
						echo '<div align="left" class="p24" style="padding-top: 4px; padding-bottom: 4px;">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
								created the "<a href="'.$baseincpat.'event.php?id='.$ref_id.'">'.$einfo['name'].'</a>" event
							</td><td align="left" valign="top" style="padding-left: 8px;">
								<a href="'.$baseincpat.'event.php?id='.$ref_id.'"><img src="'.$baseincpat.substr($einfo['defaultimg_url'], 0, -5).'tn'.substr($einfo['defaultimg_url'], -4).'" class="pictn"/></a>
							</td></tr></table>
						</div>';
				} else {
					if ($ref_type=='upld_p') {
						$photo = mysql_fetch_array(mysql_query("SELECT url FROM user_attachments WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
						echo '<div align="left" class="p24" style="padding-top: 4px; padding-bottom: 4px;">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
								<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn" onclick="PopBox.fromElement(this , {url: \''.$photo['url'].'\'});$(\'pbox-loader\').set(\'styles\',{\'display\':\'none\'});"/>
							</td><td align="left" valign="top" style="padding-left: 8px;">'.nl2br($feed['msg']).'</td></tr></table>
						</div>';
					} elseif ($ref_type=='lnk_site') {
						$atchinfo = mysql_fetch_array(mysql_query("SELECT url, host, tn_url, title, description FROM user_links WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
						echo '<div align="left" class="p24" style="padding-top: 2px; padding-bottom: 2px;">'.nl2br($feed['msg']).'</div>
						<div align="left" style="margin-top: 2px; margin-bottom: 4px; padding-top: 6px; padding-bottom: 4px; border-top: 1px solid #C5C5C5; border-bottom: 1px solid #C5C5C5;">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
								<div align="center"><a href="'.$atchinfo['url'].'" target="_blank"><img src="'.$baseincpat.$atchinfo['tn_url'].'" class="pictn"/></a></div>
								<div align="center" class="subtext" style="font-size: 10px;">'.$atchinfo['host'].'</div>
							</td><td align="left" valign="top" style="padding-left: 8px;">
								<div align="left"><a href="'.$atchinfo['url'].'" target="_blank">'.$atchinfo['title'].'</a></div>
								'; if($atchinfo['description']!=''){echo'<div align="left" class="subtext">'.$atchinfo['description'].'</div>';} echo'
							</td></tr></table>
						</div>';
					} elseif ($ref_type=='lnk_img') {
						$atchinfo = mysql_fetch_array(mysql_query("SELECT url, host, tn_url FROM user_links WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
						echo '<div align="left" class="p24" style="padding-top: 4px; padding-bottom: 4px;">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
								<div align="center"><a href="'.$atchinfo['url'].'" target="_blank"><img src="'.$baseincpat.$atchinfo['tn_url'].'" class="pictn"/></a></div>
								<div align="center" class="subtext" style="font-size: 10px;">'.$atchinfo['host'].'</div>
							</td><td align="left" valign="top" style="padding-left: 8px;">'.nl2br($feed['msg']).'</td></tr></table>
						</div>';
					} elseif ($ref_type=='ap') {
						$photo = mysql_fetch_array(mysql_query("SELECT pa_id, url FROM album_photos WHERE ap_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
						echo '<div align="left" class="p24" style="padding-top: 4px; padding-bottom: 4px;">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
								<a href="'.$baseincpat.'meefile.php?id='.$fuid.'&t=photos&aid='.$photo['pa_id'].'&view=photo&#apid='.$ref_id.'"><img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn"/></a>
							</td><td align="left" valign="top" style="padding-left: 8px;">'.nl2br($feed['msg']).'</td></tr></table>
						</div>';
					} else {
						echo '<div align="left" class="p24" style="padding-top: 2px; padding-bottom: 2px;">'.nl2br($feed['msg']).'</div>';
					}
				}
			echo '<div class="subtext"><table cellpadding="0" cellspacing="0"><tr><td align="left">on '.$feed['time'].'</td><td align="left" valign="center" class="subtext" style="padding-left: 6px;">|</td>';
			//test for messages in thread
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed_cmt WHERE f_id='$fid' LIMIT 1"), 0)<1) {
				echo '<td align="left">
				<div align="left" class="postoptlink" style="margin-left: 10px;" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'msgwrite'.$fid.'\', \'align\': \'left\'});newElem.inject($(\'feedcmts'.$fid.'\'), \'bottom\');gotopage(\'msgwrite'.$fid.'\', \''.$baseincpat.'externalfiles/home/postfeedcmt.php?id='.$fid.'\');"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><div align="left" class="postoptlinkmrkr"></div></td><td align="left" valign="center" style="padding-left: 4px;">comment</td></tr></table></div>
				</td>';
			}
				echo '<td align="left">
					<table cellpadding="0" cellspacing="0"><tr><td align="left">
						<div id="fbtnsemo'.$fid.'"'; if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' AND u_id='$id' LIMIT 1"), 0)>0) {echo' style="display: none;"';} echo'>
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<div align="left" class="postoptlink" style="margin-left: 10px;" onclick="$(\'fbtnsemo'.$fid.'\').set(\'styles\',{\'display\':\'none\'});$(\'fbtnunemo'.$fid.'\').set(\'styles\',{\'display\':\'block\'});$(\'fbtnunemo'.$fid.'text\').set(\'html\', \'unlike\');gotopage(\'feedemos'.$fid.'\', \''.$baseincpat.'externalfiles/home/grabfeedemo.php?id='.$fid.'&t=l\');"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><div align="left" class="postoptlinkmrkr"></div></td><td align="left" valign="center" style="padding-left: 4px;">meelike</td></tr></table></div>
							</td><td align="left">
							<div align="left" class="postoptlink" style="margin-left: 10px;" onclick="$(\'fbtnsemo'.$fid.'\').set(\'styles\',{\'display\':\'none\'});$(\'fbtnunemo'.$fid.'\').set(\'styles\',{\'display\':\'block\'});$(\'fbtnunemo'.$fid.'text\').set(\'html\', \'undislike\');gotopage(\'feedemos'.$fid.'\', \''.$baseincpat.'externalfiles/home/grabfeedemo.php?id='.$fid.'&t=d\');"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><div align="left" class="postoptlinkmrkr"></div></td><td align="left" valign="center" style="padding-left: 4px;">meedislike</td></tr></table></div>
							</td></tr></table>
						</div>	
					</td><td align="left">
						<div align="left" id="fbtnunemo'.$fid.'" class="postoptlink" style="'; if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' AND u_id='$id' LIMIT 1"), 0)==0) {echo'display: none; ';} echo'margin-left: 10px;" onclick="$(\'fbtnunemo'.$fid.'\').set(\'styles\',{\'display\':\'none\'});$(\'fbtnsemo'.$fid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'feedemos'.$fid.'\', \''.$baseincpat.'externalfiles/home/grabfeedemo.php?id='.$fid.'&t=u\');"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><div align="left" class="postoptlinkmrkr"></div></td><td align="left" valign="center" id="fbtnunemo'.$fid.'text" style="padding-left: 4px;">un'; if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' AND u_id='$id' AND type='d' LIMIT 1"), 0)>0) {echo'dislike';}else{echo'like';} echo'</td></tr></table></div>
					</td></tr></table>
				</td></tr></table></div></td><td align="left" valign="top" width="100px" style="padding-left: 16px;">
				<div id="fpoptbtns'.$fid.'" align="left" style="visibility: hidden; zoom: 1; opacity: 0;">
					<div align="left"><input type="button" id="visibility" value="view visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/viewvis.php?id='.$fid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});" style="padding-left: 8px; padding-right: 8px;"/></div>';
					if ($fuid==$id) {
						echo '<div align="left" style="padding-top: 12px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/deletefeed.php?id='.$fid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
					}
				echo '</div>
			</td></tr></table>
	</div><div align="left" id="fid'.$fid.'p2" style="padding-left: 62px; padding-bottom: 24px;"><div align="left" id="feedcmts'.$fid.'" style="padding-left: 12px; border-left: 1px solid #C5C5C5;">
		<div align="left" id="feedemos'.$fid.'" style="margin-top: 4px;">';
			//test for emo
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' LIMIT 1"), 0)>0) {
				include('externalfiles/home/grabfeedemo.php');	
			}
		echo '</div>';
		//test for messages in thread
		if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed_cmt WHERE f_id='$fid' LIMIT 1"), 0)>0) {
			include('externalfiles/home/grabfeedcmts.php');	
		}
	echo '</div></div>';
	$f_ct++;
}

if ((!$viewsingle)&&($f_ct>0)) {
echo '<div align="left">
	<div align="center" class="p18" style="width: 708px; padding-top: 8px; padding-bottom: 4px; border-bottom: 2px solid #C5C5C5; cursor: pointer;" onclick="gotopage(this.getParent(), \''.$baseincpat.'externalfiles/meefile/grabfeed.php?id='.$uid.'&s='.($s+14).'&fstfid='.$fst_fid; if ($f) {echo'&f='.$f;} echo'\');">show more</div>
</div>';
}

if ($f_ct==0) {
	echo '<div align="left">There are no '; if($s>0){echo'more ';} echo'posts in this feed.</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>