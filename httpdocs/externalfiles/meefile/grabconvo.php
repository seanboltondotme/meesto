<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$uid = escape_data($_GET['id']);
$sec = escape_data($_GET['s']);

if (($uid==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0)) {
	if ($sec=='mchist') {
		include ('grabmchist.php');
	} else {
		include ('grabmsgs.php');
	}
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