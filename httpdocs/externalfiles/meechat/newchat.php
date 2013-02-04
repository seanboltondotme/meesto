<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!$cid) {
	$cid = escape_data($_GET['cid']);
}

if (isset($_GET['a'])&&($_GET['a']!='')) {
	$a = escape_data($_GET['a']);
}

if (mysql_result (mysql_query("SELECT COUNT(*) FROM mc_open WHERE u_id='$id' AND c_id='$cid' LIMIT 1"), 0)==0) {
	$fmv = mysql_result(mysql_query("SELECT mcm_id FROM mc_msgs WHERE (u_id='$id' and s_id='$cid') OR (u_id='$cid' and s_id='$id') ORDER BY mcm_id DESC LIMIT 6, 1"), 0);
	if ($a=='b1') { //set lmv back 1 for chat sent to peep with no chat window open
		$lmv = mysql_result(mysql_query("SELECT mcm_id FROM mc_msgs WHERE (u_id='$id' and s_id='$cid') OR (u_id='$cid' and s_id='$id') ORDER BY mcm_id DESC LIMIT 1, 1"), 0);
	} else {
		$lmv = mysql_result(mysql_query("SELECT mcm_id FROM mc_msgs WHERE (u_id='$id' and s_id='$cid') OR (u_id='$cid' and s_id='$id') ORDER BY mcm_id DESC LIMIT 1"), 0);
	}
	$insert = mysql_query("INSERT INTO mc_open (u_id, c_id, fmv, lmv, time_stamp) VALUES ('$id', '$cid', '$fmv', '$lmv', NOW())");
}

echo '<div style="position: relative; top: 0px; left: 0px;">
							<div class="p18" align="center" style="position: absolute; top: 6px; left: 0px; z-index: 100; width: 134px; height: 36px; cursor: pointer;" onclick="if($(\'chat_convocont'.$cid.'\').getStyles(\'visibility\').visibility==\'visible\'){ meechat.hideChat('.$cid.'); }else{ meechat.openChat('.$cid.'); }">'; if (strlen(returnpersonname($cid))>13) {echo substr(returnpersonname($cid), 0, 10).'...';}else{loadpersonnameclean($cid);} echo'</div>
							
							<div align="right" id="chat_badge'.$cid.'" class="chat_badge" style="position: absolute; top: -16px; right: 0px; z-index: 110; visibility: hidden; zoom: 1; opacity: 0;">0</div>
							
							<div align="left" id="chat_convocont'.$cid.'" class="chat_convocont" style="position: absolute; top: -288px; left: -30px; width: 258px; height: 280px;'; if(isset($_GET['ns'])&&($_GET['ns']==true)){echo' visibility: hidden; zoom: 1; opacity: 0;';} echo'">
								<div align="right" style="position: absolute; top: -16px; left: -3px; width: 264px; height: 16px; background-color: #C5C5C5;">
									<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="bottom">
									
									</td><td align="left" valign="bottom">
										<div align="center" onclick="meechat.closeChat('.$cid.');">x</div>
									</td></tr></table>
								</div>
								<div align="left" id="chat_convomain" style="position: absolute; top: 2px; left: 2px; width: 190px;">
							
										<div id="chat_thread'.$cid.'" style="height: 242px; width: 254px; overflow-x: none; overflow-y: scroll; border-bottom: 1px solid #C5C5C5;">';
											include ('grabmsgs.php');
										echo '</div>
										<div style="padding-top: 4px;">
											<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
												<input type="text" id="chat_chatter'.$cid.'" name="chat_chatter'.$cid.'" size="20" maxlength="900" autocomplete="off" onfocus="if (trim(this.value) == \'type chatter here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type chatter here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="type chatter here" />
											</td><td align="left" valign="center" style="padding-left: 4px;">
												<input type="button" id="chat_sendbtn'.$cid.'" value="send" onclick="if (trim($(\'chat_chatter'.$cid.'\').get(\'value\'))!=\'type chatter here\') { meechat.newMsg(\''.$cid.'\', encodeURIComponent($(\'chat_chatter'.$cid.'\').value) ); $(\'chat_chatter'.$cid.'\').set(\'value\', \'\');}" style="padding-left: 6px; padding-right: 6px;"/>
											</td></tr></table>
										</div>
								</div>
								<div align="left" style="position: absolute; top: 281px; left: 27px; width: 134px; height: 2px; border-bottom: 2px solid #C5C5C5;"></div>
							</div>
							<div style="position: absolute; top: 0px; left: 0px; z-index: 111; width: 134px; height: 30px; cursor: pointer;" onclick="if($(\'chat_convocont'.$cid.'\').getStyles(\'visibility\').visibility==\'visible\'){ meechat.hideChat('.$cid.'); }else{ meechat.openChat('.$cid.'); }"></div>
						</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>