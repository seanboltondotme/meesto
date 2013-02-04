<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($t)) {
	$t = escape_data($_GET['t']);	
}
if (!isset($uid)) {
	$uid = escape_data($_GET['id']);	
}

$mtinfo = mysql_fetch_array (mysql_query ("SELECT u_id, name, description FROM meefile_tab WHERE mt_id='$t' ORDER BY time_stamp ASC"), MYSQL_ASSOC);

//test owner
if ($mtinfo['u_id']==$uid) {
	
if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$t' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$t'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$t'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$t' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {

if ($mtinfo['u_id']==$id) {
	echo '<div align="left" style="margin-top: 4px; margin-left: 112px;">
		<table cellpadding="0" cellspacing="0" width="870px"><tr><td align="left" valign="top">'.$mtinfo['description'].'</td><td align="right" valign="top" style="padding-top: 4px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left">
				<input type="button" id="edit" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmt.php?id='.$t.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
			</td><td align="left" style="padding-left: 12px;">
				<input type="button" id="visibility" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmtvis.php?id='.$t.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
			</td><td align="left" style="padding-left: 12px;">
				<input type="button" id="delete" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletemt.php?id='.$t.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
			</td></tr></table>
		</td></tr></table>
	</div>';
} elseif ($mtinfo['description']!='') {
	echo '<div align="left" style="margin-top: 4px; margin-left: 112px;">'.$mtinfo['description'].'</div>';
}
echo '<div align="left" id="maincontent" style="margin-top: 18px; margin-left: 112px;">';
	$mtid = $t;
	include('externalfiles/meefile/grabmtsecs.php');
echo '</div>';

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