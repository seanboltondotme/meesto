<?php
require_once ('../externals/general/functions.php');
require_once ('../externals/general/includepaths.php');

//insert test for active session !imporant

require_once('externalfiles/recaptchalib.php');
	$publickey = "";
	$privatekey = "";
	$resp = null;
	$iscaptchaerror = false;

if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	/* recaptcha not active
	
	$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

	if ($resp->is_valid) {
		
	*/
	
		if (isset($_POST['fn']) && ($_POST['fn'] != 'first name')) {
			$fn = ucwords(strtolower(escape_data($_POST['fn'])));
		} else {
			$errors[] = 'You must enter your first name.';
		}
		
		if (isset($_POST['mn']) && ($_POST['mn'] != 'middle name (opt)')) {
			$mn = ucwords(strtolower(escape_data($_POST['mn'])));
		} else {
			$mn = NULL;
		}
		
		if (isset($_POST['ln']) && ($_POST['ln'] != 'last name')) {
			$ln = ucwords(strtolower(escape_data($_POST['ln'])));
		} else {
			$errors[] = 'You must enter your last name.';
		}
		
		if (isset($_POST['e']) && ($_POST['e'] != '(ex. sean@meesto.com)')) {
			$e = escape_data($_POST['e']);
			if (eregi ('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', $e)) {
				if (mysql_num_rows(@mysql_query("SELECT user_id FROM users WHERE email='$e' LIMIT 1"))>0) {
					$errors[] = 'The email you entered was already registered.';
				}
			} else {
				$errors[] = 'You must enter your in the correct format: email@host.com';
			}
		} else {
			$errors[] = 'You must enter your email.';
		}
		
		if (isset($_POST['gender'])) {
			$gender = escape_data($_POST['gender']);
		} else {
			$errors[] = 'You must enter your gender.';
		}
		
		if (isset($_POST['smonth'])&&($_POST['smonth']!='')) {
			$smonth = escape_data($_POST['smonth']);
		} else {
			$errors[] = 'You must enter your birthday month.';
		}
		
		if (isset($_POST['sday'])&&($_POST['sday']!='')) {
			$sday = escape_data($_POST['sday']);
		} else {
			$errors[] = 'You must enter your birthday day.';
		}
		
		if (isset($_POST['syear'])&&($_POST['syear']!='')) {
			$syear = escape_data($_POST['syear']);
		} else {
			$errors[] = 'You must enter your birthday year.';
		}
		
		if (isset($_POST['pw'])&&($_POST['pw']!='')) {
			$pw = escape_data($_POST['pw']);
		} else {
			$errors[] = 'You must enter a password.';
		}
		
	/* recaptcha not active
	
	} else {
		$iscaptchaerror = true;
		$errors[] = 'Incorrect recaptcha please try again.';
	}
	
	*/
	
	if (empty($errors)) {
		//create user
		require_once('../externals/sessions/db_sessions.inc.php');
		
		$a = md5(uniqid(rand(), true));
		
		$insert = mysql_query("INSERT INTO users (active, first_name, middle_name, last_name, email, emailset_date, password, registration_date, defaultimg_url, gender, birthday) VALUES ('$a', '$fn', '$mn', '$ln', '$e', NOW(), SHA('$pw'), NOW(), 'images/nophoto.png', '$gender', '$syear-$smonth-$sday 00:00:00')");
		$id = mysql_insert_id();
		
		//insert user dependancies
		$insert = mysql_query("INSERT INTO meefile_basic (u_id) VALUES ('$id')");
		$insert = mysql_query("INSERT INTO meefile_pers (u_id) VALUES ('$id')");
		$insert = mysql_query("INSERT INTO user_activityposts (u_id, pa, ptags, mtabs, events, time_stamp) VALUES ('$id', 'y', 'y', 'y', 'y', NOW())");
		$insert = mysql_query("INSERT INTO user_e_notif (u_id, req_cnct, req_eventi, req_proji, req_rltnshp, reqa_cnct, reqa_eventi, reqa_proji, reqa_rltnshp, mkadmin_event, mkadmin_proj, tag_photo, msg, emo_myfeed, emo_onfeed, cmt_myfeed, cmt_onfeed, cmt_myphoto, cmt_onphoto, cmt_mymtsec, cmt_onmtsec, cmt_myevntcmt, cmt_onevntcmt, cmt_myprojcmt, cmt_onprojcmt, cmt_myfdbk, cmt_onfdbk, admn_evntcmt, admn_projcmt, meesto_news, meesto_blog, meesto_help_resp, time_stamp) VALUES ('$id', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', NOW())");
		//add blog tab
		$result = mysql_query ("INSERT INTO meefile_tab (u_id, name, time_stamp) VALUES ('$id', 'blog', NOW())");
		$tabid = mysql_insert_id();
		$addvis = mysql_query("INSERT INTO meefile_tab_vis (tab_id, type, sub_type, time_stamp) VALUES ('$tabid', 'strm', 'mb', NOW())");
		$addvis = mysql_query("INSERT INTO meefile_tab_vis (tab_id, type, sub_type, time_stamp) VALUES ('$tabid', 'strm', 'frnd', NOW())");
		
		//set def vis
		$addvis = mysql_query("INSERT INTO mc_vis (u_id, type, sub_type, time_stamp) VALUES ('$id', 'strm', 'mb', NOW())");
		$addvis = mysql_query("INSERT INTO mc_vis (u_id, type, sub_type, time_stamp) VALUES ('$id', 'strm', 'frnd', NOW())");
		$addvis = mysql_query("INSERT INTO defvis_feed (u_id, type, sub_type, time_stamp) VALUES ('$id', 'strm', 'mb', NOW())");
		$addvis = mysql_query("INSERT INTO defvis_feed (u_id, type, sub_type, time_stamp) VALUES ('$id', 'strm', 'frnd', NOW())");
		$addvis = mysql_query("INSERT INTO defvis_apt (u_id, type, sub_type, time_stamp) VALUES ('$id', 'strm', 'mb', NOW())");
		$addvis = mysql_query("INSERT INTO defvis_apt (u_id, type, sub_type, time_stamp) VALUES ('$id', 'strm', 'frnd', NOW())");
		$msvs = array('idcnt', 'idbas', 'idpers', 'meepic');
		foreach($msvs as $sec) {
			$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'strm', 'mb', NOW())");
			$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'strm', 'frnd', NOW())");
		}
		$minfosecs = array('genbio', 'gen2c', 'genrs', 'genbday', 'gengndr', 'genintin', 'genpol', 'genrel', 'genht', 'genct', 'genfavc', 'cntctme', 'prsact', 'prsint', 'prsfq', 'prsvs', 'prsdl', 'prsrs', 'prsam');
		foreach ($minfosecs as $sec) {
			$addvis = mysql_query("INSERT INTO meefile_infosec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'strm', 'mb', NOW())");
			$addvis = mysql_query("INSERT INTO meefile_infosec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'strm', 'frnd', NOW())");
		}
		
		//update any recieved invites
		$addimint = mysql_query("UPDATE e_invites SET ref_id='$id' WHERE email='$e' AND type='meesto'");
		
		//create session
			$_SESSION['user_id'] = $id;
			$_SESSION['name'] = $fn;
			$_SESSION['client'] = 'pc';
		
		//send verification email
			//send email
			$to = $e;
												
			//params
			$subject = 'Welcome to Meesto! - Meesto email verification';
			$emailercontent = 'Thank you for joining Meesto! <a href="'.$baseincpat.'verif.php?type=usr&aid='.$id.'&a='.$a.'">Please click here to complete your Meesto account activation</a>.<br /><br />Be sure to check out our "<a href="'.$baseincpat.'howyoucanhelp.php?">how you can help</a>" page!';
												
			include('../externals/general/emailer.php');
			
		//create dirs
		require_once ('../externals/ftp/ftpconnect.php');
			if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
				if (ftp_mkdir($conn_id, "$ftp_basedir/users/$id")) {
					ftp_chmod($conn_id, 0777, "$ftp_basedir/users/$id");
					ftp_mkdir($conn_id, "$ftp_basedir/users/$id/meepics");
					ftp_chmod($conn_id, 0777, "$ftp_basedir/users/$id/meepics");
					ftp_mkdir($conn_id, "$ftp_basedir/users/$id/photos");
					ftp_chmod($conn_id, 0777, "$ftp_basedir/users/$id/photos");
					ftp_mkdir($conn_id, "$ftp_basedir/users/$id/attachments");
					ftp_chmod($conn_id, 0777, "$ftp_basedir/users/$id/attachments");
				} else {
					reporterror('signup.php', 'creating user dirs', 'unable to make directories id='.$id.'; failed at mkdir');
				}
			} else {
				reporterror('signup.php', 'creating user dirs', 'unable to make directories id='.$id.'; failed at login');
			}
			ftp_close($conn_id); //finish and close ftp
		
		//login			
			$setlastlogin = @mysql_query ("INSERT INTO user_logins (u_id, time_stamp) VALUES ($id, NOW())");
			
		//shwew hopefully we made it here! now lets load the welcome page!
			echo '<script type="text/javascript">
				window.location.href = \''.$baseincpat.'welcome.php\';
			</script><div align="left" valign="top" style="padding: 24px;">
				We were unable to redirect you. <form action="'.$baseincpat.'welcome.php?"><input type="submit" value="click here to continue"/>
			</div>';
			exit();
	}
	
} else {
	$fn = '';
	$mn = '';
	$ln = '';
	$e = '';
}
	
