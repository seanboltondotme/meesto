<?php
require_once ('../../../externals/general/includepaths.php');

include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Create Meesto Community Project</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to create a new project. <span style="font-size: 13px;">(This is a way of requesting and discussing changes to the site.)</span></div>';

if (isset($_POST['create'])) {
	
	$errors = NULL;
	
	if (isset($_POST['name']) && ($_POST['name'] != 'type project name here...')) {
		$name = escape_form_data($_POST['name']);
	} else {
		$errors[] = 'You must enter a project name.';
	}
	
	if (isset($_POST['about']) && ($_POST['about'] != 'briefly explain your project idea here...')) {
		$about = escape_form_data($_POST['about']);
	} else {
		$errors[] = 'Please briefly explain your project idea.';
	}
	
	if (empty($errors)) {
		$insert = mysql_query("INSERT INTO comm_projs (u_id, name, about, stat, time_stamp) VALUES ('$id', '$name', '$about', 'pnd', NOW())");
		$cpid = mysql_insert_id();
		$delete = mysql_query("INSERT INTO commproj_mem (u_id, cp_id, type, time_stamp) VALUES ('$id', '$cpid', 'a', NOW())");
		//make myself an admin
		if ($id!=1) {
			$insert = mysql_query("INSERT INTO commproj_mem (u_id, cp_id, type, time_stamp) VALUES ('1', '$cpid', 'a', NOW())");
		}
		
						//send myself an email
						if ($id!=1) { //if not me
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='1' LIMIT 1"), 0);
										
							//params
							$subject = 'Someone just created a Meesto Community Project';
							$emailercontent = 'Someone just created the Meesto Community Project "<a href="'.$baseincpat.'proj.php?id='.$cpid.'">'.$name.'</a>."';
							$emailercontent .= '<br /><span style="color: #C5C5C5; font: 11px Arial, Helvetica, sans-serif;">In reference to cpid='.$cpid.'</span>';
										
							include('../../../externals/general/emailer.php');
						}
		
		echo '<div align="center" class="p18">Your project has been created!</div>
		<script type="text/javascript">
			setTimeout("parent.location.href=\''.$baseincpat.'proj.php?id='.$cpid.'\';", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/community/createproj.php" method="post">
		<div align="left" style="padding-left: 16px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">name</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="name" size="46" maxlength="400" autocomplete="off" onfocus="if (trim(this.value) == \'type project name here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type project name here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($name!=''){echo'value="'.$name.'"';}else{echo'class="inputplaceholder" value="type project name here..."';}
				echo '>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">about</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="about" cols="44" rows="3" onfocus="if (trim(this.value) == \'briefly explain your project idea here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'briefly explain your project idea here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'20000\', \'aboutovertxtalrt\');"';
				if ($about){echo'>'.$about;}else{echo' class="inputplaceholder">briefly explain your project idea here...';}
			echo '</textarea>
				<div id="aboutovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="create" name="create" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

include ('../../../externals/header/footer-pb.php');
?>