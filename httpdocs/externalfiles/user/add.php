<?php
$pjs = '<style type="text/css" media="screen">
.blockbtn-sel {
	padding-top: 8px;
	padding-left: 4px;
	padding-bottom: 8px;
	padding-right: 4px;
	cursor: pointer;
	background-color: #fff;
	border: 1px solid #FFF;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	-opera-border-radius: 6px;
	-khtml-border-radius: 6px;
	border-radius: 6px;
}

.blockbtn-sel:hover {
	padding-top: 8px;
	padding-left: 4px;
	padding-bottom: 8px;
	padding-right: 4px;
	cursor: pointer;
	background-color: #F6F6F6;
	border: 1px solid #fff;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	-opera-border-radius: 6px;
	-khtml-border-radius: 6px;
	border-radius: 6px;
}
</style>';
include ('../../../externals/header/header-pb.php');

$uid = escape_data($_GET['id']);

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Connect With '; loadpersonnameclean($uid); echo'</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to connect with '; loadpersonnameclean($uid); echo' and add '; loadpersonnameclean($uid); echo' to your peeple.</div>';

//test for pending request or connection already made !important
if (($id!=$uid)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)==0)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE type='peepcnct' AND ((u_id='$id' AND s_id='$uid') OR (u_id='$uid' AND s_id='$id')) LIMIT 1"), 0)==0)) {

