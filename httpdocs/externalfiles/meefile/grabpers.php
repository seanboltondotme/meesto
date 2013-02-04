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

$pinfo = @mysql_fetch_array (@mysql_query ("SELECT activities, interests, fav_quotes, vacation_spot, dream_life, about_me FROM meefile_pers WHERE u_id='$uid' LIMIT 1"), MYSQL_ASSOC);

	if ($pinfo ['activities'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsact' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsact' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsact' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsact' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsact' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">activities</td><td align="left" style="padding-right: 32px;">'.nl2br($pinfo ['activities']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($pinfo ['interests'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsint' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsint' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsint' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsint' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsint' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">interest/passions</td><td align="left" style="padding-right: 32px;">'.nl2br($pinfo ['interests']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($pinfo ['fav_quotes'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsfq' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsfq' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsfq' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsfq' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsfq' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">favorite quotes</td><td align="left" style="padding-right: 32px;">'.nl2br($pinfo ['fav_quotes']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($pinfo ['vacation_spot'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsvs' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsvs' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsvs' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsvs' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsvs' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">vacation spot</td><td align="left" style="padding-right: 32px;">'.nl2br($pinfo ['vacation_spot']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($pinfo ['dream_life'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsdl' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsdl' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsdl' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsdl' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsdl' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">dream life</td><td align="left" style="padding-right: 32px;">'.nl2br($pinfo ['dream_life']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($pinfo ['about_me'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsam' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsam' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='prsam' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='prsam' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsam' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">about me</td><td align="left" style="padding-right: 32px;">'.nl2br($pinfo ['about_me']).'</td></tr></table>
			</div>';
		}
	}
	
	//get custom secs
	$customsecs = @mysql_query("SELECT mpe_id, type, content FROM meefile_pers_ext WHERE u_id='$uid' ORDER BY mpe_id ASC");
	while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
		$mpeid = $customsec['mpe_id'];
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_pers_ext_vis WHERE mpe_id='$mpeid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_pers_ext_vis piv ON (piv.mpe_id='$mpeid' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_pers_ext_vis piv ON (piv.mpe_id='$mpeid' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_pers_ext_vis WHERE mpe_id='$mpeid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			if ($customsec['content']!='') {
			echo '<div align="left" style="padding-bottom: 20px;"';
						if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_pers_ext_vis WHERE mpe_id='$mpeid' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">'.$customsec['type'].'</td><td align="left" style="padding-right: 32px;">'.nl2br($customsec['content']).'</td></tr></table>
			</div>';
			}
		}
	}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>