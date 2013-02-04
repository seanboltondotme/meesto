<?php
if ((!$stream)&&(!$cid)) {
	$stream = escape_data($_GET['stream']);
	$cid = escape_data($_GET['cid']);
}

if ($cid!='') {
	
} else {
	
	$peeple = mysql_query ("SELECT DISTINCT ps.p_id, ps.stream, u.defaultimg_url FROM peep_streams ps INNER JOIN sessions s ON (ps.p_id=s.u_id) AND s.client='pc' AND (s.last_accessed>SUBDATE(NOW(), INTERVAL 8 SECOND)) INNER JOIN users u ON (ps.p_id=u.user_id) WHERE ps.u_id='$id' AND ps.stream='$stream' ORDER BY u.last_name ASC");
	while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
		$uid = $person['p_id'];
		$stream = $person['stream'];
		if (mysql_result (mysql_query("SELECT COUNT(*) FROM mc_vis WHERE u_id='$uid' AND type='strm' AND sub_type='$stream' LIMIT 1"), 0)>0) {
			echo '<div align="left" width="100%" style="border-bottom: 1px solid #C5C5C5; padding-top: 4px; padding-bottom: 2px; cursor: pointer;" onclick="if($(\'chat_pers'.$uid.'\')){meechat.openChat('.$uid.');}else{meechat.newChat(\''.$uid.'\');} $(\'chat_content\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
				<table cellpadding="0" cellspacing="0"><tr><td align="right" valign="top" width="36px">
					<img src="'.$baseincpat.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" />
				</td><td align="left" valign="top" width="538px" style="padding-left: 6px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" class="namelink">';
						if (strlen(returnpersonname($uid))>15) {
							substr(loadpersonnamenolink($uid), 0, 15).'...';
						} else {
							loadpersonnamenolink($uid);
						}
					echo '</td></tr><tr><td align="left" style="padding-left: 6px;" class="paragraph10p60"></td></tr></table>
				</td></tr></table>
			</div>';
		}
	}
	
}
?>