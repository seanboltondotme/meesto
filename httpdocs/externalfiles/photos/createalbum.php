<?php
require_once('../../../externals/sessions/db_sessions.inc.php');
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');

//get current date
date_default_timezone_set('America/Los_Angeles');
$cyear=date("Y");
$cmonth=date("m");
$cday=date("d");

$pjs = '<link rel="stylesheet" href="'.$baseincpat.'externalfiles/cal/datepicker.css" type="text/css" media="screen" charset="utf-8" />
	<script src="'.$baseincpat.'externalfiles/cal/datepicker.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="'.$baseincpat.'externalfiles/autocompleter/TextboxList.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.css" type="text/css" media="screen" charset="utf-8" />
	<script src="'.$baseincpat.'externalfiles/autocompleter/GrowingInput.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.js" type="text/javascript" charset="utf-8"></script>		
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.Binary.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css" media="screen">
		.textboxlist-loading { background: url(\''.$baseincpat.'images/spinner.gif\') no-repeat 556px center; }
		.form_tags .textboxlist, #form_hiddenpeople .textboxlist { width: 580px; }
	</style>';
$pdrjs = 'new DatePicker(\'.start_date\', {
		format: \'m-d-Y\',
		inputOutputFormat: \'m-d-Y\',
		yearPicker: false,
		startDay: 0,
		pickerClass: \'datepicker_dashboard\'
	});
	var t4 = new TextboxList(\'form_peeplenames_input\', {unique: true, plugins: {autocomplete: {placeholder: \'start typing the name of one of your peeple to receive suggestions\'}}});';
			//preload added people
			if (isset($_POST['create'])) {
				$peeple = explode(",", $_POST['peeplenames']);
				if (isset($_POST['peeplenames'])) {
					foreach ($peeple as $uid) {
						$uid = escape_data($uid);
						if ($uid!=0) {
							$pdrjs .= 't4.add(\''.returnpersonname($uid).'\', \''.$uid.'\');';
						}
					}
				}
			}
			$pdrjs .= 't4.container.addClass(\'textboxlist-loading\');	
			new Request.JSON({url: \''.$baseincpat.'externalfiles/autocompleter/grabmypeeple.php\', onSuccess: function(r){
				t4.plugins[\'autocomplete\'].setValues(r);
				t4.container.removeClass(\'textboxlist-loading\');
			}}).send();';

$fullmts = true;
include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Create Photo Album</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to create a photo album which you can add photos to and edit the visibility of.</div>';

if (isset($_POST['create'])) {
	
	$errors = NULL;
	
	if (isset($_POST['name']) && ($_POST['name'] != 'type album name here...')) {
		$name = escape_form_data($_POST['name']);
	} else {
		$errors[] = 'You must enter a photo album name.';
	}
	
	$sdate = escape_data($_POST['sdate']);
		//splice start date
		$smonth = substr($sdate, 0, 2);
		$sday = substr($sdate, 3, 2);
		$syear = substr($sdate, 6);
	if (!is_numeric($smonth)&&!is_numeric($sdate)&&!is_numeric($sdate)) {
		$errors[] = 'There was an error with the date. Please reset it and try again.';
	}
	
	if (empty($errors)) {
		$insert = mysql_query("INSERT INTO photo_albums (u_id, name, cover_url, date, time_stamp) VALUES ('$id', '$name', 'images/nophoto-pa.png', '$syear-$smonth-$sday 00:00:00', NOW())");
		$paid = mysql_insert_id();
		
		if (isset($_POST['publicvis'])) {
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$paid' AND type='pub' AND sub_type IS NOT NULL LIMIT 1"), 0)<1) {
				$addvis = mysql_query("INSERT INTO photo_album_vis (pa_id, type, sub_type, time_stamp) VALUES ('$paid', 'pub', 'y', NOW())");
			}
		}
	
		if (isset($_POST['streamvis'])) {
			foreach ($_POST['streamvis'] as $streamvis) {
				$streamvis = escape_data($streamvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$paid' AND type='strm' AND sub_type='$streamvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO photo_album_vis (pa_id, type, sub_type, time_stamp) VALUES ('$paid', 'strm', '$streamvis', NOW())");
				}
			}
		}
		
		if (isset($_POST['chanvis'])) {
			foreach ($_POST['chanvis'] as $chanvis) {
				$chanvis = escape_data($chanvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$paid' AND type='chan' AND ref_id='$chanvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO photo_album_vis (pa_id, type, ref_id, time_stamp) VALUES ('$paid', 'chan', '$chanvis', NOW())");
				}
			}
		}
		
		$peeple = explode(",", $_POST['peeplenames']);
		if (isset($_POST['peeplenames'])) {
			foreach ($peeple as $uid) {
				$uid = escape_data($uid);
				if (($uid!=0)&&(mysql_result (mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$paid' AND type='user' AND ref_id='$uid' LIMIT 1"), 0)<1)) {
					$addvis = mysql_query("INSERT INTO photo_album_vis (pa_id, type, ref_id, time_stamp) VALUES ('$paid', 'user', '$uid', NOW())");
				}
			}
		}
		
		// tests for and make activity post
		if (mysql_result(mysql_query ("SELECT COUNT(*) FROM user_activityposts WHERE u_id='$id' AND pa='y' LIMIT 1"), 0)>0) {
			$createpost = mysql_query("INSERT INTO feed (u_id, type, ref_id, time_stamp) VALUES ('$id', 'actvap', '$paid', NOW())");
		}
		
		//create directory
		require_once ('../../../externals/ftp/ftpconnect.php');
			if (ftp_login($conn_id, $ftp_user, $ftp_pass)) {
				if (ftp_mkdir($conn_id, "$ftp_basedir/users/$id/photos/$paid")) {
					ftp_chmod($conn_id, 0777, "$ftp_basedir/users/$id/photos/$paid");
				} else {
					reporterror('externalfiles/photos/createalbum.php', 'creating album dir', 'unable to make directories paid='.$paid.'; failed at mkdir');
				}
			} else {
				reporterror('externalfiles/photos/createalbum.php', 'creating album dir', 'unable to make directories paid='.$paid.'; failed at login');
			}
			ftp_close($conn_id); //finish and close ftp
		
		echo '<div align="center" class="p18">Your photo album has been created!</div>
		<script type="text/javascript">
			setTimeout("parent.location.href=\''.$baseincpat.'editalbum.php?id='.$paid.'&action=add\';", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
		if (isset($_POST['publicvis'])) {
			$ispub = true;
		}
		$plstrms = array();
		if (isset($_POST['streamvis'])) {
			foreach ($_POST['streamvis'] as $streamvis) {
				$streamvis = escape_data($streamvis);
				array_push($plstrms, $streamvis);
			}
		}
		$plchans = array();
		if (isset($_POST['chanvis'])) {
			foreach ($_POST['chanvis'] as $chanvis) {
				$chanvis = escape_data($chanvis);
				array_push($plchans, $chanvis);
			}
		}
		
	}

} else {
	$name = '';
	$vis = 'priv';
	$ispub = false;
	$plstrms = array();
	$plchans = array();
}
	
	echo '<form action="'.$baseincpat.'externalfiles/photos/createalbum.php" method="post">
		<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="90px">name</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="name" size="46" maxlength="220" autocomplete="off" onfocus="if (trim(this.value) == \'type album name here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type album name here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($name!=''){echo'value="'.$name.'"';}else{echo'class="inputplaceholder" value="type album name here..."';}
				echo '>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="90px">date</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="sdate" size="10" value="';
						if (isset($smonth)) {
							echo $smonth.'-'.$sday.'-'.$syear;
						} else {
							echo $cmonth.'-'.$cday.'-'.$cyear;
						}
					echo '" class="date start_date" />
				</td></tr></table>
			</div>
			
			
		<div align="left" style="padding-bottom: 24px;">
			<div align="left" class="p24" style="padding-bottom: 6px;">Show to</div>
			<div align="left" style="padding-left: 16px; padding-bottom: 8px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="96px">Public</td><td align="left" valign="bottom" style="font-size: 13px; padding-bottom: 2px;">
					<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'publicvis\').get(\'checked\') == false){$(\'publicvis\').set(\'checked\',true);$(\'strmbtns\').set(\'styles\',{\'display\':\'none\'});$(\'chanbtns\').set(\'styles\',{\'display\':\'none\'});$(\'hidefrmopts\').set(\'styles\',{\'display\':\'none\'});}else{$(\'publicvis\').set(\'checked\',false);$(\'strmbtns\').set(\'styles\',{\'display\':\'block\'});$(\'chanbtns\').set(\'styles\',{\'display\':\'block\'});$(\'hidefrmopts\').set(\'styles\',{\'display\':\'block\'});}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="publicvis" name="publicvis" value="y" onclick="if($(\'publicvis\').get(\'checked\') == false){$(\'publicvis\').set(\'checked\',true);}else{$(\'publicvis\').set(\'checked\',false);}"'; if($ispub){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">Make this public. <span class="paragraphA1">(This means everyone on the internet can view it.)</span></td></tr></table>
				</td></tr></table>
			</div>
			<div align="left" id="strmbtns" style="padding-left: 16px; padding-bottom: 8px;'; if($ispub){echo' display: none;';} echo'">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="96px">Streams</td><td align="left" valign="bottom" style="font-size: 13px; padding-bottom: 2px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[mb]\').get(\'checked\') == false){$(\'streamvis[mb]\').set(\'checked\',true);}else{$(\'streamvis[mb]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[mb]" name="streamvis[mb]" value="mb" onclick="if($(\'streamvis[mb]\').get(\'checked\') == false){$(\'streamvis[mb]\').set(\'checked\',true);}else{$(\'streamvis[mb]\').set(\'checked\',false);}"'; if(in_array('mb', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">my bubble</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[friends]\').get(\'checked\') == false){$(\'streamvis[friends]\').set(\'checked\',true);}else{$(\'streamvis[friends]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[friends]" name="streamvis[friends]" value="frnd" onclick="if($(\'streamvis[friends]\').get(\'checked\') == false){$(\'streamvis[friends]\').set(\'checked\',true);}else{$(\'streamvis[friends]\').set(\'checked\',false);}"'; if(in_array('frnd', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">friends</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[family]\').get(\'checked\') == false){$(\'streamvis[family]\').set(\'checked\',true);}else{$(\'streamvis[family]\').set(\'checked\',false);}""><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[family]" name="streamvis[family]" value="fam" onclick="if($(\'streamvis[family]\').get(\'checked\') == false){$(\'streamvis[family]\').set(\'checked\',true);}else{$(\'streamvis[family]\').set(\'checked\',false);}"'; if(in_array('fam', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">family</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[professional]\').get(\'checked\') == false){$(\'streamvis[professional]\').set(\'checked\',true);}else{$(\'streamvis[professional]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[professional]" name="streamvis[professional]" value="prof" onclick="if($(\'streamvis[professional]\').get(\'checked\') == false){$(\'streamvis[professional]\').set(\'checked\',true);}else{$(\'streamvis[professional]\').set(\'checked\',false);}"'; if(in_array('prof', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">professional</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[education]\').get(\'checked\') == false){$(\'streamvis[education]\').set(\'checked\',true);}else{$(\'streamvis[education]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[education]" name="streamvis[education]" value="edu" onclick="if($(\'streamvis[education]\').get(\'checked\') == false){$(\'streamvis[education]\').set(\'checked\',true);}else{$(\'streamvis[education]\').set(\'checked\',false);}"'; if(in_array('edu', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">education</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[acquaintances]\').get(\'checked\') == false){$(\'streamvis[acquaintances]\').set(\'checked\',true);}else{$(\'streamvis[acquaintances]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[acquaintances]" name="streamvis[acquaintances]" value="aqu" onclick="if($(\'streamvis[acquaintances]\').get(\'checked\') == false){$(\'streamvis[acquaintances]\').set(\'checked\',true);}else{$(\'streamvis[acquaintances]\').set(\'checked\',false);}"'; if(in_array('aqu', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">just met mee</td></tr></table>
					</td></tr></table>
				</td></tr></table>
			</div>
			<div align="left" id="chanbtns" style="padding-left: 16px;'; if($ispub){echo' display: none;';} echo'">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="96px">Channels</td><td align="left" valign="bottom" style="font-size: 13px; padding-bottom: 2px;">
					<table cellpadding="0" cellspacing="0"><tr>';
					//get channels
					$channels = @mysql_query("SELECT mpc_id, name FROM my_peeple_channels WHERE u_id='$id' ORDER BY name ASC");
					$cc = 0;
					while ($channel = @mysql_fetch_array ($channels, MYSQL_ASSOC)) {
						echo '<td align="left" valign="center"'; if($cc>0){echo' style="padding-left: 12px;"';} echo'>
							<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'chanvis['.$channel['mpc_id'].']\').get(\'checked\') == false){$(\'chanvis['.$channel['mpc_id'].']\').set(\'checked\',true);}else{$(\'chanvis['.$channel['mpc_id'].']\').set(\'checked\',false);}"><tr><td align="left"><input type="checkbox" id="chanvis['.$channel['mpc_id'].']" name="chanvis['.$channel['mpc_id'].']" value="'.$channel['mpc_id'].'" onclick="if($(\'chanvis['.$channel['mpc_id'].']\').get(\'checked\') == false){$(\'chanvis['.$channel['mpc_id'].']\').set(\'checked\',true);}else{$(\'chanvis['.$channel['mpc_id'].']\').set(\'checked\',false);}"'; if(in_array($channel['mpc_id'], $plchans)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">'.$channel['name'].'</td></tr></table>
						</td>';
						$cc++;
					}
					//if no records
						if ($cc == 0) {
							echo '<td align="left" valign="center">
								no channels yet
							</td>';
						}
					echo '</tr></table>
				</td></tr></table>
			</div>
		</div>
		<div align="left" id="hidefrmopts" class="p24" style="padding-bottom: 8px;'; if($ispub){echo' display: none;';} echo'">
			<div align="left" style="padding-bottom: 6px;">Hide from</div>
			<div align="left" class="p18" style="padding-left: 16px;">
				<div id="form_hiddenpeople">
					<input type="text" name="peeplenames" value="" id="form_peeplenames_input"/>
				</div>	
			</div>
	</div>
		
			
		</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="create" name="create" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 4px; font-size: 13px;">
				(next you will be able to add photos and tag your peeple)
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</form>';

include ('../../../externals/header/footer-pb.php');
?>