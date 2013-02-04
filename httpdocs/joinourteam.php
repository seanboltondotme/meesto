<?php
require_once('../externals/sessions/db_sessions.inc.php');

$title = 'Join The Meesto Team';
include ('../externals/header/header.php');

if (isset($_GET['t'])) {
	$t = escape_data($_GET['t']);	
} else {
	$t = '';	
}

echo '<div align="left" style="margin-left: 54px;">
	<div align="left" style="margin-top: 6px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="center" valign="center" class="p24">Join The</td><td align="center" valign="center"><img src="'.$baseincpat.'images/logo.png" /></td><td align="center" valign="center" class="p24" style="padding-left: 4px;">Team</td></tr></table>
	</div>
</div>';

echo '<div align="left" style="margin-top: 12px; width: 900px;">
	<div align="left">This is how you can apply to be a part of the Meesto Open Source Development team.</div>
</div><div align="left" style="padding-top: 32px; margin-left: 68px;">';
	
if (isset($_POST['submit'])) {
	
	$errors = NULL;
	
	if (isset($_POST['name']) && ($_POST['name'] != 'type your full name here...')) {
		$name = escape_form_data($_POST['name']);
	} else {
		$errors[] = 'You must enter your full name.';
	}
	
	if (isset($_POST['email']) && ($_POST['email'] != 'type your email here...')) {
			$email = escape_data($_POST['email']);
			if (!eregi ('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', $email)) {
				$errors[] = 'You must enter your in the correct format: email@host.com';
			}
		} else {
			$errors[] = 'You must enter your email.';
		}
	
	if (isset($_POST['how']) && ($_POST['how'] != 'type info here')) {
		$how = escape_form_data($_POST['how']);
	} else {
		$errors[] = 'You must enter how you\'d like to help.';
	}
	
	if (isset($_POST['knldg']) && ($_POST['knldg'] != 'type info here (this is optional)')) {
		$knldg = escape_form_data($_POST['knldg']);
	} else {
		$knldg = '';
	}
	
	if (isset($_POST['tbg']) && ($_POST['tbg'] != 'type info here (this is optional)')) {
		$tbg = escape_form_data($_POST['tbg']);
	} else {
		$tbg = '';
	}
	
	if (isset($_POST['oe']) && ($_POST['oe'] != 'type info here (this is optional)')) {
		$oe = escape_form_data($_POST['oe']);
	} else {
		$oe = '';
	}
	
	if (isset($_POST['o']) && ($_POST['o'] != 'type info here (this is optional)')) {
		$o = escape_form_data($_POST['o']);
	} else {
		$o = '';
	}
	
	if (empty($errors)) {
		$insert = mysql_query("INSERT INTO dev_applys (name, email, how, knldg, tbg, oe, o, time_stamp) VALUES ('$name', '$email', '$how', '$knldg', '$tbg', '$oe', '$o', NOW())");
		$daid = mysql_insert_id();
		
		//send myself an email
		$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='1' LIMIT 1"), 0);
										
			//params
			$subject = 'New Meesto Open Source Developer Application!';
			$emailercontent = $name.' just submitted a Meesto Open Source Developer Application!';
			$emailercontent .= '<br />In reference to daid='.$daid;
										
			include('../externals/general/emailer.php');
		
		
		echo '<div align="center" class="p18">Thank you for your interest in being a part of Meesto!</div><div align="center" class="subtext" style="padding-top: 2px;">
				we will contact you shortly
			</div>';
		include ('../externals/header/footer.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

} else {
	$name = '';
	$email = '';
	$how = '';
	$knldg = '';
	$tbg = '';
	$oe = '';
	$o = '';
}

echo '<div align="left" class="p24">Send us an email</div>
<div align="left" class="p18" style="margin-left: 24px; margin-top: 4px;">community [at] meesto [dot] com</div>
<div align="left" class="p24" style="margin-top: 24px;">Fill-out our application <span class="subtext" style="font-size: 14px;">(a more structured version of the email option)</span></div>
<div align="left" class="paragraph" style="margin-left: 24px; margin-top: 12px;"><form action="'.$baseincpat.'joinourteam.php" method="post">
		<div align="left" style="padding-left: 16px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="210px">name</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="name" size="46" maxlength="400" autocomplete="off" onfocus="if (trim(this.value) == \'type your full name here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type your full name here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($name!=''){echo'value="'.$name.'"';}else{echo'class="inputplaceholder" value="type your full name here..."';}
				echo '>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="210px">email</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="email" size="46" maxlength="800" autocomplete="off" onfocus="if (trim(this.value) == \'type your email here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type your email here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($email!=''){echo'value="'.$email.'"';}else{echo'class="inputplaceholder" value="type your email here..."';}
				echo '>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="210px">how you\'d like to help</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="how" cols="58" rows="3" onfocus="if (trim(this.value) == \'type info here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type info here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'28000\', \'howovertxtalrt\');"';
				if ($how){echo'>'.$how;}else{echo' class="inputplaceholder">type info here';}
			echo '</textarea>
				<div id="howovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="210px">programming knowledge</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="knldg" cols="58" rows="3" onfocus="if (trim(this.value) == \'type info here (this is optional)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type info here (this is optional)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'28000\', \'knldgovertxtalrt\');"';
				if ($knldg){echo'>'.$knldg;}else{echo' class="inputplaceholder">type info here (this is optional)';}
			echo '</textarea>
				<div id="knldgovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="210px">tech background</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="tbg" cols="58" rows="3" onfocus="if (trim(this.value) == \'type info here (this is optional)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type info here (this is optional)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'28000\', \'tbgovertxtalrt\');"';
				if ($tbg){echo'>'.$tbg;}else{echo' class="inputplaceholder">type info here (this is optional)';}
			echo '</textarea>
				<div id="tbgovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="210px">other experience</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="oe" cols="58" rows="3" onfocus="if (trim(this.value) == \'type info here (this is optional)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type info here (this is optional)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'28000\', \'oeovertxtalrt\');"';
				if ($oe){echo'>'.$oe;}else{echo' class="inputplaceholder">type info here (this is optional)';}
			echo '</textarea>
				<div id="oeovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="210px">other</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="o" cols="58" rows="3" onfocus="if (trim(this.value) == \'type info here (this is optional)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type info here (this is optional)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'28000\', \'oovertxtalrt\');"';
				if ($o){echo'>'.$o;}else{echo' class="inputplaceholder">type info here (this is optional)';}
			echo '</textarea>
				<div id="oovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="submit" name="submit" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 6px; font-size: 14px;">
				(note: we will contact you shortly)
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form></div>';

echo '</div>';


include ('../externals/header/footer.php');
?>