<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
}

$cid = escape_data($_GET['cid']);
$msg = escape_form_data(urldecode($_GET['msg']));

$insertmsg = @mysql_query ("INSERT INTO mc_msgs (s_id, u_id, body, time_stamp) VALUES ('$id', '$cid', '$msg', NOW())");
$msgid = mysql_insert_id();

if (mysql_result (mysql_query("SELECT COUNT(*) FROM mc_open WHERE u_id='$cid' AND c_id='$id' LIMIT 1"), 0)==0) {
	$lmv = mysql_result (mysql_query("SELECT mcm_id FROM mc_msgs WHERE mcm_id<'$msgid' AND ((s_id='$cid' AND u_id='$id') OR (s_id='$id' AND u_id='$cid')) ORDER BY time_stamp DESC LIMIT 1"), 0);
	$insert = mysql_query("INSERT INTO mc_open (u_id, c_id, lmv, time_stamp) VALUES ('$cid', '$id', '$lmv', NOW())");
}

include ('grabmsgs.php');


session_write_close();
exit();
?>