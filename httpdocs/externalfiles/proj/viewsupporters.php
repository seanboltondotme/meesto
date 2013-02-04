<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$cpid = escape_data($_GET['id']);
$pjs = '<script src="'.$baseincpat.'externalfiles/proj/viewsupporters.js" type="text/javascript" charset="utf-8"></script>';
$pdrjs = 'new Request.JSON({url: \''.$baseincpat.'externalfiles/proj/viewsupporters-search.php?id='.$cpid.'\', onSuccess: function(r){
				PeepSearch.setValues(r);
			}}).send();';
$fullmts = true;
include ('../../../externals/header/header-pb.php');

if (isset($_GET['fltr'])) {
	$fltr = escape_data($_GET['fltr']);
} else {
	$fltr = 's';
}	
$cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$cpid' LIMIT 1"), MYSQL_ASSOC);

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">View Supporters And Team Members</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to view supporters and team members of "'.$cpinfo['name'].'"</div>';
	
	echo '<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="center" id="fltra" class="topfltr'; if($fltr=='s'){echo'On';} echo'" style="width: 140px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrm\').set(\'class\', \'topfltr\');showAll();">
					<div align="center" class="title" style="width: 140px;">supporters</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 12px;">
				<div align="center" id="fltrm" class="topfltr'; if($fltr=='t'){echo'On';} echo'" style="width: 140px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltra\').set(\'class\', \'topfltr\');showT();">
					<div align="center" class="title" style="width: 140px;">the team</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="right" valign="top" style="padding-top: 2px; padding-left: 76px;">
				<input type="text" id="msrch" name="msrch" size="29" maxlength="60" onfocus="if (trim(this.value) == \'search\') {this.value=\'\';};this.className=\'inputfocus\'; $(\'fltra\').set(\'class\', \'topfltr\');$(\'fltrm\').set(\'class\', \'topfltr\');" onblur="if (trim(this.value) == \'\') {this.value=\'search\';this.className=\'inputplaceholder\'; $(\'fltra\').set(\'class\', \'topfltrOn\'); showAll();} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(trim(this.value)!=\'search\'){PeepSearch.filter(this.value);}" class="inputplaceholder" value="search"/>
			</td></tr></table>
		</div>
		
		<div align="left" id="peeparea" style="padding-left: 16px; padding-bottom: 12px; height: 200px; width: 644px; overflow-x: none; overflow-y: scroll;"">';
			$peeple = mysql_query ("SELECT cpm.u_id, cpm.type, u.defaultimg_url FROM commproj_mem cpm INNER JOIN users u ON cpm.u_id=u.user_id WHERE cpm.cp_id='$cpid' ORDER BY u.last_name ASC");
			while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
				$uid = $person['u_id'];
				if ($person['type']=='a') {
					$perstype = 'a';
				} else {
					$perstype = 's';
				}
				echo '<div align="left" id="'.$perstype.$uid.'" class="peepblk" style="float: left; width: 150px; margin: 4px;'; if(($fltr=='t')&&($perstype!='a')){echo' display: none;';} echo'">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
						<a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top"><img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" /></a>
					</td><td align="left" valign="top" style="padding-left: 4px;">
						<a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top">'; loadpersonnamenolink($uid); echo '</a>
					</td></tr></table>
				</div>';
			}
		echo '</div>';

include ('../../../externals/header/footer-pb.php');
?>