<?php
include ('../../../externals/header/header-pb.php');

$type = escape_data($_GET['type']);
if (isset($_GET['stat'])) {
	$stat = escape_data($_GET['stat']);
}

if ($type=='bug') {
	$typename = 'Meesto Bugs';
	if ($stat=='ip') {
		$statname = 'Currntly Being Fixed';
	} elseif ($stat=='fixed') {
		$statname = 'Fixed';
	} else {
		$statname = 'Pending';
	}
} else {
	$typename = 'Meesto Community Projects';	
	if ($stat=='ip') {
		$statname = 'In Production';
	} elseif ($stat=='apnd') {
		$statname = 'Approved - Awaiting Production';
	} elseif ($stat=='cmpltd') {
		$statname = 'Completed';
	} elseif ($stat=='cncl') {
		$statname = 'Canceled';
	} else {
		$statname = 'Pending';
	}
}

echo '<div align="left" class="p24" style="border-bottom: 1px solid #C5C5C5;">View All '.$statname.' '.$typename.'</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to view all items in  a certain category.</div>';

	echo '<div align="left" id="projarea" style="padding-left: 16px; padding-bottom: 12px; height: 200px; width: 644px; overflow-x: none; overflow-y: scroll;"">';
		if ($type=='bug') {
			$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type='bug' AND stat='$stat' ORDER BY time_stamp DESC LIMIT 8");
		} else {
			$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type IS NULL AND stat='$stat' ORDER BY time_stamp DESC LIMIT 8");
		}
		while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
			$cpid = $proj['cp_id'];
			echo '<div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #C5C5C5;">
			<a href="'.$baseincpat.'proj.php?id='.$cpid.'" target="_parent">
				<div align="left" class="p24">'.$proj['name'].'</div>
				<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
			</a>
			</div>';
		}
		echo '</div>';

include ('../../../externals/header/footer-pb.php');
?>