<?php
function reporterror ($pagename, $action, $errors) {
	global $id;
	//log into errors database with user if and date – send an appology message to user when error is recieved...
	echo 'We have taken note of this error.';
}

function loadpersonname ($pid) {
	global $baseincpat;
	global $id;
	echo '<span class="namelink"><a href="'.$baseincpat.'meefile.php?id='.$pid.'">';
		//test for custom name
		$cust_test_array = @mysql_fetch_array (@mysql_query ("SELECT cust_name FROM my_peeple WHERE u_id='$id' AND p_id='$pid' LIMIT 1"), MYSQL_ASSOC);
		$cust_test = $cust_test_array['cust_name'];					
		if ($cust_test != NULL) {
			$name = $cust_test;
		} else {
			//get name
			$info = @mysql_fetch_array (@mysql_query ("SELECT first_name, middle_name, last_name FROM users WHERE user_id='$pid' LIMIT 1"), MYSQL_ASSOC);
			if ($info['middle_name'] != '') {	
				$name = $info['first_name'].' '.$info['middle_name'].' '.$info['last_name'];
			} else {
				$name = $info['first_name'].' '.$info['last_name'];
			}
		}
		if ($pid==$id) {
			$name = $name.' (me)';	
		}
		echo $name;
	echo '</a></span>';
}

function loadpersonnamenolink ($pid) {
	global $baseincpat;
	global $id;
	echo '<span class="namelink">';
		//test for custom name
		$cust_test_array = @mysql_fetch_array (@mysql_query ("SELECT cust_name FROM my_peeple WHERE u_id='$id' AND p_id='$pid' LIMIT 1"), MYSQL_ASSOC);
		$cust_test = $cust_test_array['cust_name'];					
		if ($cust_test != NULL) {
			$name = $cust_test;
		} else {
			//get name
			$info = @mysql_fetch_array (@mysql_query ("SELECT first_name, middle_name, last_name FROM users WHERE user_id='$pid' LIMIT 1"), MYSQL_ASSOC);
			if ($info['middle_name'] != '') {	
				$name = $info['first_name'].' '.$info['middle_name'].' '.$info['last_name'];
			} else {
				$name = $info['first_name'].' '.$info['last_name'];
			}
		}
		if ($pid==$id) {
			$name = $name.' (me)';	
		}
		echo $name;
	echo '</span>';
}

function loadpersonnameclean ($pid) {
	global $baseincpat;
	global $id;
		//test for custom name
		$cust_test_array = @mysql_fetch_array (@mysql_query ("SELECT cust_name FROM my_peeple WHERE u_id='$id' AND p_id='$pid' LIMIT 1"), MYSQL_ASSOC);
		$cust_test = $cust_test_array['cust_name'];					
		if ($cust_test != NULL) {
			$name = $cust_test;
		} else {
			//get name
			$info = @mysql_fetch_array (@mysql_query ("SELECT first_name, middle_name, last_name FROM users WHERE user_id='$pid' LIMIT 1"), MYSQL_ASSOC);
			if ($info['middle_name'] != '') {	
				$name = $info['first_name'].' '.$info['middle_name'].' '.$info['last_name'];
			} else {
				$name = $info['first_name'].' '.$info['last_name'];
			}
		}
		if ($pid==$id) {
			$name = $name.' (me)';	
		}
		echo $name;
}

function returnpersonname ($pid) {
	global $id;
	//test for custom name
	$cust_test_array = @mysql_fetch_array (@mysql_query ("SELECT cust_name FROM my_peeple WHERE u_id='$id' AND p_id='$pid' LIMIT 1"), MYSQL_ASSOC);
	$cust_test = $cust_test_array['cust_name'];					
	if ($cust_test != NULL) {
		$name = $cust_test;
	} else {
		//get name
		$info = @mysql_fetch_array (@mysql_query ("SELECT first_name, middle_name, last_name FROM users WHERE user_id='$pid' LIMIT 1"), MYSQL_ASSOC);
		if ($info['middle_name'] != '') {	
			$name = $info['first_name'].' '.$info['middle_name'].' '.$info['last_name'];
		} else {
			$name = $info['first_name'].' '.$info['last_name'];
		}
	}
	if ($pid==$id) {
		$name = $name.' (me)';	
	}
	return $name;
}

