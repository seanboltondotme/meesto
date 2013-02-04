<?php
require_once('../../../externals/sessions/db_sessions.inc.php');
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');

$sec = escape_data($_GET['sec']);
$ifn = escape_data($_GET['ifn']);

if (isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
} else {
	$id = 0;
}

$pjs = '<link rel="stylesheet" href="'.$baseincpat.'externalfiles/autocompleter/TextboxList.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.css" type="text/css" media="screen" charset="utf-8" />
	<script src="'.$baseincpat.'externalfiles/autocompleter/GrowingInput.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.js" type="text/javascript" charset="utf-8"></script>		
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.Binary.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css" media="screen">
		.textboxlist-loading { background: url(\''.$baseincpat.'images/spinner.gif\') no-repeat 556px center; }
		.form_tags .textboxlist, #form_hiddenpeople .textboxlist { width: 580px; }
	</style>';
$pdrjs = 'var t4 = new TextboxList(\'form_peeplenames_input\', {unique: true, plugins: {autocomplete: {placeholder: \'start typing the name of one of your peeple to receive suggestions\'}}});';
			//preload added people
			$prsns = mysql_query("SELECT ref_id FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='user'");
			while ($prsn = mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
				$uid = $prsn['ref_id'];
				$pdrjs .= 't4.add(\''.returnpersonname($uid).'\', \''.$uid.'\');';
			}
			$pdrjs .= 't4.container.addClass(\'textboxlist-loading\');	
			new Request.JSON({url: \''.$baseincpat.'externalfiles/autocompleter/grabmypeeple.php\', onSuccess: function(r){
				t4.plugins[\'autocomplete\'].setValues(r);
				t4.container.removeClass(\'textboxlist-loading\');
			}}).send();';
			
$fullmts = true;
include ('../../../externals/header/header-pb.php');

//sec name switch
if ($sec=='genbio') {
	$secname = 'Bio';
} elseif ($sec=='gen2c') {
	$secname = 'Two Cents';
} elseif ($sec=='genrs') {
	$secname = 'Relationship Status';
} elseif ($sec=='genbday') {
	$secname = 'Birthday';
} elseif ($sec=='gengndr') {
	$secname = 'Gender';
} elseif ($sec=='genintin') {
	$secname = 'Interested In';
} elseif ($sec=='genpol') {
	$secname = 'Political View';
} elseif ($sec=='genrel') {
	$secname = 'Religious View';
} elseif ($sec=='genht') {
	$secname = 'Hometown';
} elseif ($sec=='genct') {
	$secname = 'Current Town';
} elseif ($sec=='genfavc') {
	$secname = 'Favorite Color';
} elseif ($sec=='cntctme') {
	$einfo = @mysql_fetch_array (@mysql_query ("SELECT email_type FROM meefile_basic WHERE u_id='$id' LIMIT 1"), MYSQL_ASSOC);
	$secname = ucfirst($einfo['email_type']).' Email';
} elseif ($sec=='prsact') {
	$secname = 'Activities';
} elseif ($sec=='prsint') {
	$secname = 'Interest/Passions';
} elseif ($sec=='prsfq') {
	$secname = 'Favorite Quotes';
} elseif ($sec=='prsvs') {
	$secname = 'Vacation Spot';
} elseif ($sec=='prsdl') {
	$secname = 'Dream Life';
} elseif ($sec=='prsam') {
	$secname = 'About Me';
} else {
	$secname = $sec;
}

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit '.$secname.' Visibility</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 22px;">Use this to customize who can see your information.</div>';

if (isset($_POST['save'])) {
	
	if (isset($_POST['publicvis'])) {
		if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='pub' AND sub_type IS NOT NULL LIMIT 1"), 0)<1) {
			$addvis = mysql_query("INSERT INTO meefile_infosec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'pub', 'y', NOW())");
		}
	} else {
		$delete = mysql_query("DELETE FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='pub'");
	}
	
	if (isset($_POST['streamvis'])) {
		foreach ($_POST['streamvis'] as $streamvis) {
			$streamvis = escape_data($streamvis);
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='strm' AND sub_type='$streamvis' LIMIT 1"), 0)<1) {
				$addvis = mysql_query("INSERT INTO meefile_infosec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'strm', '$streamvis', NOW())");
			}
		}
	}
	$strms = mysql_query("SELECT sub_type FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='strm'");
	while ($strm = @mysql_fetch_array ($strms, MYSQL_ASSOC)) {
		if (!@in_array($strm['sub_type'], $_POST['streamvis'])) {
			$strmdlt = $strm['sub_type'];
			$delete = mysql_query("DELETE FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='strm' AND sub_type='$strmdlt'");
		}
	}
	
	if (isset($_POST['chanvis'])) {
		foreach ($_POST['chanvis'] as $chanvis) {
			$chanvis = escape_data($chanvis);
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='chan' AND ref_id='$chanvis' LIMIT 1"), 0)<1) {
				$addvis = mysql_query("INSERT INTO meefile_infosec_vis (u_id, sec, type, ref_id, time_stamp) VALUES ('$id', '$sec', 'chan', '$chanvis', NOW())");
			}
		}
	}
	$chans = mysql_query("SELECT ref_id FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='chan'");
	while ($chan = @mysql_fetch_array ($chans, MYSQL_ASSOC)) {
		if (!@in_array($chan['ref_id'], $_POST['chanvis'])) {
			$chandlt = $chan['ref_id'];
			$delete = mysql_query("DELETE FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='chan' AND ref_id='$chandlt'");
		}
	}
	
	$peeple = explode(",", $_POST['peeplenames']);
	if (isset($_POST['peeplenames'])) {
			foreach ($peeple as $uid) {
				$uid = escape_data($uid);
				if (($uid!=0)&&(mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='user' AND ref_id='$uid' LIMIT 1"), 0)<1)) {
				$addvis = mysql_query("INSERT INTO meefile_infosec_vis (u_id, sec, type, ref_id, time_stamp) VALUES ('$id', '$sec', 'user', '$uid', NOW())");
			}
		}
	}
	$prsns = mysql_query("SELECT ref_id FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='user'");
	while ($prsn = @mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
		if (!@in_array($prsn['ref_id'], $peeple)) {
			$prsndlt = $prsn['ref_id'];
			$delete = mysql_query("DELETE FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='user' AND ref_id='$prsndlt'");
		}
	}
	
	$mainsec = $sec; //fix for visibility visualizer
	
	//apply to parent
	if ($sec=='cntctme') {
		$sec = 'idcnt';
		if (isset($_POST['publicvis'])) {
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='pub' AND sub_type IS NOT NULL LIMIT 1"), 0)<1) {
				$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'pub', 'y', NOW())");
			}
		}
		
		if (isset($_POST['streamvis'])) {
			foreach ($_POST['streamvis'] as $streamvis) {
				$streamvis = escape_data($streamvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='strm' AND sub_type='$streamvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'strm', '$streamvis', NOW())");
				}
			}
		}
		
		if (isset($_POST['chanvis'])) {
			foreach ($_POST['chanvis'] as $chanvis) {
				$chanvis = escape_data($chanvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='chan' AND ref_id='$chanvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, ref_id, time_stamp) VALUES ('$id', '$sec', 'chan', '$chanvis', NOW())");
				}
			}
		}
		
		$peeple = explode(",", $_POST['peeplenames']);
		$prsns = mysql_query("SELECT ref_id FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='user'");
		while ($prsn = @mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
			if (!@in_array($prsn['ref_id'], $peeple)) {
				$prsndlt = $prsn['ref_id'];
				$delete = mysql_query("DELETE FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='user' AND ref_id='$prsndlt'");
			}
		}
	} elseif (substr($sec, 0, 3)=='gen') {
		$sec = 'idbas';
		if (isset($_POST['publicvis'])) {
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='pub' AND sub_type IS NOT NULL LIMIT 1"), 0)<1) {
				$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'pub', 'y', NOW())");
			}
		}
		
		if (isset($_POST['streamvis'])) {
			foreach ($_POST['streamvis'] as $streamvis) {
				$streamvis = escape_data($streamvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='strm' AND sub_type='$streamvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'strm', '$streamvis', NOW())");
				}
			}
		}
		
		if (isset($_POST['chanvis'])) {
			foreach ($_POST['chanvis'] as $chanvis) {
				$chanvis = escape_data($chanvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='chan' AND ref_id='$chanvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, ref_id, time_stamp) VALUES ('$id', '$sec', 'chan', '$chanvis', NOW())");
				}
			}
		}
		
		$peeple = explode(",", $_POST['peeplenames']);
		$prsns = mysql_query("SELECT ref_id FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='user'");
		while ($prsn = @mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
			if (!@in_array($prsn['ref_id'], $peeple)) {
				$prsndlt = $prsn['ref_id'];
				$delete = mysql_query("DELETE FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='user' AND ref_id='$prsndlt'");
			}
		}
	} elseif (substr($sec, 0, 3)=='prs') {
		$sec = 'idpers';
		if (isset($_POST['publicvis'])) {
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='pub' AND sub_type IS NOT NULL LIMIT 1"), 0)<1) {
				$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'pub', 'y', NOW())");
			}
		}
		
		if (isset($_POST['streamvis'])) {
			foreach ($_POST['streamvis'] as $streamvis) {
				$streamvis = escape_data($streamvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='strm' AND sub_type='$streamvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, sub_type, time_stamp) VALUES ('$id', '$sec', 'strm', '$streamvis', NOW())");
				}
			}
		}
		
		if (isset($_POST['chanvis'])) {
			foreach ($_POST['chanvis'] as $chanvis) {
				$chanvis = escape_data($chanvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='chan' AND ref_id='$chanvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO meefile_sec_vis (u_id, sec, type, ref_id, time_stamp) VALUES ('$id', '$sec', 'chan', '$chanvis', NOW())");
				}
			}
		}
		
		$peeple = explode(",", $_POST['peeplenames']);
		$prsns = mysql_query("SELECT ref_id FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='user'");
		while ($prsn = @mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
			if (!@in_array($prsn['ref_id'], $peeple)) {
				$prsndlt = $prsn['ref_id'];
				$delete = mysql_query("DELETE FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type='user' AND ref_id='$prsndlt'");
			}
		}
	}
			//for all parents
			echo '<script type="text/javascript">';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='$sec' AND type!='user' LIMIT 1"), 0)==0) {
				echo 'window.addEvent(\'load\', function() {
					if (!parent.$(\''.$sec.'\').hasClass(\'notvissec\')) {
						var newElem = new Element(\'div\', {\'align\': \'center\', \'class\': \'palert\', \'html\': \'this is not visible to anyone!\'});newElem.inject(parent.$(\''.$sec.'\'), \'top\');
					}
					parent.$(\''.$sec.'\').addClass(\'notvissec\');
				});';
			} else {
				echo 'window.addEvent(\'load\', function() {
					if (parent.$(\''.$sec.'\').hasClass(\'notvissec\')) {
						parent.$(\''.$sec.'\').getFirst().destroy();
					}
						parent.$(\''.$sec.'\').removeClass(\'notvissec\');
				});';
			}
			echo '</script>';
	
	if (empty($errors)) {
		echo '<table cellpadding="0" cellspacing="0"><tr><td align="left">Your visibility settings have been saved.</td></tr></table>
		<script type="text/javascript">';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$mainsec' AND type!='user' LIMIT 1"), 0)==0) {
				echo 'window.addEvent(\'load\', function() {
					if (!parent.$(\''.$ifn.'\').contentWindow.$(\''.$mainsec.'\').hasClass(\'notvissec\')) {
						var newElem = new Element(\'div\', {\'align\': \'center\', \'class\': \'palert\', \'html\': \'this is not visible to anyone!\'});newElem.inject(parent.$(\''.$ifn.'\').contentWindow.$(\''.$mainsec.'\'), \'top\');
					}
					parent.$(\''.$ifn.'\').contentWindow.$(\''.$mainsec.'\').addClass(\'notvissec\');
				});';
			} else {
				echo 'window.addEvent(\'load\', function() {
					if (parent.$(\''.$ifn.'\').contentWindow.$(\''.$mainsec.'\').hasClass(\'notvissec\')) {
						parent.$(\''.$ifn.'\').contentWindow.$(\''.$mainsec.'\').getFirst().destroy();
					}
						parent.$(\''.$ifn.'\').contentWindow.$(\''.$mainsec.'\').removeClass(\'notvissec\');
				});';
			}
			echo 'setTimeout("parent.PopBox.close();", 1200);
		</script>';
	} else {
		echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left">An error occurred. Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
		reporterror('meefile/editinfosecvis.php', 'editing '.$sec.' visibility settings', $errors);
		echo '</td></tr></table>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400);
		</script>';
	}

} else {
	
	//load in prevalued arrays
	if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='pub' LIMIT 1"), 0)>0) {
		$ispub = true;
	} else {
		$ispub = false;
	}
	$plstrm = mysql_query("SELECT sub_type FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='strm'");
	$plstrms = array();
	while ($plstrminfo = mysql_fetch_array ($plstrm, MYSQL_ASSOC)) {
		array_push($plstrms, $plstrminfo['sub_type']);
	}
	$plchan = mysql_query("SELECT ref_id FROM meefile_infosec_vis WHERE u_id='$id' AND sec='$sec' AND type='chan'");
	$plchans = array();
	while ($plchaninfo = mysql_fetch_array ($plchan, MYSQL_ASSOC)) {
		array_push($plchans, $plchaninfo['ref_id']);
	}
	
	echo '<form action="'.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifn.'&sec='.$sec.'" method="post">
		<div align="left" style="padding-left: 16px; padding-bottom: 24px;">
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
		<div align="left" id="hidefrmopts" class="p24" style="padding-left: 16px; padding-bottom: 8px;'; if($ispub){echo' display: none;';} echo'">
			<div align="left" style="padding-bottom: 6px;">Hide from</div>
			<div align="left" class="p18" style="padding-left: 16px;">
				<div id="form_hiddenpeople">
					<input type="text" name="peeplenames" value="" id="form_peeplenames_input"/>
				</div>	
			</div>
		</div>
		
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
	</form>';
}

include ('../../../externals/header/footer-pb.php');
?>