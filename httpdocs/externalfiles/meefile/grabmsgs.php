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

if (isset($_GET['vid'])&&is_numeric($_GET['vid'])) { //view single message
	$vid = escape_data($_GET['vid']);	
	$msgs = mysql_query ("SELECT mt.t_id, mt.msg, mt.ref_id, mt.ref_type, DATE_FORMAT(mt.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM msg_owners mo INNER JOIN msg_threads mt ON mt.t_id=mo.t_id WHERE mt.t_id='$vid' LIMIT 1");
} elseif ($uid==$id) { //view from my meefile
	$msgs = mysql_query ("SELECT mt.t_id, mt.msg, mt.ref_id, mt.ref_type, DATE_FORMAT(mt.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM msg_owners mo INNER JOIN msg_threads mt ON mt.t_id=mo.t_id WHERE mo.u_id='$id' ORDER BY mt.time_stamp DESC");
} else { //view from peep meefile
	echo '<div align="left">
		<iframe width="100%" height="200px" align="center" id="convomsgwrite'.$uid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/meefile/writemsg.php?id='.$uid.'"></iframe>
	</div>';
	$msgs = mysql_query ("SELECT mt.t_id, mt.msg, mt.ref_id, mt.ref_type, DATE_FORMAT(mt.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM msg_owners mo INNER JOIN msg_owners mo2 ON (mo.t_id=mo2.t_id AND mo2.u_id='$uid') INNER JOIN msg_threads mt ON mt.t_id=mo.t_id WHERE mo.u_id='$id' ORDER BY mt.time_stamp DESC");
}

