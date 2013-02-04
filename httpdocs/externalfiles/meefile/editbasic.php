<?php
require_once ('../../../externals/general/includepaths.php');

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {
require_once('../../../externals/sessions/db_sessions.inc.php');
require_once ('../../../externals/general/functions.php');
$id = $_SESSION['user_id'];

$binfo= mysql_fetch_array (mysql_query ("SELECT pb.bio, pb.twocents, pb.status, pb.status_id, pb.status_status, pb.interested_in, pb.political, pb.religious, pb.hometown, pb.currenttown, pb.fav_color, u.gender, DATE_FORMAT(u.birthday, '%M %D, %Y') AS bday, DATE_FORMAT(u.birthday, '%Y-%m-%d') AS binfo FROM meefile_basic pb INNER JOIN users u ON pb.u_id=u.user_id WHERE pb.u_id='$id' LIMIT 1"), MYSQL_ASSOC);

$pjs = '<link rel="stylesheet" href="'.$baseincpat.'externalfiles/autocompleter/TextboxList.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.css" type="text/css" media="screen" charset="utf-8" />
	<script src="'.$baseincpat.'externalfiles/autocompleter/GrowingInput.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.js" type="text/javascript" charset="utf-8"></script>		
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.Binary.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css" media="screen">
		.textboxlist-loading { background: url(\''.$baseincpat.'images/spinner.gif\') no-repeat 416px center; }
		.form_tags .textboxlist, #form_peeple .textboxlist { width: 200px; }
	</style>';
$pdrjs = 'var t4 = new TextboxList(\'form_peeplenames_input\', {unique: true, max: 1, startEditableBit: false, inBetweenEditableBits: false, plugins: {autocomplete: {placeholder: \'start typing the name of one of your peeple\'}}});';
				//test to preload ht
				if (($binfo['status_id']!='')&&($binfo['status_id']>0)) {
					$pdrjs .= 't4.add(\''.returnpersonname($binfo['status_id']).'\', \''.$binfo['status_id'].'\');';
				}
			$pdrjs .= 't4.container.addClass(\'textboxlist-loading\');	
			new Request.JSON({url: \''.$baseincpat.'externalfiles/autocompleter/grabmypeeple.php\', onSuccess: function(r){
				t4.plugins[\'autocomplete\'].setValues(r);
				t4.container.removeClass(\'textboxlist-loading\');
			}}).send();';
			
$fullmts = true;
$ifname = 'editib';
include ('../../../externals/header/header-iframe.php');

if (isset($_POST['save'])) {
//save
	
	$errors = NULL;
	
	if (isset($_POST['bio']) && ($_POST['bio'] != 'a super short about me')) {
		$bio = escape_form_data($_POST['bio']);
	} else {
		$bio = '';
	}
	
	if (isset($_POST['twocents']) && ($_POST['twocents'] != 'what is your two cents? (your little advice to the world; think of it like a bumper sticker)')) {
		$twocents = escape_form_data($_POST['twocents']);
	} else {
		$twocents = '';
	}
	
	if (isset($_POST['rs_type']) && ($_POST['rs_type'] != '')) {
		$rs_type = escape_data($_POST['rs_type']);
	} else {
		$rs_type = '';
	}
	
	$rs_uid = escape_data($_POST['rs_name']);
	if ($binfo['status_id']!=$rs_uid) {
			//delete old
			$deleteold = mysql_query("DELETE FROM requests WHERE s_id='$id' AND type='rs'");
			$rs_stat = '';
				$oldrs_uid = $binfo['status_id'];
				if ($oldrs_uid>0) {
					$update = mysql_query("UPDATE meefile_basic SET status_id='' WHERE u_id='$oldrs_uid'");
				}
		//test ownership
		if ($rs_uid>0) {
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$rs_uid' LIMIT 1"), 0)>0) {
				$rs_stat = 'p';
				//make request
				$add = mysql_query("INSERT INTO requests (u_id, type, s_id, params, time_stamp) VALUES ('$rs_uid', 'rs', '$id', '$rs_type', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$rs_uid' AND req_rltnshp='y' LIMIT 1"), 0)>0) {
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$rs_uid' LIMIT 1"), 0);
					
					//params
					$subject = 'Relationship confirmation request from '.returnpersonnameasid($id, $rs_uid);
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $rs_uid).'</a> has changed '; if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$id' AND gender='male'"), 0)>0){$emailercontent .= 'his';}else{$emailercontent .= 'her';} $emailercontent .= ' relationship status to "'.$rs_type.'" with you. Please <a href="'.$baseincpat.'home.php?">confirm or deny this</a>.';
					
					include('../../../externals/general/emailer.php');
				}
					
			} else {
				$rs_stat = '';
			}
		} else {
			$rs_uid = '';
			$rs_stat = '';
		}
	} else {
		$rs_uid = '';
		$rs_stat = '';
	}
	
	if (isset($_POST['intin_m']) && isset($_POST['intin_w'])) {
		$interested_in = 'men and women';
	} elseif (isset($_POST['intin_m'])) {
		$interested_in = 'men';
	} elseif (isset($_POST['intin_w'])) {
		$interested_in = 'women';
	} else {
		$interested_in = '';
	}
	
	if (isset($_POST['political']) && ($_POST['political'] != 'your political view? (no, not just politics; your thoughts on the matter)')) {
		$political = escape_form_data($_POST['political']);
	} else {
		$political = '';
	}
	
	if (isset($_POST['religious']) && ($_POST['religious'] != 'your religious view or your views on religion?')) {
		$religious = escape_form_data($_POST['religious']);
	} else {
		$religious = '';
	}
	
	if (isset($_POST['hometown']) && ($_POST['hometown'] != 'where are you from? (feel free to talk about it, not just write a simple city name)')) {
		$ht = escape_form_data($_POST['hometown']);
	} else {
		$ht = '';
	}
	
	if (isset($_POST['currenttown']) && ($_POST['currenttown'] != 'where are you living now? (feel free to talk about it, not just write a simple city name)')) {
		$ct = escape_form_data($_POST['currenttown']);
	} else {
		$ct = '';
	}
	
	if (isset($_POST['fav_color']) && ($_POST['fav_color'] != 'your favorite color? (simple? yes. interesting? you tell me; feel free to explain.)')) {
		$fav_color = escape_form_data($_POST['fav_color']);
	} else {
		$fav_color = '';
	}
			
	if (empty($errors)) {
		$update = mysql_query("UPDATE meefile_basic SET bio='$bio', twocents='$twocents', status='$rs_type', status_id='$rs_uid', status_status='$rs_stat', interested_in='$interested_in', political='$political', religious='$religious', hometown='$ht', currenttown='$ct', fav_color='$fav_color' WHERE u_id='$id'");
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'bieditbtn\').set(\'styles\',{\'display\':\'block\'});parent.$(\'bivisbtn\').set(\'styles\',{\'display\':\'none\'});parent.gotopage(\'binfomain\', \''.$baseincpat.'externalfiles/meefile/grabbasic.php?id='.$id.'\');", \'0\');
			</script>';
	} else {
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'bieditbtn\').set(\'styles\',{\'display\':\'block\'});parent.$(\'bivisbtn\').set(\'styles\',{\'display\':\'none\'});", \'3200\');
			</script>';
		echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('meefile/editbasic.php', 'editing basic info', $errors);
	}
	
} else {

echo '<form action="'.$baseincpat.'externalfiles/meefile/editbasic.php?action=iframe" method="post">
	
	<div align="left" id="genbio" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtnbio\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtnbio\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genbio' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">bio</td><td align="left" width="406px">
			<textarea name="bio" cols="44" rows="2" onfocus="if (trim(this.value) == \'a super short about me\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'a super short about me\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'bioovertxtalrt\');"';
				if ($binfo['bio']!=''){echo'>'.$binfo['bio'];}else{echo' class="inputplaceholder">a super short about me';}
			echo '</textarea>
			<div id="bioovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtnbio" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=genbio\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="gen2c" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtn2c\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtn2c\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='gen2c' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">two cents</td><td align="left" width="406px">
			<textarea name="twocents" cols="44" rows="3" onfocus="if (trim(this.value) == \'what is your two cents? (your little advice to the world; think of it like a bumper sticker)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'what is your two cents? (your little advice to the world; think of it like a bumper sticker)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'2covertxtalrt\');"';
				if ($binfo['twocents']!=''){echo'>'.$binfo['twocents'];}else{echo' class="inputplaceholder">what is your two cents? (your little advice to the world; think of it like a bumper sticker)';}
			echo '</textarea>
			<div id="2covertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtn2c" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=gen2c\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="genrs" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtnrs\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtnrs\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genrs' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">relationship status</td><td align="left" width="406px">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-top: 2px;">
					<select name="rs_type" id="rs_type" onchange="if(this.value!=\'single\'){$(\'rspicker\').set(\'styles\',{\'display\':\'block\'}); t4.focusLast();}else{$(\'rspicker\').set(\'styles\',{\'display\':\'none\'});}">
						<option value=""'; if ($binfo['status']==''){echo'SELECTED';} echo'>choose:</option>';
						$types = array('single', 'dating', 'engaged', 'in a relationship', 'married');
						foreach ($types as $value) {
							if ($binfo['status']==$value) {
								echo '<option value="'.$value.'" SELECTED>'.$value.'</option>';
							} else {
								echo '<option value="'.$value.'">'.$value.'</option>';
							}
						}
					echo '</select>
				</td><td align="left" valign="top" id="rspicker" style="padding-left: 8px;'; if (($binfo['status']=='')||($binfo['status']=='single')){echo' display: none;';} echo'">';
					if ($binfo['status_status']=='p') {
						echo '<div class="subtext" style="font-size: 13px;">currently pending confirmation from '; loadpersonnamenolink($binfo['status_id']); echo'</div>';
					}
					echo '<div align="left">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">with</td><td align="left" valign="center" style="padding-left: 2px;">
							<div id="form_peeple" style="background-color: #fff;">
								<input type="text" name="rs_name" value="" id="form_peeplenames_input"/>
							</div>
						</td></tr></table>
					</div><div class="subtext" style="font-size: 13px;">(this will require approval from this person)</div>
				</td></tr></table>
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtnrs" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=genrs\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>';
	//get age
			date_default_timezone_set('America/Los_Angeles');
			
			$cur_year=date("Y");
			$cur_month=date("m");
			$cur_day=date("d");
			
			$dob_year=substr($binfo['binfo'], 0, 4);
			$dob_month=substr($binfo['binfo'], 5, 2);
			$dob_day=substr($binfo['binfo'], 8, 2);
			
			if($cur_month>$dob_month || ($dob_month==$cur_month &&$cur_day>=$dob_day)) {
				$age = $cur_year-$dob_year;
			} else {
				$age = $cur_year-$dob_year-1;
			}
	echo '<div align="left" id="genbday" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtnbday\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtnbday\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genbday' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">birthday</td><td align="left" width="406px">
			'.$binfo['bday'].' ('.$age.' years old'; if(($cur_month==$dob_month)&&($cur_day==$dob_day)){echo' today';} echo')
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtnbday" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=genbday\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="gengndr" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtngndr\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtngndr\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='gengndr' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">gender</td><td align="left" width="406px">
			'.$binfo['gender'].'
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtngndr" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=gengndr\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="genintin" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtnintin\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtnintin\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genintin' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">interested in</td><td align="left" width="406px">
			<table cellpadding="0" cellspacing="0"><tr><td align="left"><input type="checkbox" name="intin_m"'; if(preg_match('/\bmen\b/', $binfo['interested_in'])){echo' CHECKED';} echo'></td><td align="left" valign="center" style="padding-left: 3px;">men</td><td align="left" style="padding-left: 12px;"><input type="checkbox" name="intin_w"'; if(preg_match('/women/', $binfo['interested_in'])){echo' CHECKED';} echo'></td><td align="left" valign="center" style="padding-left: 3px;">women</td></tr></table>
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtnintin" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=genintin\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="genpol" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtnpol\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtnpol\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genpol' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">political view</td><td align="left" width="406px">
			<textarea name="political" cols="44" rows="3" onfocus="if (trim(this.value) == \'your political view? (no, not just politics; your thoughts on the matter)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'your political view? (no, not just politics; your thoughts on the matter)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'polovertxtalrt\');"';
				if ($binfo['political']!=''){echo'>'.$binfo['political'];}else{echo' class="inputplaceholder">your political view? (no, not just politics; your thoughts on the matter)';}
			echo '</textarea>
			<div id="polovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtnpol" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=genpol\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="genrel" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtnrel\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtnrel\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genrel' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">religious view</td><td align="left" width="406px">
			<textarea name="religious" cols="44" rows="3" onfocus="if (trim(this.value) == \'your religious view or your views on religion?\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'your religious view or your views on religion?\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'relovertxtalrt\');"';
				if ($binfo['religious']!=''){echo'>'.$binfo['religious'];}else{echo' class="inputplaceholder">your religious view or your views on religion?';}
			echo '</textarea>
			<div id="relovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtnrel" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=genrel\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="genht" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtnht\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtnht\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genht' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">hometown</td><td align="left" width="406px">
			<textarea name="hometown" cols="44" rows="2" onfocus="if (trim(this.value) == \'where are you from? (feel free to talk about it, not just write a simple city name)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'where are you from? (feel free to talk about it, not just write a simple city name)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'htovertxtalrt\');"';
				if ($binfo['hometown']!=''){echo'>'.$binfo['hometown'];}else{echo' class="inputplaceholder">where are you from? (feel free to talk about it, not just write a simple city name)';}
			echo '</textarea>
			<div id="htovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtnht" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=genht\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="genct" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtnct\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtnct\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genct' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">current town</td><td align="left" width="406px">
			<textarea name="currenttown" cols="44" rows="2" onfocus="if (trim(this.value) == \'where are you living now? (feel free to talk about it, not just write a simple city name)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'where are you living now? (feel free to talk about it, not just write a simple city name)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'ctovertxtalrt\');"';
				if ($binfo['currenttown']!=''){echo'>'.$binfo['currenttown'];}else{echo' class="inputplaceholder">where are you living now? (feel free to talk about it, not just write a simple city name)';}
			echo '</textarea>
			<div id="ctovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtnct" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=genct\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="genfavc" style="padding-bottom: 20px;" onmouseover="$(\'bivisbtnfavc\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'bivisbtnfavc\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='genfavc' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">favorite color</td><td align="left" width="406px">
			<textarea name="fav_color" cols="44" rows="2" onfocus="if (trim(this.value) == \'your favorite color? (simple? yes. interesting? you tell me; feel free to explain.)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'your favorite color? (simple? yes. interesting? you tell me; feel free to explain.)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'fcovertxtalrt\');"';
				if ($binfo['fav_color']!=''){echo'>'.$binfo['fav_color'];}else{echo' class="inputplaceholder">your favorite color? (simple? yes. interesting? you tell me; feel free to explain.)';}
			echo '</textarea>
			<div id="fcovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="bivisbtnfavc" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=genfavc\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
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
				<input type="button" id="cancel" value="cancel" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});parent.$(\'bieditbtn\').set(\'styles\',{\'display\':\'block\'});parent.$(\'bivisbtn\').set(\'styles\',{\'display\':\'none\'});parent.gotopage(\'binfomain\', \''.$baseincpat.'externalfiles/meefile/grabbasic.php?id='.$id.'\');"/>
			</td></tr></table>
			</div>
		</td></tr></table>
	</div>

</form>';
}

include ('../../../externals/header/footer-iframe.php');

} else {
	echo '<iframe width="100%" height="200px" align="center" id="editib" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/meefile/editbasic.php?action=iframe"></iframe>';
}
?>