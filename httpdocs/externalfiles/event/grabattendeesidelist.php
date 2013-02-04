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
	$einfo = mysql_fetch_array (mysql_query ("SELECT vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
}

if (($einfo['vis']=='pub')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0)) { //test for owner
	
		echo '<div align="left" class="p24" style="padding-top: 22px;">Attending</div><div align="left" style="margin-left: 12px;">';
			$attnding_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND rsvp='a'"), 0);
			if ($attnding_ct>0) {
				$attndings = mysql_query ("SELECT eo.u_id, u.defaultimg_url FROM event_owners eo INNER JOIN users u ON eo.u_id=u.user_id WHERE eo.e_id='$eid' AND eo.rsvp='a' ORDER BY RAND() LIMIT 6");
				while ($attnding = mysql_fetch_array ($attndings, MYSQL_ASSOC)) {
					$uid = $attnding['u_id'];
					echo '<div align="left" style="padding-top: 4px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.''.substr($attnding['defaultimg_url'], 0, -4).'m'.substr($attnding['defaultimg_url'], -4).'" /></a>
						</td><td align="left" valign="top" style="padding-left: 4px; padding-top: 2px;">'; loadpersonname($uid); echo '</td></tr></table>
					</div>';
				}
			} else {
				echo '<div align="left" style="padding-top: 4px;">None attending.</div>';
			}
		echo '</div><div align="right" style="padding-right: 6px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/viewattendees.php?id='.$eid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$attnding_ct.')</div>';
			$mattnd_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND rsvp='m'"), 0);
			if ($mattnd_ct>0) {
				echo '<div align="left" class="p24" style="padding-top: 22px;">Might Attend</div><div align="left" style="margin-left: 12px;">';
					$mattnds = mysql_query ("SELECT eo.u_id, u.defaultimg_url FROM event_owners eo INNER JOIN users u ON eo.u_id=u.user_id WHERE eo.e_id='$eid'  AND eo.rsvp='m' ORDER BY RAND() LIMIT 6");
					while ($mattnd = mysql_fetch_array ($mattnds, MYSQL_ASSOC)) {
						$uid = $mattnd['u_id'];
						echo '<div align="left" style="padding-top: 4px;">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
								<a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.''.substr($mattnd['defaultimg_url'], 0, -4).'m'.substr($mattnd['defaultimg_url'], -4).'" /></a>
							</td><td align="left" valign="top" style="padding-left: 4px; padding-top: 2px;">'; loadpersonname($uid); echo '</td></tr></table>
						</div>';
					}
				echo '</div><div align="right" style="padding-right: 6px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/viewattendees.php?id='.$eid.'&fltr=m\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$mattnd_ct.')</div>';
			}
			$nattnding_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND rsvp='n'"), 0);
			if ($nattnding_ct>0) {
				echo '<div align="left" class="p24" style="padding-top: 22px;">Not Attending</div><div align="left" style="margin-left: 12px;">';
					$nattndings = mysql_query ("SELECT eo.u_id, u.defaultimg_url FROM event_owners eo INNER JOIN users u ON eo.u_id=u.user_id WHERE eo.e_id='$eid' AND eo.rsvp='n' ORDER BY RAND() LIMIT 4");
					while ($nattnding = mysql_fetch_array ($nattndings, MYSQL_ASSOC)) {
						$uid = $nattnding['u_id'];
						echo '<div align="left" style="padding-top: 4px;">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
								<a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.''.substr($nattnding['defaultimg_url'], 0, -4).'m'.substr($nattnding['defaultimg_url'], -4).'" /></a>
							</td><td align="left" valign="top" style="padding-left: 4px; padding-top: 2px;">'; loadpersonname($uid); echo '</td></tr></table>
						</div>';
					}
				echo '</div><div align="right" style="padding-right: 6px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/viewattendees.php?id='.$eid.'&fltr=n\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$nattnding_ct.')</div>';
			}
		
} else { //if not able to view
	echo '<div align="left" valign="top" style="padding: 6px;">
		You can\'t view this event.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>