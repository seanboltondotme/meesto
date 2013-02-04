<?php
require_once ('../../../externals/general/includepaths.php');

include ('../../../externals/header/header-pb.php');

$mpcid = escape_data($_GET['id']);
$chaninfo = mysql_fetch_array (mysql_query ("SELECT u_id, name, description FROM my_peeple_channels WHERE mpc_id='$mpcid' LIMIT 1"), MYSQL_ASSOC);

if ($chaninfo['u_id']==$id) {

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit "'.ucwords($chaninfo['name']).'" Peeple Channel</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to edit your Channel info.</div>';

if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (isset($_POST['name']) && ($_POST['name'] != 'type channel name here...')) {
		$name = escape_form_data($_POST['name']);
	} else {
		$errors[] = 'You must enter a channel name.';
	}
	
	if (isset($_POST['description']) && ($_POST['description'] != 'type a description here (this is optional)')) {
		$description = escape_form_data($_POST['description']);
	} else {
		$description = '';
	}
	
	if (empty($errors)) {
		$insert = mysql_query("UPDATE my_peeple_channels SET name='$name', description='$description' WHERE mpc_id='$mpcid'");
		
		echo '<div align="center" class="p18">Your Channel has been saved!</div>
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
	$name	 = $chaninfo['name'];
	$description	 = $chaninfo['description'];
}
	
	echo '<form action="'.$baseincpat.'externalfiles/mypeeple/editchannel.php?id='.$mpcid.'" method="post">
		<div align="left" style="padding-left: 16px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">name</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<input type="text" name="name" size="46" maxlength="200" autocomplete="off" onfocus="if (trim(this.value) == \'type channel name here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type channel name here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($name!=''){echo'value="'.$name.'"';}else{echo'class="inputplaceholder" value="type channel name here..."';}
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
		You must be the owner of this Channel to use this feature.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>