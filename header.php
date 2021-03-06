<?php

	// Build the title	
	if (!$booktitle) {
		$pagetitle = "The Scriptures";
	} else {
		$pagetitle = $booktitle . " " . $chapter . " - " . $volume;
	}
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo $pagetitle; ?></title>
	<link rel="stylesheet" href="<?php echo $siteroot; ?>/scriptures.css" type="text/css" media="screen" title="Main stylesheet" charset="utf-8">
	<link rel="shortcut icon" href="favicon.ico" />

	<script src="<?php echo $siteroot; ?>/js/jquery-1.3.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $siteroot; ?>/js/jquery.hotkeys-0.7.8-packed.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $siteroot; ?>/js/scriptures.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		var siteroot = "<?php echo $siteroot; ?>";
		var siterootfolder = "<?php echo $siterootfolder; ?>";
	</script>
</head>

<body>
