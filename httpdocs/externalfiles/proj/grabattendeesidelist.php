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
}

if ($id>0) {//test vis
	
		echo '<div align="left" class="p24">The Team</div><div align="left" style="margin-left: 12px;">';
			$team_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND type='a'"), 0);
			if ($team_ct>0) {
				$teams = mysql_query ("SELECT cpm.u_id, u.defaultimg_url FROM commproj_mem cpm INNER JOIN users u ON cpm.u_id=u.user_id WHERE cpm.cp_id='$cpid' AND cpm.type='a' ORDER BY RAND() LIMIT 6");
				while ($team = mysql_fetch_array ($teams, MYSQL_ASSOC)) {
					$uid = $team['u_id'];
					echo '<div align="left" style="padding-top: 4px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.''.substr($team['defaultimg_url'], 0, -4).'m'.substr($team['defaultimg_url'], -4).'" /></a>
						</td><td align="left" valign="top" style="padding-left: 4px; padding-top: 2px;">'; loadpersonname($uid); echo '</td></tr></table>
					</div>';
				}
			} else {
				echo '<div align="left" style="padding-top: 4px;">None yet.</div>';
			}
		echo '</div><div align="right" style="padding-right: 6px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/proj/viewsupporters.php?id='.$cpid.'&fltr=t\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$team_ct.')</div>
		<div align="left" class="p24" style="padding-top: 22px;">Supporters</div><div align="left" style="margin-left: 12px;">';
			$sup_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid'"), 0);
			if ($sup_ct>0) {
				$sups = mysql_query ("SELECT cpm.u_id, u.defaultimg_url FROM commproj_mem cpm INNER JOIN users u ON cpm.u_id=u.user_id WHERE cpm.cp_id='$cpid' ORDER BY RAND() LIMIT 6");
				while ($sup = mysql_fetch_array ($sups, MYSQL_ASSOC)) {
					$uid = $sup['u_id'];
					echo '<div align="left" style="padding-top: 4px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.''.substr($sup['defaultimg_url'], 0, -4).'m'.substr($sup['defaultimg_url'], -4).'" /></a>
						</td><td align="left" valign="top" style="padding-left: 4px; padding-top: 2px;">'; loadpersonname($uid); echo '</td></tr></table>
					</div>';
				}
			} else {
				echo '<div align="left" style="padding-top: 4px;">None yet.</div>';
			}
			echo '</div><div align="right" style="padding-right: 6px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/proj/viewsupporters.php?id='.$cpid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$sup_ct.')</div>';
		
} else { //if not able to view
	echo '<div align="left" valign="top" style="padding: 6px;">
		You must login to view this information.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>