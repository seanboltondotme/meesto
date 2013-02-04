<?php
require_once ('../../../externals/general/includepaths.php');

include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Report A Bug</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to submit a bug (a.k.a. problem) with the Meesto. <span style="font-size: 13px;">(Please use details in your description such as which browser and browser version you are using as well as which OS and OS version you are using. Also note, as with the rest of the community, this is public.)</span></div>';

if (isset($_POST['report'])) {
	
	$errors = NULL;
	
	if (isset($_POST['name']) && ($_POST['name'] != 'summarize the bug in a few descriptive words...')) {
		$name = escape_form_data($_POST['name']);
	} else {
		$errors[] = 'You must enter a bug name.';
	}
	
	if (isset($_POST['about']) && ($_POST['about'] != 'briefly explain the bug here...')) {
		$about = escape_form_data($_POST['about']);
	} else {
		$errors[] = 'Please briefly explain the bug.';
	}
	
	if (empty($errors)) {
		$insert = mysql_query("INSERT INTO comm_projs (u_id, type, name, about, stat, time_stamp) VALUES ('$id', 'bug', '$name', '$about', 'pnd', NOW())");
		$cpid = mysql_insert_id();
		$insert = mysql_query("INSERT INTO commproj_mem (u_id, cp_id, type, time_stamp) VALUES ('$id', '$cpid', 'a', NOW())");
		//make myself an admin
		if ($id!=1) {
			$insert = mysql_query("INSERT INTO commproj_mem (u_id, cp_id, type, time_stamp) VALUES ('1', '$cpid', 'a', NOW())");
		}
			
						//send myself an email
						if ($id!=1) { //if not me
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='1' LIMIT 1"), 0);
										
							//params
							$subject = 'Someone just reported a Meesto Bug';
							$emailercontent = 'Someone just reported a Meesto Bug "<a href="'.$baseincpat.'proj.php?id='.$cpid.'">'.$name.'</a>."';
							$emailercontent .= '<br /><span style="color: #C5C5C5; font: 11px Arial, Helvetica, sans-serif;">In reference to cpid='.$cpid.'</span>';
										
							include('../../../externals/general/emailer.php');
						}
			
		echo '<div align="center" class="p18">This bug has been reportd!</div>
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
	
	echo '<form action="'.$baseincpat.'externalfiles/community/reportbug.php" method="post">
		<div align="left" style="padding-left: 16px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">name</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="name" size="46" maxlength="400" autocomplete="off" onfocus="if (trim(this.value) == \'summarize the bug in a few descriptive words...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'summarize the bug in a few descriptive words...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($name!=''){echo'value="'.$name.'"';}else{echo'class="inputplaceholder" value="summarize the bug in a few descriptive words..."';}
				echo '>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">about</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="about" cols="44" rows="3" onfocus="if (trim(this.value) == \'briefly explain the bug here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'briefly explain the bug here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'20000\', \'aboutovertxtalrt\');"';
				if ($about){echo'>'.$about;}else{echo' class="inputplaceholder">briefly explain the bug here...';}
			echo '</textarea>
				<div id="aboutovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="report" name="report" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

include ('../../../externals/header/footer-pb.php');
?>