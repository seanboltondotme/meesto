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
	<table cellpadding="0" cellspacing="0" width="880px"><tr><td align="left" valign="center">'; if($painfo['description']!=''){echo $painfo['description'];} echo'</td><td align="right" valign="center">';
		if($painfo['u_id']==$id)	{
			echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><form method="get" action="'.$baseincpat.'editalbum.php"><input type="hidden" name="id" value="'.$aid.'"/><input type="submit" value="edit album"/></form></td><td align="left" valign="center" style="padding-left: 12px;"><input type="button" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/editpavis.php?id='.$aid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td><td align="left" valign="center" style="padding-left: 12px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/deletepa.php?id='.$aid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>';
		}
	echo '</td></tr></table>
</div>
<div align="left" id="maincontent" style="padding-top: 4px;">';
	include('grabalbumphotos.php');
echo '</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>