$(document).ready(function () {
  titleSearch("");

	$("#search_by_title").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#titlesearch").val();
		titleSearch(searchTerm);
	});

  $("#search_by_content").click(function (e) {
    e.preventDefault();
    var contentTerm = $("#contentsearch").val();
    console.log("content: " + contentTerm);
    contentSearch(contentTerm);
  });
});

function contentSearch(content) {
  var queryUrl = "http://localhost/sixthadmin/announcements/content_search.php?content=" + content;

  $.getJSON(queryUrl, function (data) {
    processData(data);
  });
}

function titleSearch(title) {
  var queryUrl = "http://localhost/sixthadmin/announcements/title_search.php?title=" + title;

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
    var displayDate = date.getDate() + "/" + (date.getMonth() + 1) + "/" + (date.getYear() + 1900) + " " + date.getHours() + ":" + date.getMinutes();

    $("#announcements_table > tbody").append('<tr><td>' + item["ID"] + '</td><td>' + item["Title"] + '</td><td>' + item["Content"] + '</td><td>' + displayDate + '</td><td id="delete_' + item["ID"] + '"><a id="delete_link_' + item["ID"] + '" href="javascript:remove(' + item["ID"] + ')">Delete</a></td></tr>');
  });

  $("#announcements_table").append("</tbody>");
}

function remove(id) {
  var certain = confirm("Are you sure you want to delete this?");

  if(certain == false) {
    return;
  }

  $("#delete_link_" + id).remove();
  $("#delete_" + id).text("Deleting...");
	var queryUrl = "http://localhost/sixthadmin/announcements/delete.php";

	$.ajax({
		url: queryUrl,
		type: "post",
		dataType: "json",
		data: "id=" + id,
		success: function(data) {
			processRemoveResult(data, id);
		},
		error: function(data) {
			alert("An unexpected error occurred");
			console.log(data);
		}
	});
}

function processRemoveResult(data, id) {
	var status = data["status"];
	$("#delete_" + id).text(status["description"]);
}
