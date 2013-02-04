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

if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN defvis_apt piv ON (piv.u_id='$uid' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN defvis_apt piv ON (piv.u_id='$uid' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
	
	//grab albums
	echo '<div align="left">';
		//set pagination data
			$display = 28;
			if ($uid==$id) {
				$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM ap_tags WHERE u_id='$uid'"), 0);
			} else {
				$num_records = mysql_num_rows(mysql_query ("(SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN album_photos_vis apv ON ap.ap_id=apv.ap_id LEFT JOIN my_peeple mp ON mp.u_id='$uid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$uid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN album_photos_vis apv2 ON ap.ap_id=apv2.ap_id AND apv2.type='user' AND apv2.ref_id='$id' WHERE apt.u_id='$uid' AND ((apv.type='pub' AND apv.sub_type='y') OR (((apv.type='strm' AND apv.sub_type=ps.stream) OR (apv.type='chan' AND apv.ref_id=mpc.mpc_id)) AND (apv2.apvis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN ap_tags apt2 ON ap.ap_id=apt2.ap_id AND apt2.u_id='$id' WHERE apt.u_id='$uid') ORDER BY apt_id DESC"));
			}
			
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
			
	echo '<table cellpadding="0" cellspacing="0" style="padding-top: 8px;">';
	if ($uid==$id) {
		$photos = @mysql_query ("SELECT apt.apt_id, ap.url FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id WHERE apt.u_id='$uid' ORDER BY apt.apt_id DESC LIMIT $start, $display");
	} else {
		$photos = @mysql_query ("(SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN album_photos_vis apv ON ap.ap_id=apv.ap_id LEFT JOIN my_peeple mp ON mp.u_id='$uid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$uid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN album_photos_vis apv2 ON ap.ap_id=apv2.ap_id AND apv2.type='user' AND apv2.ref_id='$id' WHERE apt.u_id='$uid' AND ((apv.type='pub' AND apv.sub_type='y') OR (((apv.type='strm' AND apv.sub_type=ps.stream) OR (apv.type='chan' AND apv.ref_id=mpc.mpc_id)) AND (apv2.apvis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN ap_tags apt2 ON ap.ap_id=apt2.ap_id AND apt2.u_id='$id' WHERE apt.u_id='$uid') ORDER BY apt_id DESC LIMIT $start, $display");
	}
	$i = 0;
	while ($photo = @mysql_fetch_array ($photos, MYSQL_ASSOC)) {
		if ($i==0) {
			echo '<tr><td align="center" valign="center">';
		} elseif ($i==7) {
			echo '</tr></table><table cellpadding="0" cellspacing="0" style="padding-top: 14px;"><tr><td align="center" valign="center">';
			$i = 0;	
		} else {
			echo '<td align="center" valign="center" style="padding-left: 18px;">';	
		}
		echo '<div align="center" style="width: 110px;"><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&view=taggedpic&#aptid='.$photo['apt_id'].'"><img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn"/></a></div></td>';
		$i++;
	}
	if ($i==0) {
		echo '<tr><td align="center">none</td>';
	}
	echo '</tr></table>';
	//paginations
		echo '</div><div align="center" style="padding-top: 14px;">
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