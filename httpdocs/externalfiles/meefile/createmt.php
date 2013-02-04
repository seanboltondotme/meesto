<?php
require_once ('../../../externals/general/includepaths.php');

include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Create Meefile Tab</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to create a meefile tab where you can display custom content <span style="font-size: 13px;">(and edit its visibility)</span>.</div>';

if (isset($_POST['create'])) {
	
	$errors = NULL;
	
	if (isset($_POST['name']) && ($_POST['name'] != 'type tab name here...')) {
		$name = escape_form_data($_POST['name']);
	} else {
		$errors[] = 'You must enter a tab name.';
	}
	
	if (isset($_POST['description']) && ($_POST['description'] != 'type a description here (this is optional)')) {
		$description = escape_form_data($_POST['description']);
	} else {
		$description = '';
	}
	
	if (empty($errors)) {
		$insert = mysql_query("INSERT INTO meefile_tab (u_id, name, description, time_stamp) VALUES ('$id', '$name', '$description',NOW())");
		$mtid = mysql_insert_id();
		
		// tests for and make activity post
		if (mysql_result(mysql_query ("SELECT COUNT(*) FROM user_activityposts WHERE u_id='$id' AND mtabs='y' LIMIT 1"), 0)>0) {
			$createpost = mysql_query("INSERT INTO feed (u_id, type, ref_id, ref_type, time_stamp) VALUES ('$id', 'actvmt', '$mtid', 'mt', NOW())");
		}
		
		echo '<div align="center" class="p18">Your meefile tab has been created!</div>
		<script type="text/javascript">
			setTimeout("parent.location.href=\''.$baseincpat.'meefile.php?id='.$id.'&t='.$mtid.'\';", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/meefile/createmt.php" method="post">
		<div align="left" style="padding-left: 16px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">name</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="name" size="46" maxlength="200" autocomplete="off" onfocus="if (trim(this.value) == \'type tab name here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type tab name here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($name!=''){echo'value="'.$name.'"';}else{echo'class="inputplaceholder" value="type tab name here..."';}
				echo '>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">description</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="description" cols="44" rows="3" onfocus="if (trim(this.value) == \'type a description here (this is optional)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type a description here (this is optional)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'5000\', \'infovertxtalrt\');"';
				if ($description){echo'>'.$description;}else{echo' class="inputplaceholder">type a description here (this is optional)';}
			echo '</textarea>
				<div id="infovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="create" name="create" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 6px; font-size: 14px;">
				(note: by default a new meefile tab is invisible; be sure to set its visibility)
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

include ('../../../externals/header/footer-pb.php');
?>