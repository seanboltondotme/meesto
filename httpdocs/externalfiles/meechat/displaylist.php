<?php
$streams = array(
	"My Bubble" => "mb",
	"Friends" => "frnd",
	"Family" => "fam",
	"Professional" => "prof",
	"Education" => "edu",
	"Just Met Mee" => "aqu"
);
foreach ($streams as $name => $stream)  {
	echo'<div align="right" style="border-bottom: 1px solid #000;">
		<div class="chatgrpOn" align="left"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" style="padding-left: 2px;"><div class="chat_stat" style="height: 6px; width: 6px;"></div></td><td align="right" valign="center" style="padding-left: 4px;">'.$name.'</td></tr></table></div>
		<div align="left" id="chatlist'.$stream.'" style="width: 169px; padding-top: 2px; padding-bottom: 12px; border-top: 1px solid #C5C5C5;">';
			include('externalfiles/meechat/grabpeep.php'); 
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
?>