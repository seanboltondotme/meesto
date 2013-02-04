<?php
if (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_albums WHERE pa_id='$paid' AND u_id='$id' LIMIT 1"), 0)>0) {//test for admin

if (isset($_POST['save'])) {
//save
	
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

	if (isset($_POST['description']) && ($_POST['description'] != 'type a description for this album here')) {
		$description = escape_form_data($_POST['description']);
	} else {
		$description = '';
	}
	
		if (isset($_POST['evntatch'])) {
			foreach ($_POST['evntatch'] as $etieids) {
				$etieid = escape_data($etieids);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM patoe_ties WHERE pa_id='$paid' AND e_id='$etieid' LIMIT 1"), 0)<1) {
					$addtie = mysql_query("INSERT INTO patoe_ties (pa_id, e_id, u_id, time_stamp) VALUES ('$paid', '$etieid', '$id', NOW())");
				}
			}
		}
		$etieids = mysql_query("SELECT e_id FROM patoe_ties WHERE pa_id='$paid'");
		while ($etieid = mysql_fetch_array ($etieids, MYSQL_ASSOC)) {
			if (!in_array($etieid['e_id'], $_POST['evntatch'])) {
				$eiddlt = $etieid['e_id'];
				$delete = mysql_query("DELETE FROM patoe_ties WHERE pa_id='$paid' AND e_id='$eiddlt' ");
			}
		}
			
	if (empty($errors)) {
		$update = mysql_query("UPDATE photo_albums SET name='$name', description='$description', date='$syear-$smonth-$sday 00:00:00' WHERE pa_id='$paid'");
		echo '<div align="center" class="p18" id="savemsg">Your photo album info has been saved!</div>
		<script type="text/javascript">
				setTimeout("$(\'savemsg\').destroy();", \'1400\');
			</script>';
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}
	
}

$painfo = mysql_fetch_array (mysql_query ("SELECT name, description, DATE_FORMAT(date, '%m-%d-%Y') AS date FROM photo_albums WHERE pa_id='$paid' LIMIT 1"), MYSQL_ASSOC);
$name = $painfo['name'];
$description = $painfo['description'];
$date = $painfo['date'];
$pletieid = mysql_query("SELECT e_id FROM patoe_ties WHERE pa_id='$paid'");
$pletieids = array();
while ($pletieidinfo = mysql_fetch_array ($pletieid, MYSQL_ASSOC)) {
	array_push($pletieids, $pletieidinfo['e_id']);
}

echo '<div align="left" style="margin-left: 12px;"><form action="'.$baseincpat.'editalbum.php?id='.$paid.'" method="post">

<div align="center" style="margin-top: 8px;">
		<div id="loader_top" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		<div id="submitbtns_top" align="center">
			<input type="submit" id="submit" value="save" name="save" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'submitbtns_top\').set(\'styles\',{\'display\':\'none\'});$(\'loader_top\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
		</div>
</div>

<div align="left" class="p24" style="margin-top: 2px;">Basic Info</div><div align="left" style="margin-left: 32px; margin-top: 12px;">
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">name</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="name" size="46" maxlength="220" autocomplete="off" onfocus="if (trim(this.value) == \'type album name here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type album name here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($name!=''){echo'value="'.$name.'"';}else{echo'class="inputplaceholder" value="type album name here..."';}
				echo '>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">date</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="sdate" size="10" value="'.$date.'" class="date start_date" />
				</td></tr></table>
			</div>
</div>

<div align="left" class="p24" style="margin-top: 18px;">Extra Info</div><div align="left" style="margin-left: 32px; margin-top: 12px;">
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">description</td><td align="left" valign="center" style="padding-bottom: 2px;">
					<textarea name="description" cols="60" rows="2" onfocus="if (trim(this.value) == \'type a description for this album here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type a description for this album here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'2000\', \'overtxtalrt\');"';
				if ($description!=''){echo'>'.$description;}else{echo' class="inputplaceholder">type a description for this album here';}
			echo '</textarea>
					<div id="overtxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">event</td><td align="left" valign="center" style="padding-bottom: 2px;">
					<div align="left">Choose the event(s) this photo album belongs to. <span class="subtext">(This will not change the visibility of your photo album)</span></div>
					<div align="left" style="padding-left: 8px; height: 180px; width: 700px; overflow-x: none; overflow-y: scroll; margin-top: 6px;"">';
						$events = mysql_query ("SELECT e.e_id, e.name, DATE_FORMAT(e.start_date, '%b %D, %Y') AS date FROM events e INNER JOIN event_owners eo ON e.e_id=eo.e_id AND eo.u_id='$id' AND eo.rsvp IS NOT NULL ORDER BY e.start_date DESC");
							while ($event = mysql_fetch_array ($events, MYSQL_ASSOC)) {
								$eid = $event['e_id'];
								echo '<div align="left" style="margin-top: 6px; margin-bottom: 4px;">
									<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'evntatch['.$eid.']\').get(\'checked\') == false){$(\'evntatch['.$eid.']\').set(\'checked\',true);}else{$(\'evntatch['.$eid.']\').set(\'checked\',false);}"><tr><td align="left" valign="center" style="padding-top: 1px;"><input type="checkbox" id="evntatch['.$eid.']" name="evntatch['.$eid.']" value="'.$eid.'" onclick="if($(\'evntatch['.$eid.']\').get(\'checked\') == false){$(\'evntatch['.$eid.']\').set(\'checked\',true);}else{$(\'evntatch['.$eid.']\').set(\'checked\',false);}"'; if(in_array($eid, $pletieids)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">'.$event['name'].' <span class="subtext">('.$event['date'].')</span></td></tr></table>
								</div>';	
							}
					echo '</div>
				</td></tr></table>
			</div>
</div>
		
<div align="left" class="p24" style="margin-top: 8px;">Visibility</div><div align="left" style="margin-left: 32px; margin-top: 12px;">
	<input type="button" value="edit visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/editpavis.php?id='.$paid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
</div>

<div align="center" style="margin-top: 24px;">
		<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		<div id="submitbtns" align="center">
			<input type="submit" id="submit" value="save" name="save" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'submitbtns_top\').set(\'styles\',{\'display\':\'none\'});$(\'loader_top\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
		</div>
</div>

</form></div>';

}
?>