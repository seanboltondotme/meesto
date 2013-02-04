<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses2 = true;
}

//this is only used when comment is edited, as an AJAX for that content......not used for standard view photo

$apid = escape_data($_GET['id']);
$uid = mysql_result(mysql_query("SELECT pa.u_id FROM photo_albums pa INNER JOIN album_photos ap ON ap.pa_id=pa.pa_id AND ap.ap_id='$apid' LIMIT 1"), 0);

if (($uid==$id)) {
	$apinfo_caption = mysql_fetch_array (mysql_query ("SELECT ap.caption FROM photo_albums pa INNER JOIN album_photos ap ON ap.pa_id=pa.pa_id AND ap.ap_id='$apid' LIMIT 1"), MYSQL_ASSOC);
	echo $apinfo_caption['caption'];
}

if (isset($minses2)) {
	session_write_close();
	exit();	
}
?>