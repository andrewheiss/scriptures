<?php

function db_connect($host, $username, $password, $database) {
	$conn = mysql_connect($host, $username, $password);
	if (!$conn) {
		echo "Error connecting to database.\n";
	}

	@mysql_select_db($database, $conn) or die("Unable to select database.");
	
	return $conn;
}

function db_close() {
	mysql_close();
}
