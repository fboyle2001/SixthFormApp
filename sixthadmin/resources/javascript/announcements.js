$(document).ready(function () {
  // Search to populate the table
  titleSearch("");

	$("#search_by_title").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#titlesearch").val();
		titleSearch(searchTerm);
	});

  $("#search_by_content").click(function (e) {
    e.preventDefault();
    var contentTerm = $("#contentsearch").val();
    contentSearch(contentTerm);
  });
});

// Search by content
function contentSearch(content) {
  var queryUrl = "/sixthadmin/announcements/content_search.php?content=" + content;

  $.getJSON(queryUrl, function (data) {
    console.log(data);
    processData(data);
  });
}

// Search by title
function titleSearch(title) {
  var queryUrl = "/sixthadmin/announcements/title_search.php?title=" + title;

  $.getJSON(queryUrl, function (data) {
    processData(data);
  });
}

function processData(result) {
  // Clear the table
  $("#announcements_table > tbody").remove();

  if(result["status"]["code"] != 200) {
		$("#message").text("An error occurred");
		return;
	}

	if(result["content"]["found"] == false) {
		$("#message").text("No data found");
		return;
	}

  // Rebuild the table
  $("#announcements_table").append("<tbody>");

  $.each(result["content"]["records"], function(index, item) {
    // Convert unix timestamp to readable timestamp
    var date = new Date(item["DateAdded"] * 1000);
    var displayDate = prependZero(date.getDate()) + "/" + prependZero(date.getMonth() + 1) + "/" + prependZero(date.getYear() + 1900) + " " + prependZero(date.getHours()) + ":" + prependZero(date.getMinutes());

    $("#announcements_table > tbody").append('<tr><td>' + item["Title"] + '</td><td>' + item["Content"] + '</td><td>' + item["GroupName"] + '</td><td>' + displayDate + '</td><td id="delete_' + item["ID"] + '"><a id="delete_link_' + item["ID"] + '" href="javascript:remove(' + item["ID"] + ')">Delete</a></td></tr>');
  });

  $("#announcements_table").append("</tbody>");
}

// Delete an announcement
function remove(id) {
  var certain = confirm("Are you sure you want to delete this announcement?");

  if(certain == false) {
    return;
  }

  $("#delete_link_" + id).remove();
  $("#delete_" + id).text("Deleting...");
	var queryUrl = "/sixthadmin/announcements/delete.php";

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
