<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($eid)) {
	$eid = escape_data($_GET['id']);
	$einfo = mysql_fetch_array (mysql_query ("SELECT location, about, ntk, wtb, vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
}

//test vis
if (($einfo['vis']=='pub')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0)) {
	
	echo '<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">when</td><td align="left" class="p18" style="padding-right: 32px;">'; 
			if (substr($einfo['start'], -7)=='12:00AM') {
				date_default_timezone_set('America/Los_Angeles');
				$systerdy = strtotime("-1 day", strtotime(trim(substr($einfo['start'], 0, 14))));
				$sdate = date("M jS, Y", $systerdy);
				$einfo['start'] = $sdate.' at Midnight';
			} elseif (substr($einfo['start'], -7)=='12:00PM') {
				$einfo['start'] = trim(substr($einfo['start'], 0, 14)).' at Noon';
			}
			if (substr($einfo['end'], -7)=='12:00AM') {
				date_default_timezone_set('America/Los_Angeles');
				$eysterdy = strtotime("-1 day", strtotime(trim(substr($einfo['end'], 0, 14))));
				$edate = date("M jS, Y", $eysterdy);
				$einfo['end'] = $edate.' at Midnight';
			} elseif (substr($einfo['end'], -7)=='12:00PM') {
				$einfo['end'] = trim(substr($einfo['end'], 0, 14)).' at Noon';
			}
			if(substr($einfo['start'], 0, 14)==substr($einfo['end'], 0, 14)){echo $einfo['start'].' until '.trim(substr($einfo['end'], 16));}else{echo $einfo['start'].' to '.$einfo['end'];} 
		echo'</td></tr></table>
	</div>';
	
	if ($einfo ['location'] != '') {
		echo '<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">where</td><td align="left" style="padding-right: 32px;">'.nl2br($einfo ['location']).'</td></tr></table>
		</div>';
	}
	
	if ($einfo ['about'] != '') {
		echo '<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">about</td><td align="left" style="padding-right: 32px;">'.nl2br($einfo ['about']).'</td></tr></table>
		</div>';
	}
	
	if ($einfo ['ntk'] != '') {
		echo '<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">need to know</td><td align="left" style="padding-right: 32px;">'.nl2br($einfo ['ntk']).'</td></tr></table>
		</div>';
	}
	
	if ($einfo ['wtb'] != '') {
		echo '<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">what to bring</td><td align="left" style="padding-right: 32px;">'.nl2br($einfo ['wtb']).'</td></tr></table>
		</div>';
	}
	
	//get custom secs
	$customsecs = @mysql_query("SELECT eie_id, type, content FROM event_info_ext WHERE e_id='$eid' ORDER BY eie_id ASC");
	while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
		$eieid = $customsec['eie_id'];
		if ($customsec['content']!='') {
			echo '<div align="left" style="padding-bottom: 20px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">'.$customsec['type'].'</td><td align="left" style="padding-right: 32px;">'.nl2br($customsec['content']).'</td></tr></table>
			</div>';
		}
	}
	
} else { //unable to view private event
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		This is a private event. You must be invited to be able to view it.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>