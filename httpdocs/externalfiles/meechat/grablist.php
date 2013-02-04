<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$streams = array(
	"My Bubble" => "mb",
	"Friends" => "frnd",
	"Family" => "fam",
	"Professional" => "prof",
	"Education" => "edu",
	"Just Met Mee" => "aqu"
);
foreach ($streams as $name => $stream)  {
	$ison = false;
	echo'<div align="right" style="border-bottom: 1px solid #000;">
		<div class="chatgrp';
		if (mysql_result (mysql_query("SELECT COUNT(*) FROM mc_vis WHERE u_id='$id' AND type='strm' AND sub_type='$stream' LIMIT 1"), 0)>0) {
			$ison = true;
			echo 'On';
		}
		echo '" align="left" onclick="if(this.hasClass(\'chatgrp\')){this.set(\'class\', \'chatgrpOn\');if($(\'mc_mainstat\').hasClass(\'chatgrp\')){$(\'mc_mainstat\').set(\'class\', \'chatgrpOn\');$(\'mc_mainstat_text\').set(\'html\', \'Online\');}}else{this.set(\'class\', \'chatgrp\');if(meechat.toggleStat_OffChk()){$(\'mc_mainstat\').set(\'class\', \'chatgrp\');$(\'mc_mainstat_text\').set(\'html\', \'Offline\');}} meechat.injectLoader(\'chatlist'.$stream.'\'); loadcont(\'chat_main\', \''.$baseincpat.'externalfiles/meechat/togglestat.php?type=strm&param='.$stream.'\');"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" style="padding-left: 2px;"><div class="chat_stat" style="height: 6px; width: 6px;"></div></td><td align="right" valign="center" style="padding-left: 4px;">'.$name.'</td></tr></table></div>
		<div align="left" id="chatlist'.$stream.'" style="width: 169px; padding-top: 2px; padding-bottom: 12px; border-top: 1px solid #C5C5C5;">';
			if ($ison) {
				include('grabpeep.php');
			}
		echo'</div>
	</div>';
}
/*$channels = @mysql_query("SELECT mpc_id, name FROM my_peeple_channels WHERE u_id='$id' ORDER BY name ASC");
while ($channel = @mysql_fetch_array ($channels, MYSQL_ASSOC)) {
	echo'<div align="right" style="border-bottom: 1px solid #000;">
		<div class="chatgrp" align="left"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" style="padding-left: 2px;"><div class="chat_stat" style="height: 6px; width: 6px;"></div></td><td align="right" valign="center" style="padding-left: 4px;">'.$channel['name'].'</td></tr></table></div>
		<div align="left" id="chatlist'.$channel['mpc_id'].'" style="width: 154px; padding-top: 2px; padding-bottom: 12px; border-top: 1px solid #C5C5C5;">asd</div>
	</div>';
				
}*/

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>