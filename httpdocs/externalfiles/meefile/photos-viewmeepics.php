<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

echo '<div align="left" style="margin-left: 80px;">
<div align="left" style="margin-left: 10px;">
	<table cellpadding="0" cellspacing="0" width="880px"><tr><td align="right" valign="center">';
		if($uid==$id)	{
			echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="button" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editsecvis.php?sec=meepic\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td><td align="left" valign="center" style="padding-left: 12px;"><input type="button" value="edit Meepic" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmeepic.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>';
		}
	echo '</td></tr></table>
</div>
<div align="left" id="maincontent" style="padding-top: 4px;">';
	include('grabmeepics.php');
echo '</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>