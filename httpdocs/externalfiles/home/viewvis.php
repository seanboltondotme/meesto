<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$fid = escape_data($_GET['id']);
$pjs = '<script src="'.$baseincpat.'externalfiles/home/viewvis.js" type="text/javascript" charset="utf-8"></script>';
$pdrjs = 'new Request.JSON({url: \''.$baseincpat.'externalfiles/home/viewvis-search.php?id='.$fid.'\', onSuccess: function(r){
				PeepSearch.setValues(r);
			}}).send();';
$fullmts = true;
include ('../../../externals/header/header-pb.php');

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

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">View Feed Post Visibility</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to view feed post visibility.</div>';

	//public test
	if ((mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0)||(($type=='actvcev')&&(mysql_result (mysql_query("SELECT COUNT(*) FROM events WHERE e_id='$ref_id' AND vis='pub' LIMIT 1"), 0)>0))) {
		echo '<div align="left" style="padding-left: 16px; padding-bottom: 12px;">This is public.</div>';
	} else {

	echo '<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="center" id="fltra" class="topfltrOn" style="width: 120px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrm\').set(\'class\', \'topfltr\');showA();">
					<div align="center" class="title" style="width: 120px;">all peeple</div>
					<div align="center" class="bar" style="width: 120px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 12px;">
				<div align="center" id="fltrm" class="topfltr" style="width: 120px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltra\').set(\'class\', \'topfltr\');showM();">
					<div align="center" class="title" style="width: 120px;">my peeple</div>
					<div align="center" class="bar" style="width: 120px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="right" valign="top" style="padding-top: 2px; padding-left: 146px;">
				<input type="text" id="msrch" name="msrch" size="26px" maxlength="60" onfocus="if (trim(this.value) == \'search\') {this.value=\'\';};this.className=\'inputfocus\'; $(\'fltra\').set(\'class\', \'topfltr\');$(\'fltrm\').set(\'class\', \'topfltr\');" onblur="if (trim(this.value) == \'\') {this.value=\'search\';this.className=\'inputplaceholder\'; $(\'fltra\').set(\'class\', \'topfltrOn\'); showA();} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(trim(this.value)!=\'search\'){PeepSearch.filter(this.value);}" class="inputplaceholder" value="search"/>
			</td></tr></table>
		</div>
		
		<div align="left" id="peeparea" style="padding-left: 16px; padding-bottom: 12px; height: 200px; width: 644px; overflow-x: none; overflow-y: scroll;"">';
			if ($type=='actvapt') {
				$peeple = mysql_query ("SELECT DISTINCT mp.p_id, u.defaultimg_url, u.last_name FROM my_peeple mp INNER JOIN users u ON mp.p_id=u.user_id INNER JOIN defvis_apt dvapt ON dvapt.u_id='$fuid' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND dvapt.type='strm' AND dvapt.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND dvapt.type='chan' AND dvapt.ref_id=mpc.mpc_id LEFT JOIN defvis_apt dvapt2 ON dvapt2.u_id='$fuid' AND dvapt2.type='user' AND dvapt2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (dvapt2.aptvis_id IS NULL) ORDER BY u.last_name ASC");
			} elseif ($type=='actvap') {
				$peeple = mysql_query ("(SELECT DISTINCT mp.p_id, u.defaultimg_url, u.last_name FROM my_peeple mp INNER JOIN users u ON mp.p_id=u.user_id INNER JOIN photo_album_vis pav ON pav.pa_id='$ref_id' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND pav.type='strm' AND pav.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND pav.type='chan' AND pav.ref_id=mpc.mpc_id LEFT JOIN photo_album_vis pav2 ON pav2.pa_id='$ref_id' AND pav2.type='user' AND pav2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (pav2.pavis_id IS NULL)) UNION DISTINCT (SELECT DISTINCT apt.u_id p_id, u.defaultimg_url, u.last_name FROM ap_tags apt INNER JOIN users u ON apt.u_id=u.user_id WHERE apt.pa_id='$ref_id' AND apt.u_id!='$id') ORDER BY last_name ASC");
			} elseif (($type=='actvmt')&&($ref_type=='mt')) {
				$peeple = mysql_query ("SELECT DISTINCT mp.p_id, u.defaultimg_url, u.last_name FROM my_peeple mp INNER JOIN users u ON mp.p_id=u.user_id INNER JOIN meefile_tab_vis mtv ON mtv.mt_id='$ref_id' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND mtv.type='strm' AND mtv.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND mtv.type='chan' AND mtv.ref_id=mpc.mpc_id LEFT JOIN meefile_tab_vis mtv2 ON mtv2.mt_id='$ref_id' AND mtv2.type='user' AND mtv2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (mtv2.mtvis_id IS NULL) ORDER BY u.last_name ASC");
			} elseif (($type=='actvmt')&&($ref_type=='mts')) {
				$peeple = mysql_query ("SELECT DISTINCT mp.p_id, u.defaultimg_url, u.last_name FROM my_peeple mp INNER JOIN users u ON mp.p_id=u.user_id INNER JOIN meefile_tab_sec_vis mtsv ON mtsv.mts_id='$ref_id' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND mtsv.type='strm' AND mtsv.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND mtsv.type='chan' AND mtsv.ref_id=mpc.mpc_id LEFT JOIN meefile_tab_sec_vis mtsv2 ON mtsv2.mts_id='$ref_id' AND mtsv2.type='user' AND mtsv2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (mtsv2.mtsvis_id IS NULL) ORDER BY u.last_name ASC");
			} elseif ($type=='actvcev') {
				$peeple = mysql_query ("SELECT DISTINCT eo.u_id p_id, u.defaultimg_url, u.last_name FROM event_owners eo INNER JOIN users u ON eo.u_id=u.user_id WHERE eo.e_id='$ref_id' AND eo.u_id!='$id' ORDER BY u.last_name ASC");
			} else {
				$peeple = mysql_query ("SELECT DISTINCT mp.p_id, u.defaultimg_url, u.last_name FROM my_peeple mp INNER JOIN users u ON mp.p_id=u.user_id INNER JOIN feed_vis fv ON fv.f_id='$fid' LEFT JOIN peep_streams ps ON ps.p_id=mp.p_id AND fv.type='strm' AND fv.sub_type=ps.stream LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id AND fv.type='chan' AND fv.ref_id=mpc.mpc_id LEFT JOIN feed_vis fv2 ON fv2.f_id='$fid' AND fv2.type='user' AND fv2.ref_id=mp.p_id WHERE mp.u_id='$fuid' AND (ps.ps_id IS NOT NULL OR mpc.mpc_id IS NOT NULL) AND (fv2.fvis_id IS NULL) ORDER BY u.last_name ASC");
			}
			while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
				$uid = $person['p_id'];
				echo '<div align="left" id="'; if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0){echo'm';}else{echo'a';} echo $uid.'" class="peepblk" style="float: left; width: 150px; margin: 4px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
						<a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top"><img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" /></a>
					</td><td align="left" valign="top" style="padding-left: 4px;">
						<a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top">'; loadpersonnamenolink($uid); echo '</a>
					</td></tr></table>
				</div>';
			}
		echo '</div>';
	}

} else { //if not able to view
	echo '<div align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>