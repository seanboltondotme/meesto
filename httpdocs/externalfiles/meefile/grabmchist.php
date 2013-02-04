<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$uid = escape_data($_GET['id']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0) {
	
	//set pagination data
			$display = 32;
			$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM mc_msgs WHERE (s_id='$uid' AND u_id='$id') OR (s_id='$id' AND u_id='$uid')"), 0);
			
			if ($num_records > $display) {
				$num_pages = ceil ($num_records/$display);
			} else {
				$num_pages = 1;
			}
						
			if (isset($_GET['pg'])) {
				$page = escape_data($_GET['pg']);
				$start = ($display*($page-1));
			} else {
				$page = 1;
				$start = 0;
			}
	
	$msgs = mysql_query("SELECT m.mcm_id, m.s_id, m.body, DATE_FORMAT(m.time_stamp, '%b %D, %Y at %l:%i%p') AS time, u.defaultimg_url FROM mc_msgs m INNER JOIN users u ON u.user_id=m.s_id WHERE ((m.s_id='$uid' AND m.u_id='$id') OR (m.s_id='$id' AND m.u_id='$uid')) ORDER BY m.mcm_id DESC LIMIT $start, $display");
	while ($msg = mysql_fetch_array ($msgs, MYSQL_ASSOC)) {
		$msgsid = $msg['s_id'];
		echo '<div align="left" style="margin-bottom: 24px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><a href="'.$baseincpat.'meefile.php?id='.$msgsid.'"><img src="'.$baseincpat.''.$msg['defaultimg_url'].'" /></a></td><td align="left" valign="top" style="padding-left: 12px;">
					<div align="left" class="p24">'.nl2br($msg['body']).'</div>
					<div class="subtext">by '; loadpersonname($msgsid); echo' on '.$msg['time'].'</div>
				</td></tr></table>
		</div>';
	}
	
	//paginations
		echo '<div align="left" style="margin-top: 21px; margin-left: 124px;">
			<table cellpadding="0" cellspacing="0"><tr>';
				if ($num_pages > 1) {
								
					if ($page != 1) {
						echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'s=mchist&pg=' . ($page-1) . '\');">previous</td> ';
					}
								
					for ($i = 1; $i <= $num_pages; $i++) {
						if ($i != $page) {
							echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'s=mchist&pg=' . $i . '\');">' . $i . '</td>';
						} else {
							echo '<td style="padding-right: 3px;" class="paginationlinkOn">' .$i . '</td> ';
						}
					}
					if ($page != $num_pages) {
						echo '<td class="paginationlinks" onclick="backcontrol.setState(\'s=mchist&pg=' . ($page+1) . '\');">next</td>';
					}
				}
			echo '</tr></table>
			</div>';
	
	
} else { //if not vis
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>