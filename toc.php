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
<div id="banner"><div id="banner_container">The Standard Works<div id="banner_subtitle">The Book of Mormon, Bible, D&amp;C, and Pearl of Great Price</div></div></div>
<div id="page">
<div id="toc">
<?php while(($volume = mysql_fetch_array($volumesRS)) != null): ?>
	<div id="<?php echo $volume['lds_org']; ?>" class="volume">
		<h1><?php echo $volume['volume_title_long']; ?></h1>
		<ul class="books">
	<?php $booksRS = mysql_query($sqlBooks . $volume['volume_id']) or die("Could not run query: " . $sqlBooks . $volume['volume_id'] . ' -- ' . mysql_error()); ?>
	<?php while(($books = mysql_fetch_array($booksRS)) != null): ?>
			<li><label><a href="<?php echo $siteroot . '/' . $books['lds_org']; ?>"><?php echo $books['book_title']; ?></a></label>
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
</div>
<?php include("footer.php"); ?>
<?php db_close(); ?>
