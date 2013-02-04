<?php
$pjs = '<script type="text/javascript">
		function validEmail(email) {
			var invalidChars = " /:,;";
				
			for (var k=0; k<invalidChars.length; k++) {
				var badChar = invalidChars.charAt(k);
				if (email.indexOf(badChar) > -1) {
					return false;
				}
			}
			var atPos = email.indexOf("@",1);
			if (atPos == -1) {
				return false;
			}
			if (email.indexOf("@",atPos+1) != -1) {
				return false;
			}
			var periodPos = email.indexOf(".",atPos);
			if (periodPos == -1) {	
				return false;
			}
			if (periodPos+3 > email.length)	{
				return false;
			}
			return true;
		}
	</script>';
include ('../../../externals/header/header-pb-nlr.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Resend Meesto Account Activation</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to resend your Meesto account activation email.</div>';

if (isset($_POST['save'])) {
	
	$errors = NULL;
													
	if (isset($_POST['email']) && ($_POST['email'] != 'type your email here (ex. sean@meesto.com)')) {
		$email = escape_data($_POST['email']);
		if (!eregi('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', $email)) {
			$errors[] = 'You must enter your in the correct format: email@host.com';
		}
	} else {
		$errors[] = 'You must enter your email.';
	}
	
	if (empty($errors)) {
		if (mysql_num_rows($uinfoq = @mysql_query ("SELECT first_name, user_id, active FROM users WHERE email='$email' LIMIT 1"))>0) {
			$uinfo = @mysql_fetch_array ($uinfoq, MYSQL_ASSOC);
			if ($uinfo['active']=='yes') {
				echo 'Your email has already been activated.';
				echo '<script type="text/javascript">
					setTimeout("parent.PopBox.close();", 1200);
				</script>';
			} else {
				$fn = $uinfo['first_name'];
				$aid = $uinfo['user_id'];
				$a = $uinfo['active'];
				//resend verification email
					//send email
					$to = $email;
													
					//params
					$subject = 'Welcome to Meesto! - Meesto email verification (resent)';
					$emailercontent = 'Thank you for joining Meesto! <a href="'.$baseincpat.'verif.php?type=usr&aid='.$aid.'&a='.$a.'">Please click here to complete your Meesto account activation</a>.';
														
					include('../../../externals/general/emailer.php');
				
				echo '<table cellpadding="0" cellspacing="0"><tr><td align="left">Your activation has been resent. It may take a few mintues.<br />(Be sure to check your spam filter.)</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr></table>';
				echo '</td></tr></table>
				<script type="text/javascript">
					setTimeout("parent.PopBox.close();", 10000);
				</script>';
				include ('../../../externals/header/footer-pb.php');
			}
		} else {
				echo '<table cellpadding="0" cellspacing="0"><tr><td align="left">This email has not been registered.</td></tr><tr><td align="left" class="paragraph80" style="padding-top: 6px;"><a href="'.$baseincpat.'signup.php?" target="_parent">want to signup?</a></td></tr></table>';
				echo '</td></tr></table>
				<script type="text/javascript">
					setTimeout("parent.PopBox.close();", 3200);
				</script>';
		}
	}

} else {
	$errors = NULL;
}
	echo '<div align="center" style="margin-top: 12px;">';
	foreach ($errors as $error) {
		echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
	}
	echo '<form action="'.$baseincpat.'externalfiles/login/resndactv.php?" method="post">
			<table cellpadding="0" cellspacing="0">';
			if ($id!='') {
				$uinfo = @mysql_fetch_array (@mysql_query ("SELECT email FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
				echo '<tr><td align="center" style="padding-top: 2px;">
				<input type="hidden" name="email" value="'.$uinfo['email'].'"/>
			</td></tr></table>';
			} else {
				echo '<tr><td align="center" style="padding-top: 8px;">
					<input type="text" id="e" name="email" size="40" maxlength="200" autocomplete="off" onfocus="if (trim(this.value) == \'type your email here (ex. sean@meesto.com)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type your email here (ex. sean@meesto.com)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(this.value.length>5){ if(validEmail(this.value)){document.getElementById(\'ealrt\').innerHTML=\'\';}else{document.getElementById(\'ealrt\').innerHTML=\'please enter a valid email type (ex. sean@meesto.com)\';} }" class="inputplaceholder" value="type your email here (ex. sean@meesto.com)">
				</td></tr><tr><td align="center" style="padding-top: 2px;">
				<table cellpadding="0" cellspacing="0"><td align="left" id="ealrt" style="padding-left: 6px;" class="paragraphA1"></td></tr></table>
			</td></tr></table>';
			}
			echo '<div align="center" id="sbmtbtns">
				<div align="center" style="padding-top: 16px;">
					<input type="submit" id="submit" class="end" value="resend" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
				</div>
			</div>
			<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		</form>
		</div>';

include ('../../../externals/header/footer-pb.php');
?>