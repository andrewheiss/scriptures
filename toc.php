<?php 
include_once('include/config.php');
include_once('include/db.php');

db_connect($host, $username, $password, $database);

$sqlVolumes =<<<SQL
	SELECT *
	FROM lds_scriptures_volumes v
	ORDER BY priority
SQL;

$sqlBooks =<<<SQL
	SELECT *
	FROM lds_scriptures_books b
	WHERE volume_id=
SQL;

$volumesRS = mysql_query($sqlVolumes) or die("Could not run query: " . $sqlVolumes . " -- " . mysql_error());
?>
<?php include("header.php"); ?>
<div id="toc">
<?php while(($volume = mysql_fetch_array($volumesRS)) != null): ?>
	<div id="<?php echo $volume['lds_org']; ?>" class="volume">
		<h1><?php echo $volume['volume_title_long']; ?></h1>
		<ul class="books">
	<?php $booksRS = mysql_query($sqlBooks . $volume['volume_id']) or die("Could not run query: " . $sqlBooks . $volume['volume_id'] . ' -- ' . mysql_error()); ?>
	<?php while(($books = mysql_fetch_array($booksRS)) != null): ?>
			<li><a href="<?php echo $siteroot . '/' . $books['lds_org']; ?>"><?php echo $books['book_title']; ?></a>
				<ul class="chapters">
					<?php for($i = 1; $i <= $books['num_chapters']; $i++): ?>
					<li><a href="<?php echo $siteroot . '/' . $books['lds_org'] . '/' . $i; ?>"><?php echo $i; ?></a></li>
					<?php endfor; ?>
				</ul>
			</li>
	<?php endwhile; ?>
		</ul>
	</div>
<?php endwhile; ?>
</div>
<?php include("footer.php"); ?>
<?php db_close(); ?>
