<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$eid = escape_data($_GET['id']);
$einfo = mysql_fetch_array (mysql_query ("SELECT vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);

//test if can invite or if admin
if (($einfo['vis']=='pub')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0)) {

$peeple = @mysql_query ("SELECT u_id, rsvp FROM event_owners WHERE e_id='$eid'");
while ($person = @mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
	$uid = $person['u_id'];
	$name = returnpersonname($uid).' '.returncleanrealname($uid);
	$response[] = array($uid, $name, $person['rsvp']);
}

} else { //if not able to view
	$response[] = array();	
}

header('Content-type: application/json');
echo json_encode($response);

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>