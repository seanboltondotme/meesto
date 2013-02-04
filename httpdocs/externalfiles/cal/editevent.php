<?php
require_once ('../../../externals/general/includepaths.php');

//get current date
date_default_timezone_set('America/Los_Angeles');
$cyear=date("Y");
$cmonth=date("m");
$cday=date("d");

$pjs = '<link rel="stylesheet" href="'.$baseincpat.'externalfiles/cal/datepicker.css" type="text/css" media="screen" charset="utf-8" />
	<script src="'.$baseincpat.'externalfiles/cal/datepicker.js" type="text/javascript" charset="utf-8"></script>';
$pdrjs = 'new DatePicker(\'.start_date\', {
		format: \'m-d-Y\',
		inputOutputFormat: \'m-d-Y\',
		yearPicker: false,
		startDay: 0,
		minDate: { date: \'03-10-2010\', format: \'m-d-Y\' },
		pickerClass: \'datepicker_dashboard\',
		onShow: function() {$(\'sdspcr\').set(\'styles\',{\'display\':\'block\'});},
		onClose: function() {$(\'sdspcr\').set(\'styles\',{\'display\':\'none\'});}
	});
	new DatePicker(\'.end_date\', {
		format: \'m-d-Y\',
		inputOutputFormat: \'m-d-Y\',
		yearPicker: false,
		startDay: 0,
		minDate: { date: \'03-10-2010\', format: \'m-d-Y\' },
		pickerClass: \'datepicker_dashboard\',
		onShow: function() {$(\'edspcr\').set(\'styles\',{\'display\':\'block\'});},
		onClose: function() {$(\'edspcr\').set(\'styles\',{\'display\':\'none\'});}
	});';

$fullmts = true;
include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit Event</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to edit your event.</div>';

$eid = escape_data($_GET['id']);

if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) { //test for admin

if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (isset($_POST['name']) && ($_POST['name'] != 'type event name here...')) {
		$name = escape_form_data($_POST['name']);
	} else {
		$errors[] = 'You must enter an event name.';
	}
	
	$sdate = escape_data($_POST['sdate']);
		//splice start date
		$smonth = substr($sdate, 0, 2);
		$sday = substr($sdate, 3, 2);
		$syear = substr($sdate, 6);
	
	$stime = escape_data($_POST['stime']);
		//splice start time
		$shour = substr($stime, 0, 2);
			//test for single digit
			if (substr($shour, 1)==':') {
				$shour = substr($shour, 0, 1);
				$shoffst = 2;
			} else {
				$shoffst = 3;
			}
		$smin = substr($stime, $shoffst, 2);
		$sampm = substr($stime, $shoffst+3);
			//adjust midnight or noon to am or pm
			if ($sampm=='Midnight') {
				$sampm = 'AM';
				$shour = '00';
					//get tomorrow
					$stmrw = strtotime("+1 day", mktime($shour, $smin, 0, $smonth, $sday, $syear));
				$syear = date("Y", $stmrw);
				$smonth = date("m", $stmrw);
				$sday = date("d", $stmrw);
			}
			if ($sampm=='Noon') {
				$sampm = 'AM';
			}
			//adjust hour for am or pm
			if ($sampm=='PM') {
				$shour = $shour+12;
			}
	
	$edate = escape_data($_POST['edate']);
		//splice end date
		$emonth = substr($edate, 0, 2);
		$eday = substr($edate, 3, 2);
		$eyear = substr($edate, 6);
	
	$etime = escape_data($_POST['etime']);
		//splice start time
		$ehour = substr($etime, 0, 2);
			//test for single digit
			if (substr($ehour, 1)==':') {
				$ehour = substr($ehour, 0, 1);
				$ehoffst = 2;
			} else {
				$ehoffst = 3;
			}
		$emin = substr($etime, $ehoffst, 2);
		$eampm = substr($etime, $ehoffst+3);
			//adjust midnight or noon to am or pm
			if ($eampm=='Midnight') {
				$eampm = 'AM';
				$ehour = '00';
					//get tomorrow
					$etmrw = strtotime("+1 day", mktime($ehour, $emin, 0, $emonth, $eday, $eyear));
				$eyear = date("Y", $etmrw);
				$emonth = date("m", $etmrw);
				$eday = date("d", $etmrw);
			}
			if ($eampm=='Noon') {
				$eampm = 'AM';
			}
			//adjust hour for am or pm
			if ($eampm=='PM') {
				$ehour = $ehour+12;
			}
	
	$einfo = mysql_fetch_array (mysql_query ("SELECT vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
	if (substr($einfo['vis'], 0, 4)=='priv') {
		if (isset($_POST['alwatinvt']) && ($_POST['alwatinvt'] == 'y')) {
			$vis = 'privci';
		} else {
			$vis = 'priv';
		}
	} else {
		$vis = 'pub';
	}
	
	//test end after beg via unix ts
	$sunixts = mktime($shour, $smin, 0, $smonth, $sday, $syear);
	$eunixts = mktime($ehour, $emin, 0, $emonth, $eday, $eyear);
	if ($eunixts>$sunixts) {
		//all good :)
	} else {
		$errors[] = 'Your start date and time must be after your end date and time.';
	}
	
	if (empty($errors)) {
		$update = mysql_query("UPDATE events SET name='$name', start_date='$syear-$smonth-$sday $shour:$smin:00', end_date='$eyear-$emonth-$eday $ehour:$emin:00', vis='$vis' WHERE e_id='$eid'");
		
		echo '<div align="center" class="p18">Your event has been saved.</div>
		<script type="text/javascript">
			setTimeout("parent.location.reload();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
		if ($stime=='12:00 Midnight') {
			//get yesterday
					$systerdy = strtotime("-1 day", mktime($shour, $smin, 0, $smonth, $sday, $syear));
				$syear = date("Y", $systerdy);
				$smonth = date("m", $systerdy);
				$sday = date("d", $systerdy);
		}
		if ($etime=='12:00 Midnight') {
			//get yesterday
					$eysterdy = strtotime("-1 day", mktime($ehour, $emin, 0, $emonth, $eday, $eyear));
				$eyear = date("Y", $eysterdy);
				$emonth = date("m", $eysterdy);
				$eday = date("d", $eysterdy);
		}
	}

} else {
	$einfo = mysql_fetch_array (mysql_query ("SELECT name, vis, DATE_FORMAT(start_date, '%m-%d-%Y') AS sdate, DATE_FORMAT(start_date, '%l:%i %p') AS stime, DATE_FORMAT(end_date, '%m-%d-%Y') AS edate, DATE_FORMAT(end_date, '%l:%i %p') AS etime FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
	$name = $einfo['name'];
	$sdate = $einfo['sdate'];
		//splice end date
		$smonth = substr($sdate, 0, 2);
		$sday = substr($sdate, 3, 2);
		$syear = substr($sdate, 6);
	$stime = $einfo['stime'];
		//adjust for Noon or Midnight
		if ($stime=='12:00 PM') {
			$stime = '12:00 Noon';
		} elseif ($stime=='12:00 AM') {
			$stime = '12:00 Midnight';
			//get yesterday
					$systerdy = strtotime("-1 day", mktime($shour, $smin, 0, $smonth, $sday, $syear));
				$syear = date("Y", $systerdy);
				$smonth = date("m", $systerdy);
				$sday = date("d", $systerdy);
		}
	$edate = $einfo['edate'];
		//splice end date
		$emonth = substr($edate, 0, 2);
		$eday = substr($edate, 3, 2);
		$eyear = substr($edate, 6);
	$etime = $einfo['etime'];
		//adjust for Noon or Midnight
		if ($etime=='12:00 PM') {
			$etime = '12:00 Noon';
		} elseif ($etime=='12:00 AM') {
			$etime = '12:00 Midnight';
			//get yesterday
					$eysterdy = strtotime("-1 day", mktime($ehour, $emin, 0, $emonth, $eday, $eyear));
				$eyear = date("Y", $eysterdy);
				$emonth = date("m", $eysterdy);
				$eday = date("d", $eysterdy);
		}
	$vis = $einfo['vis'];
}
	
	$times = array ('5:00 AM', '5:15 AM', '5:30 AM', '5:45 AM', '6:00 AM', '6:15 AM', '6:30 AM', '6:45 AM', '7:00 AM', '7:15 AM', '7:30 AM', '7:45 AM', '8:00 AM', '8:15 AM', '8:30 AM', '8:45 AM', '9:00 AM', '9:15 AM', '9:30 AM', '9:45 AM', '10:00 AM', '10:15 AM', '10:30 AM', '10:45 AM', '11:00 AM', '11:15 AM', '11:30 AM', '11:45 AM', '12:00 Noon', '12:15 PM', '12:30 PM', '12:45 PM', '1:00 PM', '1:15 PM', '1:30 PM', '1:45 PM', '2:00 PM', '2:15 PM', '2:30 PM', '2:45 PM', '3:00 PM', '3:15 PM', '3:30 PM', '3:45 PM', '4:00 PM', '4:15 PM', '4:30 PM', '4:45 PM', '5:00 PM', '5:15 PM', '5:30 PM', '5:45 PM', '6:00 PM', '6:15 PM', '6:30 PM', '6:45 PM', '7:00 PM', '7:15 PM', '7:30 PM', '7:45 PM', '8:00 PM', '8:15 PM', '8:30 PM', '8:45 PM', '9:00 PM', '9:15 PM', '9:30 PM', '9:45 PM', '10:00 PM', '10:15 PM', '10:30 PM', '10:45 PM', '11:00 PM', '11:15 PM', '11:30 PM', '11:45 PM', '12:00 Midnight', '12:15 AM', '12:30 AM', '12:45 AM', '1:00 AM', '1:15 AM', '1:30 AM', '1:45 AM', '2:00 AM', '2:15 AM', '2:30 AM', '2:45 AM', '3:00 AM', '3:15 AM', '3:30 AM', '3:45 AM', '4:00 AM', '4:15 AM', '4:30 AM', '4:45 AM');
	
	echo '<form action="'.$baseincpat.'externalfiles/cal/editevent.php?id='.$eid.'" method="post">
		<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="90px">name</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="name" size="46" maxlength="200" autocomplete="off" onfocus="if (trim(this.value) == \'type event name here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type event name here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($name!=''){echo'value="'.$name.'"';}else{echo'class="inputplaceholder" value="type event name here..."';}
				echo '>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="90px">start</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="sdate" size="10" value="';
						if (isset($smonth)) {
							echo $smonth.'-'.$sday.'-'.$syear;
						} else {
							echo $cmonth.'-'.$cday.'-'.$cyear;
						}
					echo '" class="date start_date" />
				</td><td align="left" valign="center" style="padding-left: 12px;">at</td><td align="left" valign="center" style="padding-left: 12px;">
					<select id="stime" name="stime" onfocus="resizepb=window.clearInterval(resizepb);" onblur="resizepb=setInterval(\'resize_pb();\', 100);">'; 
						foreach ($times as $value) { 
							if ($value == $stime) {
								echo '<option value="'.$value.'" style="font-size: 13px;" SELECTED>'.$value.'</option>';
							} else {
								echo '<option value="'.$value.'" style="font-size: 13px;">'.$value.'</option>';
							}
						} 
					echo '</select>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="90px">end</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="edate" size="10" value="';
						if (isset($emonth)) {
							echo $emonth.'-'.$eday.'-'.$eyear;
						} else {
							echo $cmonth.'-'.$cday.'-'.$cyear;
						}
					echo '" class="date end_date" />
				</td><td align="left" valign="center" style="padding-left: 12px;">at</td><td align="left" valign="center" style="padding-left: 12px;">
					<select id="etime" name="etime" onfocus="resizepb=window.clearInterval(resizepb);" onblur="resizepb=setInterval(\'resize_pb();\', 100);">'; 
						foreach ($times as $value) { 
							if ($value == $etime) {
								echo '<option value="'.$value.'" style="font-size: 13px;" SELECTED>'.$value.'</option>';
							} else {
								echo '<option value="'.$value.'" style="font-size: 13px;">'.$value.'</option>';
							}
						} 
					echo '</select>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="90px">visibility</td><td align="left" valign="top" style="font-size: 13px; padding-top: 3px; padding-bottom: 2px;">
					<div align="left" class="subtext">Once an event has been created, you cannot edit it\'s visibility.</div><div align="left" id="alwatinvtbtn" style="padding-top: 8px;'; if($vis=='pub'){echo' display: none;';} echo'">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'alwatinvt\').get(\'checked\') == false){$(\'alwatinvt\').set(\'checked\',true);}else{$(\'alwatinvt\').set(\'checked\',false);}""><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="alwatinvt" name="alwatinvt" value="y" onclick="if($(\'alwatinvt\').get(\'checked\') == false){$(\'alwatinvt\').set(\'checked\',true);}else{$(\'alwatinvt\').set(\'checked\',false);}"'; if(substr($vis, -2)=='ci'){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">allow attendees to invite peeple</td></tr></table>
					</div>
				</td></tr></table>
			</div>
			
			
		</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 4px; font-size: 13px;">
				(next you will be able to add more event info and invite peeple)
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
		<div id="sdspcr" style="height: 40px; display: none;"></div>
		<div id="edspcr" style="height: 40px; display: none;"></div>
		
	</form>';

} else { //if not event admin
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must an admin of this event to edit its info.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>