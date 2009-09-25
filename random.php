<?php
include("include/config.php");
include("include/db.php");

function makeSeed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}

db_connect($host, $username, $password, $database);

// TODO generate a random book and chapter then send a  header(Location) update


srand(makeSeed());
$randBook = rand(1, 87);

$sql =<<<SQL
	SELECT *
	FROM lds_scriptures_books
	WHERE book_id = $randBook
	LIMIT 1
SQL;
$bookRS = mysql_query($sql) or die("Error in query: " . $sql . " -- " . mysql_error());
$book = mysql_fetch_array($bookRS);

srand(makeSeed());
$randChapter = rand(1, $book['num_chapters']);

header("Location: $siteroot/{$book['lds_org']}/$randChapter/");
?>
