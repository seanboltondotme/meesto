<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

		$display = 12;
		$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM events e INNER JOIN event_owners eo ON e.e_id=eo.e_id AND eo.u_id='$id' AND eo.rsvp IS NOT NULL AND NOW()>e.end_date"), 0);
					
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
	$events = mysql_query ("SELECT e.e_id, e.name, e.defaultimg_url, DATE_FORMAT(e.start_date, '%b %D, %Y') AS start, DATE_FORMAT(e.end_date, '%b %D, %Y at %l:%i%p') AS end, eo.rsvp FROM events e INNER JOIN event_owners eo ON e.e_id=eo.e_id AND eo.u_id='$id' AND eo.rsvp IS NOT NULL AND NOW()>e.end_date ORDER BY start_date ASC LIMIT $start, $display");
	while ($event = mysql_fetch_array ($events, MYSQL_ASSOC)) {
		$eid = $event['e_id'];
			
		echo '<div style="padding-bottom: 8px;">
			<a href="'.$baseincpat.'event.php?id='.$eid.'"><span class="p18">'.$event['name'].'</span></a> <span class="subtext" style="font-size: 14px;">('; 
					if (substr($event['start'], -7)=='12:00AM') {
						$systerdy = strtotime("-1 day", strtotime(trim(substr($event['start'], 0, 14))));
						$sdate = date("M jS, Y", $systerdy);
						$event['start'] = $sdate.' at Midnight';
					} elseif (substr($event['start'], -7)=='12:00PM') {
						$event['start'] = trim(substr($event['start'], 0, 14)).' at Noon';
					}
					echo $event['start'].')</span>
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
					echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="gotopage(\'pastevntsmain\', \''.$baseincpat.'externalfiles/cal/grabpast.php?pg=' . ($page-1) . '\');">previous</td> ';
				}
							
				for ($i = 1; $i <= $num_pages; $i++) {
					if ($i != $page) {
						echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="gotopage(\'pastevntsmain\', \''.$baseincpat.'externalfiles/cal/grabpast.php?pg=' . $i . '\');">' . $i . '</td>';
					} else {
						echo '<td style="padding-right: 3px;" class="paginationlinkOn">' .$i . '</td> ';
					}
				}
				if ($page != $num_pages) {
					echo '<td class="paginationlinks" onclick="gotopage(\'pastevntsmain\', \''.$baseincpat.'externalfiles/cal/grabpast.php?pg=' . ($page+1) . '\');">next</td>';
				}
			}
		echo '</tr></table>
		</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>