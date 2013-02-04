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

$binfo = @mysql_fetch_array (@mysql_query ("SELECT pb.bio, pb.twocents, pb.status, pb.status_id, pb.status_status, pb.interested_in, pb.political, pb.religious, pb.hometown, pb.currenttown, pb.fav_color, u.gender, DATE_FORMAT(u.birthday, '%M %D, %Y') AS bday, DATE_FORMAT(u.birthday, '%Y-%m-%d') AS binfo FROM meefile_basic pb INNER JOIN users u ON pb.u_id=u.user_id WHERE pb.u_id='$uid' LIMIT 1"), MYSQL_ASSOC);

	

	if ($binfo['bio'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genbio' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genbio' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genbio' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genbio' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genbio' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">bio</td><td align="left" style="padding-right: 32px;">'.nl2br($binfo['bio']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($binfo['twocents'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='gen2c' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='gen2c' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='gen2c' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='gen2c' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='gen2c' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">two cents</td><td align="left" style="padding-right: 32px;">'.nl2br($binfo['twocents']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($binfo['status'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genrs' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genrs' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genrs' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genrs' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genrs' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">
	relationship status</td><td align="left" style="padding-right: 32px;">'.$binfo['status']; if(($binfo['status_id']>0)&&($binfo['status_status']!='p')){echo' with '; loadpersonname($binfo['status_id']);} echo'</td></tr></table>
			</div>';
		}
	}
	
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genbday' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genbday' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genbday' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genbday' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		//get age
			date_default_timezone_set('America/Los_Angeles');
			
			$cur_year=date("Y");
			$cur_month=date("m");
			$cur_day=date("d");
			
			$dob_year=substr($binfo['binfo'], 0, 4);
			$dob_month=substr($binfo['binfo'], 5, 2);
			$dob_day=substr($binfo['binfo'], 8, 2);
			
			if($cur_month>$dob_month || ($dob_month==$cur_month &&$cur_day>=$dob_day)) {
				$age = $cur_year-$dob_year;
			} else {
				$age = $cur_year-$dob_year-1;
			}
		echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genbday' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">birthday</td><td align="left" style="padding-right: 32px;">'.$binfo['bday'].' ('.$age.' years old'; if(($cur_month==$dob_month)&&($cur_day==$dob_day)){echo' today';} echo')</td></tr></table>
		</div>';
	}
	
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='gengndr' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='gengndr' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='gengndr' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='gengndr' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='gengndr' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">gender</td><td align="left" style="padding-right: 32px;">'.$binfo['gender'].'</td></tr></table>
		</div>';
	}
	
	if ($binfo['interested_in'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genintin' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genintin' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genintin' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genintin' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genintin' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">interested in</td><td align="left" style="padding-right: 32px;">'.$binfo['interested_in'].'</td></tr></table>
			</div>';
		}
	}
	
	if ($binfo['political'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genpol' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genpol' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genpol' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genpol' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genpol' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">political view</td><td align="left" style="padding-right: 32px;">'.nl2br($binfo['political']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($binfo['religious'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genrel' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genrel' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genrel' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genrel' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genrel' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">religious view</td><td align="left" style="padding-right: 32px;">'.nl2br($binfo['religious']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($binfo['hometown'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genht' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genht' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genht' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genht' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genht' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">hometown</td><td align="left" style="padding-right: 32px;">'.nl2br($binfo['hometown']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($binfo['currenttown'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genct' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genct' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genct' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genct' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genct' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">current town</td><td align="left" style="padding-right: 32px;">'.nl2br($binfo['currenttown']).'</td></tr></table>
			</div>';
		}
	}
	
	if ($binfo['fav_color'] != '') {
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genfavc' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genfavc' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='genfavc' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='genfavc' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			echo '<div align="left" style="padding-bottom: 20px;"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genfavc' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">favorite color</td><td align="left" style="padding-right: 32px;">'.nl2br($binfo['fav_color']).'</td></tr></table>
			</div>';
		}
	}


if (isset($minses)) {
	session_write_close();
	exit();	
}
?>