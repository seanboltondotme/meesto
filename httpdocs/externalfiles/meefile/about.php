<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

echo '<div align="left" style="margin-top: 28px; margin-left: 32px;">
<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="222px" style="border-right: 2px solid #C5C5C5; padding-bottom: 36px;">
	<div align="left" class="p24" style="padding-top: 22px;">'.$fn.'\'s Peeple</div><div align="left" style="margin-left: 20px;">';
			if (($uid==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$uid' AND p_id='$id' LIMIT 1"), 0)>0)) { //test if can view
				$mypeep_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM my_peeple WHERE u_id='$uid'"), 0);
				if ($mypeep_ct>0) {
					$mypeeps = mysql_query ("SELECT mp.p_id, u.defaultimg_url FROM my_peeple mp INNER JOIN users u ON mp.p_id=u.user_id WHERE mp.u_id='$uid' ORDER BY RAND() LIMIT 6");
					while ($mypeep = mysql_fetch_array ($mypeeps, MYSQL_ASSOC)) {
						$peepid = $mypeep['p_id'];
						echo '<div align="left" style="padding-top: 4px;">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
								<a href="'.$baseincpat.'meefile.php?id='.$peepid.'"><img src="'.$baseincpat.''.substr($mypeep['defaultimg_url'], 0, -4).'m'.substr($mypeep['defaultimg_url'], -4).'" /></a>
							</td><td align="left" valign="top" style="padding-left: 4px; padding-top: 2px;">'; loadpersonname($peepid); echo '</td></tr></table>
						</div>';
					}
				} else {
					echo '<div align="left" style="padding-top: 4px;">No peeple.</div>';
				}
			echo '</div><div align="right" style="padding-right: 12px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/viewpeeple.php?id='.$uid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$mypeep_ct.')</div>';
				if ($uid!=$id) {
					$mutpeep_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM my_peeple mp INNER JOIN my_peeple mp2 ON mp2.u_id='$id' AND mp.p_id=mp2.p_id WHERE mp.u_id='$uid'"), 0);
					if ($mutpeep_ct>0) {
						echo '<div align="left" class="p24" style="padding-top: 22px;">Mutual Peeple</div><div align="left" style="margin-left: 20px;">';
							$mutpeeps = mysql_query ("SELECT mp.p_id, u.defaultimg_url FROM my_peeple mp INNER JOIN my_peeple mp2 ON mp2.u_id='$id' AND mp.p_id=mp2.p_id INNER JOIN users u ON mp.p_id=u.user_id WHERE mp.u_id='$uid' ORDER BY RAND() LIMIT 6");
							while ($mutpeep = mysql_fetch_array ($mutpeeps, MYSQL_ASSOC)) {
								$mpeepid = $mutpeep['p_id'];
								echo '<div align="left" style="padding-top: 4px;">
									<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
										<a href="'.$baseincpat.'meefile.php?id='.$mpeepid.'"><img src="'.$baseincpat.''.substr($mutpeep['defaultimg_url'], 0, -4).'m'.substr($mutpeep['defaultimg_url'], -4).'" /></a>
									</td><td align="left" valign="top" style="padding-left: 4px; padding-top: 2px;">'; loadpersonname($mpeepid); echo '</td></tr></table>
								</div>';
							}
						echo '</div><div align="right" style="padding-right: 6px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/viewpeeple.php?id='.$uid.'&fltr=m\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$mutpeep_ct.')</div>';
					}
				}
			} else { //if not able to view
				echo '<div class="container" align="left" valign="top" style="padding: 2px;">
					You must add '.$fn.' to view this.
				</div>';
			}
echo '</td><td align="left" valign="top" style="padding-left: 16px;">';
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='idbas' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='idbas' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='idbas' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='idbas' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		echo '<div align="left" id="idbas"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='idbas' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<div align="left" class="p24"';
			if ($uid==$id){echo' onmouseover="$(\'bieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
			echo '>
			<table cellpadding="0" cellspacing="0" width="690px"><tr><td align="left" valign="top">Basic Info</td><td align="right" valign="bottom">';
			if ($uid==$id){echo'<div id="bieditbtn" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="edit" onclick="$(\'bieditbtn\').set(\'styles\',{\'display\':\'none\'});$(\'bivisbtn\').set(\'styles\',{\'display\':\'block\'});gotopage(\'binfomain\', \''.$baseincpat.'externalfiles/meefile/editbasic.php\');"/></div>
			<div id="bivisbtn" style="display: none;"><input type="button" align="center" valign="center" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editsecvis.php?sec=idbas\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';}
			echo '</td></tr></table>
		</div>
		<div align="left" id="binfomain" class="paragraph" style="padding-left: 30px; padding-top: 18px; padding-bottom: 36px;"';
			if ($uid==$id){echo' onmouseover="$(\'bieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
			echo '>';
			include ('externalfiles/meefile/grabbasic.php');
		echo '</div>
		</div>';
	}
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='idcnt' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='idcnt' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='idcnt' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='idcnt' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		echo '<div align="left" id="idcnt"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='idcnt' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '><div align="left" class="p24"';
		if ($uid==$id){echo' onmouseover="$(\'cieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
		echo '>
		<table cellpadding="0" cellspacing="0" width="690px"><tr><td align="left" valign="top">Contact Info</td><td align="right" valign="bottom">';
		if ($uid==$id){echo'<div id="cieditbtn" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="edit" onclick="$(\'cieditbtn\').set(\'styles\',{\'display\':\'none\'});$(\'civisbtn\').set(\'styles\',{\'display\':\'block\'});gotopage(\'cinfomain\', \''.$baseincpat.'externalfiles/meefile/editcontact.php\');"/></div>
		<div id="civisbtn" style="display: none;"><input type="button" align="center" valign="center" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editsecvis.php?sec=idcnt\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';}
		echo '</td></tr></table>
	</div>
	<div align="left" id="cinfomain" class="paragraph" style="padding-left: 30px; padding-top: 18px; padding-bottom: 36px;"';
		if ($uid==$id){echo' onmouseover="$(\'cieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
		echo '>';
		include ('externalfiles/meefile/grabcontact.php');
	echo '</div>
	</div>';
	}
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='idpers' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='idpers' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='idpers' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='idpers' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
	echo '<div align="left" id="idpers"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='idpers' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '><div align="left" class="p24"';
		if ($uid==$id){echo' onmouseover="$(\'pieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
		echo '>
		<table cellpadding="0" cellspacing="0" width="690px"><tr><td align="left" valign="top">Personal Info</td><td align="right" valign="bottom">';
		if ($uid==$id){echo'<div id="pieditbtn" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="edit" onclick="$(\'pieditbtn\').set(\'styles\',{\'display\':\'none\'});$(\'pivisbtn\').set(\'styles\',{\'display\':\'block\'});gotopage(\'pinfomain\', \''.$baseincpat.'externalfiles/meefile/editpers.php\');"/></div>
		<div id="pivisbtn" style="display: none;"><input type="button" align="center" valign="center" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editsecvis.php?sec=idpers\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';}
		echo '</td></tr></table>
	</div>
	<div align="left" id="pinfomain" class="paragraph" style="padding-left: 30px; padding-top: 18px;"';
		if ($uid==$id){echo' onmouseover="$(\'pieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pieditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
		echo '>';
		include ('externalfiles/meefile/grabpers.php');
	echo '</div>
	</div>';
	}
echo '</td></tr></table>
</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>