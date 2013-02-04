<?php
include ('../../../externals/header/header-pb.php');

$sec = escape_data($_GET['sec']);

if ($sec=='pa') {
	$secname = 'photo albums';
} elseif ($sec=='ptags') {
	$secname = 'photo tags';
} elseif ($sec=='mtabs') {
	$secname = 'meefile tabs';
} elseif ($sec=='events') {
	$secname = 'events';
}

echo '<div align="left" class="p24" style="width: 480px; border-bottom: 1px solid #C5C5C5;">Edit Activity Post Settings For '.ucwords($secname).'</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to edit your activity post settings.</div>';

if (isset($_POST['save'])) {
	
	if (isset($_POST['infeed'])&&($_POST['infeed']=='true')) {
		$infeed = true;
	} else {
		$infeed = false;
	}
	
	if (empty($errors)) {
		if ($sec=='pa') {
			if ($infeed==true) {
				$update = mysql_query("UPDATE user_activityposts SET pa='y' WHERE u_id='$id'");
			} else {
				$update = mysql_query("UPDATE user_activityposts SET pa=NULL WHERE u_id='$id'");
			}
		} elseif ($sec=='ptags') {
			if ($infeed==true) {
				$update = mysql_query("UPDATE user_activityposts SET ptags='y' WHERE u_id='$id'");
			} else {
				$update = mysql_query("UPDATE user_activityposts SET ptags=NULL WHERE u_id='$id'");
			}
		} elseif ($sec=='mtabs') {
			if ($infeed==true) {
				$update = mysql_query("UPDATE user_activityposts SET mtabs='y' WHERE u_id='$id'");
			} else {
				$update = mysql_query("UPDATE user_activityposts SET mtabs=NULL WHERE u_id='$id'");
			}
		} elseif ($sec=='events') {
			if ($infeed==true) {
				$update = mysql_query("UPDATE user_activityposts SET events='y' WHERE u_id='$id'");
			} else {
				$update = mysql_query("UPDATE user_activityposts SET events=NULL WHERE u_id='$id'");
			}
		}
		echo '<div align="center" class="p18">Your activity post settings have been saved!</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 1200);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

} else {
	if ($sec=='pa') {
		if (mysql_result(mysql_query ("SELECT COUNT(*) FROM user_activityposts WHERE u_id='$id' AND pa='y' LIMIT 1"), 0)>0) {
			$infeed = true;
		} else {
			$infeed = false;
		}
	} elseif ($sec=='ptags') {
		if (mysql_result(mysql_query ("SELECT COUNT(*) FROM user_activityposts WHERE u_id='$id' AND ptags='y' LIMIT 1"), 0)>0) {
			$infeed = true;
		} else {
			$infeed = false;
		}
	} elseif ($sec=='mtabs') {
		if (mysql_result(mysql_query ("SELECT COUNT(*) FROM user_activityposts WHERE u_id='$id' AND mtabs='y' LIMIT 1"), 0)>0) {
			$infeed = true;
		} else {
			$infeed = false;
		}
	} elseif ($sec=='events') {
		if (mysql_result(mysql_query ("SELECT COUNT(*) FROM user_activityposts WHERE u_id='$id' AND events='y' LIMIT 1"), 0)>0) {
			$infeed = true;
		} else {
			$infeed = false;
		}
	}
}
	
	echo '<form action="'.$baseincpat.'externalfiles/settings/editfeedpost.php?sec='.$sec.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 12px;">';
		if ($sec=='pa') {
			echo 'Automatically make a feed post when you create an album?<br/><span class="subtext" style="font-size: 13px;">(Visibility is inherited from the album.)</span>';
		} elseif ($sec=='ptags') {
			echo 'Automatically make a feed post when you are tagged in a photo?<br/><span class="subtext" style="font-size: 13px;">(Visibility is inherited from your photo tag visibility settings.)</span>';
		} elseif ($sec=='mtabs') {
			echo 'Automatically make a feed post when you create a Meefile tab or tab post?<br/><span class="subtext" style="font-size: 13px;">(Visibility is inherited from each unique Meefile tab visibility setting.)</span>';
		} elseif ($sec=='events') {
			echo 'Automatically make a feed post when you create an event?<br/><span class="subtext" style="font-size: 13px;">(Visibility is inherited from the event.)</span>';
		}
		echo '</div>
		<div align="center" style="padding-bottom: 12px;" class="p24">
			<table cellpadding="0" cellspacing="0"><tr><td align="left"><input type="radio" id="infeed_t" name="infeed" value="true"'; if($infeed==true){echo' CHECKED';} echo'></td><td align="left" valign="center" style="padding-left: 3px; cursor: pointer;" onclick="$(\'infeed_t\').set(\'checked\', true);">yes</td><td align="left" style="padding-left: 18px;"><input type="radio" id="infeed_f" name="infeed" value="false"'; if($infeed==false){echo' CHECKED';} echo'></td><td align="left" valign="center" style="padding-left: 3px; cursor: pointer;" onclick="$(\'infeed_f\').set(\'checked\', true);">no</td></tr></table>
		</div>
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

include ('../../../externals/header/footer-pb.php');
?>