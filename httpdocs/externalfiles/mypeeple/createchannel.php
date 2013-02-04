<?php
require_once ('../../../externals/general/includepaths.php');

include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Create Peeple Channel</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to create a custom Stream, called a Channel, where you can privately organize your peeple without needing their approval.</div>';

if (isset($_POST['create'])) {
	
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
		$insert = mysql_query("INSERT INTO my_peeple_channels (u_id, name, description, time_stamp) VALUES ('$id', '$name', '$description',NOW())");
		$chanid = mysql_insert_id();
		
		echo '<div align="center" class="p18">Your Channel has been created!</div>
		<script type="text/javascript">
			window.addEvent(\'load\', function() {
				var newElem = new Element(\'div\', {\'align\': \'left\', \'html\': \'<div align="left" class="filter" id="fltrelm-c='.$chanid.'"  onclick="backcontrol.setState(\\\'c='.$chanid.'\\\');"><div align="left">'.$name.'</div><div align="left" class="underbar" style="background-color: #60CFDD;"></div></div>\'});
				newElem.inject(parent.$(\'chancreatebtn\'), \'before\');
				parent.backcontrol.setState(\'c='.$chanid.'\');
			});
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/mypeeple/createchannel.php" method="post">
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
				<input type="submit" id="submit" class="end" value="create" name="create" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

include ('../../../externals/header/footer-pb.php');
?>