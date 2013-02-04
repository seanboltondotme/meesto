<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses2 = true;
}

if (!isset($uid)) {
	$uid = escape_data($_GET['id']);	
}
if (!isset($mtid)) {
	$mtid = escape_data($_GET['t']);	
}
if (!isset($mtsid)) {
	$mtsid = escape_data($_GET['mtsid']);	
}

if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_tab mt INNER JOIN meefile_tab_sec mts ON (mts.mts_id=$mtsid AND mts.mt_id =mt.mt_id) WHERE mt.mt_id='$mtid' AND mt.u_id='$uid' LIMIT 1"), 0)>0) { //test for owner

if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$mtsid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$mtsid'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$mtsid'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$mtsid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {

$mtsinfo = mysql_fetch_array (mysql_query ("SELECT title, content, show_date, allow_cmts, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM meefile_tab_sec WHERE mts_id='$mtsid' LIMIT 1"), MYSQL_ASSOC);

if (($uid==$id) || ($mtsinfo['content']!='')) {
	echo '<div align="left" id="mts'.$mtsid.'">
	<div align="left" style="padding-bottom: 64px; padding-left: 2px;"';
		if ($uid==$id) {
			echo ' onmouseover="$(\'editbtns'.$mtsid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'editbtns'.$mtsid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
		}
	echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="688px">
					<div align="left" class="p24">'.$mtsinfo['title'].'</div>';
						//test for and show timestamp
						if ($mtsinfo['show_date']=='y') {
							echo '<div align="left" class="subtext" style="font-size: 13px;">posted on '.$mtsinfo['time'].'</div>';	
						}
					echo '<div align="left" style="padding-top: 12px;">
						'.nl2br($mtsinfo['content']).'
					</div>';
					//load previously added attachments
					$atchments = mysql_query("SELECT ref_type, ref_id FROM mts_atchmnts WHERE mts_id='$mtsid' ORDER BY mtsa_id ASC");
					$i = 0;
					while ($atchment = mysql_fetch_array ($atchments, MYSQL_ASSOC)) {
						if ($i==0) {
							echo '<div align="left" style="margin-top: 8px;">
							<table cellpadding="0" cellspacing="0"><tr>';
						}
						$ref_type = $atchment['ref_type'];
						$ref_id = $atchment['ref_id'];
							if ($ref_type=='upld_p') {
								$photo = mysql_fetch_array(mysql_query("SELECT url FROM user_attachments WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
								echo '<td align="left" valign="top" style="padding-left: 12px;">
										<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn" onclick="PopBox.fromElement(this , {url: \''.$photo['url'].'\'});$(\'pbox-loader\').set(\'styles\',{\'display\':\'none\'});"/>
									</td>';
							} elseif ($ref_type=='lnk_site') {
								$atchinfo = mysql_fetch_array(mysql_query("SELECT url, host, tn_url, title, description FROM user_links WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
								echo '</tr></table></div>
								<div align="left" style="margin-top: 6px; margin-left: 12px; width: 444px; padding-top: 6px; padding-bottom: 4px; border-top: 1px solid #C5C5C5; border-bottom: 1px solid #C5C5C5;">
									<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
										<div align="center"><a href="'.$atchinfo['url'].'" target="_blank"><img src="'.$baseincpat.$atchinfo['tn_url'].'" class="pictn"/></a></div>
										<div align="center" class="subtext" style="font-size: 10px;">'.$atchinfo['host'].'</div>
									</td><td align="left" valign="top" style="padding-left: 8px;">
										<div align="left"><a href="'.$atchinfo['url'].'" target="_blank">'.$atchinfo['title'].'</a></div>
										'; if($atchinfo['description']!=''){echo'<div align="left" class="subtext">'.$atchinfo['description'].'</div>';} echo'
									</td></tr></table>
								</div>';
								$i = -1;
							} elseif ($ref_type=='lnk_img') {
								$atchinfo = mysql_fetch_array(mysql_query("SELECT url, host, tn_url FROM user_links WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
								echo '<td align="left" valign="top" style="padding-left: 12px;">
									<div align="center"><a href="'.$atchinfo['url'].'" target="_blank"><img src="'.$baseincpat.$atchinfo['tn_url'].'" class="pictn"/></a></div>
									<div align="center" class="subtext" style="font-size: 10px;">'.$atchinfo['host'].'</div>
									</td>';
							} elseif ($ref_type=='ap') {
								$photo = mysql_fetch_array(mysql_query("SELECT pa_id, url FROM album_photos WHERE ap_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
								echo '<td align="left" valign="top" style="padding-left: 12px;">
										<a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&aid='.$photo['pa_id'].'&view=photo&#apid='.$ref_id.'"><img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn"/></a>
									</td>';
							}
						if ($i==5) {
							echo '</tr></table>
							</div>';
							$i = -1;
						}
						$i++;
					}
						if (($i<6)&&($i>0)) {
							echo '</tr></table>
							</div>';
							$i = -1;
						}
					echo '<div align="left" id="msgcmts'.$mtsid.'" style="padding-left: 12px; margin-top: 8px; border-left: 1px solid #C5C5C5;">';
						//test for messages in thread
						if ($mtsinfo['allow_cmts']=='y') {
							include('grabmtcmts.php');	
						}
					echo '</div>
				</td><td align="right" valign="top" width="110px" style="padding-left: 24px;">';
					if ($uid==$id) {
						echo '<div align="left" id="editbtns'.$mtsid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
							<div><input type="button" align="center" valign="center" value="edit" onclick="gotopage(\'mts'.$mtsid.'\', \''.$baseincpat.'externalfiles/meefile/editmtsec.php?t='.$mtid.'&mtsid='.$mtsid.'\');"/></div>
							<div style="padding-top: 12px;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmtsecvis.php?id='.$mtsid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 12px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletemts.php?id='.$mtsid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
						</div>';
					}
				echo '</td></tr></table>
			</div>
	</div>';
}

} else { //if not vis
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

} else { //if not tab owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		This person does not own this information.
	</div>';
}

if (isset($minses2)) {
	session_write_close();
	exit();	
}
?>