while ($msg = mysql_fetch_array ($msgs, MYSQL_ASSOC)) {
	$tid = $msg['t_id'];
	$ref_id = $msg['ref_id'];
	$ref_type = $msg['ref_type'];
	$msgsid = mysql_result (mysql_query("SELECT u_id FROM msg_owners WHERE t_id='$tid' AND type='s' LIMIT 1"), 0);
	echo '<div align="left"';
			/*if (($uid==$id)||($msgsid==$id)) {
				echo ' onmouseover="$(\'btndeletethrd'.$tid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletethrd'.$tid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}*/
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><a href="'.$baseincpat.'meefile.php?id='.$msgsid.'"><img src="'.$baseincpat.''.mysql_result (mysql_query("SELECT defaultimg_url FROM users WHERE user_id='$msgsid' LIMIT 1"), 0).'" /></a></td><td align="left" valign="top" width="458px" style="padding-left: 12px;">';
				if ($ref_type=='upld_p') {
					$photo = mysql_fetch_array(mysql_query("SELECT url FROM user_attachments WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
					echo '<div align="left" class="p24" style="padding-top: 4px; padding-bottom: 4px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn" onclick="PopBox.fromElement(this , {url: \''.$photo['url'].'\'});$(\'pbox-loader\').set(\'styles\',{\'display\':\'none\'});"/>
						</td><td align="left" valign="top" style="padding-left: 8px;">'.nl2br($msg['msg']).'</td></tr></table>
					</div>';
				} elseif ($ref_type=='lnk_site') {
					$atchinfo = mysql_fetch_array(mysql_query("SELECT url, host, tn_url, title, description FROM user_links WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
					echo '<div align="left" class="p24" style="padding-top: 2px; padding-bottom: 2px;">'.nl2br($msg['msg']).'</div>
					<div align="left" style="margin-top: 2px; margin-bottom: 4px; padding-top: 6px; padding-bottom: 4px; border-top: 1px solid #C5C5C5; border-bottom: 1px solid #C5C5C5;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<div align="center"><a href="'.$atchinfo['url'].'" target="_blank"><img src="'.$baseincpat.$atchinfo['tn_url'].'" class="pictn"/></a></div>
							<div align="center" class="subtext" style="font-size: 10px;">'.$atchinfo['host'].'</div>
						</td><td align="left" valign="top" style="padding-left: 8px;">
							<div align="left"><a href="'.$atchinfo['url'].'" target="_blank">'.$atchinfo['title'].'</a></div>
							'; if($atchinfo['description']!=''){echo'<div align="left" class="subtext">'.$atchinfo['description'].'</div>';} echo'
						</td></tr></table>
					</div>';
				} elseif ($ref_type=='lnk_img') {
					$atchinfo = mysql_fetch_array(mysql_query("SELECT url, host, tn_url FROM user_links WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
					echo '<div align="left" class="p24" style="padding-top: 4px; padding-bottom: 4px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<div align="center"><a href="'.$atchinfo['url'].'" target="_blank"><img src="'.$baseincpat.$atchinfo['tn_url'].'" class="pictn"/></a></div>
							<div align="center" class="subtext" style="font-size: 10px;">'.$atchinfo['host'].'</div>
						</td><td align="left" valign="top" style="padding-left: 8px;">'.nl2br($msg['msg']).'</td></tr></table>
					</div>';
				} elseif ($ref_type=='ap') {
					$photo = mysql_fetch_array(mysql_query("SELECT pa_id, url FROM album_photos WHERE ap_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
					echo '<div align="left" class="p24" style="padding-top: 4px; padding-bottom: 4px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<a href="'.$baseincpat.'meefile.php?id='.$msgsid.'&t=photos&aid='.$photo['pa_id'].'&view=photo&#apid='.$ref_id.'"><img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn"/></a>
						</td><td align="left" valign="top" style="padding-left: 8px;">'.nl2br($msg['msg']).'</td></tr></table>
					</div>';
				} elseif ($ref_type=='evntmsg') {
					$einfo = mysql_fetch_array(mysql_query ("SELECT name, defaultimg_url FROM events WHERE e_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
					echo '<div align="left" class="p24" style="padding-top: 4px; padding-bottom: 4px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">RE: "<a href="'.$baseincpat.'event.php?id='.$ref_id.'">'.$einfo['name'].'</a>"<br />'.nl2br($msg['msg']).'</td><td align="left" valign="top" style="padding-left: 8px;">
							<a href="'.$baseincpat.'event.php?id='.$ref_id.'"><img src="'.$baseincpat.substr($einfo['defaultimg_url'], 0, -5).'tn'.substr($einfo['defaultimg_url'], -4).'" class="pictn"/></a>
						</td></tr></table>
					</div>';
				} else {
					echo '<div align="left" class="p24" style="padding-top: 2px; padding-bottom: 2px;">'.nl2br($msg['msg']).'</div>';
				}
			echo '<div class="subtext">by '; loadpersonname($msgsid); echo' on '.$msg['time'].'</div>';
			//test for multiple receivers
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM msg_owners WHERE t_id='$tid' LIMIT 3"), 0)>2) {
				echo '<div>(this was also sent to ';
				$msgextownrs = mysql_query ("SELECT u_id FROM msg_owners WHERE t_id='$tid' AND type='r' AND u_id!='$uid' ORDER BY mo_id ASC");
				$msgoct = mysql_result(mysql_query("SELECT COUNT(*) FROM msg_owners WHERE t_id='$tid' AND type='r' AND u_id!='$uid'"), 0)-1;
				$i = 0;
				while ($msgextownr = mysql_fetch_array ($msgextownrs, MYSQL_ASSOC)) {
					if (($i==$msgoct)&&($i==1)) {
						echo ' and ';	
					} elseif (($i==$msgoct)&&($i>0)) {
						echo ', and ';	
					} elseif ($i>0) {
						echo ', ';	
					}
					loadpersonname($msgextownr['u_id']);
					$i++;
				}
				echo ')</div>';
				$i++;
			}
		echo '</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">';
			/*if (($uid==$id)||($msgsid==$id)) {
				echo '<div id="btndeletethrd'.$tid.'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick=""/></div>';
			}*/
		echo '</td></tr></table>
	</div><div align="left" style="margin-left: 62px; margin-bottom: 36px;"><div align="left" id="msgcmts'.$tid.'" style="padding-left: 12px; border-left: 1px solid #C5C5C5;">';
		include('grabmsgcmts.php');
	echo '</div></div>';
}

if (isset($minses2)) {
	session_write_close();
	exit();	
}
?>