<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
}

$t = escape_data($_GET['t']);

if ($t=='on') {
	$update = mysql_query("UPDATE users SET mc_sound='y' WHERE user_id='$id'");
} else {
	$update = mysql_query("UPDATE users SET mc_sound=NULL WHERE user_id='$id'");
}

session_write_close();
exit();
?>