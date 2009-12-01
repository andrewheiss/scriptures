<?php
include("include/config.php");
include("include/db.php");

$conn = db_connect($host, $username, $password, $database);

$query = explode(' ', $_REQUEST['query']);

$book = mysql_escape_string($query[0]);

$sql =<<<SQL
	SELECT b.*
	FROM lds_scriptures_books b
	WHERE b.book_title='$book'
SQL;

$results = mysql_query($sql, $conn) or die('Something went wrong! ' . mysql_error());
db_close($conn);

$line = mysql_fetch_array($results);
$url = "/" . $line['lds_org'] . "/" . $query[1];

echo json_encode($url);
?>
