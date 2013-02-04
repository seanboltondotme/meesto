<?php
                   
$ftp_server = "www.meesto.com";
$ftp_user = "";
$ftp_pass = "";

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");

$ftp_basedir = 'httpdocs';
?>