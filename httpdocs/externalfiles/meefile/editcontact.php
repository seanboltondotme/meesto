<?php
require_once ('../../../externals/general/includepaths.php');

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {

$ifname = 'editic';
include ('../../../externals/header/header-iframe.php');

if (isset($_POST['save'])) {
//save
	
	$errors = NULL;
			
	if (empty($errors)) {
				if (isset($_POST['cinfot0']) && ($_POST['cinfot0']=='other')) {
					if (isset($_POST['cinfott0']) && ($_POST['cinfott0'] != 'enter type')) {
						$emailtype = escape_form_data($_POST['cinfott0']);
					} else {
						$emailtype  = '';
					}
				} elseif ($_POST['cinfot0'] != '') {
					$emailtype  = escape_data($_POST['cinfot0']);
				} else {
					$emailtype  = '';	
				}
		$update = mysql_query("UPDATE meefile_basic SET email_type='$emailtype' WHERE u_id='$id'");
		//email custom sections
			$customsecs = mysql_query("SELECT mc_id, sec FROM meefile_contact WHERE u_id='$id'");
			while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
				$mcid = $customsec['mc_id'];
				$sec = $customsec['sec'];
				if ($sec=='im') {
					$cntntnm = 'im name';
				} elseif ($sec=='phone') {
					$cntntnm = 'phone number';
				} elseif ($sec=='adrs') {
					$cntntnm = 'address';
				} elseif ($sec=='web') {
					$cntntnm = 'website';
				} else {
					$cntntnm = 'email';
				}
				if (isset($_POST['cinfoc'.$mcid]) && ($_POST['cinfoc'.$mcid] != 'enter '.$cntntnm)) {
					$content = escape_data($_POST['cinfoc'.$mcid]);
				} else {
					$content  = '';
				}
				if (isset($_POST['cinfot'.$mcid]) && ($_POST['cinfot'.$mcid]=='other')) {
					if (isset($_POST['cinfott'.$mcid]) && ($_POST['cinfott'.$mcid] != 'enter type')) {
						$type = escape_data($_POST['cinfott'.$mcid]);
					} else {
						$type  = '';
					}
				} elseif ($_POST['cinfot'.$mcid] != '') {
					$type  = escape_data($_POST['cinfot'.$mcid]);
				} else {
					$type  = '';	
				}
				$update = mysql_query("UPDATE meefile_contact SET type='$type', content='$content' WHERE mc_id='$mcid'");
			}
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'cieditbtn\').set(\'styles\',{\'display\':\'block\'});parent.$(\'civisbtn\').set(\'styles\',{\'display\':\'none\'});parent.gotopage(\'cinfomain\', \''.$baseincpat.'externalfiles/meefile/grabcontact.php?id='.$id.'\');", \'0\');
			</script>';
	} else {
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'cieditbtn\').set(\'styles\',{\'display\':\'block\'});parent.$(\'civisbtn\').set(\'styles\',{\'display\':\'none\'});", \'3200\');
			</script>';
		echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('meefile/editcontact.php', 'editing basic info', $errors);
	}
	
} else {
	
echo '<form action="'.$baseincpat.'externalfiles/meefile/editcontact.php?action=iframe" method="post">
	
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">email</td><td align="left" valign="top" width="516px">
			<div align="left" id="cinfocustsecsemail">';
					//default email
					$mcid = 0;
					$deinfo = mysql_fetch_array (mysql_query ("SELECT u.email, mb.email_type FROM users u, meefile_basic mb WHERE u.user_id=mb.u_id='$id' LIMIT 1"), MYSQL_ASSOC);
					echo '<div align="left" id="cntctme"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='cntctme' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '><div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px; padding-bottom: 26px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="195px">
								'.$deinfo['email'].'
							</td><td align="left" valign="center" style="padding-left: 12px;">
								<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
									<option value=""'; if($deinfo['email_type']==''){$isother=false; echo' SELECTED';} echo'>choose:</option>
									<option value="main"'; if($deinfo['email_type']=='main'){$isother=false; echo' SELECTED';} echo'>main</option>
									<option value="work"'; if($deinfo['email_type']=='work'){$isother=false; echo' SELECTED';} echo'>work</option>
									<option value="home"'; if($deinfo['email_type']=='home'){$isother=false; echo' SELECTED';} echo'>home</option>
									<option value="other"'; if($isother){echo' SELECTED';} echo'>other</option>
								</select>
							</td><td align="left" valign="center" style="padding-left: 4px;">
								<div align="left" id="cinfottc'.$mcid.'" style="'; if(!$isother){echo'display: none;';} echo'">
								<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
									if ($deinfo['email_type']!=''){echo'value="'.$deinfo['email_type'].'"';}else{echo' class="inputplaceholder" value="enter type"';}
								echo'/>
								</div>
							</td></tr></table>
						</td><td align="right" valign="top" width="110px">
							<div align="left"  id="cinfobtns'.$mcid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
								<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=cntctme\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
							</div>
						</td></tr></table>
					</div>
					</div>';
			//get secs
			$customsecs = mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$id' AND sec='email' ORDER BY mc_id ASC");
			while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
				$mcid = $customsec['mc_id'];
				$isother = true;
				echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<input type="text" name="cinfoc'.$mcid.'" size="20" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter email\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter email\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
								if ($customsec['content']!=''){echo'value="'.$customsec['content'].'"';}else{echo' class="inputplaceholder" value="enter email"';}
							echo'/>
						</td><td align="left" valign="center" style="padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value=""'; if($customsec['type']==''){$isother=false; echo' SELECTED';} echo'>choose:</option>
								<option value="main"'; if($customsec['type']=='main'){$isother=false; echo' SELECTED';} echo'>main</option>
								<option value="work"'; if($customsec['type']=='work'){$isother=false; echo' SELECTED';} echo'>work</option>
								<option value="home"'; if($customsec['type']=='home'){$isother=false; echo' SELECTED';} echo'>home</option>
								<option value="other"'; if($isother){echo' SELECTED';} echo'>other</option>
							</select>
						</td><td align="left" valign="center" style="padding-left: 4px;">
							<div align="left" id="cinfottc'.$mcid.'" style="'; if(!$isother){echo'display: none;';} echo'">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
								if ($customsec['type']!=''){echo'value="'.$customsec['type'].'"';}else{echo' class="inputplaceholder" value="enter type"';}
							echo'/>
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
		echo '</div>
			<div align="left" style="padding-top: 8px; padding-bottom: 20px;">
				<input type="button" value="add email" onclick="var newElem = new Element(\'div\', {\'align\': \'left\'});newElem.inject($(\'cinfocustsecsemail\'), \'bottom\');gotopage(newElem, \''.$baseincpat.'externalfiles/meefile/addcinfosec.php?s=email\');"/>
			</div>
		</td></tr></table>
	</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">IM names</td><td align="left" valign="top" width="516px">
			<div align="left" id="cinfocustsecsimname">';
			//get secs
			$customsecs = mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$id' AND sec='im' ORDER BY mc_id ASC");
			while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
				$mcid = $customsec['mc_id'];
				$isother = true;
				echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<input type="text" name="cinfoc'.$mcid.'" size="20" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter im name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter im name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
								if ($customsec['content']!=''){echo'value="'.$customsec['content'].'"';}else{echo' class="inputplaceholder" value="enter im name"';}
							echo'/>
						</td><td align="left" valign="center" style="padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfottc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value=""'; if($customsec['type']==''){$isother=false; echo' SELECTED';} echo'>choose:</option>
								<option value="aim"'; if($customsec['type']=='aim'){$isother=false; echo' SELECTED';} echo'>aim</option>
								<option value="gchat"'; if($customsec['type']=='gchat'){$isother=false; echo' SELECTED';} echo'>gchat</option>
								<option value="msn"'; if($customsec['type']=='msn'){$isother=false; echo' SELECTED';} echo'>msn</option>
								<option value="yahoo"'; if($customsec['type']=='yahoo'){$isother=false; echo' SELECTED';} echo'>yahoo</option>
								<option value="skype"'; if($customsec['type']=='skype'){$isother=false; echo' SELECTED';} echo'>skype</option>
								<option value="other"'; if($isother){echo' SELECTED';} echo'>other</option>
							</select>
						</td><td align="left" valign="center" style="padding-left: 4px;">
							<div align="left" id="cinfottc'.$mcid.'" style="'; if(!$isother){echo'display: none;';} echo'">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
								if ($customsec['type']!=''){echo'value="'.$customsec['type'].'"';}else{echo' class="inputplaceholder" value="enter type"';}
							echo'/>
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
		echo '</div>
			<div align="left" style="padding-top: 8px; padding-bottom: 20px;">
				<input type="button" value="add IM name" onclick="var newElem = new Element(\'div\', {\'align\': \'left\'});newElem.inject($(\'cinfocustsecsimname\'), \'bottom\');gotopage(newElem, \''.$baseincpat.'externalfiles/meefile/addcinfosec.php?s=im\');"/>
			</div>
		</td></tr></table>
	</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">phone</td><td align="left" valign="top" width="516px">
			<div align="left" id="cinfocustsecsphone">';
			//get secs
			$customsecs = mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$id' AND sec='phone' ORDER BY mc_id ASC");
			while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
				$mcid = $customsec['mc_id'];
				$isother = true;
				echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<input type="text" name="cinfoc'.$mcid.'" size="20" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter phone number\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter phone number\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
								if ($customsec['content']!=''){echo'value="'.$customsec['content'].'"';}else{echo' class="inputplaceholder" value="enter phone number"';}
							echo'/>
						</td><td align="left" valign="center" style="padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value=""'; if($customsec['type']==''){$isother=false; echo' SELECTED';} echo'>choose:</option>
								<option value="home"'; if($customsec['type']=='home'){$isother=false; echo' SELECTED';} echo'>home</option>
								<option value="work"'; if($customsec['type']=='work'){$isother=false; echo' SELECTED';} echo'>work</option>
								<option value="mobile"'; if($customsec['type']=='mobile'){$isother=false; echo' SELECTED';} echo'>mobile</option>
								<option value="other"'; if($isother){echo' SELECTED';} echo'>other</option>
							</select>
						</td><td align="left" valign="center" style="padding-left: 4px;">
							<div align="left" id="cinfotc'.$mcid.'" style="'; if(!$isother){echo'display: none;';} echo'">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
								if ($customsec['type']!=''){echo'value="'.$customsec['type'].'"';}else{echo' class="inputplaceholder" value="enter type"';}
							echo'/>
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
		echo '</div>
			<div align="left" style="padding-top: 8px; padding-bottom: 20px;">
				<input type="button" value="add phone number" onclick="var newElem = new Element(\'div\', {\'align\': \'left\'});newElem.inject($(\'cinfocustsecsphone\'), \'bottom\');gotopage(newElem, \''.$baseincpat.'externalfiles/meefile/addcinfosec.php?s=phone\');"/>
			</div>
		</td></tr></table>
	</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">address</td><td align="left" valign="top" width="516px">
			<div align="left" id="cinfocustsecsaddress">';
			//get secs
			$customsecs = mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$id' AND sec='adrs' ORDER BY mc_id ASC");
			while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
				$mcid = $customsec['mc_id'];
				$isother = true;
				echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px; padding-bottom: 8px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<textarea name="cinfoc'.$mcid.'" cols="19" rows="2" onfocus="if (trim(this.value) == \'enter address\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter address\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'480\', \'ciadrovertxtalrt'.$mcid.'\');"';
								if ($customsec['content']!=''){echo'>'.$customsec['content'];}else{echo' class="inputplaceholder">enter address';}
							echo '</textarea>
							<div id="ciadrovertxtalrt'.$mcid.'" align="left" class="palert"></div>
						</td><td align="left" valign="top" style="padding-top: 2px; padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value=""'; if($customsec['type']==''){$isother=false; echo' SELECTED';} echo'>choose:</option>
								<option value="home"'; if($customsec['type']=='home'){$isother=false; echo' SELECTED';} echo'>home</option>
								<option value="work"'; if($customsec['type']=='work'){$isother=false; echo' SELECTED';} echo'>work</option>
								<option value="other"'; if($isother){echo' SELECTED';} echo'>other</option>
							</select>
						</td><td align="left" valign="top" style="padding-top: 2px; padding-left: 4px;">
							<div align="left" id="cinfotc'.$mcid.'" style="'; if(!$isother){echo'display: none;';} echo'">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
								if ($customsec['type']!=''){echo'value="'.$customsec['type'].'"';}else{echo' class="inputplaceholder" value="enter type"';}
							echo'/>
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
		echo '</div>
			<div align="left" style="padding-top: 8px; padding-bottom: 20px;">
				<input type="button" value="add address" onclick="var newElem = new Element(\'div\', {\'align\': \'left\'});newElem.inject($(\'cinfocustsecsaddress\'), \'bottom\');gotopage(newElem, \''.$baseincpat.'externalfiles/meefile/addcinfosec.php?s=adrs\');"/>
			</div>
		</td></tr></table>
	</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">website</td><td align="left" valign="top" width="516px">
			<div align="left" id="cinfocustsecsweb">';
			//get secs
			$customsecs = mysql_query("SELECT mc_id, type, content FROM meefile_contact WHERE u_id='$id' AND sec='web' ORDER BY mc_id ASC");
			while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
				$mcid = $customsec['mc_id'];
				$isother = true;
				echo '<div align="left" id="mc'.$mcid.'" style="margin-bottom: 4px;" onmouseover="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'cinfobtns'.$mcid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_contact_vis WHERE mc_id='$mcid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="406px">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<input type="text" name="cinfoc'.$mcid.'" size="20" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter website\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter website\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
								if ($customsec['content']!=''){echo'value="'.$customsec['content'].'"';}else{echo' class="inputplaceholder" value="enter website"';}
							echo'/>
						</td><td align="left" valign="center" style="padding-left: 12px;">
							<select name="cinfot'.$mcid.'" style="font-size: 14px; padding-right: 4px;" onchange="if(this.value==\'other\'){ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'block\'}); }else{ $(\'cinfotc'.$mcid.'\').set(\'styles\',{\'display\':\'none\'}); }">
								<option value=""'; if($customsec['type']==''){$isother=false; echo' SELECTED';} echo'>choose:</option>
								<option value="personal"'; if($customsec['type']=='personal'){$isother=false; echo' SELECTED';} echo'>personal</option>
								<option value="work"'; if($customsec['type']=='work'){$isother=false; echo' SELECTED';} echo'>work</option>
								<option value="other"'; if($isother){echo' SELECTED';} echo'>other</option>
							</select>
						</td><td align="left" valign="center" style="padding-left: 4px;">
							<div align="left" id="cinfotc'.$mcid.'" style="'; if(!$isother){echo'display: none;';} echo'">
							<input type="text" name="cinfott'.$mcid.'" size="8" maxlength="26" autocomplete="off" onfocus="if (trim(this.value) == \'enter type\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter type\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
								if ($customsec['type']!=''){echo'value="'.$customsec['type'].'"';}else{echo' class="inputplaceholder" value="enter type"';}
							echo'/>
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
		echo '</div>
			<div align="left" style="padding-top: 8px; padding-bottom: 20px;">
				<input type="button" value="add website" onclick="var newElem = new Element(\'div\', {\'align\': \'left\'});newElem.inject($(\'cinfocustsecsweb\'), \'bottom\');gotopage(newElem, \''.$baseincpat.'externalfiles/meefile/addcinfosec.php?s=web\');"/>
			</div>
		</td></tr></table>
	</div>
	
	<div align="center" style="padding-top: 8px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left">
			<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		</td><td align="left">
			<div id="submitbtns" align="left">
			<table cellpadding="0" cellspacing="0"><tr><td align="left">
				<input type="submit" id="submit" value="save" name="save" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
			</td><td align="left" style="padding-left: 12px;">
				<input type="button" id="cancel" value="cancel" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});parent.$(\'cieditbtn\').set(\'styles\',{\'display\':\'block\'});parent.$(\'civisbtn\').set(\'styles\',{\'display\':\'none\'});parent.gotopage(\'cinfomain\', \''.$baseincpat.'externalfiles/meefile/grabcontact.php?id='.$id.'\');"/>
			</td></tr></table>
			</div>
		</td></tr></table>
	</div>

</form>';
}

include ('../../../externals/header/footer-iframe.php');

} else {
	echo '<iframe width="100%" height="200px" align="center" id="editic" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/meefile/editcontact.php?action=iframe"></iframe>';
}
?>