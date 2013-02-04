<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($cpid)) {
	$cpid = escape_data($_GET['id']);
	$cpinfo = mysql_fetch_array (mysql_query ("SELECT type, stat, timeline, about, u_id FROM comm_projs WHERE cp_id='$cpid' LIMIT 1"), MYSQL_ASSOC);
}
	
	echo '<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">project status</td><td align="left" class="p18" style="padding-right: 32px;">'; 
			if ($cpinfo['stat']=='') {
				
			} else {
				echo 'Pending';	
			}
		echo'</td></tr></table>
	</div>';
	
	if ($cpinfo['timeline'] != '') {
		echo '<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">timeline</td><td align="left" style="padding-right: 32px;">'.nl2br($cpinfo['timeline']).'</td></tr></table>
		</div>';
	}
	
	echo '<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">created by</td><td align="left" style="padding-right: 32px;">'; loadpersonname($cpinfo['u_id']); echo'</td></tr></table>
	</div>';
	
	if ($cpinfo['about'] != '') {
		echo '<div align="left" style="padding-bottom: 20px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">about</td><td align="left" style="padding-right: 32px;">'.nl2br($cpinfo['about']).'</td></tr></table>
		</div>';
	}
	
	//get custom secs
	$customsecs = mysql_query("SELECT cpie_id, type, content FROM commproj_info_ext WHERE cp_id='$cpid' ORDER BY cpie_id ASC");
	while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
		$cpieid = $customsec['cpie_id'];
		if ($customsec['content']!='') {
			echo '<div align="left" style="padding-bottom: 20px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">'.$customsec['type'].'</td><td align="left" style="padding-right: 32px;">'.nl2br($customsec['content']).'</td></tr></table>
			</div>';
		}
	}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>