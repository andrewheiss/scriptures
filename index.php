<?php 

/* Scriptures reader
   by Ben Crowder and Chad Hansen

   2009
*/

include_once('include/config.php');
include_once('include/db.php');

// Get the page we want to load/edit
$bookname = trim($_GET["book"]);
$chapter = trim($_GET["chapter"]);
$verse = (isset($_GET["verse"])) ? trim($_GET['verse']) : "";

$passage = false;
if (strpos($verse, '-') !== false)
{
	$passage = true;
	$pEnd = substr($verse, strpos($verse, '-') + 1);
	$temp = substr($verse, 0, strpos($verse, '-'));
	$verse = $temp;
}

// Highlight selected verse(s)
$highlight = false;
if ($verse) $highlight = true;

// Get permalink for this page
$loclink = "$siteroot/$book";
if ($chapter) $loclink .= "/$chapter";
if ($verse) $loclink .= "/$verse";

// If chapter/verse isn't specified, choose sane defaults
$highlight = false;
if ($verse != "") $highlight = true;

// Get permalink for this page
$loclink = "$siteroot/$book";
if ($chapter) $loclink .= "/$chapter";
if ($verse) $loclink .= "/$verse";

// If chapter/verse isn't specified, choose sane defaults
if (!$chapter || $chapter < 1 || $chapter == "") $chapter = 1;
if ((!$verse || $verse < 1 || $verse == "") && !$passage) $verse = 1;

if ($bookname != "") // if a book is specified
{
	db_connect($host, $username, $password, $database);

	// First off, let's figure out what book we're in

	$query = "SELECT book_id, book_title, volume_title_long, volume_subtitle, b.num_chapters FROM lds_scriptures_books b INNER JOIN lds_scriptures_volumes v ON v.volume_id = b.volume_id WHERE b.lds_org='$book'";
	$result = mysql_query($query) or die ("Couldn't run: $query");

	$bookid = trim(mysql_result($result, 0, "book_id"));
	$booktitle = trim(mysql_result($result, 0, "book_title"));
	$volume = trim(mysql_result($result, 0, "volume_title_long"));
	$volume_subtitle = trim(mysql_result($result, 0, "volume_subtitle"));
	$num_chapters = trim(mysql_result($result, 0, "num_chapters"));

	// Now get the verses

	$query = "SELECT chapter, verse, verse_scripture FROM lds_scriptures_verses WHERE book_id=$bookid AND chapter=$chapter";
	$result = mysql_query($query) or die ("Couldn't run: $query");

	$verses = array();

	while ($row = mysql_fetch_assoc($result))
	{
		array_push($verses, $row);
	}

	// Now figure out what comes before and after
	if ($chapter > 1) $prevchapter = $chapter - 1;
	if ($chapter + 1 <= $num_chapters) $nextchapter = $chapter + 1;
	else $nextchapter = $chapter;

	$prevurl = "$siteroot/$bookname/$prevchapter";
	$nexturl = "$siteroot/$bookname/$nextchapter";

	db_close();

	include("header.php");
?>
	<div id="shroud" style="display: none"></div>
	<div id="console" style="display: none">
		<div id="console_back"></div>
		<div id="console_entry">
			<div id="console_title">Go to:</div>
			<input type="text" id="console_input" name="console_input" />
		</div>
		<div id="console_dropdown">
		</div>
	</div>

	<div id="banner"><div id="banner_container"><?php echo $volume; ?><?php if ($volume_subtitle) { ?><div id="banner_subtitle"><?php echo $volume_subtitle; ?></div><?php } ?></div></div>
	
	<div id="loc"><a href="<?php echo $prevurl; ?>" id="prevlink">&laquo; Prev</a><a href="<?php echo $nexturl; ?>" id="nextlink">Next &raquo;</a><a href="<?php echo $loclink; ?>" id="loclink"><?php echo $booktitle; ?> <?php echo $chapter; ?></a></div>
	
	<div id="page">
		<?php
			$content = "";
			if (sizeof($verses)) {
				foreach ($verses as $the_verse) {
					$versenum = $the_verse['verse'];
					$versetext = $the_verse['verse_scripture'];

					$content .= "\t<div class='versenum'>$versenum</div>\n";
					$content .= "\t<div id='v_$versenum' class='verse";
					if ($versenum == $verse) $content .= " selected";
					
					if ($highlight && $versenum == $verse)
					{
						$content .= " highlight";
						if ($passage && $verse != $pEnd)
							$verse++;
					}

					$content .= "'>$versetext</div>\n";
					$content .= "<input id='vtag_$versenum' type='hidden' />\n";
					$content .= "\n";
				}
			}
			echo $content;
		?>
	</div>

<?php include("footer.php"); ?>
<?php
}
else // if no book is specified include the static toc
{
	// toc
	include("toc.php");
}
?>
