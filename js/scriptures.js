/* scriptures.js */
/* Ben Crowder */


$(document).ready(function() {
	bindShortcuts();

	$(document).bind('keydown', 'g', openConsole);

	var vnum = parseInt($(".selected").attr("id").split('_')[1]);
	scrollToVerse(vnum, true);
});

function bindShortcuts() {
	$(document).bind('keydown', 'n', nextChapter);
	$(document).bind('keydown', 'p', prevChapter);

	$(document).bind('keydown', 'j', nextVerse);
	$(document).bind('keydown', 'k', prevVerse);

	$(document).bind('keydown', 'r', randomChapter);
	$(document).bind('keydown', 't', jumpToTOC);
}

function unbindShortcuts() {
	$(document).unbind('keydown', 'n', nextChapter);
	$(document).unbind('keydown', 'p', prevChapter);

	$(document).unbind('keydown', 'j', nextVerse);
	$(document).unbind('keydown', 'k', prevVerse);

	$(document).unbind('keydown', 'r', randomChapter);
	$(document).unbind('keydown', 't', jumpToTOC);
}

function randomChapter() {
	document.location.href = siteroot + "/random.php";
}

function jumpToTOC() {
	document.location.href = siteroot + "/toc.php";
}

function nextChapter() {
	document.location.href = $("#nextlink").attr("href");
}

function prevChapter() {
	document.location.href = $("#prevlink").attr("href");
}

function nextVerse() {
	var vnum = parseInt($(".selected").attr("id").split('_')[1]);
	var next = vnum + 1;

	if ($("#v_" + next).length) {
		scrollToVerse(next, true);
	}
}

function prevVerse() {
	var vnum = parseInt($(".selected").attr("id").split('_')[1]);
	var prev = vnum - 1;

	if (prev > 0) {
		scrollToVerse(prev, false);
	}
}

function scrollToVerse(vnum, forward) {
	cur = $(".selected");
	cur.removeClass("selected");
	$("#v_" + vnum).addClass("selected");

	var oldOffset = (window.innerHeight / 2) - 70 - (cur[0].scrollHeight / 2);
	var newOffset = (window.innerHeight / 2) - 70 - ($("#v_" + vnum)[0].scrollHeight / 2);

	var oldY = cur.attr("offsetTop") - oldOffset;
	var newY = $("#v_" + vnum).attr("offsetTop") - newOffset;

	var scrollspeed = 4;

	if (forward) {
		for (var i = oldY; i < newY; i += scrollspeed) {
			window.scroll(0, i);
		}
	} else {
		for (var i = oldY; i > newY; i -= scrollspeed) {
			window.scroll(0, i);
		}
	}

	window.scroll(0, newY);
}

function openConsole() {
	$("#shroud").fadeIn(100);
	$("#console").fadeIn(100);

	// Focus on the input
	$("#console_input").focus();

	// Clear out the text field
	$("#console_input").val("");

	unbindShortcuts();

	// Unbind the g-key and bind the escape
	$(document).unbind('keydown', 'g', openConsole);
	$(document).bind('keydown', 'esc', closeConsole);
	$(document).bind('keydown', 'return', jumpToLoc);

	return false;
}

function closeConsole() {
	$("#shroud").fadeOut(100);
	$("#console").fadeOut(100);
	$(document).bind('keydown', 'g', openConsole);
	$(document).unbind('keydown', 'esc', closeConsole);
	$(document).unbind('keydown', 'return', jumpToLoc);

	bindShortcuts();

	return false;
}

function jumpToLoc() {

}
