<?php
require_once ('../../../externals/general/includepaths.php');
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
		function availEmail(email) {
			
				var xhr = false;
		
					if (window.XMLHttpRequest) {
						xhr = new XMLHttpRequest();
					} else if (window.ActiveXObject) {
						try {
								xhr = new ActiveXObject("Msxml2.XMLHTTP");
						} catch (e1) {
							try {
								xhr = new ActiveXObject("Microsoft.XMLHTTP");
							} catch (e2) {}
						}
					}
					
					if (xhr) {
						xhr.onreadystatechange = function () {
							if (xhr.readyState == 4) {
								if (xhr.status == 200) {
									var echo = xhr.responseText;
								} else {
									var echo = "There was a problem with the request " + xhr.status;
								}
								
								document.getElementById(\'eldr\').style.display=\'none\';
								if (echo == \'avail\') {
									document.getElementById(\'ealrt\').className=\'paragraph80\';
									document.getElementById(\'ealrt\').innerHTML=\'this email is available!\';
									document.getElementById(\'estat\').value=\'y\';
								} else {
									document.getElementById(\'ealrt\').className=\'paragraphalrt\';
									document.getElementById(\'ealrt\').innerHTML=\'this email is already in use\';
									document.getElementById(\'estat\').value=\'n\';
								}
								
							}
						}
						xhr.open("GET", "'.$baseincpat.'externalfiles/settings/testemail.php?e="+encodeURIComponent(email), true);
						xhr.send(null);
					}
			return false;
		}
	</script>';
include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit Meest Account Email</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to change your Meesto account email. This is the email you use to login.</div>';

//test ownership
if (mysql_num_rows(@mysql_query ("SELECT user_id FROM users WHERE user_id='$id' LIMIT 1"))>0) {

$uinfo = @mysql_fetch_array (@mysql_query ("SELECT email FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);

if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (isset($_POST['email']) && ($_POST['email'] != 'type your email here')) {
		$email = trim(strip_tags(escape_data($_POST['email'])));
		if (eregi ('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', $email)) {
			if (mysql_num_rows(@mysql_query("SELECT user_id FROM users WHERE email='$e' LIMIT 1"))>0) {
				$errors[] = 'The email you entered is already in use.';
			}
		} else {
			$errors[] = 'You must enter your in the correct format: email@host.com';
		}
	} else {
		$errors[] = 'You must enter your email.';
	}
	
	if (empty($errors)) {
		
		echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">';
		if ($uinfo['email']!=$email) {
			$result = @mysql_query ("UPDATE users SET email='$email' WHERE user_id='$id'");
			if (mysql_affected_rows()>0) {
				//log change
					$olde = $uinfo['email'];
				$addlog = @mysql_query ("INSERT INTO user_chngdemail (u_id, new_email, old_email, time_stamp) VALUES ($id, '$email', '$olde', NOW())");
				
				//update verif
				$a = md5(uniqid(rand(), true));
				
				$result = @mysql_query ("UPDATE users SET active='$a', emailset_date=NOW() WHERE user_id='$id'");
				
				//send verification email
					//send email
					$to = $email;
													
					//params
					$subject = 'Meesto verification of new account email';
					$emailercontent = 'You have changed your email. <a href="'.$baseincpat.'verif.php?type=usr&aid='.$id.'&a='.$a.'">Please click here to complete your new Meesto account email activation</a>.';
														
					include('../../../externals/general/emailer.php');
					
				echo 'Your email has been changed. An email has been sent to this email verify this email.';
				
			} else {
				//report error
				echo '<table cellpadding="0" cellspacing="0" width="480px"><tr><td align="left" class="paragraph60">An error occurred: we were unable to change your email.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
				reporterror('settings/editemail.php', 'editing account email', 'unable to change email');
				echo '</td></tr></table>';
			}
		} else {
			echo 'Your email was not changed.';	
		}
		echo '</td></tr></table>
		<script type="text/javascript">
			setTimeout("parent.location.reload();", 2400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	}

} else {
	$errors = NULL;
}
	echo '<div align="center" style="margin-top: 12px;">';
	foreach ($errors as $error) {
		echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
	}
		echo '<form action="'.$baseincpat.'externalfiles/settings/editemail.php?" method="post">
			<table cellpadding="0" cellspacing="0"><tr><td align="center" style="padding-top: 8px;">
				<input type="text" id="e" name="email" size="30" maxlength="200" autocomplete="off" onfocus="if(document.getElementById(\'estat\').value==\'y\'){document.getElementById(\'ealrt\').innerHTML=\'this email is available!\';}else{document.getElementById(\'ealrt\').className=\'paragraphA1\';} if (trim(this.value) == \'(ex. sean@meesto.com)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if(document.getElementById(\'estat\').value!=\'y\'){document.getElementById(\'ealrt\').className=\'paragraphalrt\';} if (trim(this.value) == \'\') {this.value=\'(ex. sean@meesto.com)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(this.value.length>5){ if(validEmail(this.value)){document.getElementById(\'eldr\').style.display=\'block\';document.getElementById(\'ealrt\').innerHTML=\'checking availabilty...\';availEmail(this.value);}else{document.getElementById(\'eldr\').style.display=\'none\';document.getElementById(\'ealrt\').innerHTML=\'please enter a valid email type (ex. sean@meesto.com)\';} }else{document.getElementById(\'eldr\').style.display=\'none\';document.getElementById(\'ealrt\').innerHTML=\'\';}" value="'.$uinfo['email'].'">
				<input type="hidden" id="estat" value="n"/>
			</td></tr><tr><td align="center" style="padding-top: 2px;">
				<table cellpadding="0" cellspacing="0"><td align="left" id="eldr" style="padding-left: 6px; display: none;"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" id="ealrt" style="padding-left: 6px;" class="paragraphA1"></td></tr></table>
			</td></tr></table>
			<div align="center" id="sbmtbtns">
				<div align="center" style="padding-top: 16px;">
					<input type="submit" id="submit" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
				</div>
			</div>
			<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		</form>
	</div>';

} else {
	//report error
	echo '<table cellpadding="0" cellspacing="0" width="480px"><tr><td align="left" class="paragraph60">An error occurred: we were unable to retrieve your records.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
	reporterror('settings/editemail.php', 'editing account email', 'not a registered user_id');
	echo '</td></tr></table>';
}

include ('../../../externals/header/footer-pb.php');
?>