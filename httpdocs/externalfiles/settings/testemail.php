<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

//test for avail email
	$e = strtolower(strip_tags(escape_data(urldecode($_GET['e']))));
	if (($e!='')&&(mysql_num_rows(@mysql_query("SELECT user_id FROM users WHERE email='$e' LIMIT 1")) == 0)) {
		echo 'avail';
	} else {
		echo 'no';
	}


if (isset($minses)) {
	session_write_close();
	exit();	
}
?>