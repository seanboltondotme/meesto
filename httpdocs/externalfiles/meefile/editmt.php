<?php
require_once ('../../../externals/general/includepaths.php');

include ('../../../externals/header/header-pb.php');

$mtid = escape_data($_GET['id']);
$mtinfo = mysql_fetch_array (mysql_query ("SELECT u_id, name, description FROM meefile_tab WHERE mt_id='$mtid' LIMIT 1"), MYSQL_ASSOC);

if ($mtinfo['u_id']==$id) {

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit "'.ucwords($mtinfo['name']).'" Meefile Tab</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to edit a meefile tab.</div>';

if (isset($_POST['save'])) {
	
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
		$update = mysql_query("UPDATE meefile_tab SET name='$name', description='$description' WHERE mt_id='$mtid'");
		
		echo '<div align="center" class="p18">Your meefile tab has been saved.</div>
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
	//load values
	$name = $mtinfo['name'];
	$description = $mtinfo['description'];
}
	
	echo '<form action="'.$baseincpat.'externalfiles/meefile/editmt.php?id='.$mtid.'" method="post">
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
				<input type="submit" id="submit" class="end" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

} else { //if not tab owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must be the owner of this tab to use this feature.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>