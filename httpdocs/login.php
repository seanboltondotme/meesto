<?php
require_once ('../externals/general/functions.php');
$rel = strip_tags(escape_data(urldecode($_GET['rel'])));
if (isset($_POST['login'])) {
		
		require_once('../externals/sessions/db_sessions.inc.php');
		
		if (empty($_POST['email'])) {
			$errors [] = 'Please enter your email address.';
		} else {
			if (eregi ('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', trim(strip_tags(escape_data($_POST['email']))))) {
			$email = trim(strip_tags(escape_data($_POST['email'])));
			} else {
				$errors [] = 'Please enter a valid email address. (ex. sean@meesto.com)';
			}
		}
								
		if (empty($_POST['password'])) {
			$errors[] = 'Please enter your password.';
		} else {
			$password = trim(strip_tags(escape_data($_POST['password'])));
		}
	
		if (empty($errors)) {
			
			$user = @mysql_fetch_array (@mysql_query ("SELECT user_id, first_name FROM users WHERE email='$email' AND password=SHA('$password') AND ((active='yes') OR (NOW() BETWEEN emailset_date AND ADDDATE(emailset_date, INTERVAL 31 DAY))) LIMIT 1"), MYSQL_NUM);
		
			if (isset($user[0])) {
					
					$_SESSION['user_id'] = $user[0];
					$_SESSION['name'] = $user[1];
					$_SESSION['client'] = 'pc';
					$id = $_SESSION['user_id'];
					
					$setlastlogin = @mysql_query ("INSERT INTO user_logins (u_id, time_stamp) VALUES ('$id', NOW())");
					
					if ($rel!='') {
						echo '<script type="text/javascript">
							window.location.href = \''.$baseincpat.$rel.'\';
						</script>
						<div align="left" valign="top" style="padding: 24px;">
							We were unable to redirect you. <form action="'.$baseincpat.'home.php?"><input type="submit" value="click here to go to the home page"/></form>
						</div>';
					} else {
						$url = $baseincpat.'home.php?';
						header("Location: $url");
					}
					exit();
			} elseif (@mysql_num_rows(@mysql_query ("SELECT user_id FROM users WHERE email='$email' AND password=SHA('$password') AND active!='yes' LIMIT 1")) > 0) {
				$errors[] = 'Your account email needs to be activated. Please click the link in the activation email we sent to you.<br ><span class="paragraph80">If you did not receive the email click the \'resend activation\' button to your bottom right.</span>';
			} elseif (@mysql_num_rows(@mysql_query ("SELECT user_id FROM users WHERE email='$email' LIMIT 1")) > 0) {
				$errors[] = 'The password you entered was incorrect.';
			} elseif (@mysql_num_rows(@mysql_query ("SELECT user_id FROM users WHERE email='$email' LIMIT 1"))==0) {
				$errors[] = 'The email you entered has not been registered. <span class="paragraph80"><a href="'.$baseincpat.'signup.php">Click here to signup</a></span>.';
			} else {
				$errors[] = 'An error occurred, please check your email and password and try again.<br /><span class="paragraph80">If the problem persists please let us know.</span>';
				$reporter = true;
			}
		
		@mysql_close();
		}
}
require_once('../externals/sessions/db_sessions.inc.php');

if ($_SESSION['user_id'] != NULL) {
	
	echo '<script type="text/javascript">
		window.location.href = \''.$baseincpat.'home.php\';
	</script>';
	exit();
	
}

$title = 'Login';
include ('../externals/header/header.php');

//main structure
echo '<div align="left" class="p24" style="width: 600px; padding-top: 16px;">Login</div><div align="center" style="width: 900px; padding-top: 16px; padding-bottom: 32px;">';

if (isset($_POST['login'])) {
	echo '<div align="left" class="palert" style="width: 600px; padding-bottom: 12px;">';
	foreach ($errors as $error) {
		echo $error.' '; if($reporter){reporterror('login.php', 'logging in', $error);} echo'<br />';	
	}
	echo '</div>';
}
echo '<div align="left" style="width: 640px; padding-left: 150px;">
	<table cellpadding="0" cellspacing="0" width="640px"><tr><td align="center" width="474px">
		<form action="'.$baseincpat.'login.php'; if($rel!=''){echo'?rel='.urlencode($rel);} echo'" method="post">
			<div align="left">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" width="100px">email</td><td style="padding-left: 4px;">
					<input type="text" id="email" name="email" size="24" maxlength="200" autocomplete="off" onfocus="this.className=\'inputfocus\';" onblur="this.className=\'inputplaceholderblur\';" value="">
				</td></tr></table>
			</div><div align="left" style="padding-top: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" width="100px">password</td><td style="padding-left: 4px;">
					<input type="password" id="password" name="password" size="24" maxlength="40" autocomplete="off" onfocus="this.className=\'inputfocus\';" onblur="this.className=\'inputplaceholderblur\';" value="">
				</td></tr></table>
			</div><div align="center" style="padding-top: 18px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left">
					<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
				</td><td align="left">
					<div id="submitbtns" align="left">
						<input type="submit" id="submit" value="login" name="login" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
					</div>
				</td></tr></table>
			</div>
		</form>
	</td><td align="right" valign="top" width="166px">
		<div style="border-left: 1px solid #C5C5C5; padding-left: 18px; padding-bottom: 24px;">
			<div align="left"><input type="button" value="forgot password" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/login/frgtpw.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
			<div align="left" style="padding-top: 12px;"><input type="button" value="resend activation" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/login/resndactv.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
		</div>
	</td></tr></table>
	
	</div>
</div>';

include ('../externals/header/footer.php');
?>