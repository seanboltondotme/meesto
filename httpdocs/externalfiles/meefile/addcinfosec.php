<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$s = escape_data($_GET['s']);

$insert = mysql_query ("INSERT INTO meefile_contact (u_id, sec) VALUES ('$id', '$s')");
$mcid = mysql_insert_id();

//add default vis
	$sec= 'idcnt';
	if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='pub' LIMIT 1"), 0)>0) {
		$addvis = mysql_query("INSERT INTO meefile_contact_vis (mc_id, type, sub_type, time_stamp) VALUES ('$mcid', 'pub', 'y', NOW())");
	}
	$plstrm = mysql_query("SELECT sub_type FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='strm'");
	while ($plstrminfo = mysql_fetch_array ($plstrm, MYSQL_ASSOC)) {
		$streamvis = $plstrminfo['sub_type'];
		$addvis = mysql_query("INSERT INTO meefile_contact_vis (mc_id, type, sub_type, time_stamp) VALUES ('$mcid', 'strm', '$streamvis', NOW())");
	}
	$plchan = mysql_query("SELECT ref_id FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='chan'");
	while ($plchaninfo = mysql_fetch_array ($plchan, MYSQL_ASSOC)) {
		$chanvis = $plchaninfo['ref_id'];
		$addvis = mysql_query("INSERT INTO meefile_contact_vis (mc_id, type, ref_id, time_stamp) VALUES ('$mcid', 'chan', '$chanvis', NOW())");
	}
	$prsns = mysql_query("SELECT ref_id FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='user'");
	while ($prsn = mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
		$uid = $prsn['ref_id'];
		$addvis = mysql_query("INSERT INTO meefile_contact_vis (mc_id, type, ref_id, time_stamp) VALUES ('$mcid', 'user', '$uid', NOW())");
	}

$ifname = 'editic'; //for visibility visualizer

if ($s=='email') {
			echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<input type="text" name="cinfoc'.$mcid.'" size="20" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter email\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter email\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter email"/>
						</td><td align="left" valign="center" style="padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value="" SELECTED>choose:</option>
								<option value="main">main</option>
								<option value="work">work</option>
								<option value="home">home</option>
								<option value="other">other</option>
							</select>
						</td><td align="left" valign="center" style="padding-left: 4px;">
							<div align="left" id="cinfottc'.$mcid.'" style="display: none;">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter type"/>
							</div>
						</td></tr></table>
					</td><td align="right" valign="top" width="110px">
						<div align="left"  id="cinfobtns'.$mcid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
							<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editcinfovis.php?ifn='.$ifname.'&id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 10px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletecinfo.php?id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
						</div>
					</td></tr></table>
				</div>';
} elseif ($s=='im') {
			echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<input type="text" name="cinfoc'.$mcid.'" size="20" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter im name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter im name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter im name" />
						</td><td align="left" valign="center" style="padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value="" SELECTED>choose:</option>
								<option value="aim">aim</option>
								<option value="gchat">gchat</option>
								<option value="msn">msn</option>
								<option value="yahoo">yahoo</option>
								<option value="skype">skype</option>
								<option value="other">other</option>
							</select>
						</td><td align="left" valign="center" style="padding-left: 4px;">
							<div align="left" id="cinfottc'.$mcid.'" style="display: none;">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter type" />
							</div>
						</td></tr></table>
					</td><td align="right" valign="top" width="110px">
						<div align="left"  id="cinfobtns'.$mcid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
							<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editcinfovis.php?ifn='.$ifname.'&id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 10px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletecinfo.php?id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
						</div>
					</td></tr></table>
				</div>';
} elseif ($s=='phone') {
			echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<input type="text" name="cinfoc'.$mcid.'" size="20" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter phone number\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter phone number\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter phone number" />
						</td><td align="left" valign="center" style="padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value="" SELECTED>choose:</option>
								<option value="home">home</option>
								<option value="work">work</option>
								<option value="mobile">mobile</option>
								<option value="other">other</option>
							</select>
						</td><td align="left" valign="center" style="padding-left: 4px;">
							<div align="left" id="cinfotc'.$mcid.'" style="display: none;">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter type" />
							</div>
						</td></tr></table>
					</td><td align="right" valign="top" width="110px">
						<div align="left"  id="cinfobtns'.$mcid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
							<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editcinfovis.php?ifn='.$ifname.'&id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 10px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletecinfo.php?id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
						</div>
					</td></tr></table>
				</div>';
} elseif ($s=='adrs') {
			echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px; padding-bottom: 8px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<textarea name="cinfoc'.$mcid.'" cols="19" rows="2" onfocus="if (trim(this.value) == \'enter address\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter address\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'480\', \'ciadrovertxtalrt'.$mcid.'\');" class="inputplaceholder">enter address</textarea>
							<div id="ciadrovertxtalrt'.$mcid.'" align="left" class="palert"></div>
						</td><td align="left" valign="top" style="padding-top: 2px; padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value="" SELECTED>choose:</option>
								<option value="home">home</option>
								<option value="work">work</option>
								<option value="other">other</option>
							</select>
						</td><td align="left" valign="top" style="padding-top: 2px; padding-left: 4px;">
							<div align="left" id="cinfotc'.$mcid.'" style="'; if(!$isother){echo'display: none;';} echo'">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter type" />
							</div>
						</td></tr></table>
					</td><td align="right" valign="top" width="110px">
						<div align="left"  id="cinfobtns'.$mcid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
							<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editcinfovis.php?ifn='.$ifname.'&id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 10px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletecinfo.php?id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
						</div>
					</td></tr></table>
				</div>';
} elseif ($s=='web') {
			echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<input type="text" name="cinfoc'.$mcid.'" size="20" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter website\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter website\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}"class="inputplaceholder" value="enter website" />
						</td><td align="left" valign="center" style="padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value="" SELECTED>choose:</option>
								<option value="personal">personal</option>
								<option value="work">work</option>
								<option value="other">other</option>
							</select>
						</td><td align="left" valign="center" style="padding-left: 4px;">
							<div align="left" id="cinfotc'.$mcid.'" style="'; if(!$isother){echo'display: none;';} echo'">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter type" />
							</div>
						</td></tr></table>
					</td><td align="right" valign="top" width="110px">
						<div align="left"  id="cinfobtns'.$mcid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
							<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editcinfovis.php?ifn='.$ifname.'&id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 10px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletecinfo.php?id='.$mcid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
						</div>
					</td></tr></table>
				</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>