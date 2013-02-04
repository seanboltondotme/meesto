<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$insert = mysql_query ("INSERT INTO meefile_pers_ext (u_id) VALUES ('$id')");
$mpeid = mysql_insert_id();
	
//add default vis
	$sec= 'idpers';
	if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='pub' LIMIT 1"), 0)>0) {
		$addvis = mysql_query("INSERT INTO meefile_pers_ext_vis (mpe_id, type, sub_type, time_stamp) VALUES ('$mpeid', 'pub', 'y', NOW())");
	}
	$plstrm = mysql_query("SELECT sub_type FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='strm'");
	while ($plstrminfo = mysql_fetch_array ($plstrm, MYSQL_ASSOC)) {
		$streamvis = $plstrminfo['sub_type'];
		$addvis = mysql_query("INSERT INTO meefile_pers_ext_vis (mpe_id, type, sub_type, time_stamp) VALUES ('$mpeid', 'strm', '$streamvis', NOW())");
	}
	$plchan = mysql_query("SELECT ref_id FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='chan'");
	while ($plchaninfo = mysql_fetch_array ($plchan, MYSQL_ASSOC)) {
		$chanvis = $plchaninfo['ref_id'];
		$addvis = mysql_query("INSERT INTO meefile_pers_ext_vis (mpe_id, type, ref_id, time_stamp) VALUES ('$mpeid', 'chan', '$chanvis', NOW())");
	}
	$prsns = mysql_query("SELECT ref_id FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='user'");
	while ($prsn = mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
		$uid = $prsn['ref_id'];
		$addvis = mysql_query("INSERT INTO meefile_pers_ext_vis (mpe_id, type, ref_id, time_stamp) VALUES ('$mpeid', 'user', '$uid', NOW())");
	}

$ifname = 'editip'; //for visibility visualizer

	echo '<div align="left" id="mpe'.$mpeid.'" style="padding-bottom: 20px;" onmouseover="$(\'pibtns'.$mpeid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pibtns'.$mpeid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_pers_ext_vis WHERE mpe_id='$mpeid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" style="padding-left: 2px;">
			<input type="text" name="cst'.$mpeid.'" size="14" maxlength="40" autocomplete="off" onfocus="if (trim(this.value) == \'enter name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter name">
		</td><td align="left" width="406px">
			<textarea name="cs'.$mpeid.'" cols="44" rows="3" onfocus="if (trim(this.value) == \'type whatever you would like\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type whatever you would like\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'csovertxtalrt'.$mpeid.'\');" class="inputplaceholder">type whatever you would like</textarea>
			<div id="csovertxtalrt'.$mpeid.'" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div align="left" id="pibtns'.$mpeid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
					<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editpinfovis.php?ifn='.$ifname.'&id='.$mpeid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 10px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletepinfo.php?id='.$mpeid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
				</div>
		</td></tr></table>
	</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>