function returnpersonnameasid ($pid, $asid) {
	global $id;
	//test for custom name
	$cust_test_array = @mysql_fetch_array (@mysql_query ("SELECT cust_name FROM my_peeple WHERE u_id='$asid' AND p_id='$pid' LIMIT 1"), MYSQL_ASSOC);
	$cust_test = $cust_test_array['cust_name'];					
	if ($cust_test != NULL) {
		$name = $cust_test;
	} else {
		//get name
		$info = @mysql_fetch_array (@mysql_query ("SELECT first_name, middle_name, last_name FROM users WHERE user_id='$pid' LIMIT 1"), MYSQL_ASSOC);
		if ($info['middle_name'] != '') {	
			$name = $info['first_name'].' '.$info['middle_name'].' '.$info['last_name'];
		} else {
			$name = $info['first_name'].' '.$info['last_name'];
		}
	}
	if ($asid==$id) {
		$name = $name.' (me)';	
	}
	return $name;
}

function returncleanrealname ($pid) {
	global $id;
	//get name
	$info = @mysql_fetch_array (@mysql_query ("SELECT first_name, middle_name, last_name FROM users WHERE user_id='$pid' LIMIT 1"), MYSQL_ASSOC);
	if ($info['middle_name'] != '') {	
		$name = $info['first_name'].' '.$info['middle_name'].' '.$info['last_name'];
	} else {
		$name = $info['first_name'].' '.$info['last_name'];
	}
	if ($pid==$id) {
		$name = $name.' (me)';	
	}
	return $name;
}

function escape_data ($data) {
	if (ini_get('magic_quotes_gpc')) {
		$data = stripslashes($data);
	}
						
	if (function_exists('mysql_real_esacpestring')) {
		global $dbc;
		$data = @mysql_real_escape_string(trim($data), $dbc);
	} else {
		$data = @mysql_escape_string(trim($data));
	}
	
	$data = strip_tags($data);
				
	return $data;
}

function escape_form_data ($data) {
	//needs to escape standard charactesr with exl (e.g. &mdash; etc.) !important
		
		//special characters
		$data = preg_replace('/<3/', '&lt;3', $data);
	
	$data = strip_tags($data);
	
	$data = preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '<a href="http://$2$3" target="_blank">$2$4</a>', $data);
	
	if (ini_get('magic_quotes_gpc')) {
		$data = stripslashes($data);
	}
						
	if (function_exists('mysql_real_esacpestring')) {
		global $dbc;
		$data = @mysql_real_escape_string(trim($data), $dbc);
	} else {
		$data = @mysql_escape_string(trim($data));
	}
	
	
	
	return $data;
}

function escape_emailcont_data ($data) {
	//needs to escape standard charactesr with exl (e.g. &mdash; etc.) !important
		
		//special characters
		$data = preg_replace('/<3/', '&lt;3', $data);
	
	$data = strip_tags($data);
	
	$data = preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '<a href="http://$2$3" target="_blank">$2$4</a>', $data);
	
	return $data;
}

function nicetime($date) {
    if(empty($date)) {
        return "No date provided";
    }
    
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60","60","24","7","4.35","12","10");
    
    $now = time();
    $unix_date = strtotime($date);
    
       // check validity of date
    if(empty($unix_date)) {    
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {    
        $difference = $now - $unix_date;
        $tense = "ago";
        
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }
    
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference != 1) {
        $periods[$j].= "s";
    }
    
    return "$difference $periods[$j] {$tense}";
}
?>