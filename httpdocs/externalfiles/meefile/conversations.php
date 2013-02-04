<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

echo '<div align="left" style="margin-top: 4px; margin-left: 238px;">'; if($uid==$id){echo'these are private conversations you\'ve received';}else{echo'these are private conversations between you and '.$fn;} echo'</div>
<div align="left" style="margin-top: 28px; margin-left: 76px;">
<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" id="filterlist" width="180px">
	
	<div class="filterOn" id="fltrelm-0" onclick="backcontrol.setState(\'0\');">
		<div>messages</div>
		<div class="underbar" style="background-color: #000;"></div>
	</div>';
	if ($id!=$uid) {
		echo '<div class="filter" id="fltrelm-s=mchist"  onclick="backcontrol.setState(\'s=mchist\');">
			<div>meechat history</div>
			<div class="underbar" style="background-color: #000;"></div>
		</div>';
	}
	
echo '</td><td align="left"  valign="top" style="padding-left: 26px;">
	<div id="maincontent" style="width: 720px;">';
	include ('externalfiles/meefile/grabmsgs.php');
echo '</div>
</td></tr></table>
</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>