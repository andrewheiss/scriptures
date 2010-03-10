<?php
include("include/config.php");
include("include/db.php");

$conn = db_connect($host, $username, $password, $database);

function parse_query($submitted_query) {
	$query = explode(' ', $submitted_query);
	
	if (strcspn($query[0], '0123456789') == strlen($query[0])) {
		# One word book title (ie. Jacob, James, Matthew)
		$book = $query[0];
		$chapter = $query[1];
		
		if ($query[1] && strcspn($query[1], '0123456789') == strlen($query[1])) {
			# Two word book title (ie. Solomon's Song)
			$book.= ' ' . $query[1];
			$chapter = $query[2];
			
			if ($query[2] && strcspn($query[2], '0123456789') == strlen($query[2])) {
				# Three word book title (ie. Doctrine and Covenants, Words of Mormon)
				$book.= ' ' . $query[2];
				$chapter = $query[3];
			}
		}
	}
	
	if (strcspn($query[0], '0123456789') != strlen($query[0])) {
		# Book that starts with a number (ie. 1 Nephi, 2 Corinthians, 3 John)
		$book = $query[0] . ' ' . $query[1];
		$chapter = $query[2];
	}

	$get_verse = explode(':', $chapter);
	$result['book'] = $book;
	$result['chapter'] = $get_verse[0];
	$result['verse'] = $get_verse[1];

} // End of parse_query()

$query = parse_query($_REQUEST['query']);

$book = mysql_escape_string($query['book']);

$sql =<<<SQL
	SELECT b.*
	FROM lds_scriptures_books b
	WHERE b.book_title='$book'
SQL;

$results = mysql_query($sql, $conn) or die('Something went wrong! ' . mysql_error());
db_close($conn);

$line = mysql_fetch_array($results);
$verse_url = ($query['verse']) ? '/' . $query['verse'] : "";
$url = "/" . $line['lds_org'] . "/" . $query['chapter'] . $verse_url;

echo json_encode($url);
?>
