<?php
$conn = mysql_connect('bomdb.imetchad.org', 'lds_scriptures', 'm0rm0n') or die('Something went wrong!' . mysql_error());
mysql_select_db('lds_scriptures', $conn) or die('Something went wrong!' . mysql_error());

$query = explode(' ', $_REQUEST['query']);

$book = mysql_escape_string($query[0]);
$num = mysql_escape_string((isset($_REQUEST['num'])) ? $_REQUEST['num'] : '5');

/**
 * Priority Order for results:
 * 1. BoM
 * 2. NT
 * 3. OT
 * 4. D&C
 * 5. PoGP
 */
$sql =<<<SQL
	SELECT b.*, v.priority
	FROM lds_scriptures_books b, lds_scriptures_volumes v
	WHERE b.volume_id=v.volume_id 
		AND (b.book_title REGEXP '(^$book.+|[0-9] $book.+)' OR b.book_title_short REGEXP '(^$book.+|[0-9] $book.+)')
	ORDER BY v.priority, b.book_title
	LIMIT $num 
SQL;

$results = mysql_query($sql, $conn) or die('Something went wrong! ' . mysql_error());
?>

<?php while (($line = mysql_fetch_array($results)) != null): ?>
<pre>
<?php print_r($line); ?>
</pre>
<?php endwhile; ?>
