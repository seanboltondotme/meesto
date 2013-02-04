<?php
include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit Name</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to edit the name you use on Meesto.</div>';

$uinfo = @mysql_fetch_array (@mysql_query ("SELECT first_name, middle_name, last_name, full_name FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);

if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (isset($_POST['fn']) && ($_POST['fn'] != 'first name') && ($_POST['fn'] != '')) {
		$fn = ucwords(escape_data($_POST['fn']));
	} else {
		$errors[] = 'You must enter a first name.';
	}
	
	if (isset($_POST['mn']) && ($_POST['mn'] != 'middle name (opt)')) {
		$mn = ucwords(escape_data($_POST['mn']));
	} else {
		$mn = '';
	}
	
	if (isset($_POST['ln']) && ($_POST['ln'] != 'last name') && ($_POST['ln'] != '')) {
		$ln = ucwords(escape_data($_POST['ln']));
	} else {
		$errors[] = 'You must enter a last name.';
	}
	
	if (isset($_POST['fulln']) && ($_POST['fulln'] != 'full name - for search (opt)')) {
		$fulln = ucwords(escape_data($_POST['fulln']));
	} else {
		$fulln = '';
	}
	
	if (empty($errors)) {
		$update = mysql_query("UPDATE users SET first_name='$fn', middle_name='$mn', last_name='$ln', full_name='$fulln' WHERE user_id='$id'");
		echo '<div align="center" class="p18">Your name has been saved!</div>
		<script type="text/javascript">
			setTimeout("parent.location.reload();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

} else {
	$fn = $uinfo['first_name'];
	$mn = $uinfo['middle_name'];
	$ln = $uinfo['last_name'];
	$fulln = $uinfo['full_name'];
}
	
	echo '<form action="'.$baseincpat.'externalfiles/settings/editname.php" method="post">
		<div align="center" style="padding-left: 16px;">
			
			<div align="left" style="margin-bottom: 12px;">
				<div align="left" style="padding-bottom: 6px;"><span class="p24">Account Name </span><span class="subtext">(This is how everyone on Meesto will view your name.)</span></div>
				<div align="left" style="margin-left: 32px; margin-top: 6px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left">
						<input type="text" id="fn" name="fn" size="14" maxlength="60" autocomplete="off" onfocus="if (trim(this.value) == \'first name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'first name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" value="'.$fn.'">
					</td><td align="left" style="padding-left: 8px;">
						<input type="text" id="mn" name="mn" size="14" maxlength="60" autocomplete="off" onfocus="if (trim(this.value) == \'middle name (opt)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'middle name (opt)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" '; if($mn!=''){echo'value="'.$mn.'"';}else{echo'class="inputplaceholder" value="middle name (opt)"';} echo'>
					</td><td align="left" style="padding-left: 8px;">
						<input type="text" id="ln" name="ln" size="16" maxlength="60" autocomplete="off" onfocus="if (trim(this.value) == \'last name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'last name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" value="'.$ln.'">
					</td></tr></table>
				</div>
			</div>
			
			<div align="left" style="margin-top: 24px; margin-bottom: 12px;">
				<div align="left" style="padding-bottom: 6px;"><span class="p24">Full/Alternate Name </span><span class="subtext">(This is used to help people search for you.)</span></div>
				<div align="left" style="margin-left: 32px; margin-top: 6px;">
					<input type="text" id="fulln" name="fulln" size="48" maxlength="220" autocomplete="off" onfocus="if (trim(this.value) == \'full name - for search (opt)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'full name - for search (opt)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" '; if($fulln!=''){echo'value="'.$fulln.'"';}else{echo'class="inputplaceholder" value="full name - for search (opt)"';} echo'>
				</div>
			</div>
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