/* scriptures.js */
/* Ben Crowder */

var bookFound;
$(document).ready(function() {
	bindShortcuts();

	$(document).bind('keydown', 'g', openConsole);

	var vnum = parseInt($(".selected").attr("id").split('_')[1]);
	scrollToVerse(vnum, true);

	bookFound = false;
});

function bindShortcuts() {
	$(document).bind('keydown', 'n', nextChapter);
	$(document).bind('keydown', 'p', prevChapter);
	$(document).bind('keydown', 'z', togglePilcrows);

	$(document).bind('keydown', 'j', nextVerse);
	$(document).bind('keydown', 'k', prevVerse);

	$(document).bind('keydown', 'r', randomChapter);
/*	$(document).bind('keydown', 't', goToTOC); */
}

function unbindShortcuts() {
	$(document).unbind('keydown', 'n', nextChapter);
	$(document).unbind('keydown', 'p', prevChapter);

	$(document).unbind('keydown', 'j', nextVerse);
	$(document).unbind('keydown', 'k', prevVerse);

	$(document).unbind('keydown', 'r', randomChapter);
	$(document).unbind('keydown', 't', goToTOC);
}

function randomChapter() {
	document.location.href = siteroot + "/random.php";
}

function goToTOC() {
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

function togglePilcrows(argument) {
	$('.pilcrow').animate({
		'opacity' : 'toggle',
	});
}

function openConsole() {
	$("#shroud").fadeIn(100);
	$("#console").fadeIn(100);

	// Focus on the input
	$("#console_input").focus();

	// Clear out the text field
	$("#console_input").val("");

	// Add autocomplete to #console_input
	// added by Chad Hansen
	$("#console_input").bind('keydown', doAutocomplete);

	unbindShortcuts();

	// Unbind the g-key and bind the escape
	$(document).unbind('keydown', 'g', openConsole);
	$(document).bind('keydown', 'esc', closeConsole);
	$(document).bind('keydown', 'return', goToLoc);
	$(document).bind('keydown', 'up', prevSel);
	$(document).bind('keydown', 'down', nextSel);

	bookfound = false;

	return false;
}

function closeConsole() {
	$("#shroud").fadeOut(100);
	$("#console").fadeOut(100);

	$(document).bind('keydown', 'g', openConsole);
	$(document).unbind('keydown', 'esc', closeConsole);
	$(document).unbind('keydown', 'return', goToLoc);
	$(document).unbind('keydown', 'up', prevSel);
	$(document).unbind('keydown', 'down', nextSel);

	bindShortcuts();

	return false;
}

// author: Chad Hansen
function doAutocomplete(e) {
	var input = $("#console_input").attr("value");
	url = siteroot + "/autocomplete.php?query=" + input + String.fromCharCode(e.keyCode).toLowerCase();
	if (!bookFound)
	{
		$.getJSON(url, function(data) {
			var dropdown = $("#console_dropdown");
			if (data.length > 1)
			{
				content = "<ul>";
				for (i = 0; i < data.length; i++)
				{
					content += "<li>" + data[i].book_title + "</li>";
				}
				content += "</ul>";
				dropdown.html(content);
				dropdown.show();
			}
			else if (data.length == 0)
			{
				dropdown.html("<ul><li>No matches</li></ul>");
				dropdown.show();
			}
			else
			{
				// match found
				$("#console_input").attr("value", data[0].book_title + " ");
				dropdown.html("");
				dropdown.hide();
				bookFound = true;
			}
		});
	}
	else
	{
		if (input == "")
			bookFound = false;
	}
}

// author: Chad Hansen
function goToLoc() {
	var query = $("#console_input").attr("value");
	closeConsole();
	if (query == "toc")
	{
		window.location = siteroot;
	}
	else
	{
		$.getJSON(siterootfolder + "/getScriptureURL.php?query=" + query, function(data) {
			window.location = siteroot + data;
		});
	}
}

function nextSel() {
	var cur = $("li.sel");
	if (cur.next()[0]) {
		cur.removeClass("sel");
		cur.next().addClass("sel");
	}
}

function prevSel() {
	var cur = $("li.sel");
	if (cur.prev()[0]) {
		cur.removeClass("sel");
		cur.prev().addClass("sel");
	}
}
