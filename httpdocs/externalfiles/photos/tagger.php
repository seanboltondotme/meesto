<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}
	
	$person = mysql_fetch_array (mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
	$name = returnpersonname($id).' '.returncleanrealname($id);
	$response[] = array($id, $name, '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-left: 4px;"><img src="'.$baseincpat.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" /></td><td align="left" valign="top" id="peepname'.$id.'" style="padding-left: 6px; padding-top: 4px;">'.returnpersonname($id).'</td></tr></table>');
	
$peeple = @mysql_query ("SELECT mp.p_id, u.defaultimg_url FROM my_peeple mp INNER JOIN users u ON mp.p_id=u.user_id WHERE mp.u_id='$id'");
while ($person = @mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
	$uid = $person['p_id'];
	$name = returnpersonname($uid).' '.returncleanrealname($uid);
	$response[] = array($uid, $name, '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-left: 4px;"><img src="'.$baseincpat.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" /></td><td align="left" valign="top" id="peepname'.$uid.'" style="padding-left: 6px; padding-top: 4px;">'.returnpersonname($uid).'</td></tr></table>');
}

header('Content-type: application/json');
echo json_encode($response);

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>