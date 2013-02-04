<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($mtid)) {
	$mtid = escape_data($_GET['t']);	
}
if (!isset($uid)) {
	$uid = escape_data($_GET['id']);	
}

$mtinfo = mysql_fetch_array (mysql_query ("SELECT u_id, name, description FROM meefile_tab WHERE mt_id='$mtid' ORDER BY time_stamp ASC"), MYSQL_ASSOC);

//test owner
if ($mtinfo['u_id']==$uid) {
	
if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$mtid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$mtid'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$mtid'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$mtid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {

	//get mtsecs
	if (isset($_GET['vid'])&&is_numeric($_GET['vid'])) {
		$vid = escape_data($_GET['vid']);
		$viewsingle = true;
		$customsecs = mysql_query("SELECT mts_id FROM meefile_tab_sec WHERE mts_id='$vid' LIMIT 1");
	} else {
		$viewsingle = false;
		
		//set pagination data
			$display = 6;
			if ($uid==$id) {
				$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM meefile_tab_sec WHERE mt_id='$mtid'"), 0);
			} else {
				$mtsuid = $mtinfo['u_id'];
				$num_records = mysql_result(mysql_query ("SELECT COUNT(DISTINCT mts.mts_id) FROM meefile_tab_sec mts INNER JOIN meefile_tab_sec_vis mtsv ON mts.mts_id=mtsv.mts_id LEFT JOIN my_peeple mp ON mp.u_id='$mtsuid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$mtsuid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN meefile_tab_sec_vis mtsv2 ON mts.mts_id=mtsv2.mts_id AND mtsv2.type='user' AND mtsv2.ref_id='$id' WHERE (mts.mt_id='$mtid') AND ((mtsv.type='pub' AND mtsv.sub_type='y') OR (((mtsv.type='strm' AND mtsv.sub_type=ps.stream) OR (mtsv.type='chan' AND mtsv.ref_id=mpc.mpc_id)) AND (mtsv2.mtsvis_id IS NULL)))"), 0);
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
	
		if (($page==1)&&($uid==$id)) {
		echo '<div align="left" style="padding-bottom: 20px;">
			<input type="button" id="addnewcustsec" value="add new section" onclick="var newElem = new Element(\'div\', {\'align\': \'left\'});newElem.inject($(\'mtsecs\'), \'top\');gotopage(newElem, \''.$baseincpat.'externalfiles/meefile/addmtsec.php?t='.$mtid.'\');"/>
		</div>';
		}
		
		if ($uid==$id) {
			$customsecs = mysql_query("SELECT mts_id FROM meefile_tab_sec WHERE mt_id='$mtid' ORDER BY mts_id DESC LIMIT $start, $display");
		} else {
			$customsecs = mysql_query("SELECT DISTINCT mts.mts_id FROM meefile_tab_sec mts INNER JOIN meefile_tab_sec_vis mtsv ON mts.mts_id=mtsv.mts_id LEFT JOIN my_peeple mp ON mp.u_id='$mtsuid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$mtsuid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN meefile_tab_sec_vis mtsv2 ON mts.mts_id=mtsv2.mts_id AND mtsv2.type='user' AND mtsv2.ref_id='$id' WHERE (mts.mt_id='$mtid') AND ((mtsv.type='pub' AND mtsv.sub_type='y') OR (((mtsv.type='strm' AND mtsv.sub_type=ps.stream) OR (mtsv.type='chan' AND mtsv.ref_id=mpc.mpc_id)) AND (mtsv2.mtsvis_id IS NULL))) ORDER BY mts.mts_id DESC LIMIT $start, $display");
		}
	}
	
	echo '<div align="left" id="mtsecs">';
	
	while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
		$mtsid = $customsec['mts_id'];
		include('grabmtsec.php');
	}
	if (!$viewsingle) { //if viewing more than one
		//paginations
		echo '</div><div align="center">
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
	}

} else { //if not vis
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

} else { //not correct owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		This person does not own this meefile tab.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>