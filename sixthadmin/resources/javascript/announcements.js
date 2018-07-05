$(document).ready(function () {
  contSearch("");

	$("#search_by_title").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#titlesearch").val();
		titleSearch(searchTerm);
	});

  $("#search_by_content").click(function (e) {
    e.preventDefault();
    var contentTerm = $("#contentsearch").val();
    console.log("content: " + contentTerm);
    contSearch(contentTerm);
  });
});

function contSearch(content) {
  console.log("made it");
  var queryUrl = "http://localhost/sixthadmin/announcements/content_search.php?content=" + content;
  $.getJSON(queryUrl, function (data) {
    processData(data);
  });
}

function titleSearch(title) {
  var queryUrl = "http://localhost/sixthadmin/announcements/title_search.php?title=" + title;
  console.log(queryUrl);
  $.getJSON(queryUrl, function (data) {
    processData(data);
  });
}

function processData(result) {
  $("#announcements_table > tbody").remove();

  if(result["status"]["code"] != 200) {
		$("#message").text("An error occurred");
		return;
	}

	if(result["content"]["found"] == false) {
		$("#message").text("No data found");
		return;
	}

  $("#announcements_table").append("<tbody>");

  $.each(result["content"]["records"], function(index, item) {
    var date = new Date(item["DateAdded"] * 1000);
    var displayDate = date.getDate() + "/" + (date.getMonth() + 1) + "/" + (date.getYear() + 1900);

    $("#announcements_table > tbody").append('<tr><td>' + item["ID"] + '</td><td>' + item["Title"] + '</td><td>' + item["Content"] + '</td><td>' + displayDate + '</td><td>DELETE HERE</td></tr>');
  });

  $("#announcements_table").append("</tbody>");
}

function contentSearch(title) {}
