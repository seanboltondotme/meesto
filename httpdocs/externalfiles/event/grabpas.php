<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($eid)) {
	$eid = escape_data($_GET['eid']);	
}

//test vis
$einfo = mysql_fetch_array (mysql_query ("SELECT vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
if (($einfo['vis']=='pub')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0)) {

	//grab albums
	echo '<div align="left">';
		//set pagination data
			$display = 6;
			$num_records = mysql_num_rows(mysql_query ("(SELECT DISTINCT pa.pa_id FROM photo_albums pa INNER JOIN patoe_ties patet ON pa.pa_id=patet.pa_id AND patet.e_id='$eid' INNER JOIN photo_album_vis pav ON pa.pa_id=pav.pa_id LEFT JOIN my_peeple mp ON mp.u_id=pa.u_id AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id=pa.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN photo_album_vis pav2 ON pa.pa_id=pav2.pa_id AND pav2.type='user' AND pav2.ref_id='$id' WHERE ((pav.type='pub' AND pav.sub_type='y') OR (((pav.type='strm' AND pav.sub_type=ps.stream) OR (pav.type='chan' AND pav.ref_id=mpc.mpc_id)) AND (pav2.pavis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT pa.pa_id FROM photo_albums pa INNER JOIN ap_tags apt ON pa.pa_id=apt.pa_id AND apt.u_id='$id' INNER JOIN patoe_ties patet ON pa.pa_id=patet.pa_id AND patet.e_id='$eid') UNION DISTINCT (SELECT DISTINCT pa.pa_id FROM photo_albums pa INNER JOIN patoe_ties patet ON pa.pa_id=patet.pa_id AND patet.e_id='$eid' AND pa.u_id='$id')"));
			
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
	echo '<table cellpadding="0" cellspacing="0">';
	$albums = @mysql_query ("(SELECT DISTINCT pa.pa_id, pa.u_id, pa.name, pa.cover_url, pa.date FROM photo_albums pa INNER JOIN patoe_ties patet ON pa.pa_id=patet.pa_id AND patet.e_id='$eid' INNER JOIN photo_album_vis pav ON pa.pa_id=pav.pa_id LEFT JOIN my_peeple mp ON mp.u_id=pa.u_id AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id=pa.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN photo_album_vis pav2 ON pa.pa_id=pav2.pa_id AND pav2.type='user' AND pav2.ref_id='$id' WHERE ((pav.type='pub' AND pav.sub_type='y') OR (((pav.type='strm' AND pav.sub_type=ps.stream) OR (pav.type='chan' AND pav.ref_id=mpc.mpc_id)) AND (pav2.pavis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT pa.pa_id, pa.u_id, pa.name, pa.cover_url, pa.date FROM photo_albums pa INNER JOIN ap_tags apt ON pa.pa_id=apt.pa_id AND apt.u_id='$id' INNER JOIN patoe_ties patet ON pa.pa_id=patet.pa_id AND patet.e_id='$eid') UNION DISTINCT (SELECT DISTINCT pa.pa_id, pa.u_id, pa.name, pa.cover_url, pa.date FROM photo_albums pa INNER JOIN patoe_ties patet ON pa.pa_id=patet.pa_id AND patet.e_id='$eid' AND pa.u_id='$id') ORDER BY date DESC LIMIT $start, $display");
	$i= 0;
	while ($album = @mysql_fetch_array ($albums, MYSQL_ASSOC)) {
		if ($i==0) {
			echo '<tr><td align="center" valign="center">';
		} else {
			echo '<td align="center" valign="center" style="padding-left: 18px;">';	
		}
		echo '<div align="center" style="width: 110px;"><a href="'.$baseincpat.'meefile.php?id='.$album['u_id'].'&t=photos&aid='.$album['pa_id'].'">
		<div align="center" style="padding-left: 4px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" style="background-color: #C5C5C5;"><div align="right" style="height: 4px; width: 6px; background-color: #fff;"></div></td></tr><tr><td align="center">
				<table cellpadding="0" cellspacing="0"><tr><td align="right" valign="top"><img src="'.$baseincpat.$album['cover_url'].'" class="pictn"/></td><td align="left" valign="bottom" style="background-color: #C5C5C5;"><div align="right" style="height: 6px; width: 4px; background-color: #fff;"></div></td></tr></table>
			</td></tr></table>
		</div>
		</a></div>
		<a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&aid='.$album['pa_id'].'"><div align="center" style="width: 120px; color: #000; padding-top: 2px; line-height: 18px;">'; if(strlen($album['name'])>32){echo substr($album['name'], 0, 30).'...';}else{echo $album['name'];} echo'</div></a>
		</td>';
		$i++;
	}
	if ($i==0) {
		echo '<tr><td align="center">none</td>';
	}
	echo '</tr></table>';
	//paginations
		echo '</div><div align="center" style="padding-top: 6px;">
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