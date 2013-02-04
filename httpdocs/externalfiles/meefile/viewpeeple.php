<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$uid = escape_data($_GET['id']);
$pjs = '<script src="'.$baseincpat.'externalfiles/meefile/viewpeeple.js" type="text/javascript" charset="utf-8"></script>';
$pdrjs = 'new Request.JSON({url: \''.$baseincpat.'externalfiles/meefile/viewpeeple-search.php?id='.$uid.'\', onSuccess: function(r){
				PeepSearch.setValues(r);
			}}).send();';
$fullmts = true;
include ('../../../externals/header/header-pb.php');

if (isset($_GET['fltr'])) {
	$fltr = escape_data($_GET['fltr']);
} else {
	$fltr = 'a';
}	
$uinfo = mysql_fetch_array (mysql_query ("SELECT first_name FROM users WHERE user_id='$uid' LIMIT 1"), MYSQL_ASSOC);

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">View Peeple</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to view '.$uinfo['first_name'].'\'s peeple.</div>';

//test if can view
if (($uid==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$uid' AND p_id='$id' LIMIT 1"), 0)>0)) {
	
	echo '<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="center" id="fltra" class="topfltr'; if($fltr=='a'){echo'On';} echo'" style="width: 140px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrm\').set(\'class\', \'topfltr\');showA();">
					<div align="center" class="title" style="width: 140px;">all peeple</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 12px;">
				<div align="center" id="fltrm" class="topfltr'; if($fltr=='m'){echo'On';} echo'" style="width: 140px;'; if($uid==$id){echo' visibility: hidden; zoom: 1; opacity: 0;';} echo'" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltra\').set(\'class\', \'topfltr\');showM();">
					<div align="center" class="title" style="width: 140px;">mutual peeple</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="right" valign="top" style="padding-top: 2px; padding-left: 72px;">
				<input type="text" id="msrch" name="msrch" size="30px" maxlength="60" onfocus="if (trim(this.value) == \'search\') {this.value=\'\';};this.className=\'inputfocus\'; $(\'fltra\').set(\'class\', \'topfltr\');$(\'fltrm\').set(\'class\', \'topfltr\'); $$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'block\'});" onblur="if (trim(this.value) == \'\') {this.value=\'search\';this.className=\'inputplaceholder\'; $(\'fltra\').set(\'class\', \'topfltrOn\'); showA();} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(trim(this.value)!=\'search\'){PeepSearch.filter(this.value);} if(trim(this.value)==\'\'){$(\'fltra\').set(\'class\', \'topfltrOn\');}else{$(\'fltra\').set(\'class\', \'topfltr\');}" class="inputplaceholder" value="search"/>
			</td></tr></table>
		</div>
		
		<div align="left" id="peeparea" style="padding-left: 16px; padding-bottom: 12px; height: 200px; width: 644px; overflow-x: none; overflow-y: scroll;"">';
			$peeple = mysql_query ("SELECT mp.p_id, u.defaultimg_url FROM my_peeple mp INNER JOIN users u ON mp.p_id=u.user_id WHERE mp.u_id='$uid' ORDER BY u.last_name ASC");
			while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
				$uid = $person['p_id'];
				$ismutual = false;
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0) {
					$ismutual = true;
				}
				echo '<div align="left" id="'; if($ismutual){echo'm';}else{echo'p';} echo $uid.'" class="peepblk" style="float: left; width: 150px; margin: 4px;'; if(($fltr!='a')&&(!$ismutual)){echo' display: none;';} echo'">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
						<a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top"><img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" /></a>
					</td><td align="left" valign="top" style="padding-left: 4px;">
						<a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top">'; loadpersonnamenolink($uid); echo '</a>
					</td></tr></table>
				</div>';
			}
		echo '</div>';

} else { //if not able to view
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You can\'t view this information.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>