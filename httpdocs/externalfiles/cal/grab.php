<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

		$display = 12;
		$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM events e INNER JOIN event_owners eo ON e.e_id=eo.e_id AND eo.u_id='$id' AND eo.rsvp IS NOT NULL AND NOW()<=e.end_date"), 0);
					
		if ($num_records > $display) {
			$num_pages = ceil ($num_records/$display);
		} else {
			$num_pages = 1;
		}
					
		if (isset($_GET['pg'])&&is_numeric($_GET['pg'])) {
			$page = escape_data($_GET['pg']);
			$start = ($display*($page-1));
		} else {
			$page = 1;
			$start = 0;
		}
	
	date_default_timezone_set('America/Los_Angeles');
	
	//display my peeple
	$events = mysql_query ("SELECT e.e_id, e.name, e.defaultimg_url, DATE_FORMAT(e.start_date, '%b %D, %Y at %l:%i%p') AS start, DATE_FORMAT(e.end_date, '%b %D, %Y at %l:%i%p') AS end, eo.rsvp FROM events e INNER JOIN event_owners eo ON e.e_id=eo.e_id AND eo.u_id='$id' AND eo.rsvp IS NOT NULL AND NOW()<=e.end_date ORDER BY start_date ASC LIMIT $start, $display");
	while ($event = mysql_fetch_array ($events, MYSQL_ASSOC)) {
		$eid = $event['e_id'];
			
		echo '<div style="padding-bottom: 24px;" onmouseover="$(\'btns'.$eid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btns'.$eid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="90px"><a href="'.$baseincpat.'event.php?id='.$eid.'"><img src="'.$baseincpat.''.substr($event['defaultimg_url'], 0, -5).'tn'.substr($event['defaultimg_url'], -4).'" /></a></td><td align="left" valign="top" width="462px" style="padding-left: 12px;">
				<a href="'.$baseincpat.'event.php?id='.$eid.'">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24">'.$event['name'].'</td></tr><tr><td align="left" class="subtext" style="padding-top: 2px;">'; 
					if (substr($event['start'], -7)=='12:00AM') {
						$systerdy = strtotime("-1 day", strtotime(trim(substr($event['start'], 0, 14))));
						$sdate = date("M jS, Y", $systerdy);
						$event['start'] = $sdate.' at Midnight';
					} elseif (substr($event['start'], -7)=='12:00PM') {
						$event['start'] = trim(substr($event['start'], 0, 14)).' at Noon';
					}
					if (substr($event['end'], -7)=='12:00AM') {
						$eysterdy = strtotime("-1 day", strtotime(trim(substr($event['end'], 0, 14))));
						$edate = date("M jS, Y", $eysterdy);
						$event['end'] = $edate.' at Midnight';
					} elseif (substr($event['end'], -7)=='12:00PM') {
						$event['end'] = trim(substr($event['end'], 0, 14)).' at Noon';
					}
					if(substr($event['start'], 0, 14)==substr($event['end'], 0, 14)){echo $event['start'].' until '.trim(substr($event['end'], 16));}else{echo $event['start'].' to '.$event['end'];} 
				echo'</td></tr><tr><td align="left" style="color: #000; padding-top: 2px;">';
					//show rsvp
					if ($event['rsvp']=='a') {
						echo 'You are attending.';	
					} elseif ($event['rsvp']=='m') {
						echo 'You might attend.';	
					} else {
						echo 'You aren\'t attending.';	
					}
				echo '</td></tr></table>
				</a>
			</td><td align="left" valign="top" width="100px" style="padding-left: 12px;">
				<div id="btns'.$eid.'" align="left" style="padding-top: 6px; visibility: hidden; zoom: 1; opacity: 0;">';
					//test if can invite or if admin
					if (($einfo['vis']=='pub')||($einfo['vis']=='privci')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
						echo '<input type="button" value="invite" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/invite.php?id='.$eid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>';	
					}
				echo '</div>
			</td></tr></table>
		</div>';
	}
	//if no records
	if ($num_records==0) {
		echo '<div align="left">no events here yet</div>';
	}
	//paginations
	echo '<div align="center">
		<table cellpadding="0" cellspacing="0"><tr>';
			if ($num_pages > 1) {
							
				if ($page != 1) {
					echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'pg=' . ($page-1) . '\');">previous</td> ';
				}
							
				for ($i = 1; $i <= $num_pages; $i++) {
					if ($i != $page) {
						echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'pg=' . $i . '\');">' . $i . '</td>';
					} else {
						echo '<td style="padding-right: 3px;" class="paginationlinkOn">' .$i . '</td> ';
					}
				}
				if ($page != $num_pages) {
					echo '<td class="paginationlinks" onclick="backcontrol.setState(\'pg=' . ($page+1) . '\');">next</td>';
				}
			}
		echo '</tr></table>
		</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>