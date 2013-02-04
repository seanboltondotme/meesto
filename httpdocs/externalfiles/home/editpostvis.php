<?php
require_once('../../../externals/sessions/db_sessions.inc.php');
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');

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
if (isset($_POST['save'])) {
	
		if (isset($_POST['publicvis'])) {
			$pdrjs = 'parent.$(\'postfeed'.$id.'\').contentWindow.$(\'publicvis\').set(\'checked\', true);
							parent.$(\'postfeed'.$id.'\').contentWindow.$(\'pubbtn\').set(\'styles\',{\'display\':\'block\'});
							parent.$(\'postfeed'.$id.'\').contentWindow.$(\'strmbtns\').set(\'styles\',{\'display\':\'none\'});';
		} else {
			$pdrjs = 'parent.$(\'postfeed'.$id.'\').contentWindow.$(\'publicvis\').set(\'checked\', false);
							parent.$(\'postfeed'.$id.'\').contentWindow.$(\'pubbtn\').set(\'styles\',{\'display\':\'none\'});
							parent.$(\'postfeed'.$id.'\').contentWindow.$(\'strmbtns\').set(\'styles\',{\'display\':\'block\'});';
		}
		$strmlst = array('mb', 'frnd', 'fam', 'prof', 'edu', 'aqu');
		if (!isset($_POST['streamvis'])) {
			$_POST['streamvis'] = array();
		}
		foreach ($strmlst as $strmname) {
			if (in_array($strmname, $_POST['streamvis'])) {
				$pdrjs .= 'parent.$(\'postfeed'.$id.'\').contentWindow.$(\'streamvis['.$strmname.']\').set(\'checked\', true);';
			} else {
				$pdrjs .= 'parent.$(\'postfeed'.$id.'\').contentWindow.$(\'streamvis['.$strmname.']\').set(\'checked\', false);';	
			}
		}
		$chanlst = mysql_query("SELECT mpc_id FROM my_peeple_channels WHERE u_id='$id' ORDER BY name ASC");
		if (!isset($_POST['chanvis'])) {
			$_POST['chanvis'] = array();
		}
		while ($chaninfo = mysql_fetch_array ($chanlst, MYSQL_ASSOC)) {
			if (in_array($chaninfo['mpc_id'], $_POST['chanvis'])) {
				$pdrjs .= 'parent.$(\'postfeed'.$id.'\').contentWindow.$(\'chanvis['.$chaninfo['mpc_id'].']\').set(\'checked\', true);';
			} else {
				$pdrjs .= 'parent.$(\'postfeed'.$id.'\').contentWindow.$(\'chanvis['.$chaninfo['mpc_id'].']\').set(\'checked\', false);';	
			}
		}
		if (isset($_POST['peeplenames'])) {
			$pdrjs .= 'parent.$(\'postfeed'.$id.'\').contentWindow.$(\'form_peeplenames_input\').set(\'value\', \''.$_POST['peeplenames'].'\');';
		} else {
			$pdrjs .= 'parent.$(\'postfeed'.$id.'\').contentWindow.$(\'form_peeplenames_input\').set(\'value\', \'\');';
		}
	
} else {
	$pdrjs = 'if (parent.$(\'postfeed'.$id.'\').contentWindow.$(\'publicvis\').get(\'checked\') == true){
						$(\'publicvis\').set(\'checked\', true);
						$(\'strmbtns\').set(\'styles\',{\'display\':\'none\'});
						$(\'chanbtns\').set(\'styles\',{\'display\':\'none\'});
						$(\'hidefrmopts\').set(\'styles\',{\'display\':\'none\'});
				}
				$(\'streamvis[mb]\').set(\'checked\', parent.$(\'postfeed'.$id.'\').contentWindow.$(\'streamvis[mb]\').get(\'checked\') );
				$(\'streamvis[frnd]\').set(\'checked\', parent.$(\'postfeed'.$id.'\').contentWindow.$(\'streamvis[frnd]\').get(\'checked\') );
				$(\'streamvis[fam]\').set(\'checked\', parent.$(\'postfeed'.$id.'\').contentWindow.$(\'streamvis[fam]\').get(\'checked\') );
				$(\'streamvis[prof]\').set(\'checked\', parent.$(\'postfeed'.$id.'\').contentWindow.$(\'streamvis[prof]\').get(\'checked\') );
				$(\'streamvis[edu]\').set(\'checked\', parent.$(\'postfeed'.$id.'\').contentWindow.$(\'streamvis[edu]\').get(\'checked\') );
				$(\'streamvis[aqu]\').set(\'checked\', parent.$(\'postfeed'.$id.'\').contentWindow.$(\'streamvis[aqu]\').get(\'checked\') );';
	$plchans = @mysql_query("SELECT mpc_id FROM my_peeple_channels WHERE u_id='$id' ORDER BY name ASC");
	while ($plchan = @mysql_fetch_array ($plchans, MYSQL_ASSOC)) {
		$pdrjs .= '$(\'chanvis['.$plchan['mpc_id'].']\').set(\'checked\', parent.$(\'postfeed'.$id.'\').contentWindow.$(\'chanvis['.$plchan['mpc_id'].']\').get(\'checked\') );';
	}
	$pdrjs .= 'var t4 = new TextboxList(\'form_peeplenames_input\', {unique: true, plugins: {autocomplete: {placeholder: \'start typing the name of one of your peeple to receive suggestions\'}}});
				t4.container.addClass(\'textboxlist-loading\');	
			new Request.JSON({url: \''.$baseincpat.'externalfiles/autocompleter/grabmypeeple.php\', onSuccess: function(r){
				t4.plugins[\'autocomplete\'].setValues(r);
					var blkpeeps = parent.$(\'postfeed'.$id.'\').contentWindow.$(\'form_peeplenames_input\').get(\'value\').split(\',\');
					for (var i = 0; i < blkpeeps.length; i++){
						for (var j = 0; j < r.length; j++){
							if (blkpeeps[i] == r[j][0]) {
								t4.add(r[j][2], r[j][0]);
								j = r.length;
							}
						}
					}
				t4.container.removeClass(\'textboxlist-loading\');
			}}).send();';
}
			
$fullmts = true;
include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit Feed Post Visibility</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 22px;">Use this to customize who can see your information.</div>';

if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (isset($_POST['newdef'])) {
	
		if (isset($_POST['publicvis'])) {
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM defvis_feed WHERE u_id='$id' AND type='pub' AND sub_type IS NOT NULL LIMIT 1"), 0)<1) {
				$addvis = mysql_query("INSERT INTO defvis_feed (u_id, type, sub_type, time_stamp) VALUES ('$id', 'pub', 'y', NOW())");
			}
		} else {
			$delete = mysql_query("DELETE FROM defvis_feed WHERE u_id='$id' AND type='pub'");
		}
		
		if (isset($_POST['streamvis'])) {
			foreach ($_POST['streamvis'] as $streamvis) {
				$streamvis = escape_data($streamvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM defvis_feed WHERE u_id='$id' AND type='strm' AND sub_type='$streamvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO defvis_feed (u_id, type, sub_type, time_stamp) VALUES ('$id', 'strm', '$streamvis', NOW())");
				}
			}
		}
		$strms = mysql_query("SELECT sub_type FROM defvis_feed WHERE u_id='$id' AND type='strm'");
		while ($strm = @mysql_fetch_array ($strms, MYSQL_ASSOC)) {
			if (!@in_array($strm['sub_type'], $_POST['streamvis'])) {
				$strmdlt = $strm['sub_type'];
				$delete = mysql_query("DELETE FROM defvis_feed WHERE u_id='$id' AND type='strm' AND sub_type='$strmdlt'");
			}
		}
		
		if (isset($_POST['chanvis'])) {
			foreach ($_POST['chanvis'] as $chanvis) {
				$chanvis = escape_data($chanvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM defvis_feed WHERE u_id='$id' AND type='chan' AND ref_id='$chanvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO defvis_feed (u_id, type, ref_id, time_stamp) VALUES ('$id', 'chan', '$chanvis', NOW())");
				}
			}
		}
		$chans = mysql_query("SELECT ref_id FROM defvis_feed WHERE u_id='$id' AND type='chan'");
		while ($chan = @mysql_fetch_array ($chans, MYSQL_ASSOC)) {
			if (!@in_array($chan['ref_id'], $_POST['chanvis'])) {
				$chandlt = $chan['ref_id'];
				$delete = mysql_query("DELETE FROM defvis_feed WHERE u_id='$id' AND type='chan' AND ref_id='$chandlt'");
			}
		}
		
		$peeple = explode(",", $_POST['peeplenames']);
		if (isset($_POST['peeplenames'])) {
			foreach ($peeple as $uid) {
				$uid = escape_data($uid);
				if (($uid!=0)&&(mysql_result (mysql_query("SELECT COUNT(*) FROM defvis_feed WHERE u_id='$id' AND type='user' AND ref_id='$uid' LIMIT 1"), 0)<1)) {
					$addvis = mysql_query("INSERT INTO defvis_feed (u_id, type, ref_id, time_stamp) VALUES ('$id', 'user', '$uid', NOW())");
				}
			}
		}
		$prsns = mysql_query("SELECT ref_id FROM defvis_feed WHERE u_id='$id' AND type='user'");
		while ($prsn = @mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
			if (!@in_array($prsn['ref_id'], $peeple)) {
				$prsndlt = $prsn['ref_id'];
				$delete = mysql_query("DELETE FROM defvis_feed WHERE u_id='$id' AND type='user' AND ref_id='$prsndlt'");
			}
		}
	
	}
	
	if (empty($errors)) {
		echo '<div align="center" class="p18">Your visibility settings have been saved.</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 1200);
		</script>';
	} else {
		echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left">An error occurred. Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
		reporterror('home/editpostvis.php', 'default vis settings', $errors);
		echo '</td></tr></table>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400);
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/home/editpostvis.php" method="post">
		<div align="left" style="padding-left: 16px; padding-bottom: 24px;">
			<div align="left" class="p24" style="padding-bottom: 6px;">Show to</div>
			<div align="left" style="padding-left: 16px; padding-bottom: 8px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="96px">Public</td><td align="left" valign="bottom" style="font-size: 13px; padding-bottom: 2px;">
					<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'publicvis\').get(\'checked\') == false){$(\'publicvis\').set(\'checked\',true);$(\'strmbtns\').set(\'styles\',{\'display\':\'none\'});$(\'chanbtns\').set(\'styles\',{\'display\':\'none\'});$(\'hidefrmopts\').set(\'styles\',{\'display\':\'none\'});}else{$(\'publicvis\').set(\'checked\',false);$(\'strmbtns\').set(\'styles\',{\'display\':\'block\'});$(\'chanbtns\').set(\'styles\',{\'display\':\'block\'});$(\'hidefrmopts\').set(\'styles\',{\'display\':\'block\'});}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="publicvis" name="publicvis" value="fam" onclick="if($(\'publicvis\').get(\'checked\') == false){$(\'publicvis\').set(\'checked\',true);}else{$(\'publicvis\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">Make this public. <span class="paragraphA1">(This means everyone on the internet can view it.)</span></td></tr></table>
				</td></tr></table>
			</div>
			<div align="left" id="strmbtns" style="padding-left: 16px; padding-bottom: 8px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="96px">Streams</td><td align="left" valign="bottom" style="font-size: 13px; padding-bottom: 2px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[mb]\').get(\'checked\') == false){$(\'streamvis[mb]\').set(\'checked\',true);}else{$(\'streamvis[mb]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[mb]" name="streamvis[mb]" value="mb" onclick="if($(\'streamvis[mb]\').get(\'checked\') == false){$(\'streamvis[mb]\').set(\'checked\',true);}else{$(\'streamvis[mb]\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">my bubble</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[frnd]\').get(\'checked\') == false){$(\'streamvis[frnd]\').set(\'checked\',true);}else{$(\'streamvis[frnd]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[frnd]" name="streamvis[frnd]" value="frnd" onclick="if($(\'streamvis[frnd]\').get(\'checked\') == false){$(\'streamvis[frnd]\').set(\'checked\',true);}else{$(\'streamvis[frnd]\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">friends</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[fam]\').get(\'checked\') == false){$(\'streamvis[fam]\').set(\'checked\',true);}else{$(\'streamvis[fam]\').set(\'checked\',false);}""><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[fam]" name="streamvis[fam]" value="fam" onclick="if($(\'streamvis[fam]\').get(\'checked\') == false){$(\'streamvis[fam]\').set(\'checked\',true);}else{$(\'streamvis[fam]\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">family</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[prof]\').get(\'checked\') == false){$(\'streamvis[prof]\').set(\'checked\',true);}else{$(\'streamvis[prof]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[prof]" name="streamvis[prof]" value="prof" onclick="if($(\'streamvis[prof]\').get(\'checked\') == false){$(\'streamvis[prof]\').set(\'checked\',true);}else{$(\'streamvis[prof]\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">professional</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[edu]\').get(\'checked\') == false){$(\'streamvis[edu]\').set(\'checked\',true);}else{$(\'streamvis[edu]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[edu]" name="streamvis[edu]" value="edu" onclick="if($(\'streamvis[edu]\').get(\'checked\') == false){$(\'streamvis[edu]\').set(\'checked\',true);}else{$(\'streamvis[edu]\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">education</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[aqu]\').get(\'checked\') == false){$(\'streamvis[aqu]\').set(\'checked\',true);}else{$(\'streamvis[aqu]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[aqu]" name="streamvis[aqu]" value="aqu" onclick="if($(\'streamvis[aqu]\').get(\'checked\') == false){$(\'streamvis[aqu]\').set(\'checked\',true);}else{$(\'streamvis[aqu]\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">just met mee</td></tr></table>
					</td></tr></table>
				</td></tr></table>
			</div>
			<div align="left" id="chanbtns" style="padding-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="96px">Channels</td><td align="left" valign="bottom" style="font-size: 13px; padding-bottom: 2px;">
					<table cellpadding="0" cellspacing="0"><tr>';
					//get channels
					$channels = @mysql_query("SELECT mpc_id, name FROM my_peeple_channels WHERE u_id='$id' ORDER BY name ASC");
					$cc = 0;
					while ($channel = @mysql_fetch_array ($channels, MYSQL_ASSOC)) {
						echo '<td align="left" valign="center"'; if($cc>0){echo' style="padding-left: 12px;"';} echo'>
							<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'chanvis['.$channel['mpc_id'].']\').get(\'checked\') == false){$(\'chanvis['.$channel['mpc_id'].']\').set(\'checked\',true);}else{$(\'chanvis['.$channel['mpc_id'].']\').set(\'checked\',false);}"><tr><td align="left"><input type="checkbox" id="chanvis['.$channel['mpc_id'].']" name="chanvis['.$channel['mpc_id'].']" value="'.$channel['mpc_id'].'" onclick="if($(\'chanvis['.$channel['mpc_id'].']\').get(\'checked\') == false){$(\'chanvis['.$channel['mpc_id'].']\').set(\'checked\',true);}else{$(\'chanvis['.$channel['mpc_id'].']\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">'.$channel['name'].'</td></tr></table>
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
		<div align="left" id="hidefrmopts" class="p24" style="padding-left: 16px; padding-bottom: 8px;">
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
			</div><div align="center" style="font-size: 13px; padding-top: 8px;">
				<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'newdef\').get(\'checked\') == false){$(\'newdef\').set(\'checked\',true);}else{$(\'newdef\').set(\'checked\',false);}"><tr><td align="left"><input type="checkbox" id="newdef" name="newdef" value="newdef" onclick="if($(\'newdef\').get(\'checked\') == false){$(\'newdef\').set(\'checked\',true);}else{$(\'newdef\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">make this my new default option</td></tr></table>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
	</form>';
}

include ('../../../externals/header/footer-pb.php');
?>