$title = 'Signup';
//js
$pjs =  '<script type="text/javascript">
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
								
								$(\'eldr\').style.display=\'none\';
								if (echo == \'avail\') {
									$(\'ealrt\').className=\'paragraph80\';
									$(\'ealrt\').innerHTML=\'this email is available!\';
									$(\'estat\').value=\'y\';
									
								} else {
									$(\'ealrt\').className=\'palert\';
									$(\'ealrt\').innerHTML=\'this email is already in use\';
									$(\'estat\').value=\'n\';
									
								}
								
							}
						}
						xhr.open("GET", "'.$baseincpat.'externalfiles/settings/testemail.php?e="+encodeURIComponent(email), true);
						xhr.send(null);
					}
			return false;
		}
	</script>';
include ('../externals/header/header.php');

//main structure
echo '<div align="left" style="width: 900px;">
<div align="left" class="p24" style="margin-bottom: 4px;">Signup for Meesto Prototype</div>
<div align="left" style="font-size: 18px; line-height: 26px; border-bottom: 1px solid #C5C5C5; padding-bottom: 6px;">It has all the main features of any other social networking tool, without the spying, and you can use it today.<br /><span style="font-size: 16px;">(The prototype is not perfect and will be replaced by a tool developed by the <a href="'.$baseincpat.'howyoucanhelp.php?t=development">Open Source Community</a>.)</span></div>';

//content area
echo '<div align="left" style="margin-top: 6px; margin-bottom: 4px;">Take a deep breath. Fill out your info. Don\'t be afraid to smile, today\'s gonna be a good day.</div>
<div align="center" style="margin-top: 32px;">';

foreach ($errors as $error) {
	echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
}

echo '<form action="'.$baseincpat.'signup.php" method="post">
<table cellpadding="0" cellspacing="0"><tr><td align="left">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="110px">name:</td><td align="left" style="padding-left: 2px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left">
			<input type="text" id="fn" name="fn" size="14" maxlength="60" autocomplete="off" onfocus="if (trim(this.value) == \'first name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'first name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="" style="font-size: 18px;" '; if($fn!=''){echo'value="'.$fn.'"';}else{echo'class="inputplaceholder" value="first name"';} echo'>
		</td><td align="left" style="padding-left: 8px;">
			<input type="text" id="mn" name="mn" size="14" maxlength="60" autocomplete="off" onfocus="if (trim(this.value) == \'middle name (opt)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'middle name (opt)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="" style="font-size: 18px;" '; if($mn!=''){echo'value="'.$mn.'"';}else{echo'class="inputplaceholder" value="middle name (opt)"';} echo'>
		</td><td align="left" style="padding-left: 8px;">
			<input type="text" id="ln" name="ln" size="16" maxlength="60" autocomplete="off" onfocus="if (trim(this.value) == \'last name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'last name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="" style="font-size: 18px;" '; if($ln!=''){echo'value="'.$ln.'"';}else{echo'class="inputplaceholder" value="last name"';} echo'>
		</td></tr></table>
	</td></tr></table>
</td></tr><tr><td align="left" style="padding-top: 12px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="110px">email:</td><td align="left" style="padding-left: 2px;">
		<input type="text" id="e" name="e" size="30" maxlength="200" autocomplete="off" onfocus="if($(\'estat\').value==\'y\'){$(\'ealrt\').innerHTML=\'this email is available!\';}else{$(\'ealrt\').className=\'paragraphA1\';} if (trim(this.value) == \'(ex. sean@meesto.com)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if($(\'estat\').value==\'y\'){$(\'ealrt\').innerHTML=\'\';}else{$(\'ealrt\').className=\'palert\';} if (trim(this.value) == \'\') {this.value=\'(ex. sean@meesto.com)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(this.value.length>5){ if(validEmail(this.value)){$(\'eldr\').style.display=\'block\';$(\'ealrt\').innerHTML=\'checking availabilty...\';availEmail(this.value);}else{$(\'eldr\').style.display=\'none\';$(\'ealrt\').innerHTML=\'please enter a valid email type (ex. sean@meesto.com)\';} }else{$(\'eldr\').style.display=\'none\';$(\'ealrt\').innerHTML=\'\';}" '; if($e!=''){echo'value="'.$e.'"';}else{echo'class="inputplaceholder" value="(ex. sean@meesto.com)"';} echo'/>
		<input type="hidden" id="estat" value="n"/>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" id="eldr" style="display: none;"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" id="ealrt" style="padding-left: 6px;"></td></tr></table>
	</td></tr></table>
</td></tr><tr><td align="left" style="padding-top: 12px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="110px">gender:</td><td align="left" style="padding-left: 2px;" class="paragraph60">
		<table cellpadding="0" cellspacing="0"><tr><td align="center">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if(document.getElementById(\'gm\').checked == false){document.getElementById(\'gm\').checked = true;} "><tr><td align="left">
							<input type="radio" name="gender" id="gm" value="male" onclick="if(document.getElementById(\'gm\').checked == false){document.getElementById(\'gm\').checked = true;} " '; if((isset($_POST['save']))&&($_POST['gender']=='male')){echo' CHECKED';} echo'/>
						</td><td align="left" style="padding-left: 4px;">male</td></tr></table>
					</td><td align="center" style="padding-left: 16px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if(document.getElementById(\'gf\').checked == false){document.getElementById(\'gf\').checked = true;} "><tr><td align="left">
							<input type="radio" name="gender" id="gf" value="female" onclick="if(document.getElementById(\'gf\').checked == false){document.getElementById(\'gf\').checked = true;} " '; if((isset($_POST['save']))&&($_POST['gender']=='female')){echo' CHECKED';} echo'/>
						</td><td align="left" style="padding-left: 4px;">female</td></tr></table>
					</td></tr></table>
	</td></tr></table>
</td></tr><tr><td align="left" style="padding-top: 12px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="110px">birthday:</td><td align="left" style="padding-left: 2px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" style="padding-left: 4px;">
									<select id="smonth" name="smonth" onchange="s_solarcal(this.value, document.getElementById(\'syear\').value); ">'; 
										//set arrays
										$thisyear = date('Y');
										$endyear = $thisyear-13;
										$month = array (1 => '1 - January', '2 - February', '3 - March', '4 - April', '5 - May', '6 - June', '7 - July', '8 - August', '9 - September', '10 - October', '11 - November', '12 - December');
										$days_r = range (1, 31);
										$days_sm = range (1, 30);
										$days_feblp = range (1, 29);
										$days_feb = range (1, 28);
										$year = range ($endyear, 1919);
										echo '<option value="" SELECTED>month</option>\n';
									foreach ($month as $key => $value) {
										if ((isset($_POST['save']))&&($key==$_POST['smonth'])) {
											echo "<option value=\"$key\" SELECTED>$value</option>\n";
										} else {
											echo "<option value=\"$key\">$value</option>\n";
										}
									} echo '</select>
								</td><td align="left" style="padding-left: 4px;">
									<input type="hidden" id="sday" name="sday" value="'; if(isset($_POST['save'])){echo $_POST['sday'];}elseif(substr($thisday, 0, 1)=='0'){echo substr($thisday, 1, 1);}else{echo $thisday;} echo'"/>
									<select id="sday_r" name="sday_r" style="display: '; if(!(($thismonth==2)||($thismonth==4)||($thismonth==6)||($thismonth==9)||($thismonth==11))){echo'block';}else{echo'none';} echo';" onchange="document.getElementById(\'sday\').value=this.value; if(this.value>30){document.getElementById(\'sday_sm\').value=30;}else{document.getElementById(\'sday_sm\').value=this.value;} if(this.value>28){document.getElementById(\'sday_feb\').value=28;}else{document.getElementById(\'sday_feb\').value=this.value;} if(this.value>29){document.getElementById(\'sday_feblp\').value=29;}else{document.getElementById(\'sday_feblp\').value=this.value;} ">'; 
									echo '<option value="" SELECTED>day</option>\n';
									foreach ($days_r as $value) { 
										if ((isset($_POST['save']))&&($value==$_POST['sday'])) {
											echo "<option value=\"$key\" SELECTED>$value</option>\n";
										} else {
											echo "<option value=\"$value\">$value</option>\n";
										}
									 } 
									echo '</select>
									<select id="sday_sm" name="sday_sm" style="display: '; if(($thismonth==4)||($thismonth==6)||($thismonth==9)||($thismonth==11)){echo'block';}else{echo'none';} echo';" onchange="document.getElementById(\'sday\').value=this.value; document.getElementById(\'sday_r\').value=this.value; if(this.value>28){document.getElementById(\'sday_feb\').value=28;}else{document.getElementById(\'sday_feb\').value=this.value;} if(this.value>29){document.getElementById(\'sday_feblp\').value=29;}else{document.getElementById(\'sday_feblp\').value=this.value;} ">'; 
									echo '<option value="" SELECTED>day</option>\n';
									foreach ($days_sm as $value) { 
										if ((isset($_POST['save']))&&($value==$_POST['sday'])) {
											echo "<option value=\"$key\" SELECTED>$value</option>\n";
										} else {
											echo "<option value=\"$value\">$value</option>\n";
										}
									 } 
									echo '</select>
									<select id="sday_feb" name="sday_feb" style="display: '; if(($thismonth==2)&&!(($thisyear > 1582) && ((!($thisyear%400)) || ((!!($thisyear%100)) && (!($thisyear%4)))))){echo'block';}else{echo'none';} echo';" onchange="document.getElementById(\'sday\').value=this.value; document.getElementById(\'sday_r\').value=this.value;document.getElementById(\'sday_sm\').value=this.value;document.getElementById(\'sday_feblp\').value=this.value; ">'; 
									echo '<option value="" SELECTED>day</option>\n';
									foreach ($days_feb as $value) { 
										if ((isset($_POST['save']))&&($value==$_POST['sday'])) {
											echo "<option value=\"$key\" SELECTED>$value</option>\n";
										} elseif (($thisday=='29')&&($value=='28')) {
											echo "<option value=\"$value\" SELECTED>$value</option>\n";
										} else {
											echo "<option value=\"$value\">$value</option>\n";
										}
									 } 
									echo '</select>
									<select id="sday_feblp" name="sday_feblp" style="display: '; if(($thismonth==2)&&(($thisyear > 1582) && ((!($thisyear%400)) || ((!!($thisyear%100)) && (!($thisyear%4)))))){echo'block';}else{echo'none';} echo';" onchange="document.getElementById(\'sday\').value=this.value; document.getElementById(\'sday_r\').value=this.value;document.getElementById(\'sday_sm\').value=this.value; if(this.value>28){document.getElementById(\'sday_feb\').value=28;}else{document.getElementById(\'sday_feb\').value=this.value;} ">'; 
									echo '<option value="" SELECTED>day</option>\n';
									foreach ($days_feblp as $value) { 
										if ((isset($_POST['save']))&&($value==$_POST['sday'])) {
											echo "<option value=\"$key\" SELECTED>$value</option>\n";
										} else {
											echo "<option value=\"$value\">$value</option>\n";
										}
									 } 
									echo '</select>
								</td><td align="left" style="padding-left: 4px;">
									<select id="syear" name="syear" onchange="s_solarcal(document.getElementById(\'smonth\').value, this.value); ">'; 
									echo '<option value="" SELECTED>year</option>\n';
									foreach ($year as $value) { 
										if ((isset($_POST['save']))&&($value==$_POST['syear'])) {
											echo "<option value=\"$key\" SELECTED>$value</option>\n";
										} else {
											echo "<option value=\"$value\">$value</option>\n";
										}
									 } 
									echo '</select>
								</td></tr></table>
	</td></tr></table>
</td></tr><tr><td align="left" style="padding-top: 12px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="110px">password:</td><td align="left" style="padding-left: 2px;">
		<input type="password" id="pw" name="pw" size="24" maxlength="30" autocomplete="off" onfocus="this.className=\'inputfocus\';" onblur="this.className=\'inputplaceholderblur\';" onkeyup="" value="">
	</td></tr></table>
</td></tr><tr><td align="left" style="padding-top: 12px; padding-bottom: 24px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="110px">verification:</td><td align="left" class="paragraph60" style="padding-left: 2px;">';
	
	/* recaptcha not active – needs valid access key
				
				echo '<script>
				var RecaptchaOptions = {
				   theme: \'custom\',
				   lang: \'en\',
				   custom_theme_widget: \'recaptcha_widget\'
				};
				</script>
				
				<div id="recaptcha_widget" style="display:none">
				
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
					
					<table cellpadding="0" cellspacing="0"><tr><td align="left">
						<div id="recaptcha_image"></div>
					</td></tr><tr><td align="left" style="padding-top: 6px;">';	
						if ($iscaptchaerror==true) {
							echo '<div class="palert">Incorrect please try again</div>';
						}
						echo '<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" size="41" autocomplete="off" onfocus="if (trim(this.value) == \'type the words above\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type the words above\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="" class="inputplaceholder" value="type the words above">
					</td></tr></table>
					
				</td><td align="left" valign="top" style="padding-left: 10px; padding-top: 6px;">
				
					<table cellpadding="0" cellspacing="0"><tr><td align="left">
						<div><a href="javascript:Recaptcha.reload()"><img src="'.$baseincpat.'images/captcha/refresh.png" /></a></div>
					</td></tr><tr><td align="left">
						<div id="captchabtntext" style="display: none;"><a href="javascript:Recaptcha.switch_type(\'image\')"><img src="'.$baseincpat.'images/captcha/text.png" onclick="$(\'captchabtntext\').style.display=\'none\';$(\'captchabtnaudio\').style.display=\'block\';"/></a></div>
					</td></tr><tr><td align="left">
						<div id="captchabtnaudio"><a href="javascript:Recaptcha.switch_type(\'audio\')"><img src="'.$baseincpat.'images/captcha/audio.png" onclick="$(\'captchabtnaudio\').style.display=\'none\';$(\'captchabtntext\').style.display=\'block\';"/></a></div>
					</td></tr><tr><td align="left">
						<div><a href="javascript:Recaptcha.showhelp()"><img src="'.$baseincpat.'images/captcha/help.png" /></a></div>
					</td></tr></table>
				
				</td><td align="left" valign="top" style="padding-left: 18px; padding-top: 6px;">
				
					<table cellpadding="0" cellspacing="0"><tr><td align="left"><img src="'.$baseincpat.'images/captcha/logo.png" /></td></tr><tr><td align="left" style="padding-top: 14px;"><img src="'.$baseincpat.'images/captcha/tagline.png" /></td></tr></table>
				
				</td></tr></table>
				
				<script type="text/javascript" src="http://api.recaptcha.net/challenge?k='.$publickey.'"></script>';
				
			*/
			echo 'recaptcha is turned off';
			
				
	echo '</td></tr></table>
</td></tr></table>
<div align="center" style="padding-top: 12px; border-top: 1px solid #E4E4E4;">
		<div align="center" id="sbmtbtns">
			<div align="center">every journey begins with one step...</div>
			<div align="center" style="padding-top: 8px;">
				<input type="submit" id="submit" class="end" value="begin" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
</div>
</form>
</div>';

include ('../externals/header/footer.php');
?>