if (isset($_POST['connect'])) {
	
	$errors = NULL;
	
	if (isset($_POST['streamvis'])) {
		$i = 0;
		foreach ($_POST['streamvis'] as $streamvis) {
			$streamvis = escape_data($streamvis);
			//send connect request
			if ($i>0) {
				$rstrms .= ';'.$streamvis;	
			} else {
				$rstrms = $streamvis;	
			}
			if ($streamvis!= 'mb') {
				$i++;
			}
			if ($i==0) {
				$errors[] = 'You must select a stream to connect through.';
			}
		}
	} else {
		$errors[] = 'You must select a stream to connect through.';
	}
	
	if (empty($errors)) {
		$insert = mysql_query("INSERT INTO requests (u_id, type, s_id, params, time_stamp) VALUES ('$uid', 'peepcnct', '$id', '$rstrms', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$uid' AND req_cnct='y' LIMIT 1"), 0)>0) {
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$uid' LIMIT 1"), 0);
					
					//params
					$subject = 'Connection request from '.returnpersonnameasid($id, $uid);
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $uid).'</a> has requested to connect with you through your ';
					$i = 0;
					$streamct = count($_POST['streamvis'])-1;
					 foreach ($_POST['streamvis'] as $streamnm) {
						if ($streamnm != 'mb') {
							if (($i==$streamct)&&($i==1)) {
								$emailercontent .= ' and ';	
							} elseif (($i==$streamct)&&($i>0)) {
								$emailercontent .= ', and ';	
							} elseif ($i>0) {
								$emailercontent .= ', ';	
							}
							 
							if ($streamnm == 'frnd') {
								$emailercontent .= 'friends';
							} elseif ($streamnm == 'fam') {
								$emailercontent .= 'family';
							} elseif ($streamnm == 'prof') {
								$emailercontent .= 'professional';
							} elseif ($streamnm == 'edu') {
								$emailercontent .= 'education';
							} elseif ($streamnm == 'aqu') {
								$emailercontent .= 'just met mee';
							}
							$i++;
					 	}
					 }
					if ($i>1) {
						$emailercontent .= ' streams.';
					} else {
						$emailercontent .= ' stream.';
					}
					
					include('../../../externals/general/emailer.php');
				}
		echo '<div align="center" class="p18">Your request to connect has been sent to '; loadpersonnameclean($uid); echo'!</div>
		<script type="text/javascript">
			setTimeout("parent.$(\'useraddbtnarea'.$uid.'\').getChildren().destroy();var srchStatElm = new Element(\'div\', {\'align\': \'left\', \'html\': \'pending connection\'});srchStatElm.inject(parent.$(\'useraddbtnarea'.$uid.'\'), \'top\');", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/user/add.php?id='.$uid.'" method="post">
		<div align="center" style="padding-left: 16px;">
			
			<div align="left" class="p18" style="width: 540px; margin-right: 24px; margin-bottom: 8px;">
				Select the streams through which you\'d like to connect:
			</div>
			
			<div align="left" class="blockbtn-sel" style="width: 540px; margin-bottom: 2px;" onclick="if($(\'streamvis[frnd]\').get(\'checked\') == false){$(\'streamvis[frnd]\').set(\'checked\',true);}else{$(\'streamvis[frnd]\').set(\'checked\',false);}">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[frnd]" name="streamvis[frnd]" value="frnd" onclick="if($(\'streamvis[frnd]\').get(\'checked\') == false){$(\'streamvis[frnd]\').set(\'checked\',true);}else{$(\'streamvis[frnd]\').set(\'checked\',false);}"/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">friends</td><td align="left" valign="center" class="subtext" style="padding-left: 12px;">| Ideal for all friends.</td></tr></table>
			</div>
			
			<div align="left" class="blockbtn-sel" style="width: 540px; margin-bottom: 2px;" onclick="if($(\'streamvis[fam]\').get(\'checked\') == false){$(\'streamvis[fam]\').set(\'checked\',true);}else{$(\'streamvis[fam]\').set(\'checked\',false);}">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[fam]" name="streamvis[fam]" value="fam" onclick="if($(\'streamvis[fam]\').get(\'checked\') == false){$(\'streamvis[fam]\').set(\'checked\',true);}else{$(\'streamvis[fam]\').set(\'checked\',false);}"/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">family</td><td align="left" valign="center" class="subtext" style="padding-left: 12px;">| Ideal for family members.</td></tr></table>
			</div>
			
			<div align="left" class="blockbtn-sel" style="width: 540px; margin-bottom: 2px;" onclick="if($(\'streamvis[prof]\').get(\'checked\') == false){$(\'streamvis[prof]\').set(\'checked\',true);}else{$(\'streamvis[prof]\').set(\'checked\',false);}">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[prof]" name="streamvis[prof]" value="prof" onclick="if($(\'streamvis[prof]\').get(\'checked\') == false){$(\'streamvis[prof]\').set(\'checked\',true);}else{$(\'streamvis[prof]\').set(\'checked\',false);}"/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">professional</td><td align="left" valign="center" class="subtext" style="padding-left: 12px;">| Ideal for people you\'ve met professionally.</td></tr></table>
			</div>
			
			<div align="left" class="blockbtn-sel" style="width: 540px; margin-bottom: 2px;" onclick="if($(\'streamvis[edu]\').get(\'checked\') == false){$(\'streamvis[edu]\').set(\'checked\',true);}else{$(\'streamvis[edu]\').set(\'checked\',false);}">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[edu]" name="streamvis[edu]" value="edu" onclick="if($(\'streamvis[edu]\').get(\'checked\') == false){$(\'streamvis[edu]\').set(\'checked\',true);}else{$(\'streamvis[edu]\').set(\'checked\',false);}"/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">education</td><td align="left" valign="center" class="subtext" style="padding-left: 12px;">| Ideal for people you\'ve met through education.</td></tr></table>
			</div>
			
			<div align="left" class="blockbtn-sel" style="width: 540px; margin-bottom: 4px;" onclick="if($(\'streamvis[aqu]\').get(\'checked\') == false){$(\'streamvis[aqu]\').set(\'checked\',true);}else{$(\'streamvis[aqu]\').set(\'checked\',false);}">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[aqu]" name="streamvis[aqu]" value="aqu" onclick="if($(\'streamvis[aqu]\').get(\'checked\') == false){$(\'streamvis[aqu]\').set(\'checked\',true);}else{$(\'streamvis[aqu]\').set(\'checked\',false);}"/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">just met mee</td><td align="left" valign="center" class="subtext" style="padding-left: 12px;">| Ideal for people you just met.</td></tr></table>
			</div>
		
			<div align="center" class="blockbtn-sel" style="width: 340px; margin-top: 6px;" onclick="if($(\'streamvis[mb]\').get(\'checked\') == false){$(\'streamvis[mb]\').set(\'checked\',true);}else{$(\'streamvis[mb]\').set(\'checked\',false);}">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[mb]" name="streamvis[mb]" value="mb" onclick="if($(\'streamvis[mb]\').get(\'checked\') == false){$(\'streamvis[mb]\').set(\'checked\',true);}else{$(\'streamvis[mb]\').set(\'checked\',false);}"/></td><td align="left" valign="center" style="padding-left: 8px;">also add to my bubble <span class="subtext" style="font-size: 14px;">(wont require aproval)</span></td></tr></table>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 2px;">
				<input type="submit" id="submit" class="end" value="request to connect" name="connect" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 6px; font-size: 14px;">
				(note: this will require approval from '; loadpersonnameclean($uid); echo')
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

} else { //if not event admin
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		This option is not available because you are either already connected to '; loadpersonnameclean($uid); echo' or have a pending request to be.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>