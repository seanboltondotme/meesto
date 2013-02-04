<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$chan = escape_data($_GET['id']);
if (!isset($_POST['save'])) {
	$pjs = '<script src="'.$baseincpat.'externalfiles/mypeeple/chaneditlist.js" type="text/javascript" charset="utf-8"></script>';
	$pdrjs = 'new Request.JSON({url: \''.$baseincpat.'externalfiles/mypeeple/chaneditlist-search.php\', onSuccess: function(r){
					PeepSearch.setValues(r);
				}}).send();';
	$fullmts = true;
}
include ('../../../externals/header/header-pb.php');	
if ($chan=='mb') {
	$mpcname = 'My Bubble';
} else {
	$mpcname= mysql_result(mysql_query ("SELECT name FROM my_peeple_channels WHERE mpc_id='$chan' LIMIT 1"), 0);
}

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit "'.$mpcname.'" Peeple List</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to edit the list of peeple in this Channel.</div>';

//test if can invite or if admin
if (($chan=='mb')||(mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple_channels WHERE mpc_id='$chan' AND u_id='$id' LIMIT 1"), 0)>0)) {
	
if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (empty($errors)) {
		
		//save
		if ($chan=='mb') {
			if (isset($_POST['peeple'])) {
				foreach ($_POST['peeple'] as $pid) {
					$pid = escape_data($pid);
					if (mysql_result (mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND stream='mb' AND p_id='$pid' LIMIT 1"), 0)<1) {
						$addvis = mysql_query("INSERT INTO peep_streams (u_id, stream, p_id, time_stamp) VALUES ('$id', 'mb', '$pid', NOW())");
					}
				}
			}
			$chnls = mysql_query("SELECT p_id FROM peep_streams WHERE u_id='$id' AND stream='mb'");
			while ($chnl = @mysql_fetch_array ($chnls, MYSQL_ASSOC)) {
				if (!@in_array($chnl['p_id'], $_POST['peeple'])) {
					$chandlt = $chnl['p_id'];
					$delete = mysql_query("DELETE FROM peep_streams WHERE u_id='$id' AND stream='mb' AND p_id='$chandlt'");
				}
			}
		} else {
			if (isset($_POST['peeple'])) {
				foreach ($_POST['peeple'] as $pid) {
					$pid = escape_data($pid);
					if (mysql_result (mysql_query("SELECT COUNT(*) FROM mpc_mems WHERE mpc_id='$chan' AND p_id='$pid' LIMIT 1"), 0)<1) {
						$addvis = mysql_query("INSERT INTO mpc_mems (mpc_id, p_id, time_stamp) VALUES ('$chan', '$pid', NOW())");
					}
				}
			}
			$chnls = mysql_query("SELECT p_id FROM mpc_mems WHERE mpc_id='$chan'");
			while ($chnl = @mysql_fetch_array ($chnls, MYSQL_ASSOC)) {
				if (!@in_array($chnl['p_id'], $_POST['peeple'])) {
					$chandlt = $chnl['p_id'];
					$delete = mysql_query("DELETE FROM mpc_mems WHERE mpc_id='$chan' AND p_id='$chandlt'");
				}
			}
		}
		
		
		echo '<div align="center" class="p18">Your Channel peeple list has been saved.</div>
		<script type="text/javascript">
			setTimeout("parent.backcontrol.setState(parent.backcontrol.getState());", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/mypeeple/chaneditlist.php?id='.$chan.'" method="post">
		<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="center" id="fltrchs" class="topfltrOn" style="width: 140px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrslctd\').set(\'class\', \'topfltr\');showAll();">
					<div align="center" class="title" style="width: 140px;">choose</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 12px;">
				<div align="center" id="fltrslctd" class="topfltr" style="width: 140px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrchs\').set(\'class\', \'topfltr\');showSelected();">
					<div align="center" class="title" style="width: 140px;">selected</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="right" valign="top" style="padding-top: 2px; padding-left: 72px;">
				<input type="text" id="msrch" name="msrch" size="30px" maxlength="60" onfocus="if (trim(this.value) == \'search\') {this.value=\'\';};this.className=\'inputfocus\'; $(\'fltrchs\').set(\'class\', \'topfltr\');$(\'fltrslctd\').set(\'class\', \'topfltr\');" onblur="if (trim(this.value) == \'\') {this.value=\'search\';this.className=\'inputplaceholder\'; $(\'fltrchs\').set(\'class\', \'topfltrOn\'); showAll();} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(trim(this.value)!=\'search\'){PeepSearch.filter(this.value);} if(trim(this.value)==\'\'){$(\'fltrchs\').set(\'class\', \'topfltrOn\');}else{$(\'fltrchs\').set(\'class\', \'topfltr\');}" class="inputplaceholder" value="search"/>
			</td></tr></table>
		</div>
		
		<div align="left" id="peeparea" style="padding-left: 16px; padding-bottom: 12px; height: 130px; width: 644px; overflow-x: none; overflow-y: scroll;"">';
			$peeple = mysql_query ("SELECT mp.p_id, u.defaultimg_url FROM my_peeple mp INNER JOIN users u ON mp.p_id = u.user_id WHERE mp.u_id='$id' ORDER BY u.last_name ASC");
			while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
				$pid = $person['p_id'];
				if ((($chan=='mb')&&(mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND stream='mb' AND p_id='$pid' LIMIT 1"), 0)>0))||(mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems WHERE mpc_id='$chan' AND p_id='$pid' LIMIT 1"), 0)>0)) {
					echo '<div align="left" id="peep'.$pid.'" class="peepchsrblkOn" style="float: left; width: 150px; margin: 4px;" onclick="if($(\'peepchk'.$pid.'\').get(\'checked\') == false){$(\'peepchk'.$pid.'\').set(\'checked\',true); $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblkOn\');}else{$(\'peepchk'.$pid.'\').set(\'checked\',false);  $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblk\');}">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" />
						</td><td align="left" valign="top" style="padding-left: 4px;">
							<div align="left">';
								loadpersonnamenolink($pid);
							echo '</div><div align="left" style="padding: 2px;">
								<input type="checkbox" id="peepchk'.$pid.'" name="peeple['.$pid.']" value="'.$pid.'" onclick="if($(\'peepchk'.$pid.'\').get(\'checked\') == false){$(\'peepchk'.$pid.'\').set(\'checked\',true); $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblkOn\');}else{$(\'peepchk'.$pid.'\').set(\'checked\',false);  $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblk\');}" CHECKED/>
							</div>
						</td></tr></table>
					</div>';
				} else {
					echo '<div align="left" id="peep'.$pid.'" class="peepchsrblk" style="float: left; width: 150px; margin: 4px;" onclick="if($(\'peepchk'.$pid.'\').get(\'checked\') == false){$(\'peepchk'.$pid.'\').set(\'checked\',true); $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblkOn\');}else{$(\'peepchk'.$pid.'\').set(\'checked\',false);  $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblk\');}">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" />
						</td><td align="left" valign="top" style="padding-left: 4px;">
							<div align="left">';
								loadpersonnamenolink($pid);
							echo '</div><div align="left" style="padding: 2px;">
								<input type="checkbox" id="peepchk'.$pid.'" name="peeple['.$pid.']" value="'.$pid.'" onclick="if($(\'peepchk'.$pid.'\').get(\'checked\') == false){$(\'peepchk'.$pid.'\').set(\'checked\',true); $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblkOn\');}else{$(\'peepchk'.$pid.'\').set(\'checked\',false);  $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblk\');}"/>
							</div>
						</td></tr></table>
					</div>';
				}
			}
		echo '</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
	</form>';

} else { //if not able to view
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You can\'t view this event.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>