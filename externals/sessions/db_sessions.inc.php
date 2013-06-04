<?php # Script 3.1 - db_sessions.inc.php

/* 
 *	This page creates the functional
 *	interface for storing session data
 *	in a database.
 *	This page also starts the session.
 */

// Global variable used for the database 
// connections in all session functions:
$dbc = NULL;

// Define the open_session() function:
// This function takes no arguments.
// This function should open the database connection.
function open_session() {

	global $dbc;
	
	// Connect to the database.
	DEFINE('DB_USER', 'root');
	DEFINE ('DB_PASSWORD', '');
	DEFINE ('DB_HOST', 'localhost');
	DEFINE ('DB_NAME', 'meesto');
	
	$dbc = mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not connect to MySQL: ' . mysql_error() );
	
	@mysql_select_db (DB_NAME, $dbc) OR die ('Could not select the database: ' . mysql_error() );
	
	session_cache_expire(30);
	
	return true;

} // End of open_session() function.
 
// Define the close_session() function:
// This function takes no arguments.
// This function closes the database connection.
function close_session() {

	global $dbc;
	
	return mysql_close($dbc);
	
} // End of close_session() function.

// Define the read_session() function:
// This function takes one argument: the session ID.
// This function retrieves the session data.
function read_session($sid) {

	global $dbc;

 	// Query the database:
 	$q = sprintf('SELECT data FROM sessions WHERE id="%s"', mysql_real_escape_string($sid, $dbc)); 
	$r = mysql_query($q, $dbc);
	
	// Retrieve the results:
	if (mysql_num_rows($r) == 1) {
	
		list($data) = mysql_fetch_array($r, MYSQL_NUM);
		
		// Return the data:
		return $data;

	} else { // Return an empty string.
		return '';
	}
	
} // End of read_session() function.

// Define the write_session() function:
// This function takes two arguments: 
// the session ID and the session data.
function write_session($sid, $data) {

	global $dbc;

	// Store in the database:
 	$q = sprintf('REPLACE INTO sessions (id, data) VALUES ("%s", "%s")',  mysql_real_escape_string($sid, $dbc), mysql_real_escape_string($data, $dbc)); 
	$r = mysql_query($q, $dbc);
	
	$q = sprintf('UPDATE sessions SET u_id="%d" WHERE id="%s"', (int) $_SESSION['user_id'],  mysql_real_escape_string($sid, $dbc)); 
	$r = mysql_query($q, $dbc);
	
	$q = sprintf('UPDATE sessions SET client="%s" WHERE id="%s"', $_SESSION['client'],  mysql_real_escape_string($sid, $dbc)); 
	$r = mysql_query($q, $dbc);

	return mysql_affected_rows($dbc);

} // End of write_session() function.

// Define the destroy_session() function:
// This function takes one argument: the session ID.
function destroy_session($sid) {

	global $dbc;

	// Delete from the database:
 	$q = sprintf('DELETE FROM sessions WHERE id="%s"',  mysql_real_escape_string($sid, $dbc)); 
	$r = mysql_query($q, $dbc);
	
	// Clear the $_SESSION array:
	$_SESSION = array();

	return mysql_affected_rows($dbc);

} // End of destroy_session() function.

// Define the clean_session() function:
// This function takes one argument: a value in seconds.
function clean_session($expire) {

	global $dbc;

	// Delete old sessions:
 	$q = sprintf('DELETE FROM sessions WHERE DATE_ADD(last_accessed, INTERVAL %d SECOND) < NOW()', (int) $expire); 
	$r = mysql_query($q, $dbc);

	return mysql_affected_rows($dbc);

} // End of clean_session() function.

# **************************** #
# ***** END OF FUNCTIONS ***** #
# **************************** #

// Declare the functions to use:
session_set_save_handler('open_session', 'close_session', 'read_session', 'write_session', 'destroy_session', 'clean_session');

// Make whatever other changes to the session settings.

// Start the session:
session_start();
?>
