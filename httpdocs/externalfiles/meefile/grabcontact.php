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

$deinfo = mysql_fetch_array (mysql_query ("SELECT u.email, mb.email_type FROM users u, meefile_basic mb WHERE u.user_id=mb.u_id='$uid' LIMIT 1"), MYSQL_ASSOC);

	$defemlvis = false;
	
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='cntctme' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='cntctme' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_infosec_vis piv ON (piv.u_id='$uid' AND piv.sec='cntctme' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$uid' AND sec='cntctme' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$defemlvis = true;
		echo '<div align="left" style="padding-bottom: 12px;"';
						if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='cntctme' AND type!='user' LIMIT 1"), 0)==0)) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">email</td><td align="left" style="padding-right: 32px;">
				<div align="left" style="padding-bottom: 8px;">'.$deinfo ['email']; if($deinfo ['email_type']!=''){echo' <span class="subtext">('.$deinfo ['email_type'].')</span>';} echo '</div>';
	}
	
	//get custom secs
	$customsecs = @mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$uid' AND sec='email' ORDER BY mc_id ASC");
	while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
		$mcid = $customsec['mc_id'];
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			if ($customsec['content']!='') {
				if (!$defemlvis) {
					$defemlvis = true;
					echo '<div align="left" style="padding-bottom: 20px;"';
						if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0)) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">email</td><td align="left" style="padding-right: 32px;">';
				}
				echo '<div align="left" style="padding-bottom: 8px;">'.$customsec['content']; if($customsec['type']!=''){echo' <span class="subtext">('.$customsec['type'].')</span>';} echo '</div>';
			}
		}
	}
	if ($defemlvis) {
		echo '</td></tr></table>
		</div>';
	}
	
	//get custom secs
	$customsecs = @mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$uid' AND sec='im' ORDER BY mc_id ASC");
	$i = 0;
	while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
		$mcid = $customsec['mc_id'];
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			if ($customsec['content']!='') {
				if ($i==0) {
					echo '<div align="left" style="padding-bottom: 20px;"';
						if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0)) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">IM name</td><td align="left" style="padding-right: 32px;">';
				}
				echo '<div align="left" style="padding-bottom: 8px;">'.$customsec['content']; if($customsec['type']!=''){echo' <span class="subtext">('.$customsec['type'].')</span>';} echo '</div>';
				$i++;
			}
		}
	}
	if ($i>0) {
		echo '</td></tr></table>
		</div>';
	}
	
	//get custom secs
	$customsecs = @mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$uid' AND sec='phone' ORDER BY mc_id ASC");
	$i = 0;
	while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
		$mcid = $customsec['mc_id'];
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			if ($customsec['content']!='') {
				if ($i==0) {
					echo '<div align="left" style="padding-bottom: 20px;"';
						if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0)) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">phone number</td><td align="left" style="padding-right: 32px;">';
				}
				echo '<div align="left" style="padding-bottom: 8px;">'.$customsec['content']; if($customsec['type']!=''){echo' <span class="subtext">('.$customsec['type'].')</span>';} echo '</div>';
				$i++;
			}
		}
	}
	if ($i>0) {
		echo '</td></tr></table>
		</div>';
	}
	
	//get custom secs
	$customsecs = @mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$uid' AND sec='adrs' ORDER BY mc_id ASC");
	$i = 0;
	while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
		$mcid = $customsec['mc_id'];
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			if ($customsec['content']!='') {
				if ($i==0) {
					echo '<div align="left" style="padding-bottom: 20px;"';
						if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0)) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">address</td><td align="left" style="padding-right: 32px;">';
				}
				echo '<div align="left" style="padding-bottom: 8px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">'.nl2br($customsec['content']); if($customsec['type']!=''){echo'</td><td align="left" valign="top" style="padding-left: 6px;"><span class="subtext">('.$customsec['type'].')</span>';} echo '
					</td></tr></table>
				</div>';
				$i++;
			}
		}
	}
	if ($i>0) {
		echo '</td></tr></table>
		</div>';
	}
	
	//get custom secs
	$customsecs = @mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$uid' AND sec='web' ORDER BY mc_id ASC");
	$i = 0;
	while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
		$mcid = $customsec['mc_id'];
		if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_contact_vis piv ON (piv.mc_id='$mcid' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
			if ($customsec['content']!='') {
				if ($i==0) {
					echo '<div align="left" style="padding-bottom: 20px;"';
						if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0)) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">website</td><td align="left" style="padding-right: 32px;">';
				}
				echo '<div align="left" style="padding-bottom: 8px;"><a href="'; if(substr($customsec['content'], 0, 7)!='http://'){echo'http://';} echo $customsec['content']; echo'" target="_blank">'.$customsec['content'].'</a>'; if($customsec['type']!=''){echo' <span class="subtext">('.$customsec['type'].')</span>';} echo '</div>';
				$i++;
			}
		}
	}
	if ($i>0) {
		echo '</td></tr></table>
		</div>';
	}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>