/* scriptures.js */
/* Ben Crowder */


$(document).ready(function() {
	// Key bindings
	$(document).bind('keydown', 'n', nextChapter);
	$(document).bind('keydown', 'p', prevChapter);

	$(document).bind('keydown', 'j', nextVerse);
	$(document).bind('keydown', 'k', prevVerse);
});

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

	var offset = (window.innerHeight / 2) - 100;

	var oldY = cur.attr("offsetTop") - offset;
	var newY = $("#v_" + vnum).attr("offsetTop") - offset;

	var scrollspeed = 3;

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
