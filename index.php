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
$loclink = "$siteroot/$bookname";
if ($chapter) $loclink .= "/$chapter";
if ($verse) $loclink .= "/$verse";

// If chapter/verse isn't specified, choose sane defaults
if (!$chapter || $chapter < 1 || $chapter == "") $chapter = 1;
if ((!$verse || $verse < 1 || $verse == "") && !$passage) $verse = 1;

if ($bookname != "") // if a book is specified
{
	$conn = db_connect($host, $username, $password, $database);

	// First off, let's figure out what book we're in

	$query = "SELECT book_id, book_title, volume_title_long, volume_subtitle, b.num_chapters FROM lds_scriptures_books b INNER JOIN lds_scriptures_volumes v ON v.volume_id = b.volume_id WHERE b.lds_org='$bookname'";
	$result = mysql_query($query) or die ("Couldn't run: $query");

	$bookid = trim(mysql_result($result, 0, "book_id"));
	$booktitle = trim(mysql_result($result, 0, "book_title"));
	$volume = trim(mysql_result($result, 0, "volume_title_long"));
	$volume_subtitle = trim(mysql_result($result, 0, "volume_subtitle"));
	$num_chapters = trim(mysql_result($result, 0, "num_chapters"));

	// Check if there are any pilcrows in the chapter
	
	$query = "SELECT COUNT(pilcrow) as pilcrows FROM lds_scriptures_verses WHERE book_id=$bookid AND chapter=$chapter AND pilcrow = 1";
	$result = mysql_query($query) or die ("Couldn't run: $query");
	if (trim(mysql_result($result, 0, "pilcrows") == 0)) {
		$pilcrow = false;
	} else {
		$pilcrow = true;
		$pilcrow_sql = ", pilcrow";
		$pilcrow_footer = "<br /><b>z</b> = toggle &para;s";
	}
	
	// Now get the verses
	
	$query = "SELECT chapter, verse, verse_scripture $pilcrow_sql FROM lds_scriptures_verses WHERE book_id=$bookid AND chapter=$chapter";
	$result = mysql_query($query) or die ("Couldn't run: $query");

	$verses = array();

	while ($row = mysql_fetch_assoc($result))
	{
		array_push($verses, $row);
	}

	// Now figure out what comes before and after
	if ($chapter > 1) $prevchapter = $chapter - 1;
	if ($chapter + 1 <= $num_chapters) $nextchapter = $chapter + 1;

	$prevurl = "$siteroot/$bookname/$prevchapter";
	$nexturl = "$siteroot/$bookname/$nextchapter";
	
	// Take care of first and last chapters of each book and overwrite $prevurl or $nexturl accordingly
	// FUTURE: maybe… make interbook next/previous links respect the overall book order (right now it goes OT, NT, BoM, D&C, PoGP, doesn't follow custom book order). Probably okay to just live with it.
	
	if ($chapter == 1) { // First chapter
		$prev = $bookid - 1;
		if ($prev > 0) {
			$query = "SELECT lds_org, num_chapters FROM lds_scriptures_books WHERE book_id = $prev";
			$result = mysql_query($query) or die ("Couldn't run: $query");

			$prevbook = trim(mysql_result($result, 0, 'lds_org'));
			$prevchapter = trim(mysql_result($result, 0, 'num_chapters'));
			$prevurl = "$siteroot/$prevbook/$prevchapter";
		}
	}
	
	if ($chapter == $num_chapters) { // Last chapter
		$next = $bookid + 1;
		if ($next <= 87) { 
			// Final book_id, Articles of Faith, hard coded in. Shouldn't be a problem until Standard Works are updated/expanded some day. Until then, 87 works better for performance.
			$query = "SELECT lds_org FROM lds_scriptures_books WHERE book_id = $next";
			$result = mysql_query($query) or die ("Couldn't run: $query");

			$nextbook = trim(mysql_result($result, 0, 'lds_org'));
			$nextchapter = 1;
			$nexturl = "$siteroot/$nextbook/$nextchapter";
		}
	}
	
	db_close($conn);

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

	<div id="banner"><div id="banner_container"><a href="<?php echo $siteroot; ?>/"><?php echo $volume; ?></a><?php if ($volume_subtitle) { ?><div id="banner_subtitle"><?php echo $volume_subtitle; ?></div><?php } ?></div></div>
	
	<div id="loc"><a href="<?php echo $prevurl; ?>" id="prevlink">&laquo; Prev</a><a href="<?php echo $nexturl; ?>" id="nextlink">Next &raquo;</a><a href="<?php echo $loclink; ?>" id="loclink"><?php echo $booktitle; ?> <?php echo $chapter; ?></a></div>
	
	<div id="page">
		<?php
			$content = "";
			if (sizeof($verses)) {
				foreach ($verses as $the_verse) {
					$versenum = $the_verse['verse'];
					$versetext = $the_verse['verse_scripture'];
					if ($pilcrow) {
						$pilcrow_html = ($the_verse['pilcrow']) ? "<span class=\"pilcrow\">&para; </span>" : "";
					}

					$content .= "\t<div class='versenum'>$versenum</div>\n";
					$content .= "\t<div id='v_$versenum' class='verse";
					if ($versenum == $verse) $content .= " selected";
					
					if ($highlight && $versenum == $verse)
					{
						$content .= " highlight";
						if ($passage && $verse != $pEnd)
							$verse++;
					}

					$content .= "'>$pilcrow_html$versetext</div>\n";
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
