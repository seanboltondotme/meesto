<?php
include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit Password</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 24px;">Use this to edit your account password. (This is the password you use to login to Meesto.)</div>';

if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (isset($_POST['pword']) && ($_POST['pword'] != '')) {
		$pword = trim(strip_tags(escape_data($_POST['pword'])));
		if (mysql_result (mysql_query ("SELECT COUNT(*) FROM users WHERE user_id='$id' AND password=SHA('$pword') LIMIT 1"), 0)==0) {
			$errors[] = 'Your current password did not match what we have on file. Please try again.';
		}
	} else {
		$errors[] = 'Please enter your current password.';
	}
	
	if (isset($_POST['npword']) && ($_POST['npword'] != '')) {
		$npword = trim(strip_tags(escape_data($_POST['npword'])));
	} else {
		$errors[] = 'Please enter your new password.';
	}
	
	if (isset($_POST['npwordc']) && ($_POST['npwordc'] != '')) {
		$npwordc = trim(strip_tags(escape_data($_POST['npwordc'])));
	} else {
		$errors[] = 'Please confirm your new password.';
	}
	
	if ($npword!=$npwordc) {
		$errors[] = 'Your new password did not match your confirmation.';
	}
	
	if (empty($errors)) {
		$update = mysql_query("UPDATE users SET password=SHA('$npword') WHERE user_id='$id' AND password=SHA('$pword')");
		if (mysql_affected_rows()>0) {
			echo '<div align="center" class="p18">Your password has been successfully changed!</div>';
		} else {
			echo '<div align="center" class="p18">We were unable to change your password. Sorry for the inconvenience. Please try again.</div>';	
		}
		echo '<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/settings/editpword.php" method="post">
		<div align="center" style="padding-left: 16px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="200px">current password</td><td align="left" valign="center" style="padding-bottom: 2px;">
					<input type="password" id="pword" name="pword" size="30" maxlength="40" autocomplete="off" onfocus="this.className=\'inputfocus\';" onblur="this.className=\'inputplaceholderblur\';" />
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="200px">new password</td><td align="left" valign="center" style="padding-bottom: 2px;">
					<input type="password" id="npword" name="npword" size="30" maxlength="40" autocomplete="off" onfocus="this.className=\'inputfocus\';" onblur="this.className=\'inputplaceholderblur\';" />
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="200px">confirm new password</td><td align="left" valign="center" style="padding-bottom: 2px;">
					<input type="password" id="npwordc" name="npwordc" size="30" maxlength="40" autocomplete="off" onfocus="this.className=\'inputfocus\';" onblur="this.className=\'inputplaceholderblur\';" />
				</td></tr></table>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

include ('../../../externals/header/footer-pb.php');
?>